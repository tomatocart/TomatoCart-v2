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

Ext.define('Toc.zone_groups.MainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.layout = 'border';
    config.border = false;
    
    config.grdZoneEntries = Ext.create('Toc.zone_groups.ZoneEntriesGrid');
    config.grdZoneGroups = Ext.create('Toc.zone_groups.ZoneGroupsGrid');
    
    config.grdZoneGroups.on('selectchange', this.onGrdZoneGroupsSelectChange, this);
    config.grdZoneGroups.getStore().on('load', this.onGrdZoneGroupsLoad, this);
    
    config.grdZoneEntries.getStore().on('load', this.onGrdZoneEntriesLoad, this);
    
    config.items = [config.grdZoneGroups, config.grdZoneEntries];
    
    this.callParent([config]);
  },
  
  onGrdZoneGroupsLoad: function() {
    if (this.grdZoneGroups.getStore().getCount() > 0) {
      this.grdZoneGroups.getSelectionModel().select(0);
      var record = this.grdZoneGroups.getStore().getAt(0);
      
      this.onGrdZoneGroupsSelectChange(record);
    }
  },
  
  onGrdZoneGroupsSelectChange: function(record) {
    this.grdZoneEntries.setTitle('<?php echo lang('heading_zone_groups_title'); ?>: '+ record.get('geo_zone_name'));
    this.grdZoneEntries.iniGrid(record);
  },
  
  onGrdZoneEntriesLoad: function() {
    record = this.grdZoneGroups.getSelectionModel().getLastSelected() || null;
    if (record) {
      record.set('geo_zone_entries', this.grdZoneEntries.getStore().getCount());
    }
  }
});

/* End of file zone_groups_main_panel.php */
/* Location: ./templates/base/web/views/zone_groups/zone_groups_main_panel.php */