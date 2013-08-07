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
 * @filesource system/modules/reports_products/views/main.php
 */

  echo 'Ext.namespace("Toc.reports_products");';
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
/* Location: system/modules/reports_products/views/main.php */
