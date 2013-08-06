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
  
  echo 'Ext.namespace("Toc.images");';
  
  include 'images_grid.php';
  include 'images_resize_dialog.php';
  include 'images_check_dialog.php';
?>

Ext.override(Toc.desktop.ImagesWindow, {
 
  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('images-win');
     
    if(!win){
      var grd = Ext.create('Toc.images.ImagesGrid');
      
      grd.on('checkimages', function(record) {this.onCheckImages(record);}, this);
      grd.on('resizeimages', function(record) {this.onResizeImages(record);}, this);
      
      win = desktop.createWindow({
        id: 'images-win',
        title: '<?php echo lang('heading_images_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-images-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCheckImages: function(record) {
    var dlg = this.createImagesCheckDialog();
    dlg.setTitle(record.get('module'));
    
    dlg.show();
  },
  
  onResizeImages: function(record) {
    var dlg = this.createImagesResizeDialog();
    dlg.setTitle(record.get('module'));
    
    dlg.show();
  },
    
  createImagesCheckDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('images-check-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.images.ImagesCheckDialog);
    }
    
    return dlg;
  },
  
  createImagesResizeDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('images-resize-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.images.ImagesResizeDialog);
    }
    
    return dlg;
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/images/main.php */