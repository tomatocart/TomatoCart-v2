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

  echo 'Ext.namespace("Toc.products");';
  
  include 'variants_data_panel.php';
  include 'variants_panel.php';
  include 'variants_groups_dialog.php';
  include 'images_grid.php';
  include 'images_panel.php';
  include 'accessories_panel.php';
  include 'xsell_products_panel.php';
  include 'categories_panel.php';
  include 'data_panel.php';
  include 'meta_panel.php';
  include 'general_panel.php';
  include 'categories_tree_panel.php';
  include 'products_main_panel.php';
  include 'products_grid.php';
  include 'products_dialog.php';
?>

Ext.override(Toc.desktop.ProductsWindow, {
  createWindow : function(){
    win = this.createProductsWindow();
    
    win.show();
  },

  createProductsWindow: function(productId) {
    var desktop = this.app.getDesktop();
    win = desktop.getWindow('products-win');

    if (!win) {
      pnl = Ext.create('Toc.products.ProductsMainPanel');
      pnl.on('createProduct', this.onCreateProduct, this);
      pnl.on('editProduct', this.onEditProduct, this);
      pnl.on('notifysuccess', this.onShowNotification, this);

      win = desktop.createWindow({
        id: 'products-win',
        title:'<?php echo lang('heading_products_title'); ?>',
        width:870,
        height:400,
        iconCls: 'icon-products-win',
        layout: 'fit',
        items: pnl
      });
    }

    return win;
  },
  
  
  onEditProduct: function(params) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('products-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({products_id: params.record.get('products_id')}, Toc.products.ProductDialog);
    }
    
    dlg.setTitle(params.record.get('products_name'));
    
    this.onSaveSuccess({dlg: dlg, grd: params.grdProducts});
    this.createPnlVariantsObservers(dlg.pnlVariants);
    
    dlg.show();
  },
  
  onCreateProduct: function(grdProducts) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('products-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.products.ProductDialog);
    }
    
    this.onSaveSuccess({'dlg': dlg, 'grd': grdProducts});
    this.createPnlVariantsObservers(dlg.pnlVariants);
    
    dlg.show();
  },
  
  createPnlVariantsObservers: function(pnlVariants) {
    pnlVariants.on('addvariantsgroups', function(group_ids) {
      var dlgVariantsGroup = this.createVariantsGroupDialog(group_ids);
      
      this.createDlgVariantsGroupObservers({'dlg': dlgVariantsGroup, 'scope': pnlVariants});
      
      dlgVariantsGroup.show();
    }, this);
  },
  
  createDlgVariantsGroupObservers: function(params) {
    params.dlg.on('groupChange', function(groups) {
      if (this.groupIds.length === 0) {
        this.generatePnlVariantsGroups(groups);
      } else {
        var ids = [];
        Ext.each(groups, function(group) {
          ids.push(group.id);
        });
        
        if ( this.groupIds.sort().toString() != ids.sort().toString()) {
          Ext.MessageBox.confirm(
            TocLanguage.msgWarningTitle, 
            '<?php echo lang('msg_warning_variants_groups_changed'); ?>',
            function(btn) {
              if (btn == 'yes') {
                this.deleteVariants();
                this.generatePnlVariantsGroups(groups);
              }
            }, this);
        }
      }
    }, params.scope);
  },
  
  createVariantsGroupDialog: function(group_ids) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('variants_group-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({group_ids: group_ids}, Toc.products.VariantsGroupsDialog);
    }
    
    return dlg;
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  } ,
  
  onSaveSuccess: function(params) {
    params.dlg.on('savesuccess', function(feedback) {
      params.grd.onRefresh();
      
      this.onShowNotification(feedback);
    }, this);
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/products/main.php */