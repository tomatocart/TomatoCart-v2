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

  echo 'Ext.namespace("Toc.categories");';
  
  include 'categories_grid.php';
  include 'categories_dialog.php';
  include 'categories_main_panel.php';
  include 'categories_general_panel.php';
  include 'categories_move_dialog.php';
  include 'categories_tree_panel.php';
  include 'categories_meta_info_panel.php';
?>

Ext.override(Toc.desktop.CategoriesWindow, {
  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('categories-win');
     
    if (!win) {
      var pnlCategories = Ext.create('Toc.categories.mainPanel');
      
      this.attachEventsToCategoriesGrd(pnlCategories);
      
      win = desktop.createWindow({
        id: 'categories-win',
        title: '<?php echo lang('heading_categories_title'); ?>',
        width: 870,
        height: 400,
        iconCls: 'icon-categories-win',
        layout: 'fit',
        items: pnlCategories
      });
    }
    
    win.show();
  },
  
  onCreateCategory: function(pnlCategoriesTree, categoriesId) {
    var dlg = this.createCategoriesDialog(categoriesId);
    
    this.onSaveSuccess(dlg, pnlCategoriesTree);
    
    dlg.show();
  },
  
  onEditCategory: function(pnlCategoriesTree, record) {
    var dlg = this.createCategoriesDialog();
    dlg.setTitle(record.get('categories_name'));
    
    this.onSaveSuccess(dlg, pnlCategoriesTree);
    
    dlg.show(record.get('categories_id'));
  },
  
  onMoveCategory: function(pnlCategoriesTree, record) {
    var dlg = this.createCategoriesMoveDialog();
    
    dlg.setTitle('<?php echo lang("action_heading_move_categories"); ?>');
    
    this.onSaveSuccess(dlg, pnlCategoriesTree);
    
    dlg.show(record.get('categories_id'));
  },
  
  onBatchMoveCategories: function(pnlCategoriesTree, categoriesIds) {
    var dlg = this.createCategoriesMoveDialog();
    
    dlg.setTitle('<?php echo lang("action_heading_batch_move_categories"); ?>');
    
    this.onSaveSuccess(dlg, pnlCategoriesTree);
    
    dlg.show(categoriesIds);
  },
  
  onSaveSuccess: function(dlg, pnlCategoriesTree) {
    dlg.on('savesuccess', function() {
      pnlCategoriesTree.refresh();
    }, this);
  },
  
  onDeleteCategorySuccess: function (pnlCategoriesTree, feedback) {
    this.onShowNotification(feedback);
    pnlCategoriesTree.refresh();
  },
  
  onShowNotification: function (feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  },
  
  createCategoriesDialog: function(categoriesId) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('categories-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({categoriesId: categoriesId}, Toc.categories.CategoriesDialog);
      
      dlg.on('savesuccess', this.onShowNotification, this);
    }

    return dlg;
  },
  
  createCategoriesMoveDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('categories-move-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.categories.CategoriesMoveDialog);
      
      dlg.on('savesuccess', this.onShowNotification, this);
    }
    
    return dlg;
  },
  
  attachEventsToCategoriesGrd: function(pnlCategories) {
    pnlCategories.grdCategories.on('deletesuccess', function(feedback) {
      this.onDeleteCategorySuccess(pnlCategories.pnlCategoriesTree, feedback);
    }, this);
    
    pnlCategories.grdCategories.on('create', function(categoriesId) {
      this.onCreateCategory(pnlCategories.pnlCategoriesTree, categoriesId);
    }, this);
    
    pnlCategories.grdCategories.on('edit', function(rec) {
      this.onEditCategory(pnlCategories.pnlCategoriesTree, rec);
    }, this);
    
    pnlCategories.grdCategories.on('movecategory', function(rec) {
      this.onMoveCategory(pnlCategories.pnlCategoriesTree, rec);
    }, this);
    
    pnlCategories.grdCategories.on('batchmovecategories', function(categoriesIds) {
      this.onBatchMoveCategories(pnlCategories.pnlCategoriesTree, categoriesIds);
    }, this);
    
    pnlCategories.grdCategories.on('notifysuccess', this.onShowNotification, this);
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/categories/main.php */