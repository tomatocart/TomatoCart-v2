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

  echo 'Ext.namespace("Toc.feature_products_manager");';
  
  include 'feature_products_manager_grid.php';
?>

Ext.override(Toc.desktop.FeatureProductsManagerWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('feature_products_manager-win');
     
    if(!win){
      var grd = Ext.create('Toc.feature_products_manager.ProductsManagerGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'feature_products_manager-win',
        title: '<?php echo lang('heading_feature_products_manager_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-feature_products_manager-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/feature_products_manager/main.php */