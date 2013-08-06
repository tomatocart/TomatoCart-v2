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

Ext.define('Toc.orders.OrdersTransactionGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_transaction_history'); ?>';
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields: ['date', 'status', 'comments'],
      proxy: {
        type: 'ajax',
        url: '<?php echo site_url('orders/get_transaction_history'); ?>',
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
      { header: '<?php echo lang('table_heading_date_added'); ?>', dataIndex: 'date', width: 140, align: 'center'},
      { header: '<?php echo lang('table_heading_status'); ?>', dataIndex: 'status', width: 120, align: 'center'},
      { header: '<?php echo lang('table_heading_comments'); ?>', dataIndex: 'comments', flex: 1}
    ];
    
    this.callParent([config]);
  }
});

/* End of file orders_transaction_grid.php */
/* Location: ./templates/base/web/views/orders/orders_transaction_grid.php */