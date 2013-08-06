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
/* Location: ./templates/base/web/views/products/products_main_panel.php */