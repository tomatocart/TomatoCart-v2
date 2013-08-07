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

Ext.define('Toc.currencies.CurrenciesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'currencies-dialog-win';
    config.title = '<?php echo lang('action_heading_new_currency'); ?>';
    config.width = 450;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-currencies-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text:TocLanguage.btnSave,
        handler: function(){
          this.submitForm();
        },
        scope:this
      },
      {
        text: TocLanguage.btnClose,
        handler: function() {
          this.close();
        },
        scope:this
      }
    ];
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);  
  },
  
  show: function(id) {
    var currenciesId = id || null;
    
    if (currenciesId > 0) {
      this.frmCurrency.form.baseParams['currencies_id'] = currenciesId;
      
      this.frmCurrency.load({
        url: '<?php echo site_url('currencies/load_currency'); ?>',
        success: function(form, action) {
          if(action.result.data.is_default == '1') {
            Ext.getCmp('is_default').disable();
          }
          
          Toc.currencies.CurrenciesDialog.superclass.show.call(this);
        },
        failure: function(form, action) {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmCurrency = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('currencies/save_currency'); ?>',
      baseParams: {},
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      border: false,
      bodyPadding: 10,
      items: [
        {xtype: 'textfield', fieldLabel: '<?php echo lang('field_title'); ?>', name: 'title', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '<?php echo lang('field_code'); ?>', name: 'code', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '<?php echo lang('field_symbol_left'); ?>', name: 'symbol_left'},
        {xtype: 'textfield', fieldLabel: '<?php echo lang('field_symbol_right'); ?>', name: 'symbol_right'},
        {xtype: 'numberfield', fieldLabel: '<?php echo lang('field_decimal_places'); ?>', name: 'decimal_places', allowDecimals: false},
        {xtype: 'numberfield', fieldLabel: '<?php echo lang('field_currency_value'); ?>', name: 'value', decimalPrecision: 10},
        {xtype: 'checkbox', fieldLabel: '<?php echo lang('field_set_default'); ?>', id: 'is_default', name: 'is_default', anchor: ''}
      ]
    });
    
    return this.frmCurrency;
  },
  
  submitForm : function() {
    this.frmCurrency.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },    
      failure: function(form, action) {
        if(action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this
    });   
  }
});

/* End of file currencies_dialog.php */
/* Location: ./templates/base/web/views/currencies/currencies_dialog.php */