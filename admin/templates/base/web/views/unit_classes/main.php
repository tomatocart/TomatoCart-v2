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

  echo 'Ext.namespace("Toc.unit_classes");';
  
  include 'unit_classes_dialog.php';
  include 'unit_classes_grid.php';
?>

Ext.override(Toc.desktop.UnitClassesWindow, {

  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('unit_classes-win');
     
    if (!win) {
      var grd = Ext.create('Toc.unit_classes.UnitClassesGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateUnitClasses(grd);}, this);
      grd.on('edit', function(record) {this.onEditUnitClasses(grd, record);}, this);
      
      win = desktop.createWindow ({
        id: 'unit_classes-win',
        title: '<?php echo lang('heading_unit_classes_title'); ?>',
        width: 600,
        height: 400,
        iconCls: 'icon-unit_classes-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCreateUnitClasses: function(grd) {
    var dlg = this.createUnitClassesDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditUnitClasses: function(grd, record) {
    var dlg = this.createUnitClassesDialog();
    dlg.setTitle(record.get("unit_class_title"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get("unit_class_id"));
  },
  
  createUnitClassesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('unit_classes-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.unit_classes.UnitClassesDialog);
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
/* Location: ./templates/base/web/views/unit_classes/main.php */