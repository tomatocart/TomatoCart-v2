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

  echo 'Ext.namespace("Toc.articles");';
  
  include 'articles_grid.php';
  include 'articles_dialog.php';
  include 'articles_general_panel.php';
  include 'articles_meta_info_panel.php';
?>

Ext.override(Toc.desktop.ArticlesWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('articles-win');
     
    if(!win){
      grd = Ext.create('Toc.articles.ArticlesGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateArticles(grd);}, this);
      grd.on('edit', function(record) {this.onEditArticles(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'articles-win',
        title: '<?php echo lang('heading_articles_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-articles-win',
        layout: 'fit',
        items: grd
      });
    }

    win.show();
  },
  
  onCreateArticles: function(grd) {
    var dlg = this.createArticlesDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditArticles: function(grd, record) {
    var dlg = this.createArticlesDialog();
    dlg.setTitle(record.get("articles_name"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get("articles_id"));
  },
  
  createArticlesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('articles-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.articles.ArticlesDialog);
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
/* Location: ./templates/base/web/views/articles/main.php */