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

Ext.define('Toc.customers_groups.CustomersGroupsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'customers_groups-dialog-win';
    config.title = '<?php echo lang('action_heading_new_customer_group'); ?>';
    config.width = 480;
    config.modal = true;
    config.iconCls = 'icon-customers_groups-win';
    config.layout = 'fit';
    
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
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function (id) {
    var groupsId = id || null;
    
    if (groupsId > 0) {
      this.frmCustomersGroups.baseParams['groups_id'] = groupsId;
     
      this.frmCustomersGroups.load({
        url: '<?php echo site_url('customers_groups/load_customers_groups'); ?>',
        success: function() {
          Toc.customers_groups.CustomersGroupsDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmCustomersGroups = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('customers_groups/save_customers_groups'); ?>',
      baseParams: {},
      fieldDefaults: {
        labelSeparator: '',
        anchor: '98%',
        labelWidth: 120
      },
      border: false,
      bodyPadding: 5,
      items: [
        {
          xtype: 'numberfield', 
          fieldLabel: '<?php echo lang('field_group_discount'); ?>  (%)', 
          name: 'customers_groups_discount',
          minValue: 0,
          maxValue: 100,
          value: 0, 
          allowBlank: false
        },
        {
          xtype: 'checkbox', 
          fieldLabel: '<?php echo lang('field_set_as_default'); ?>', 
          name: 'is_default',
          anchor: '',
          inputValue: 1 
        }
      ]
    });
    
    <?php
      $i = 1;
      foreach(lang_get_all() as $l)
      {
    ?>
        this.lang<?php echo $l['id']; ?> = Ext.create('Ext.form.TextField', {
          name: 'customers_groups_name[<?php echo $l['id']; ?>]',
          fieldLabel: '<?php echo $i != 1 ? '&nbsp;' : lang('field_group_name') ?>',
          allowBlank: false,
          labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>'
        });
        
        this.frmCustomersGroups.insert(<?php echo $i; ?>, this.lang<?php echo $l['id']; ?>);
    <?php
        $i++;
      }
    ?>
    
    return this.frmCustomersGroups;
  },
  
  submitForm : function() {
    this.frmCustomersGroups.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
         this.fireEvent('savesuccess', action.result.feedback);
         this.close();  
      },    
      failure: function(form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },  
      scope: this
    });   
  }
});

/* End of file customers_groups_dialog.php */
/* Location: ./templates/base/web/views/customers_groups/customers_groups_dialog.php */