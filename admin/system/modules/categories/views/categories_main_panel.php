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
 * @filesource modules/categories/views/categories_main_panel.php
 */
?>

Ext.define('Toc.categories.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.layout = {
      type: 'border',
      padding: 5
    };
    
    config.border = false;
    
    config.pnlCategoriesTree = Ext.create('Toc.categories.CategoriesTreePanel');
    config.grdCategories = Ext.create('Toc.categories.CategoriesGrid');
    
    config.pnlCategoriesTree.on('selectchange', this.onPnlCategoriesTreeNodeSelectChange, this);
    
    config.items = [config.pnlCategoriesTree, config.grdCategories];
    
    this.callParent([config]);
  },
  
  onPnlCategoriesTreeNodeSelectChange: function(categoryId) {
    this.grdCategories.refreshGrid(categoryId);
  }
});


/* End of file categories_main_panel.php */
/* Location: ./system/modules/categories/views/categories_main_panel.php */

