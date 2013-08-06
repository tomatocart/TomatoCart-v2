<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
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
        url: Toc.CONF.CONN_URL,
        extraParams: {
          module: 'orders',
          action: 'get_transaction_history',
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
/* Location: ./system/modules/orders/views/orders_transaction_grid.php */