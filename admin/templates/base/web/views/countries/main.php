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

  echo 'Ext.namespace("Toc.countries");';
  
  include 'countries_dialog.php';
  include 'countries_grid.php';
  include 'countries_main_panel.php';
  include 'countries_zones_dialog.php';
  include 'countries_zones_grid.php';
?>

Ext.override(Toc.desktop.CountriesWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('countries-win');
     
    if (!win) {
      var pnl = Ext.create('Toc.countries.MainPanel');
      
      pnl.grdCountries.on('notifysuccess', this.onShowNotification, this);
      pnl.grdCountries.on('create', function() {this.onCreateCountry(pnl.grdCountries);}, this);
      pnl.grdCountries.on('edit', function(record) {this.onEditCountry(pnl.grdCountries, record);}, this);
      
      pnl.grdZones.on('notifysuccess', this.onShowNotification, this);
      pnl.grdZones.on('create', function(countriesId) {this.onCreateZones(pnl.grdZones, countriesId);}, this);
      pnl.grdZones.on('edit', function(params) {this.onEditZones(pnl.grdZones, params);}, this);

      win = desktop.createWindow({
        id: 'slide_images-win',
        title: '<?php echo lang('heading_countries_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-countries-win',
        layout: 'fit',
        items: pnl
      });
    }
           
    win.show();
  },
  
  onCreateCountry: function(grdCountries) {
    var dlgCountries = this.createCountriesDialog();
    
    this.onSaveSuccess(dlgCountries, grdCountries);
    
    dlgCountries.show();
  },
  
  onEditCountry: function(grdCountries, record) {
    var dlgCountries = this.createCountriesDialog();
    dlgCountries.setTitle(record.get('countries_name'));
    
    this.onSaveSuccess(dlgCountries, grdCountries);
    
    dlgCountries.show(record.get('countries_id'));
  },
  
  onCreateZones: function(grdZones, countriesId) {
    if (countriesId > 0) {
      var dlgZones = this.createZonesDialog();
      
      this.onSaveSuccess(dlgZones, grdZones);
      
      dlgZones.show(countriesId);
    }else {
      Ext.MessageBox.alert(TocLanguage.msgInfoTitle, TocLanguage.msgMustSelectOne);
    }
  },
  
  onEditZones: function(grdZones, params) {
    var dlgZones = this.createZonesDialog();
    dlgZones.setTitle(params.countriesName);
    
    var zoneId = params.record.get('zone_id');
    
    this.onSaveSuccess(dlgZones, grdZones);
    
    dlgZones.show(params.countriesId, zoneId);
  },
  
  createZonesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('zones-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.countries.ZonesDialog);
    }
    
    return dlg; 
  },
  
  createCountriesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('countries-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({},Toc.countries.CountriesDialog);
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
/* Location: ./templates/base/web/views/countries/main.php */