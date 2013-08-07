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

  echo 'Ext.namespace("Toc.products_expected");';
  
  include 'products_expected_grid.php';
  include 'products_expected_dialog.php';
?>

Ext.override(Toc.desktop.ProductsExpectedWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('products_expected-win');
     
    if(!win){
      var grd = Ext.create('Toc.products_expected.ProductsExpectedGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('edit', function(record) {this.onEditProductsExpected(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'products_expected-win',
        title: '<?php echo lang('heading_products_expected_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-products_expected-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onEditProductsExpected: function(grd, record) {
    
    var dlg = this.createProductsExpectedDialog();
    dlg.setTitle(record.get('products_name'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('products_id'));
  },
    
  createProductsExpectedDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('products_expected-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.products_expected.ProductsExpectedDialog);
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
/* Location: ./templates/base/web/views/products_expected/main.php */