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

  echo 'Ext.namespace("Toc.manufacturers");';
  
  include 'manufacturers_grid.php';
  include 'manufacturers_dialog.php';
  include 'manufacturers_general_panel.php';
  include 'manufacturers_meta_info_panel.php';
?>

Ext.override(Toc.desktop.ManufacturersWindow, {

  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('manufacturers-win');
     
    if (!win) {
      grd = Ext.create('Toc.manufacturers.ManufacturersGrid');
      
      grd.on('create', function() {this.onCreateManufacturer(grd);}, this);
      grd.on('edit', function(record) {this.onEditManufacturer(grd, record);}, this);
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'manufacturers-win',
        title: '<?php echo lang('heading_manufacturers_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-manufacturers-win',
        layout: 'fit',
        items: grd
      });
    }
           
    win.show();
  },
  
  onCreateManufacturer: function(grd) {
    var dlg = this.createManufacturersDialog();
    dlg.setTitle('<?php echo lang('heading_new_manufacturers_title'); ?>');
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },

  createManufacturersDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('manufacturers_dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.manufacturers.ManufacturersDialog);
    }
    
    return dlg;
  },
  
  onEditManufacturer: function(grd, record) {
    var dlg = this.createManufacturersDialog();
    dlg.setTitle(record.get('manufacturers_name'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('manufacturers_id'));
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
/* Location: ./templates/base/web/views/manufacturers/main.php */