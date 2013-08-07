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

  echo 'Ext.namespace("Toc.tax_classes");';
  
  include 'tax_classes_dialog.php';
  include 'tax_classes_grid.php';
  include 'tax_classes_main_panel.php';
  include 'tax_rates_dialog.php';
  include 'tax_rates_grid.php';
?>

Ext.override(Toc.desktop.TaxClassesWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('tax_classes-win');
     
    if (!win) {
      var pnl = Ext.create('Toc.tax_classes.TaxClassesMainPanel');
      
      pnl.grdTaxClasses.on('notifysuccess', this.onShowNotification, this);
      pnl.grdTaxClasses.on('create', function() {this.onCreateTaxClasses(pnl.grdTaxClasses);}, this);
      pnl.grdTaxClasses.on('edit', function(record) {this.onEditTaxClasses(pnl.grdTaxClasses, record);}, this);
      
      pnl.grdTaxRates.on('notifysuccess', this.onShowNotification, this);
      pnl.grdTaxRates.on('create', function(taxClassId) {this.onCreateTaxRates(pnl.grdTaxRates, taxClassId);}, this);
      pnl.grdTaxRates.on('edit', function(params) {this.onEditTaxRates(pnl.grdTaxRates, params);}, this);
      
      win = desktop.createWindow({
        id: 'slide_images-win',
        title: '<?php echo lang('heading_tax_classes_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-tax_classes-win',
        layout: 'fit',
        items: pnl
      });
    }
           
    win.show();
  },
  
  onCreateTaxClasses: function(grdTaxClasses) {
    var dlgTaxClasses = this.createTaxClassesDialog();
    
    this.onSaveSuccess(dlgTaxClasses, grdTaxClasses);
    
    dlgTaxClasses.show();
  },
  
  onCreateTaxRates: function(grdTaxRates, taxClassId) {
    if (taxClassId > 0) {
      var dlg = this.createTaxRatesDialog();
       
      this.onSaveSuccess(dlg, grdTaxRates);
       
      dlg.show(taxClassId);
    } else {
      Ext.MessageBox.alert(TocLanguage.msgInfoTitle, TocLanguage.msgMustSelectOne);
    }   
  },
  
  onEditTaxClasses: function(grdTaxClasses, record) {
    var dlgTaxClasses = this.createTaxClassesDialog();
    dlgTaxClasses.setTitle(record.get('tax_class_title'));
    
    this.onSaveSuccess(dlgTaxClasses, grdTaxClasses);
    
    dlgTaxClasses.show(record.get('tax_class_id'));
  },
  
  onEditTaxRates: function(grdTaxRates, params) {
    var taxRatesId = params.record.get('tax_rates_id');
    var dlgTaxRates = this.createTaxRatesDialog();
    
    dlgTaxRates.setTitle(params.taxClassTitle);
    
    this.onSaveSuccess(dlgTaxRates, grdTaxRates);
    
    dlgTaxRates.show(params.taxClassId, taxRatesId);
  },
  
  createTaxClassesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('tax-class-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.tax_classes.TaxClassesDialog);
    }
    
    return dlg;
  },
  
  createTaxRatesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('tax-rate-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.tax_classes.TaxRatesDialog);
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
/* Location: ./templates/base/web/views/tax_classes/main.php */