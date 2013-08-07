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

  echo 'Ext.namespace("Toc.guest_book");';
  
  require_once('guest_book_grid.php');
  require_once('guest_book_dialog.php');
?>

Ext.override(Toc.desktop.GuestBookWindow, {

  createWindow: function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('guest_book-win');
     
    if (!win) {
      var grd = Ext.create('Toc.guest_book.GuestBookGrid');
      
      grd.on('create', this.onCreateGuestBook, this);
      grd.on('edit', this.onEditGuestBook, this);
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'guest_book-win',
        title: '<?php echo lang('heading_guest_book_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-guest_book-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCreateGuestBook: function(params) {
    var dlg = this.createGuestBookDialog();
    
    this.onSaveSuccess(dlg, params);
    
    dlg.show();
  },
  
  onEditGuestBook: function(params) {
    var dlg = this.createGuestBookDialog();
    
    dlg.setTitle(params.record.get("title"));
    
    this.onSaveSuccess(dlg, params);
    
    dlg.show(params.record.get('guest_books_id'));
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  },
  
  onSaveSuccess: function(dlg, params) {
    dlg.on('savesuccess', function(feedback) {
      params.grd.onRefresh();
      
      this.onShowNotification(feedback);
    }, this);
  },
  
  createGuestBookDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('guest_book-dialog');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.guest_book.GuestBookDialog);
    }
    
    return dlg;
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/guestbook/main.php */