<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource
 */

    echo 'Ext.namespace("Toc.slideImages");';

    require_once 'slide_images_grid.php';
    require_once 'slide_images_dialog.php';
?>

Ext.override(Toc.desktop.SlideImagesWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('slide_images-win');
     
    if (!win) {
      grd = Ext.create('Toc.slideImages.SlideImagesGrid');
      
      grd.on('create', function() {this.onCreateSlideImage(grd);}, this);
      grd.on('edit', function(record) {this.onEditSlideImage(grd, record);}, this )
      grd.on('notifysuccess', this.onShowNotification, this);

      win = desktop.createWindow({
        id: 'slide_images-win',
        title: '<?php echo lang('heading_slide_images_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-slide_images-win',
        layout: 'fit',
        items: grd
      });
    }
           
    win.show();
  },
  
  onCreateSlideImage: function(grd) {
    var dlg = this.createSlideImagesDialog();
    dlg.setTitle('<?php echo lang('heading_title_new_slide_image'); ?>');
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  createSlideImagesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('slide_images_dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.slideImages.SlideImagesDialog);
    }
    
    return dlg;
  },
  
  onEditSlideImage: function(grd, record) {
    var dlg = this.createSlideImagesDialog();
    dlg.setTitle('<?php echo lang('heading_title_edit_slide_image'); ?>');
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('image_id'));
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
/* Location: ./templates/base/web/views/slide_images/main.php */