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

Ext.define('Toc.products.CategoriesPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_categories'); ?>';
    config.layout = 'border';
    config.bodyPadding = 5;
    config.treeLoaded = false;
    config.items = this.buildForm(config.productsId);
    
    this.callParent([config]);
  },
  
  buildForm: function(productsId) {
    var store = Ext.create('Ext.data.TreeStore', {
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('products/get_categories_tree'); ?>',
        extraParams: {
          productsId: productsId
        }
      },
      root: {
        id: '0',
        text: '<?php echo lang('top_category'); ?>',
        leaf: false,
        expandable: true,  
        expanded: true  
      },
      listeners: {
        'load': function() {
          this.pnlCategoriesTree.expandAll();
          this.treeLoaded = true;
        },
        scope: this
      },
      autoLoad: true
    });
    
    this.pnlCategoriesTree = Ext.create('Ext.tree.Panel', {
      store: store,
      useArrows: true,
      region: 'center',
      name: 'categories',
      border: false,
      rootVisible: false
    });
    
    return this.pnlCategoriesTree;  
  },
  
  getCategories: function() {
    var categories = [];
    var checkedRecords = this.pnlCategoriesTree.getChecked();
    
    if (!Ext.isEmpty(checkedRecords)) {
      Ext.each(checkedRecords, function(checkedRecord) {
        categories.push(checkedRecord.get('id'));
      });
    }
    
    return categories.join(',');
  }
});

/* End of file categories_panel.php */
/* Location: ./templates/base/web/views/products/categories_panel.php */