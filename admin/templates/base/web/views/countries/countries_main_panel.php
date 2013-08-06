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
?>

Ext.define('Toc.countries.MainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.layout = 'border';
    config.border = false;
    
    config.grdCountries = Ext.create('Toc.countries.CountriesGrid');
    config.grdZones = Ext.create('Toc.countries.ZonesGrid');
    
    config.grdCountries.on('selectchange', this.onGrdCountriesSelectChange, this);
    config.grdCountries.getStore().on('load', this.onGrdCountriesLoad, this);
    
    config.grdZones.getStore().on('load', this.onGrdZonesLoad, this);
    
    config.items = [config.grdCountries, config.grdZones];
    
    this.callParent([config]);
  },
  
  onGrdCountriesLoad: function() {
    if (this.grdCountries.getStore().getCount() > 0) {
      this.grdCountries.getSelectionModel().select(0);
      var record = this.grdCountries.getStore().getAt(0);
      
      this.onGrdCountriesSelectChange(record);
    }
  },
  
  onGrdCountriesSelectChange: function(record) {
    this.grdZones.setTitle('<?php echo lang('heading_countries_title'); ?>: '+ record.get('countries_name'));
    this.grdZones.iniGrid(record);
  },
  
  onGrdZonesLoad: function() {
    record = this.grdCountries.getSelectionModel().getLastSelected() || null;
    if (record) {
      record.set('total_zones', this.grdZones.getStore().getCount());
    }
  }
});

/* End of file countries_main_panel.php */
/* Location: ./templates/base/web/views/countries/countries_main_panel.php */