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

  echo 'Ext.namespace("Toc.modules_order_total");';
  
  require_once('modules_order_total_config_dialog.php');
  require_once('modules_order_total_grid.php');
?>

Ext.override(Toc.desktop.ModulesOrderTotalWindow, {

  createWindow : function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('modules_order_total-win');
     
    if(!win){
      var grid = Ext.create('Toc.modules_order_total.ModulesOrderTotalGrid', {owner: this});

      win = desktop.createWindow({
        id: 'modules_order_total-win',
        title:'<?php echo lang('heading_title'); ?>',
        width:800,
        height:400,
        iconCls: 'icon-modules_order_total-win',
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
    var dlg = desktop.getWindow('modules_order_total-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow(config, Toc.modules_order_total.OrderTotalModuleConfigDialog);
      
      dlg.on('saveSuccess', function(feedback) {
        this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
      }, this);
    }
    
    return dlg;
  }
});
