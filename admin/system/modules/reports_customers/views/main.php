<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource system/modules/reports_customers/views/main.php
 */

  echo 'Ext.namespace("Toc.reports_customers");';
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
/* Location: system/modules/reports_customers/views/main.php */