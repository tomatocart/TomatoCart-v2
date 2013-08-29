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

Ext.define('Toc.orders.OrdersDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'orders-dialog-win';
    config.title = '<?php echo lang('heading_orders_title'); ?>';
    config.width = 700;
    config.height = 520;
    config.layout = 'fit';
    config.modal = true;
    config.iconCls = 'icon-orders-win';
    config.items = this.buildForm(config.ordersId);
    
   config.tplSummary = new Ext.Template(
    '<table width="100%">',
      '<tr>',
        '<td width= "33%" valign="top">',
          '<h1><?php echo icon('personal.png'); ?><span style= "margin-left:4px;"><?php echo lang('subsection_customer'); ?></span></h1>',
          '{customer}',
        '</td>',
       
        '<td width= "33%" valign="top">',
          '<h1><?php echo icon('home.png'); ?><span style= "margin-left:4px;"><?php echo lang('subsection_shipping_address'); ?></span></h1>', 
          '{shippingAddress}', 
        '</td>',
       
        '<td valign="top">',
          '<h1><?php echo icon('bill.png'); ?><span style= "margin-left:4px;"><?php echo lang('subsection_billing_address'); ?></span></h1>',
          '{billingAddress}',
        '</td>',
      '</tr>',
      '<tr>',    
        '<td width= "33%" valign="top">',
          '<h1><?php echo icon('payment.png'); ?><span style= "margin-left:4px;"><?php echo lang('subsection_payment_method'); ?></span></h1>',
          '{paymentMethod}',
        '</td>',
        '<td width= "33%" valign="top">',
          '<h1><?php echo icon('history.png'); ?><span style= "margin-left:4px;"><?php echo lang('subsection_status'); ?></h1>',
          '{status}',
        '</td>',
        '<td valign="top">',
          '<h1><?php echo icon('calculator.png'); ?><span style= "margin-left:4px;"><?php echo lang('subsection_total'); ?></span></h1>',
          '{total}',
        '</td>',
      '</tr>',
    '</table>',
    '<div>',
      '<fieldset style="padding:5px;border: 1px solid #DDDDDD; float:left;height:110px;width:310px;">',
        '<legend style="color:#0069BF; font-weight:bolder;">',
          '<?php echo lang('subsection_customers_comments');?>',
        '</legend>',
        '<p>',
          '{customers_comment}',
        '</p>',
      '</fieldset>',
      '<fieldset style="padding:5px;border: 1px solid #DDDDDD; float:right;height:110px;width:310px;">',
         '<legend style="color:#0069BF; font-weight:bolder;">',
           '<?php echo lang('subsection_internal_comments');?>',
         '</legend>', 
         '<textarea id="admin-comment" class="x-form-textarea x-form-field" style="width: 295px; height: 85px">',
          '{admin_comment}',
         '</textarea>',
       '</fieldset>',
     '</div>'
    );
    
    config.buttons = [
      {
        text: TocLanguage.btnClose,
        handler: function() { 
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'updatesuccess': true});
    
    this.callParent([config]);
  },
  
  buildForm: function(ordersId) {
    this.pnlSummary = Ext.create('Ext.Panel', {
      title: '<?php echo lang('section_summary'); ?>',
      border: false,
      cls: 'pnlSummary',
      bodyPadding: 10,
      buttons: [{
        text: '<?php echo lang('button_update'); ?>',
        handler: function() { 
          this.updateComment(ordersId);
        },
        scope: this
      }]
    });
    
    this.grdProducts = Ext.create('Toc.orders.OrdersProductsGrid', {ordersId: ordersId});
    this.grdTransactionHistory = Ext.create('Toc.orders.OrdersTransactionGrid', {ordersId: ordersId});
    this.pnlOrdersStatus = Ext.create('Toc.orders.OrdersStatusPanel', {ordersId: ordersId});
    
    this.tabOrders = Ext.create('Ext.TabPanel', {
      activeTab: 0,
      border: false,
      defaults:{autoScroll: true},
      items: [this.pnlSummary, this.grdProducts, this.grdTransactionHistory, this.pnlOrdersStatus]
    });
    
    this.loadSummaryPanel(ordersId);
    
    return this.tabOrders;
  },
  
  loadSummaryPanel: function(ordersId) {
    Ext.Ajax.request({
      url: '<?php echo site_url('orders/load_summary_data'); ?>',
      params: {
        orders_id: ordersId        
      },
      success: function(response) {
        var data = Ext.decode(response.responseText);
        var html = this.tplSummary.apply(data);
        this.pnlSummary.update(html);
      },
      scope: this
    });
  },
  
  updateComment: function(ordersId) {
    var adminComment = Ext.get('admin-comment').getValue();
    
    Ext.Ajax.request({
      url: '<?php echo site_url('orders/update_comment'); ?>',
      params: {
        admin_comment: adminComment,
        orders_id: ordersId     
      },
      callback: function(options, success, response) {
        result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          this.fireEvent('updatesuccess', result.feedback);
        } else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
        }
      },
      scope: this
    });
  }
});

/* End of file orders_dialog.php */
/* Location: ./templates/base/web/views/orders/orders_dialog.php */