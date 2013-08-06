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

  echo 'Ext.namespace("Toc.currencies");';
  
  include 'currencies_grid.php';
  include 'currencies_dialog.php';
  include 'currencies_update_rates_dialog.php';
?>

Ext.override(Toc.desktop.CurrenciesWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('currencies-win');
     
    if(!win){
      var grd = Ext.create('Toc.currencies.CurrenciesGrid');
      
      grd.on('create', function() {this.onCreateCurrency(grd);}, this);
      grd.on('edit', function(record) {this.onEditCurrency(grd, record);}, this);
      grd.on('updaterates', function(record) {this.onUpdateRates(grd, record);}, this);
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'currencies-win',
        title: '<?php echo lang("heading_currencies_title"); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-currencies-win',
        layout: 'fit',
        items: grd
      });
    }

    win.show();
  },
  
  onCreateCurrency: function(grd) {
    dlg = this.createCurrenciesDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditCurrency: function(grd, record) {
    var dlg = this.createCurrenciesDialog();
    dlg.setTitle(record.get("title"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get("currencies_id"));
  },
  
  onUpdateRates: function(grd, record) {
    var dlg = this.createUpdateRatesDialog(record.get('currencies_id'));
    dlg.setTitle(record.get("title"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  createCurrenciesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('currencies-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.currencies.CurrenciesDialog);
    }
    
    return dlg;
  },
  
  createUpdateRatesDialog: function(currenciesId) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('currencies-update-rates-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({currenciesId: currenciesId}, Toc.currencies.CurrenciesUpdateRatesDialog);
    }
    
    return dlg;
  },
  
  onSaveSuccess: function(dlg, grd) {
    dlg.on('savesuccess', function(feedback) {
      this.onShowNotification(feedback);
      
      grd.onRefresh();
    }, this);
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/currencies/main.php */