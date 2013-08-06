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

  echo 'Ext.namespace("Toc.modules_payment");';
  
  require_once('modules_payment_config_dialog.php');
  require_once('modules_payment_grid.php');
?>

Ext.override(Toc.desktop.ModulesPaymentWindow, {

  createWindow : function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('modules_payment-win');
     
    if(!win){
      var grid = Ext.create('Toc.modules_payment.ModulesPaymentGrid', {owner: this});

      win = desktop.createWindow({
        id: 'modules_payment-win',
        title:'<?php echo lang('heading_title'); ?>',
        width:800,
        height:400,
        iconCls: 'icon-modules_payment-win',
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
    var dlg = desktop.getWindow('modules_payment-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow(config, Toc.modules_payment.PaymentModuleConfigDialog);
      
      dlg.on('saveSuccess', function(feedback) {
        this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
      }, this);
    }
    
    return dlg;
  }
});
