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

  echo 'Ext.namespace("Toc.reviews");';
  
  include 'reviews_grid.php';
  include 'reviews_edit_dialog.php';
?>

Ext.override(Toc.desktop.ReviewsWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('reviews-win');
     
    if(!win){
      var grd = Ext.create(Toc.reviews.ReviewsGrid);
      
      grd.on('edit', function(record) {this.onEditReviews(grd, record);}, this);
      
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'reviews-win',
        title: '<?php echo lang('heading_title_reviews'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-reviews-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onEditReviews: function(grd, record) {
    var dlg = this.createReviewsEditDialog();
    
    dlg.setTitle(record.get('products_name'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('reviews_id'));
  },
    
  createReviewsEditDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('reviews-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.reviews.ReviewsEditDialog);
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
/* Location: ./templates/base/web/views/reviews/main.php */