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

  echo 'Ext.namespace("Toc.orders_status");';
  
  include 'orders_status_grid.php';
  include 'orders_status_dialog.php';
?>

Ext.override(Toc.desktop.OrdersStatusWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('orders_status-win');
     
    if(!win){
      var grd = Ext.create('Toc.orders_status.OrdersStatusGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateOrdersStatus(grd);}, this);
      grd.on('edit', function(record) {this.onEditOrdersStatus(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'orders_status-win',
        title: '<?php echo lang('heading_orders_status_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-orders_status-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCreateOrdersStatus: function(grd) {
    var dlg = this.createOrdersStatusDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditOrdersStatus: function(grd, record) {
    var dlg = this.createOrdersStatusDialog();
    dlg.setTitle(record.get("orders_status_name"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get("orders_status_id"));
  },
  
  createOrdersStatusDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('orders_status-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.orders_status.OrdersStatusDialog);
    }
    
    return dlg;
  },
  
  onSaveSuccess: function(dlg, grd) {
    dlg.on('savesuccess', function(feedback) {
      this.onShowNotification(feedback);
      
      grd.onRefresh();
    }, this);
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/orders_status/main.php */