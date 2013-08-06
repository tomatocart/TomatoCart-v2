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

Ext.define('Toc.products_expected.ProductsExpectedGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'products_id', 
        'products_name',
        'products_date_available'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('products_expected/list_products_expected'); ?>',
        extraParams: {
          module: 'products_expected',
          action: 'list_products_expected'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.columns =[
      {header: '<?php echo lang('table_heading_products'); ?>', dataIndex: 'products_name', flex: 1},
      {header: '<?php echo lang('table_heading_date_expected'); ?>', dataIndex: 'products_date_available'},
      {
        xtype: 'actioncolumn', 
        width: 80,
        header: '<?php echo lang("table_heading_action"); ?>',
        items: [{
          tooltip: TocLanguage.tipEdit,
          iconCls: 'icon-action icon-edit-record',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', rec);
          },
          scope: this
        }]
      }
    ];
    
    config.tbar = [
      {
        text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onRefresh,
        scope: this
      }
    ];
    
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.store,
      dock: 'bottom',
      displayInfo: true
    }];
    
    this.addEvents({'notifysuccess': true, 'edit': true});
    
    this.callParent([config]);
  },
  
  onRefresh: function() {
    this.getStore().load();
  }
});

/* End of file products_expected_grid.php */
/* Location: ./templates/base/web/views/products_expected/products_expected_grid.php */