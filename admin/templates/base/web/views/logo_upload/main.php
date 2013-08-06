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

  echo 'Ext.namespace("Toc.logo_upload");';
  
  include 'logo_upload_dialog.php';
?>

Ext.override(Toc.desktop.LogoUploadWindow, {

  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('logo_upload-win');
    
    if (!win) {
      win = desktop.createWindow({}, Toc.logo_upload.LogoUploadDialog);
      
      win.on('savesuccess', this.onShowNotification, this);
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
/* Location: ./templates/base/web/views/logo_upload/main.php */