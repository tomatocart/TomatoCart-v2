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

  echo 'Ext.namespace("Toc.specials");';
  
  include 'specials_dialog.php';
  include 'specials_grid.php';
?>

Ext.override(Toc.desktop.SpecialsWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('specials-win');
     
    if(!win){
      var grd = Ext.create('Toc.specials.SpecialsGrid');
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateSpecial(grd);}, this);
      grd.on('edit', function(record) {this.onEditSpecial(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'specials-win',
        title: '<?php echo lang('heading_specials_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-specials-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCreateSpecial: function(grd) {
    var dlg = this.createSpecialsDialog();
    dlg.setTitle('<?php echo lang("action_heading_new_special"); ?>');
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditSpecial: function(grd, record) {
    var specialsId = record.get('specials_id');
    var dlg = this.createSpecialsDialog();
    dlg.setTitle(record.get('products_name'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(specialsId);
  },
    
  createSpecialsDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('specials-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.specials.SpecialsDialog);
    }
    
    return dlg;
  },
  
  createBatchSpecialsDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('batch-specials-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.specials.BatchSpecialsDialog);
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
/* Location: ./templates/base/web/views/specials/main.php */