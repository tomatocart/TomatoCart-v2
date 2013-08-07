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
 * @filesource accessories_panel.php
 */
?>

Ext.define('Toc.products.AccessoriesPanel', {
  extend: 'Ext.grid.GridPanel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.title = '<?php echo lang('section_accessories'); ?>';
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    config.productsId = config.productsId || null;
    
    config.store = Ext.create('Ext.data.Store', {
      fields: ['accessories_id', 'products_name'],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'products',
          action: 'get_accessories',
          products_id: config.productsId
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.selModel = Ext.create('Ext.selection.CheckboxModel');
    config.selType = 'cellmodel';
    
    config.columns =[
      Ext.create('Ext.grid.RowNumberer'),
      { header: '<?php echo lang('table_heading_products'); ?>', dataIndex: 'products_name', sortable: true, flex: 1},
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
    ];
    
    this.buildCboProducts(config.productsId);
    
    config.tbar = [
      { 
        text: TocLanguage.btnRefresh,
        iconCls:'refresh',
        handler: this.onRefresh,
        scope: this
      }, 
      '->', 
      this.cboProducts, 
      ' ', 
      {
        text: '<?php echo lang('button_insert'); ?>',
        iconCls : 'add',
        handler: this.addProduct,
        scope: this
      }
    ];
    
    this.callParent([config]);
  },
  
  buildCboProducts: function(productsId) {
    var store = Ext.create('Ext.data.Store', {
      fields: ['id', 'text'],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'products',
          action: 'get_products',
          products_id: productsId
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.cboProducts = Ext.create('Ext.form.ComboBox', {
      name: 'accessories_name',
      store: store,
      emptyText: '<?php echo lang('section_accessories'); ?>',
      width: 400,
      queryMode: 'local', 
      displayField: 'text', 
      valueField: 'id',
      pageSize: Toc.CONF.GRID_PAGE_SIZE, 
      editable: false
    }); 
  },
  
  addProduct: function() {
    var accessoriesId = this.cboProducts.getValue();
    var productName = this.cboProducts.getRawValue().toString();
    
    if (!Ext.isEmpty(accessoriesId)) {
      store = this.getStore();
      
      if (store.findExact('accessories_id', accessoriesId) == -1) {
        Ext.define('accessory', {
          extend: 'Ext.data.Model',
          fields: [
              {name: 'accessories_id', type: 'string'},
              {name: 'products_name', type: 'string'}
          ]
        });
        
        var accessory_record = Ext.ModelManager.create({
          accessories_id: accessoriesId,
          products_name: productName
        }, 'accessory');
        
        store.add(accessory_record);
      }
    }
  },
  
  onDelete: function(record) {
    this.getStore().remove(record);
  },
  
  onRefresh: function() {
    this.getStore().load();
  },
  
  getAccessoriesIds: function() {
    var batch = [];
    
    this.getStore().each(function(record) {
      batch.push(record.get('accessories_id'));
    });
    
    return batch.join(';');
  }
});

/* End of file accessories_panel.php */
/* Location: ./system/modules/products/views/accessories_panel.php */