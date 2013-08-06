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
 * @filesource categories_panel.php
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
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'products',
          action: 'get_categories_tree',
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
/* Location: ./system/modules/products/views/categories_panel.php */
