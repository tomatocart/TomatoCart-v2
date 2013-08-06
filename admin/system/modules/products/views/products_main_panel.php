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
 * @filesource products_main_panel.php
 */
?>

Ext.define('Toc.products.ProductsMainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.layout = {
      type: 'border',
      padding: 5
    };
    config.border = false;
    
    config.pnlCategoriesTree = Ext.create('Toc.products.CategoriesTreePanel');
    config.pnlCategoriesTree.on('selectchange', this.onPnlCategoriesTreeNodeSelectChange, this);
    
    config.grdProducts = Ext.create('Toc.products.ProductsGrid');
    config.grdProducts.on('create', function() {this.fireEvent('createProduct', config.grdProducts)}, this);
    config.grdProducts.on('edit', function(record) {this.fireEvent('editProduct', {'grdProducts': config.grdProducts, 'record': record})}, this);
    config.grdProducts.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    
    config.items = [config.pnlCategoriesTree, config.grdProducts];
    
    this.addEvents({'createProduct': true});
    
    this.callParent([config]);
  },
  
  onPnlCategoriesTreeNodeSelectChange: function(categoryId) {
    this.grdProducts.refreshGrid(categoryId);
  }
});

/* End of file products_main_panel.php */
/* Location: ./system/modules/products/views/products_main_panel.php */