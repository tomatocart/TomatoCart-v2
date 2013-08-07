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

  echo 'Ext.namespace("Toc.homepage_info");';
  
  include 'homepage_info_panel.php';
  include 'homepage_info_dialog.php';
  include 'meta_info_panel.php';
?>

Ext.override(Toc.desktop.HomepageInfoWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('homepage_info-win');
    
    if (!win) {
      win = desktop.createWindow({}, Toc.homepage_info.HomepageInfoDialog);
      
      win.on('savesuccess', function(feedback) {
        this.app.showNotification({
          title: TocLanguage.msgSuccessTitle,
          html: feedback
        });
      }, this);
    }
    
    win.show();
  }
});

/* End of file homepage_info.php */
/* Location: ./templates/base/web/views/homepage_info/homepage_info.php */