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

// ------------------------------------------------------------------------

  echo 'Ext.namespace("Toc.orders");';
  
  include 'orders_grid.php';
  include 'orders_dialog.php';
  include 'orders_products_grid.php';
  include 'orders_status_panel.php';
  include 'orders_transaction_grid.php';
  include 'orders_delete_confirm_dialog.php';
?>

Ext.override(Toc.desktop.OrdersWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('orders-win');
     
    if (!win) {
      grd = Ext.create('Toc.orders.OrdersGrid');
      
      grd.on('delete', function(record) {this.onDeleteOrder(grd, record);}, this);
      grd.on('batchdelete', function(params) {this.onBatchDeleteOrder(grd, params);}, this);
      grd.on('view', this.onViewOrder, this);
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'orders-win',
        title: '<?php echo lang('heading_orders_title'); ?>',
        width: 850,
        height: 400,
        iconCls: 'icon-orders-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onDeleteOrder: function(grd, record) {
    var dlg = this.createOrdersDeleteConfirmDialog(grd, 'delete_order');
    
    dlg.show('delete_order', record.get('orders_id'), record.get('orders_id') + ': ' + record.get('customers_name'));
  },
  
  onBatchDeleteOrder: function(grd, params) {
    var dlg = this.createOrdersDeleteConfirmDialog(grd, 'delete_orders');
    
    dlg.show('delete_orders', Ext.JSON.encode(params.ordersIds), params.orders);
  },
  
  onViewOrder: function(record) {
    var dlg = this.createOrdersDialog({ordersId: record.get("orders_id")});
    
    dlg.setTitle(record.get('orders_id') + ': ' + record.get('customers_name'));
    
    dlg.on('updatesuccess', function(feedback) {
      this.onShowNotification(feedback);
    }, this);
    
    dlg.show();
  },
  
  createOrdersDialog: function(config) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('orders-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow(config, Toc.orders.OrdersDialog);
    }
    
    return dlg;
  },
  
  createOrdersDeleteConfirmDialog: function(grd, action) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('orders-delete-confirm-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({'action': action}, Toc.orders.OrdersDeleteComfirmDialog);
      
      dlg.on('deletesuccess', function(feedback) {
        grd.onRefresh();
        
        this.onShowNotification(feedback);
      }, this);
    }

    return dlg;
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/orders/main.php */