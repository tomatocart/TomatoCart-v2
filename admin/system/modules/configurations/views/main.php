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

  echo 'Ext.namespace("Toc.configuration");';
?>

Ext.override(Toc.desktop.ConfigurationWindow, {
  createWindow : function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow(this.id);
      
    if (!win) {
      var grd = Ext.create('Toc.configurations.ConfigurationGrid', {gID: this.params.gID, owner: this});
      grd.on('saveproperty', this.onSaveProperty, this);
      
      win = desktop.createWindow({
        id: this.id,
        title: this.title,
        width: 800,
        height: 450,
        iconCls: 'icon-configuration-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onSaveProperty: function(feedback) {
    this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
  }
});


/* End of file main.php */
/* Location: ./system/modules/configuration/main.php */