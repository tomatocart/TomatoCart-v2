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

Ext.define('Toc.customers.CustomersDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'customers-dialog-win';
    config.title = '<?php echo lang('action_heading_new_customer'); ?>';
    config.modal = true;
    config.width = 500;
    config.iconCls = 'icon-customers-win';
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function() {
          this.submitForm();
        },
        scope: this
      },
      {
        text: TocLanguage.btnClose,
        handler: function() { 
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess' : true});  
    
    this.callParent([config]);
  },
  
  show: function (id) {
    var customersId = id || null;
    
    this.frmCustomers.form.reset();
    this.frmCustomers.form.baseParams['customers_id'] = customersId;
    
    if (customersId > 0) {
      this.frmCustomers.load({
        url: '<?php echo site_url('customers/load_customer'); ?>',
        success: function(form, action) {
          Toc.customers.CustomersDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Toc.customers.CustomersDialog.superclass.show.call(this);
    }
  },
  
  buildForm: function() {
    var dsCustomersGroups = Ext.create('Ext.data.Store', {
      fields:[
        'id',
        'text'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('customers/get_customers_groups'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.cboCustomersGroups = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_customer_group'); ?>',
      labelWidth: 149,
      store: dsCustomersGroups, 
      name: 'customers_groups_id', 
      queryMode: 'local',
      displayField: 'text', 
      valueField: 'id', 
      editable: false,
      forceSelection: true,      
      emptyText: '<?php echo lang('none'); ?>'
    });
    
    this.frmCustomers = Ext.create('Ext.form.FormPanel', {
      url: '<?php echo site_url('customers/save_customer'); ?>',
      baseParams: {}, 
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelAlign: 'left',
        labelWidth: 149,
        anchor: '98%'
      },
      items: [
        {
          layout: 'column',
          border: false,
          items: [
            { 
              width: 220,
              border: false,
              items:[
                {fieldLabel: '<?php echo lang('field_gender'); ?>', boxLabel: '<?php echo lang('gender_male'); ?>' , name: 'customers_gender', xtype:'radio', inputValue: 'm', checked: true}
              ]
            },
            { 
              border: false,
              items:[
                { hideLabel: true, boxLabel: '<?php echo lang('gender_female'); ?>' , name: 'customers_gender', xtype:'radio', inputValue: 'f'}
              ]
            }
          ]
        },
        {xtype: 'textfield', fieldLabel: '<?php echo lang('field_first_name'); ?>', name: 'customers_firstname', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '<?php echo lang('field_last_name'); ?>', name: 'customers_lastname', allowBlank: false},
        {xtype: 'datefield', fieldLabel: '<?php echo lang('field_date_of_birth'); ?>', editable: false, name: 'customers_dob', format: 'Y-m-d'},
        {xtype: 'textfield', fieldLabel: '<?php echo lang('field_email_address'); ?>', name: 'customers_email_address', allowBlank: false},
        {xtype: 'checkbox', anchor: '', fieldLabel: '<?php echo lang('field_newsletter_subscription'); ?>', name: 'customers_newsletter'},
        {xtype: 'textfield', inputType: 'password', fieldLabel: '<?php echo lang('field_password'); ?>', name: 'customers_password'},
        {xtype: 'textfield', inputType: 'password', fieldLabel: '<?php echo lang('field_password_confirmation'); ?>', name: 'confirm_password'},
        {xtype: 'checkbox',anchor: '', fieldLabel: '<?php echo lang('field_status'); ?>', name: 'customers_status'},
        this.cboCustomersGroups
      ]
    });
    
    return this.frmCustomers;
  },
  
  submitForm : function() {
    this.frmCustomers.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
         this.fireEvent('savesuccess', action.result.feedback);
         
         this.close();  
      },    
      failure: function(form, action) {
        if (action.failureType != 'client') {
          Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },  
      scope: this
    });   
  }
});

/* End of file customers_dialog.php */
/* Location: ./templates/base/web/views/customers/customers_dialog.php */