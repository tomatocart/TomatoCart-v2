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
 * @filesource modules/categories/views/categories_tree_panel.php
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
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'categories',
          action: 'load_categories_tree'
        }
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
/* Location: ./system/modules/categories/views/categories_tree_panel.php */