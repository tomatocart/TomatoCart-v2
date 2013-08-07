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

  echo 'Ext.namespace("Toc.configuration");';
  
  include 'configurations_grid.php';
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
/* Location: ./templates/base/web/views/configurations/main.php */