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
 * @filesource system/modules/invoices/views/invoices_status_panel.php
 */
?>

Ext.define('Toc.invoices.InvoicesStatusPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config = config || {};
    config.title = '<?php echo lang('section_status_history'); ?>';
    config.layout = 'border';
    config.border = false;
    
    config.items = this.buildForm(config.ordersId);
    
    this.callParent([config]);
  },
  
  buildForm: function(ordersId) {
    var store = Ext.create('Ext.data.Store', {
      fields: [
        'orders_status_history_id',
        'date_added',
        'status',
        'comments',
        'customer_notified'
      ],
      proxy: {
        type: 'ajax',
        url: Toc.CONF.CONN_URL,
        extraParams: {
          module: 'orders',
          action: 'list_orders_status',
          orders_id: ordersId   
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    var grdOrdersStatus = Ext.create('Ext.grid.Panel' , {
      border: false,
      region: 'center',
      store: store,
      columns: [
        { header: '<?php echo lang('table_heading_date_added'); ?>', dataIndex: 'date_added', width: 120, align: 'center'},
        { header: '<?php echo lang('table_heading_status'); ?>', dataIndex: 'status', width: 120, align: 'center'},
        { header: '<?php echo lang('table_heading_comments'); ?>', dataIndex: 'comments', align: 'center', flex: 1},
        { header: '<?php echo lang('table_heading_customer_notified'); ?>', dataIndex: 'customer_notified',  width: 120, align: 'center'}
      ]
    });
    
    return grdOrdersStatus;
  }
});

/* End of file invoices_status_panel.php */
/* Location: system/modules/invoices/views/invoices_status_panel.php */