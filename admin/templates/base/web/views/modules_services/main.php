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

  echo 'Ext.namespace("Toc.modules_services");';
  
  require_once('modules_services_config_dialog.php');
  require_once('modules_services_grid.php');
?>

Ext.override(Toc.desktop.ModulesServicesWindow, {

  createWindow : function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('modules_services-win');
     
    if(!win){
      var grid = Ext.create('Toc.modules_services.ModulesServicesGrid', {owner: this});

      win = desktop.createWindow({
        id: 'modules_services-win',
        title:'<?php echo lang('heading_title'); ?>',
        width:800,
        height:400,
        iconCls: 'icon-modules_services-win',
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
    var dlg = desktop.getWindow('modules_services-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow(config, Toc.modules_service.ServicesModuleConfigDialog);
      
      dlg.on('saveSuccess', function(feedback) {
        this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
      }, this);
    }
    
    return dlg;
  }
});
