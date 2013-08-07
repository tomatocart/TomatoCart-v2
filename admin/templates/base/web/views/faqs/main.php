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

  echo 'Ext.namespace("Toc.faqs");';
  
  include('faqs_dialog.php');
  include('faqs_grid.php');
?>

Ext.override(Toc.desktop.FaqsWindow, {

  createWindow : function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('faqs-win');
     
    if (!win) {
      var grd = Ext.create('Toc.faqs.FaqsGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateFaqs(grd);}, this);
      grd.on('edit', function(record) {this.onEditFaqs(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'faqs-win',
        title: '<?php echo lang('heading_faqs_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-faqs-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCreateFaqs: function(grd) {
    var dlg = this.createFaqsDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditFaqs: function(grd, record) {
    var dlg = this.createFaqsDialog();
    dlg.setTitle(record.get("faqs_question"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get("faqs_id"));
  },
  
  createFaqsDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('faqs-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.faqs.FaqsDialog);
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
/* Location: ./templates/base/web/views/faqs/main.php */