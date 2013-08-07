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

  echo 'Ext.namespace("Toc.reports_customers");';
  
  include 'best_orders_panel.php';
  include 'orders_total_panel.php';
?>

Ext.override(Toc.desktop.ReportsCustomersWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow(this.id);
     
    if (!win) {
      if (this.params.report == 'orders-total') {
        var pnl = Ext.create('Toc.reports_customers.OrdersTotalPanel');
      } else {
        var pnl = Ext.create('Toc.reports_customers.BestOrdersPanel');
      }
      
      win = desktop.createWindow({
        id: this.id,
        title: this.title,
        width: 800,
        height: 400,
        iconCls: this.iconCls,
        layout: 'fit',
        items: pnl
      });
    }
    
    win.show();
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/reports_customers/main.php */