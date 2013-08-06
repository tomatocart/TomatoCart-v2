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
  
  echo 'Ext.namespace("Toc.cache");';
  
  include 'cache_grid.php';
?>

Ext.override(Toc.desktop.CacheWindow, {
  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('cache-win');
     
    if(!win){
      var grd = Ext.create('Toc.cache.CacheGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'cache-win',
        title: '<?php echo lang('heading_cache_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-cache-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/cache/main.php */