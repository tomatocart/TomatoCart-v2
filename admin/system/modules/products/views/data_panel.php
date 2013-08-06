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
 * @filesource data_panel.php
 */
?>

Ext.define('Toc.products.DataPanel', {
  extend: 'Ext.TabPanel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_data'); ?>';
    config.activeTab = 0;
    config.productsType = 1;
    config.tabExtraOptions = null;
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var dsProductsType = Ext.create('Ext.data.Store', {
      fields: ['id', 'text'],
      data:[
        {id: '<?php echo PRODUCT_TYPE_SIMPLE; ?>', text: '<?php echo lang('products_type_simple'); ?>'}
      ]
    });
    
    this.cboProductsType = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_products_type'); ?>',
      labelWidth: 120,
      width: 335,
      store: dsProductsType, 
      name: 'products_type_ids', 
      queryMode: 'local',
      displayField: 'text', 
      valueField: 'id', 
      editable: false,
      forceSelection: true,      
      value: '<?php echo PRODUCT_TYPE_SIMPLE; ?>',
      listeners: {
        select: this.onProductsTypeSelect,
        scope: this
      }
    });
    
    var dsManufacturers = Ext.create('Ext.data.Store', {
      fields: ['id', 'text'],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'products',
          action: 'get_manufacturers'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      listeners: {
        load: function() {this.cboManufacturers.setValue('0');},
        scope: this
      },
      autoLoad: true
    });
    
    this.cboManufacturers = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_manufacturer'); ?>',
      width: 335,
      labelWidth: 120, 
      store: dsManufacturers,
      queryMode: 'local', 
      name: 'manufacturers_id', 
      displayField: 'text', 
      valueField: 'id', 
      editable: false,
      forceSelection: true  
    });
    
    var dsWeightClasses = Ext.create('Ext.data.Store', {
      fields: ['id', 'text'],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'products',
          action: 'get_weight_classes'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      listeners: {
        load: function() {this.cboWeightClasses.setValue('<?php echo SHIPPING_WEIGHT_UNIT; ?>');},
        scope: this
      },
      autoLoad: true
    });
    
    this.cboWeightClasses = Ext.create('Ext.form.ComboBox', {
      width: 95,
      fieldLabel: '<?php echo lang('field_manufacturer'); ?>', 
      store: dsWeightClasses,
      queryMode: 'local', 
      name: 'products_weight_class', 
      hideLabel: true,
      displayField: 'text', 
      valueField: 'id', 
      editable: false,
      forceSelection: true  
    });
    
    this.fsStatus = Ext.create('Ext.form.FieldSet', {
      title: '<?php echo lang('subsection_data'); ?>', 
      layout: 'column', 
      width: 750,
      autoHeight: true,
      labelSeparator: ' ',
      items:[
        {
          columnWidth: .52,
          labelSeparator: ' ',
          border: false,
          defaults: {
            anchor: '98%'
          },
          items:[
            this.cboProductsType,
            {
              layout: 'column',
              border: false,
              items:[
                {
                  width: 210,
                  labelSeparator: ' ',
                  border: false,
                  items:[
                    {xtype:'radio', fieldLabel: '<?php echo lang('field_status'); ?>', labelWidth: 120, name: 'products_status', boxLabel: '<?php echo lang('status_enabled'); ?>', inputValue: '1', checked: true}
                  ]
                },
                {
                  width: 80,
                  border: false,
                  items: [
                    {xtype:'radio', fieldLabel: '<?php echo lang('status_disabled'); ?>', labelWidth: 120, boxLabel: '<?php echo lang('status_disabled'); ?>', name: 'products_status', hideLabel: true, inputValue: '0'}
                  ]
                }
              ]
            },
            {xtype: 'datefield', fieldLabel: '<?php echo lang('field_date_available'); ?>', labelWidth: 120, name: 'products_date_available', format: 'Y-m-d', anchor: '100%', width: 335}         
          ]
        },
        {
          columnWidth: .47,
          labelSeparator: ' ',
          border: false,
          defaults: {
            anchor: '97%'
          },              
          items: [
            {fieldLabel: '<?php echo lang('field_sku'); ?>', labelWidth: 120, xtype:'textfield', name: 'products_sku', width: 335},
            {fieldLabel: '<?php echo lang('field_model'); ?>', labelWidth: 120, xtype:'textfield', name: 'products_model', width: 335},
            this.cboManufacturers,
            {
              layout: 'column',
              width: 335,
              border: false,
              items:[
                {
                  width: 240,
                  labelSeparator: ' ',
                  border: false,
                  items:[
                    {fieldLabel: '<?php echo lang('field_weight'); ?>', labelWidth: 120, xtype:'textfield', name: 'products_weight', width: 235}
                  ]
                },
                {
                  border: false,
                  items: this.cboWeightClasses
                }
              ]
            }
          ]
        }
      ]
    });
    
    this.dsTaxClasses = Ext.create('Ext.data.Store', {
      fields: ['id', 'rate', 'text'],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'products',
          action: 'get_tax_classes'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      listeners: {
        load: function() {this.cboTaxClass.setValue('0');},
        scope: this
      },
      autoLoad: true
    });
    
    this.cboTaxClass = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_tax_class'); ?>',
      labelWidth: 120,
      store: this.dsTaxClasses,
      queryMode: 'local', 
      name: 'products_tax_class_id', 
      displayField: 'text', 
      valueField: 'id', 
      editable: false,
      listeners: {
        select: this.onTaxClassSelect,
        scope: this
      }
    });
    
    this.txtPriceNet = Ext.create('Ext.form.TextField', {
      fieldLabel: '<?php echo lang('field_price_net'); ?>',
      labelWidth: 120, 
      name: 'products_price',
      value: '0',
      listeners: {
        change: this.onPriceNetChange,
        scope: this
      }
    });
    
    this.txtPriceGross = Ext.create('Ext.form.TextField', {
      fieldLabel: '<?php echo lang('field_price_gross'); ?>',
      labelWidth: 120, 
      name: 'products_price_gross',
      value: '0',
      listeners: {
        change: this.onPriceGrossChange,
        scope: this
      }
    });
    
    var dsQuantityDiscountGroup = Ext.create('Ext.data.Store', {
      fields: ['id', 'text'],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'products',
          action: 'get_quantity_discount_groups'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.cboPriceDiscountGroups = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_price_discount_groups'); ?>',
      labelWidth: 120, 
      store: dsQuantityDiscountGroup,
      queryMode: 'local', 
      name: 'quantity_discount_groups_id', 
      displayField: 'text', 
      valueField: 'id', 
      editable: false,
      forceSelection: true
    });
    
    this.fsPrice = Ext.create('Ext.form.FieldSet', {
      title: '<?php echo lang('subsection_price'); ?>',
      layout: 'anchor', 
      defaults: {
        anchor: '98%'
      },
      columnWidth: 0.49,
      height: 205,
      labelSeparator: ' ',
      items:[this.cboTaxClass, this.txtPriceNet, this.txtPriceGross, this.cboPriceDiscountGroups] 
    });
    
    var dsUnitClass = Ext.create('Ext.data.Store', {
      fields: ['id', 'text'],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'products',
          action: 'get_quantity_units'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      listeners: {
        load: function() {this.cboUnitClasses.setValue('<?php echo DEFAULT_UNIT_CLASSES; ?>');},
        scope: this
      },
      autoLoad: true
    });
    
    this.cboUnitClasses = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_quantity_unit'); ?>',
      labelWidth: 120, 
      store: dsUnitClass,
      queryMode: 'local', 
      name: 'quantity_unit_class', 
      displayField: 'text', 
      valueField: 'id', 
      editable: false,
      forceSelection: true
    });
    
    this.fsInformation = Ext.create('Ext.form.FieldSet', {
      title: '<?php echo lang('subsection_information'); ?>',
      layout: 'anchor', 
      height: 205,
      labelSeparator: ' ',
      columnWidth: 0.51,
      style: 'margin-left: 10px',
      defaults: {
        anchor: '98%'
      },
      items:[
        this.txtQuantity = Ext.create('Ext.form.NumberField', {
          fieldLabel: '<?php echo lang('field_quantity'); ?>', 
          labelWidth: 120, 
          name: 'products_quantity', 
          allowDecimals: false, value: 0
        }), 
        {fieldLabel: '<?php echo lang('field_minimum_order_quantity'); ?>', labelWidth: 120, xtype:'numberfield', name: 'products_moq', allowDecimals: false, value: 1},
        {fieldLabel: '<?php echo lang('field_increment'); ?>', labelWidth: 120, xtype:'numberfield', name: 'order_increment', allowDecimals: false, value: 1},
        this.cboUnitClasses,
        this.txtMaxOrderQuantity = Ext.create('Ext.form.NumberField', {
          fieldLabel: '<?php echo lang('field_Maximum_order_quantity'); ?>', 
          labelWidth: 120, 
          name: 'products_max_order_quantity', 
          allowDecimals: false, 
          disabled:true, 
          minValue: 1
        }),
        this.chkUnlimited = Ext.create('Ext.form.Checkbox', {
          fieldLabel: '',
          labelWidth: 120,
          boxLabel: '<?php echo lang('field_unlimited'); ?>',
          name: 'unlimited',
          checked: true,
          listeners: {
            change: this.onChkUnlimitedChecked,
            scope: this
          }
        })
      ] 
    });
    
    var pnlGeneral = Ext.create('Ext.Panel', {
      title: '<?php echo lang('section_general'); ?>',
      style: 'padding: 10px',
      items: [
        this.fsStatus,
        {
          layout: 'column',
          border: false,
          width: 750,
          items: [
            this.fsPrice,
            this.fsInformation
          ]
        }
      ]
    });
    
    return pnlGeneral;
  },
  
  getTaxRate: function() {
    rate = 0;
    rateId = this.cboTaxClass.getValue();
    store = this.dsTaxClasses;

    for (i = 0; i < store.getCount(); i++) {
      record = store.getAt(i);
      
      if(record.get('id') == rateId) {
        rate = record.get('rate');
        break;
      }
    }
    
    return rate;  
  },
  
  onPriceNetChange: function() {
    value = this.txtPriceNet.getValue();
    rate = this.getTaxRate();

    if (rate > 0) {
      value = value * ((rate / 100) + 1);
    }

    this.txtPriceGross.setValue(Math.round(value * Math.pow(10, 4)) / Math.pow(10, 4));
  },
  
  onPriceGrossChange: function() {
    value = this.txtPriceGross.getValue();
    rate = this.getTaxRate();

    if (rate > 0) {
      value = value / ((rate / 100) + 1);
    }

    this.txtPriceNet.setValue(Math.round(value * Math.pow(10, 4)) / Math.pow(10, 4));
  },
  
  onTaxClassSelect: function(combo, value) {
    value = this.txtPriceNet.getValue();
    rate = this.getTaxRate();
    
    if (rate > 0) {
      value = value * ((rate / 100) + 1);
    }
    
    this.txtPriceGross.setValue(Math.round(value * Math.pow(10, 4)) / Math.pow(10, 4));
  },
  
  onProductsTypeSelect: function(combo, record) {
    return false;
  },  
  
  onChkUnlimitedChecked: function(checkbox, checked) {
    if (checked) {
      this.txtMaxOrderQuantity.disable();
      this.txtMaxOrderQuantity.allowBlank = true;
      this.txtMaxOrderQuantity.setValue('');
    } else {
      this.txtMaxOrderQuantity.enable();
      this.txtMaxOrderQuantity.allowBlank = false;
    }
  },
  
  getProductsType: function() {
    return this.cboProductsType.getValue();
  },
  
  updateCbos: function(cboData) {
    if (Ext.isString(cboData.products_tax_class_id)) {
      this.cboTaxClass.setValue(cboData.products_tax_class_id);
    }
    
    if (Ext.isString(cboData.manufacturers_id)) {
      this.cboManufacturers.setValue(cboData.manufacturers_id);
    }
    
    if (Ext.isString(cboData.products_weight_class)) {
      this.cboWeightClasses.setValue(cboData.products_weight_class);
    }
    
    if (Ext.isString(cboData.quantity_discount_groups_id)) {
      this.cboPriceDiscountGroups.setValue(cboData.quantity_discount_groups_id);
    }
    
    if (Ext.isString(cboData.quantity_unit_class)) {
      this.cboUnitClasses.setValue(cboData.quantity_unit_class);
    }
  }
});

/* End of file data_panel.php */
/* Location: ./system/modules/products/views/data_panel.php */

