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

  echo 'Ext.namespace("Toc.weight_classes");';
  
  include 'weight_classes_dialog.php';
  include 'weight_classes_grid.php';
?>

Ext.override(Toc.desktop.WeightClassesWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('weight_classes-win');
     
    if(!win){
      var grd = Ext.create('Toc.weight_classes.WeightClassesGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateWeightClasses(grd);}, this);
      grd.on('edit', function(record) {this.onEditWeightClasses(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'weight_classes-win',
        title: '<?php echo lang('heading_weight_classes_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-weight_classes-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCreateWeightClasses: function(grd) {
    var dlg = this.createWeightClassesDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditWeightClasses: function(grd, record) {
    var dlg = this.createWeightClassesDialog();
    dlg.setTitle(record.get('action_heading_new_weight_class'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('weight_class_id'));
  },
    
  createWeightClassesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('weight_classes-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.weight_classes.WeightClassesDialog);
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
/* Location: ./templates/base/web/views/weight_classes/main.php */