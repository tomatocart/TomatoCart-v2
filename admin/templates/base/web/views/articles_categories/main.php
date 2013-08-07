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

    echo 'Ext.namespace("Toc.articles_categories");';

    include 'articles_categories_dialog.php';
    include 'articles_categories_grid.php';
    include 'articles_categories_general_panel.php';
    include 'articles_categories_meta_info_panel.php';
?>

Ext.override(Toc.desktop.ArticlesCategoriesWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('articles_categories-win');
     
    if(!win){
      grd = Ext.create('Toc.articles_categories.ArticlesCategoriesGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateArticlesCategory(grd);}, this);
      grd.on('edit', function(record) {this.onEditArticlesCategory(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'articles_categories-win',
        title: '<?php echo lang('heading_articles_categories_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-articles_categories-win',
        layout: 'fit',
        items: grd
      });
    }

    win.show();
  },
  
  onCreateArticlesCategory: function(grd) {
    var dlg = this.createArticleCategoriesDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditArticlesCategory: function(grd, record) {
    var dlg = this.createArticleCategoriesDialog();
    dlg.setTitle(record.get("articles_categories_name"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get("articles_categories_id"));
  },
  
  createArticleCategoriesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('articles_categories-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.articles_categories.ArticlesCategoriesDialog);
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
/* Location: ./templates/base/web/views/articles_categories/main.php */