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

Ext.define('Toc.currencies.CurrenciesUpdateRatesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    this.currenciesId = config.currenciesId;
    
    config.id = 'currencies-update-rates-win';
    config.title = '<?php echo lang('action_heading_update_rates'); ?>';
    config.iconCls = 'icon-update-exchange-rates';
    config.layout = 'fit';
    config.width = 450;
    config.height = 240;
    config.modal = true;
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: '<?php echo lang('button_update'); ?>',
        handler: function() {
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
  
  buildForm: function() {
    this.frmUpdateRates = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('currencies/update_currency_rates'); ?>',
      baseParams: {  
        currencies_id: this.currenciesId
      },
      border: false,
      bodyPadding: 10,
      frame: false,
      fieldDefaults: {
        labelSeparator: ''
      },
      items: [
        {border: false, html: '<p class="form-info"><?php echo lang('introduction_update_exchange_rates'); ?></p>'},
        {xtype: 'radiofield', name: 'service', boxLabel: 'Oanda (http://www.oanda.com)', inputValue: 'oanda', hideLabel: true, checked: true},
        {border: false, html: '<p class="form-info"><?php echo lang('service_terms_agreement'); ?></p>'}
      ]
    });
    
    return this.frmUpdateRates;
  },
  
  submitForm : function() {
    this.frmUpdateRates.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action){
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },    
      failure: function(form, action) {
        Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
      },
      scope: this
    });   
  }
});

/* End of file currencies_update_rates_dialog.php */
/* Location: ./templates/base/web/views/currencies/currencies_update_rates_dialog.php */