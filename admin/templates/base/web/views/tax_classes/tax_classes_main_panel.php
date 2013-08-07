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
 * @filesource ./system/modules/countries/tax_classes_main_panel.php
 */
?>

Ext.define('Toc.tax_classes.TaxClassesMainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.layout = 'border';
    config.border = false;
    
    config.grdTaxClasses = Ext.create('Toc.tax_classes.TaxClassesGrid');
    config.grdTaxRates = Ext.create('Toc.tax_classes.TaxRatesGrid');
    
    config.grdTaxClasses.on('selectchange', this.onGrdTaxClassesSelectChange, this);
    config.grdTaxClasses.getStore().on('load', this.onGrdTaxClassesLoad, this);
    
    config.grdTaxRates.getStore().on('load', this.onGrdTaxRatesLoad, this);
    
    config.items = [config.grdTaxClasses, config.grdTaxRates];
    
    this.callParent([config]);
  },
  
  onGrdTaxClassesLoad: function() {
    if (this.grdTaxClasses.getStore().getCount() > 0) {
      this.grdTaxClasses.getSelectionModel().select(0);
      record = this.grdTaxClasses.getStore().getAt(0);
      
      this.onGrdTaxClassesSelectChange(record);
    }else {
      this.grdTaxRates.onRefresh();
    }
  },
  
  onGrdTaxClassesSelectChange: function(record) {
    this.grdTaxRates.setTitle('<?php echo lang('heading_tax_classes_title'); ?>: '+ record.get('tax_class_title'));
    this.grdTaxRates.iniGrid(record);
  },
  
  onGrdTaxRatesLoad: function() {
    record = this.grdTaxClasses.getSelectionModel().getLastSelected() || null;
    
    if (record) {
      record.set('tax_total_rates', this.grdTaxRates.getStore().getCount());
      
      record.commit();
    }
  }
});

/* End of file tax_classes_main_panel.php */
/* Location: ./system/modules/countries/tax_classes_main_panel.php */