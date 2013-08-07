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

Ext.define('Toc.orders_status.OrdersStatusDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'orders_status-dialog-win';
    config.title = '<?php echo lang('action_heading_new_order_status'); ?>';
    config.layout = 'fit';
    config.width = 450;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-orders_status-win';
    
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
        handler: function(){
          this.close();
        },
        scope:this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function(id) {
    var ordersStatusId = id || null;      
    
    if (ordersStatusId > 0) {
      this.frmOrdersStatus.form.baseParams['orders_status_id'] = ordersStatusId;
      
      this.frmOrdersStatus.load({
        url: '<?php echo site_url('orders_status/load_orders_status'); ?>',
        params: {
          orders_status_id: ordersStatusId
        },
        success: function(form, action) {
          if (action.result.data['default'] == '1') {
            this.chkDefault.disable();
          }
          
          Toc.orders_status.OrdersStatusDialog.superclass.show.call(this);
        },
        failure: function(form, action) {
          Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this       
      });
    } else {   
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmOrdersStatus = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('orders_status/save_orders_status'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        anchor: '97%',
        labelSeparator: '',
        labelWidth: 150
      }
    });
    
    <?php
      $i = 1;
      
      foreach(lang_get_all() as $l)
      {
    ?>
        var txtLang<?php echo $l['id']; ?> = Ext.create('Ext.form.TextField', {
          name: 'name[<?php echo $l['id']; ?>]',
          fieldLabel: '<?php echo $i != 1 ? '&nbsp;' : lang('field_name'); ?>',
          allowBlank: false,
          labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>'
        });
        
        this.frmOrdersStatus.add(txtLang<?php echo $l['id']; ?>);
    <?php
        $i++;
      }
    ?>
    
    this.chkDefault = Ext.create('Ext.form.Checkbox', {
      name: 'default',
      fieldLabel: '<?php echo lang('field_set_as_default'); ?>'
    });
    
    this.frmOrdersStatus.add(this.chkDefault);
    
    this.frmOrdersStatus.add({xtype: 'checkbox', name: 'public_flag', fieldLabel: '<?php echo lang('field_public_flag'); ?>'});
    
    return this.frmOrdersStatus;
  },
  
  submitForm: function() {
    this.frmOrdersStatus.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action){
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

/* End of file orders_status_dialog.php */
/* Location: ./templates/base/web/views/orders_status/orders_status_dialog.php */