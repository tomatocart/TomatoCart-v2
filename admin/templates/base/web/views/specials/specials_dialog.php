<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */
?>

Ext.define('Toc.specials.SpecialsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'specials-dialog-win';
    config.layout = 'fit';
    config.width = 525;
    config.autoheight = true;
    config.modal = true;
    config.iconCls = 'icon-specials-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function () {
          this.cboProducts.enable();
          this.submitForm();
        }, 
        scope: this
      },
      {
        text: TocLanguage.btnClose,
        handler: function () {
          this.close();
        }, 
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function (id) {
    var specialsId = id || null;
    
    if (specialsId > 0) {
      this.frmSpecials.form.baseParams['specials_id'] = specialsId;
      
      this.frmSpecials.load({
        url : '<?php echo site_url('specials/load_specials'); ?>',
        success: function (form, action) {
          var netValue = action.result.data.specials_new_products_price;
          var rate = this.getTaxRate();

          if (rate > 0) {
            netValue = netValue / ((rate / 100) + 1);
          }
          
          this.cboProducts.setRawValue(action.result.data.products_name);
          this.cboProducts.disable();
          this.txtPriceGross.setValue(Math.round(netValue * Math.pow(10, 4)) / Math.pow(10, 4));
          
          Toc.specials.SpecialsDialog.superclass.show.call(this);
        },
        failure: function (form, action) {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this
      });
    } else {
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.dsProducts = Ext.create('Ext.data.Store', {
      fields:[
        'products_id', 
        'products_name',
        'rate'
      ],
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('specials/list_products'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    this.cboProducts = Ext.create('Ext.form.ComboBox', {
      store: this.dsProducts,
      fieldLabel: '<?php echo lang("field_product"); ?>',
      name: 'products_id',
      allowBlank: false,
      queryMode: 'remote',
      valueField: 'products_id',
      displayField: 'products_name',
      triggerAction: 'all',
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      editable: false
    });
    
    this.txtPriceNet = Ext.create('Ext.form.TextField', {
      fieldLabel: '<?php echo lang("field_price_net_percentage"); ?>', 
      xtype:'textfield',
      name: 'specials_new_products_price',
      value: '0'
    });
    this.txtPriceNet.on('change', this.onPriceNetChange, this);
    
    this.txtPriceGross = Ext.create('Ext.form.TextField', {
      fieldLabel: '<?php echo lang("field_price_gross"); ?>', 
      xtype:'textfield',
      name: 'products_price_gross',
      value: '0'
    });
    this.txtPriceGross.on('change', this.onPriceGrossChange, this);
    
    this.frmSpecials = Ext.create('Ext.form.Panel', {
      url : '<?php echo site_url('specials/save_specials'); ?>',
      baseParams: {},
      border: false,
      frame: false,
      bodyPadding: 5,
      fieldDefaults: {
        anchor: '97%',
        labelWidth: 200,
        labelSeparator: ''
      },
      items: [
        this.cboProducts,
        this.txtPriceNet, 
        this.txtPriceGross, 
        {
          xtype: 'checkbox',
          fieldLabel: '<?php echo lang("field_status"); ?>',
          name: 'status',
          anchor: ''
        },
        {
          xtype: 'datefield',
          fieldLabel: '<?php echo lang("field_date_start"); ?>',
          name: 'start_date',
          format: 'Y-m-d',
          allowBlank: false
        }, 
        {
          xtype: 'datefield',
          fieldLabel: '<?php echo lang("field_date_expires"); ?>',
          name: 'expires_date',
          format: 'Y-m-d',
          allowBlank: false
        }
      ]
    });
    
    return this.frmSpecials;
  },
  
  onPriceNetChange: function() {
    netValue = this.txtPriceNet.getValue();
    taxRate = this.getTaxRate();

    if (netValue.indexOf('%') > -1) {
      this.txtPriceGross.setValue('');
      this.txtPriceGross.disable();
      return false;
    } else if ( this.txtPriceGross.disabled == true ) {
      this.txtPriceGross.enable();
    }
    
    if (taxRate > 0) {
      netValue = netValue * ((taxRate / 100) + 1);
    }

    this.txtPriceGross.setValue(Math.round(netValue * Math.pow(10, 4)) / Math.pow(10, 4));
  },
  
  onPriceGrossChange: function(){
    grossValue = this.txtPriceGross.getValue();
    rate = this.getTaxRate();
    
    if (grossValue.indexOf('%') > -1) {
      this.txtPriceGross.setValue('');
      this.txtPriceGross.disable();
      this.txtPriceNet.focus();
      return false;
    } 
    
    if (rate > 0) {
      grossValue = grossValue / ((rate / 100) + 1);
    }

    this.txtPriceNet.setValue(Math.round(grossValue * Math.pow(10, 4)) / Math.pow(10, 4));
  },
  
  getTaxRate: function() {
    rate = 0;
    rateId = this.cboProducts.getValue();
    
    for (i = 0; i < this.dsProducts.getCount(); i++) {
      record = this.dsProducts.getAt(i);
      
      if(record.id == rateId) {
        rate = record.get('rate');
        break;
      }
    }
    return rate;  
  },
  
  submitForm: function () {
    this.frmSpecials.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function (form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },
      failure: function (form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this
    });
  }
});

/* End of file specials_dialog.php */
/* Location: ./templates/base/web/views/specials/specials_dialog.php */