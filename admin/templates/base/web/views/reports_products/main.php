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

    echo 'Ext.namespace("Toc.reports_products");';
      
    require_once 'products_purchased_panel.php';
    require_once 'products_viewed_panel.php';
    require_once 'categories_purchased_panel.php';
    require_once 'low_stock_panel.php';
?>

Ext.override(Toc.desktop.ReportsProductsWindow, {

  createWindow: function() {
    desktop = this.app.getDesktop();
    win = desktop.getWindow(this.id);
     
    if (!win) {
      switch(this.params.report) {
        case 'products-purchased':
          var pnl = Ext.create('Toc.reports_products.ProductsPurchasedPanel');
          break;
        case 'products-viewed':
          var pnl = Ext.create('Toc.reports_products.ProductsViewedPanel');
          break;
        case 'categories-purchased':
          var pnl = Ext.create('Toc.reports_products.CategoriesPurchasedPanel');
          break;
        default:
          var pnl = Ext.create('Toc.reports_products.LowStockPanel');
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
/* Location: ./templates/base/web/views/reports_products/main.php */