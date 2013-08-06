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


  echo 'Ext.namespace("Toc.invoices");';
  
  include 'invoices_grid.php';
  include 'invoices_dialog.php';
  include 'invoices_status_panel.php';
  include 'templates/base/web/views/orders/orders_products_grid.php';
  include 'templates/base/web/views/orders/orders_transaction_grid.php';
?>

Ext.override(Toc.desktop.InvoicesWindow, {

  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('invoices-win');
     
    if (!win) {
      var grd = Ext.create('Toc.invoices.InvoicesGrid');
      
      grd.on('view', this.onView, this);
      
      win = desktop.createWindow({
        id: 'invoices-win',
        title: '<?php echo lang('heading_invoices_title'); ?>',
        width: 850,
        height: 400,
        iconCls: 'icon-invoices-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onView: function(record) {
    var dlg = this.createInvoicesDialog({ordersId: record.get('orders_id')});
    
    dlg.setTitle(record.get('invoices_number') + ': ' + record.get('customers_name'));
    
    dlg.show();
  },
  
  createInvoicesDialog: function(config) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('invoices-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow(config, Toc.invoices.InvoicesDialog);
    }
    
    return dlg;
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/invoices/main.php */