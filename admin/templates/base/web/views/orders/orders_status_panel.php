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

Ext.define('Toc.orders.OrdersStatusPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config = config || {};
    config.title = '<?php echo lang('section_status_history'); ?>';
    config.layout = 'border';
    config.border = false;
    
    config.items = this.buildForm(config.ordersId);
    
    this.callParent([config]);
  },
  
  buildForm: function(ordersId) {
    this.grdOrdersStatus = this.getOrdersStatusGrid(ordersId);
    this.frmOrdersStatus = this.getOrdersStatusForm(ordersId);
    
    return [this.grdOrdersStatus, this.frmOrdersStatus];
  },
  
  getOrdersStatusGrid: function(ordersId) {
    var store = Ext.create('Ext.data.Store', {
      fields: [
        'orders_status_history_id',
        'date_added',
        'status',
        'comments',
        'customer_notified'
      ],
      proxy: {
        type: 'ajax',
        url: '<?php echo site_url('orders/list_orders_status'); ?>',
        extraParams: {
          orders_id: ordersId   
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    var grdOrdersStatus = Ext.create('Ext.grid.Panel' , {
      border: false,
      region: 'center',
      store: store,
      columns: [
        { header: '<?php echo lang('table_heading_date_added'); ?>', dataIndex: 'date_added', width: 120, align: 'center'},
        { header: '<?php echo lang('table_heading_status'); ?>', dataIndex: 'status', width: 120, align: 'center'},
        { header: '<?php echo lang('table_heading_comments'); ?>', dataIndex: 'comments', align: 'center', flex: 1},
        { header: '<?php echo lang('table_heading_customer_notified'); ?>', dataIndex: 'customer_notified',  width: 120, align: 'center'}
      ]
    });
    
    return grdOrdersStatus;
  },
  
  getOrdersStatusForm: function(ordersId) {
    var dsStatus = Ext.create('Ext.data.Store', {
      fields: [
        'status_id',
        'status_name'
      ],
      proxy: {
        type: 'ajax',
        url: '<?php echo site_url('orders/get_status'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.frmOrdersStatus = Ext.create('Ext.form.Panel', {
      region: 'south',
      border: false,
      height: 200,
      bodyPadding: 10, 
      url: '<?php echo site_url('orders/update_orders_status'); ?>',
      baseParams: {  
        orders_id: ordersId
      },
      fieldDefaults: {
        labelSeparator: '',
        labelWidth: 200
      },
      items: [
        {xtype: 'combobox', fieldLabel: '<?php echo lang('field_status'); ?>', store: dsStatus, displayField: 'status_name', valueField: 'status_id', name: 'status', editable: false, queryMode: 'local', allowBlank: false},                        
        {xtype: 'textareafield', fieldLabel: '<?php echo lang('field_add_comment'); ?>', name: 'comment', anchor: '97%'},
        {xtype: 'checkboxfield', fieldLabel: '<?php echo lang('field_notify_customer'); ?>', name: 'notify_customer'},
        {xtype: 'checkboxfield', fieldLabel: '<?php echo lang('field_notify_customer_with_comments'); ?>', name: 'notify_with_comments'}
      ],
      buttons: [{text: '<?php echo lang('button_update');?>', iconCls:'refresh', handler: this.submitForm, scope: this}]
    });
    
    return this.frmOrdersStatus;
  },
  
  submitForm : function() {
    this.frmOrdersStatus.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action){
         this.grdOrdersStatus.getStore().load(); 
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

/* End of file orders_status_panel.php */
/* Location: ./system/modules/orders/views/orders_status_panel.php */