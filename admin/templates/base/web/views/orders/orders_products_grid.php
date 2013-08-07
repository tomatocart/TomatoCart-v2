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

Ext.define('Toc.orders.OrdersProductsGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_products'); ?>';
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    config.border = false;
    
    config.store = Ext.create('Ext.data.Store', {
      fields: [
        'orders_id',
        'products',
        'sku',
        'tax',
        'price_net',
        'price_gross',
        'total_net',
        'total_gross',
        'return_quantity'
      ],
      proxy: {
        type: 'ajax',
        url: '<?php echo site_url('orders/list_order_products'); ?>',
        extraParams: {
          orders_id: config.ordersId    
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.columns = [
      { header: '<?php echo lang('table_heading_products'); ?>', dataIndex: 'products', flex: 1},
      { header: '<?php echo lang('table_heading_product_sku'); ?>', dataIndex: 'sku', width: 80, align: 'right'},
      { header: '<?php echo lang('table_heading_tax'); ?>', dataIndex: 'tax', align: 'center', width: 80, align: 'right'},
      { header: '<?php echo lang('table_heading_price_gross'); ?>', dataIndex: 'price_gross',  width: 120, align: 'right'},
      { header: '<?php echo lang('table_heading_total_gross'); ?>', dataIndex: 'total_gross',  width: 120, align: 'right'},
      { header: '<?php echo lang('table_heading_return_quantity'); ?>', dataIndex: 'return_quantity',  width: 100, align: 'center'}
    ];
    
    this.callParent([config]);
  }
});

/* End of file orders_products_grid.php */
/* Location: ./templates/base/web/views/orders/orders_products_grid.php */