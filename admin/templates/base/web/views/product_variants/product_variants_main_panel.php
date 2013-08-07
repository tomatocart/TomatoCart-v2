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

Ext.define('Toc.product_variants.MainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.layout = 'border';
    config.border = false;
    
    config.grdVariantsEntries = Ext.create('Toc.product_variants.ProductVariantsEntriesGrid');
    config.grdVariantsGroups = Ext.create('Toc.product_variants.ProductVariantsGroupsGrid');
    
    config.grdVariantsGroups.on('selectchange', this.onGrdVariantsGroupsSelectChange, this);
    config.grdVariantsGroups.getStore().on('load', this.onGrdVariantsGroupsLoad, this);
    config.grdVariantsEntries.getStore().on('load', this.onGrdVariantsEntriesLoad, this);
    
    config.items = [config.grdVariantsGroups, config.grdVariantsEntries];
    
    this.callParent([config]);    
  },
  
  onGrdVariantsGroupsLoad: function() {
    if (this.grdVariantsGroups.getStore().getCount() > 0) {
      this.grdVariantsGroups.getSelectionModel().select(0);
      var record = this.grdVariantsGroups.getStore().getAt(0);
      
      this.onGrdVariantsGroupsSelectChange(record);
    }else {
      this.grdVariantsEntries.onRefresh();
    }
  },
  
  onGrdVariantsGroupsSelectChange: function(record) {
    this.grdVariantsEntries.setTitle('<?php echo lang("heading_product_variants_title");?>:  '+ record.get('products_variants_groups_name'));
    this.grdVariantsEntries.iniGrid(record);
  },
  
  onGrdVariantsEntriesLoad: function() {
    var record = this.grdVariantsGroups.getSelectionModel().getLastSelected() || null;
    if (record) {
      record.set('total_entries', this.grdVariantsEntries.getStore().getCount());
      record.commit();
    }
  } 
});

/* End of file product_variants_main_panel.php */
/* Location: ./templates/base/web/views/product_variants/product_variants_main_panel.php */