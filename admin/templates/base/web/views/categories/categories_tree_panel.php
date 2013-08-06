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

Ext.define('Toc.categories.CategoriesTreePanel', {
  extend: 'Ext.tree.TreePanel',
  
  constructor: function(config) {
    config = config || {};
    
    config.region = 'west';
    config.border = false;
    config.autoScroll = true;
    config.containerScroll = true;
    config.split = true;
    config.width = 170;
    config.rootVisible = true;
    config.currentCategoryId = '0';
    
    config.store = Ext.create('Ext.data.TreeStore', {
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('categories/load_categories_tree'); ?>'
      },
      root: {
        id: 0,
        text: '<?php echo lang('top_category'); ?>',
        leaf: false,
        expandable: true,  
        expanded: true  
      },
      listeners: {
        'load': function() {
          this.expandAll();
          this.setCategoryId(0);
        },
        scope: this
      }
    });
    
    config.tbar = [{
      text: TocLanguage.btnRefresh,
      iconCls: 'refresh',
      handler: this.refresh,
      scope: this
    }];
    
    config.listeners = {
      "itemclick": this.onCategoryNodeClick,
      scope: this
    };
    
    this.addEvents({'selectchange' : true});
    
    this.callParent([config]);
  },
  
  onCategoryNodeClick: function (view, record) {
    var categoryId = record.get('id');
    
    this.setCategoryId(categoryId);
  },
  
  setCategoryId: function(categoryId) {
    this.currentCategoryId = categoryId;
    
    this.fireEvent('selectchange', categoryId);
  },  
  
  refresh: function() {
    this.getStore().load();
  }
});

/* End of file categories_tree_panel.php */
/* Location: ./templates/base/web/views/categories/categories_tree_panel.php */