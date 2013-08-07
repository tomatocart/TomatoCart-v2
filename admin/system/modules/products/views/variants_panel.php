<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource variants_panel.php
 */
?>
Ext.define('Toc.products.VariantsPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_variants'); ?>';
    config.layout = 'border';
    config.defaults = {split: true};
    
    this.groupIds = [];
    this.variantsValues = [];
    this.productsId = config.productsId || null;
    this.downloadable = false;
    this.dlgProducts = config.dlgProducts;
    
    config.items = this.buildForm(config.productsId);
    
    this.addEvents({'variantschange' : true, 'addvariantsgroups': true});
    
    this.callParent([config]);
  },
  
  buildForm: function(productsId) {
    this.pnlVariantGroups = Ext.create('Ext.Panel', {
      width: 220,
      split: true,
      border: false,
      region: 'west',
      split: true,
      collapsible: true,
      bodyPadding: 5,
      labelAlign: 'top',
      autoScroll: true,
      tbar: [{
        text: '<?php echo lang('button_manage_variants_groups'); ?>',
        iconCls : 'add',
        handler: function() {
          this.fireEvent('addvariantsgroups', this.groupIds);
        },
        scope: this
      }]
    });
    
    this.grdVariants = this.buildGrdVariants(productsId);
    this.pnlVariantDataContainer = this.buildVariantDataPanel();
    
    return [this.pnlVariantGroups, this.grdVariants, this.pnlVariantDataContainer];
  },
  
  buildGrdVariants: function(productsId) {
    var dsVariants = Ext.create('Ext.data.Store', {
      fields: ['products_variants_id', 'variants_values', 'variants_groups', 'variants_values_name', 'data', 'default'],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'products',
          action: 'get_variants_products',
          products_id: productsId
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      listeners: {
        load: this.onDsVariantsLoad,
        scope: this
      },
      autoLoad: true
    });
    
    var grdVariants = Ext.create('Ext.grid.GridPanel', {
      region: 'center',
      border: false,
      split: true,
      collapsible: true,
      store: dsVariants,
      columns: [
        {header: '<?php echo lang("table_heading_variants"); ?>', dataIndex: 'variants_values_name', flex: 1},
        {
          xtype: 'checkcolumn',
          header: '<?php echo lang("table_heading_default"); ?>',
          dataIndex: 'default',
          width: 55,
          listeners: {
            checkchange: function(checkcolumn, recordIndex, checked) {
              var record = this.up('gridpanel').getStore().getAt(recordIndex);
              
              if (this.up('gridpanel').getStore().getCount() > 1) {
                this.up('gridpanel').getStore().each(function(item) {
                  if (item.get(this.dataIndex) && item != record)
                  {
                    item.set(this.dataIndex, !item.data[this.dataIndex]);
                    item.commit();
                  }
                }, this);
              }
              
              record.set(this.dataIndex, checked);
            }
          }
        },
        {
          xtype:'actioncolumn', 
          width:50,
          header: '<?php echo lang("table_heading_action"); ?>',
          items: [{
            iconCls: 'icon-action icon-delete-record',
            tooltip: TocLanguage.tipDelete,
            handler: function(grid, rowIndex, colIndex) {
              var rec = grid.getStore().getAt(rowIndex);
              
              this.onDelete(rec);
            },
            scope: this              
          }]
        }
      ],
      listeners: {
        selectionchange: this.onGrdVariantsRowSelect,
        scope: this
      }
    });
    
    return grdVariants;
  },
  
  onGrdVariantsRowSelect: function(target, selections) {
    if (selections.length > 0) {
      var record = selections[0];
      var cardID = record.get('variants_values');
      
      this.pnlVariantDataContainer.getLayout().setActiveItem(cardID);
      
      if (!Ext.get(cardID).isVisible()) {
        Ext.get(cardID).setVisible(true);
      }
      
      this.setVariantsValues(record.get('variants_groups'));
    
      this.pnlVariantDataContainer.doLayout();
      this.dlgProducts.doLayout();
    }
  },
  
  onDsVariantsLoad: function() {
    if (this.grdVariants.getStore().getCount() > 0) {
      this.grdVariants.getStore().each(function(record) {
        this.pnlVariantDataContainer.add(this.buildVariantDataCard(record.get('variants_values'), record.get('data')));
      }, this);
      
      var record = this.grdVariants.getStore().getAt(0);
      this.generatePnlVariantsGroups(record.get('variants_groups'));
      var variantsValuesArray = record.get('variants_values').split('-');
      
      Ext.each(variantsValuesArray, function(value) {
        this.groupIds.push(value.split('_')[0]);
      }, this);
      
      this.grdVariants.getSelectionModel().select(0);

      var cardID = record.get('variants_values');
      this.pnlVariantDataContainer.getLayout().setActiveItem(cardID);
      this.setVariantsValues(record.get('variants_groups'));
        
      this.pnlVariantDataContainer.doLayout();
      this.dlgProducts.doLayout();
    }
  },
  
  generatePnlVariantsGroups: function(groups) {
    this.groupIds = [];
    this.deletePnlVariants();
    
    if (groups.length > 0) {
      for(var i = 0; i < groups.length; i++) {
        var cboVariants = {
          xtype: 'combo',
          store: Ext.create('Ext.data.Store', {
            fields: ['id', 'text'],
            proxy: {
              type: 'ajax',
              url : Toc.CONF.CONN_URL,
              extraParams: {
                module: 'products',
                action: 'get_variants_values',
                group_id: groups[i].id
              },
              reader: {
                type: 'json',
                root: Toc.CONF.JSON_READER_ROOT,
                totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
              }
            },
            autoLoad: true
          }),
          fieldLabel: groups[i].name,
          labelWidth: 50,
          queryMode: 'local',
          valueField: 'id',
          displayField: 'text',
          name: groups[i].name + '_' + groups[i].id,
          editable: false
        };
        
        this.groupIds.push(groups[i].id);
        this.pnlVariantGroups.add(cboVariants);
      }
      
      this.pnlVariantGroups.add(new Ext.Button({
        text: TocLanguage.btnAdd,
        iconCls: 'add',
        handler: this.addProductVariant,
        scope: this
      }));
    }
    
    this.pnlVariantGroups.doLayout();
  },
  
  addProductVariant: function() {
    var error = false;
    var values = [];
    var names = [];  
    var groups = [];
    
    //get variants values
    Ext.each(this.pnlVariantGroups.query('combobox'), function(item) {
      if (Ext.isEmpty(item.getRawValue())) {
        error = true;  
      } else {
        var values_id = item.getValue();
        var groups_id = item.getName().split('_')[1];
        
        var values_name = item.getRawValue();
        var groups_name = item.getName().split('_')[0];
        
        values.push(groups_id + '_' + values_id);
        names.push(groups_name + ': ' + values_name);
        groups.push({id: groups_id, name: groups_name, rawvalue: values_name, value: values_id});
      }
    });
    
    if (error === true) {
      Ext.MessageBox.alert(TocLanguage.msgErrTitle, '<?php echo lang('msg_warning_must_choose_value_for_variant_group'); ?>');
      return;
    }
    
    //check whether variants combination exist
    variants_values = values.sort().join('-');
    
    var store = this.grdVariants.getStore();
    var found = false;
    
    if (store.count() > 0) {
      store.each(function(record, index) {
        var tmp = record.get('variants_values');
        
        if (tmp == variants_values) {
          found = true;
          this.grdVariants.getSelectionModel().select(index);
        }
      } ,this);
    }
    
    if (found == true) {
      Ext.MessageBox.alert(TocLanguage.msgErrTitle, '<?php echo lang('msg_warning_variant_values_exist'); ?>');
      return;
    }
    
    //add record
    Ext.define('Variants', {
      extend: 'Ext.data.Model',
      fields: [
          {name: 'products_variants_id'},
          {name: 'variants_values'},
          {name: 'variants_groups'},
          {name: 'variants_values_name'},
          {name: 'data'},
          {name: 'default'}
      ]
    });
    
    var data = {
      variants_quantity: 0,
      variants_net_price: 0,
      variants_sku: '',
      variants_model: '',
      variants_weight: 0,
      variants_status: 0,
      variants_image: null,
      variants_download_file: null,
      variants_download_filename: null
    };
    
    var variants_record = Ext.ModelManager.create({
      products_variants_id: -1, 
      variants_values: variants_values,
      variants_groups: groups, 
      variants_values_name: names.join('; '),
      data: data, 
      'default': ((store.getCount() > 0) ? 0 : 1)
    }, 'Variants');
    
    store.add(variants_record);
    
    this.pnlVariantDataContainer.add(this.buildVariantDataCard(variants_values, data));
    this.grdVariants.getSelectionModel().select(store.count()-1);
  },
  
  buildVariantDataCard: function(valuesId, data) {
    var card = Ext.create('Toc.products.VariantDataPanel', {
      valuesId: valuesId, 
      data: data, 
      downloadable: this.downloadable, 
      dlgProducts: this.dlgProducts
    });
    
    return card;   
  },
  
  deletePnlVariants: function() {
    this.pnlVariantGroups.items.each(function(item) {
      var el = item.el.up('.x-form-item');
      
      if (el) {
        this.pnlVariantGroups.remove(item, true);
        el.remove();
      }
    }, this);
    
    this.pnlVariantGroups.removeAll();
  },
  
  buildVariantDataPanel: function() {
    var pnlVariantDataContainer = Ext.create('Ext.Panel', {
      layout:'card',
      region: 'east',
      width: 300,
      autoScroll: true,
      split: true,
      collapsible: true,
      layoutOnCardChange: true,
      border:false
    });
    
    return pnlVariantDataContainer;
  },
  
  setVariantsValues: function(variants_groups) {
    Ext.each(variants_groups, function(group){
      var combo = this.pnlVariantGroups.query('combobox[name="' + group.name + '_' + group.id +  '"]');
      
      combo[0].setValue(group.value);
      combo[0].setRawValue(group.rawvalue);
    }, this);
  },
  
  getVariants: function() {
    var data = [];

    this.grdVariants.getStore().each(function(record) {
      var is_default = 0;
      if (record.get('default')) {
        is_default = 1;
      }
      data.push(record.get('variants_values') + ':' + record.get('products_variants_id') + ':' + is_default);
    });
    
    return data.join(';');
  },
  
  checkStatus: function() {
    var selected = false;
    var store = this.grdVariants.getStore();
    
    if (this.disabled == true) {
      selected = true;
    } else {
      if (store.getCount() > 0) {
        for (var i =0; i < store.getCount(); i++) {
          if (store.getAt(i).get('default') == '1') {
            selected = true;
            break;
          }
        }
      } else {
        selected = true;
      }
    }
    
    return selected;  
  },
  
  deleteVariants: function() {
    this.deletePnlVariants();
    this.grdVariants.getStore().removeAll();
    this.pnlVariantDataContainer.removeAll();
  },
  
  onDelete: function(record) {
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function(btn) {
        if ( btn == 'yes' ) {
          var cardID = record.get('variants_values');
          
          if (this.pnlVariantDataContainer.query('#' + cardID)) {
            this.pnlVariantDataContainer.remove(cardID);
          }
          
          this.grdVariants.getStore().remove(record);
          
          if (this.grdVariants.getStore().getCount() > 0) {
            this.grdVariants.getSelectionModel().select(0);
          }else {
            this.pnlVariantDataContainer.doLayout();
            this.dlgProducts.doLayout(); 
          }
        }
      }, 
    this);
  },
  
  onProductTypeChange: function(type) {
    this.enable();
    this.downloadable = false;
  }
});

/* End of file variants_panel.php */
/* Location: ./system/modules/products/views/variants_panel.php */