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
 * @filesource main.php
 */

  echo 'Ext.namespace("Toc.modules_shipping");';
  
  require_once('modules_shipping_config_dialog.php');
  require_once('modules_shipping_grid.php');
?>

Ext.override(Toc.desktop.ModulesShippingWindow, {

  createWindow : function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('modules_shipping-win');
     
    if(!win){
      var grid = Ext.create('Toc.modules_shipping.ModulesShippingGrid', {owner: this});

      win = desktop.createWindow({
        id: 'modules_shipping-win',
        title:'<?php echo lang('heading_title'); ?>',
        width:800,
        height:400,
        iconCls: 'icon-modules_shipping-win',
        constrainHeader:true,
        layout: 'fit',
        items: grid,
        owner: this
      });
    }
    
    win.show();
  },
  
  createConfigurationDialog: function(config) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('modules_shipping-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow(config, Toc.modules_shipping.ShippingModuleConfigDialog);
      
      dlg.on('saveSuccess', function(feedback) {
        this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
      }, this);
    }
    
    return dlg;
  }
});
