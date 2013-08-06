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
        url: '<?php echo site_url('orders/list_orders_status'); ?>',
        extraParams: {
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
/* Location: ./templates/base/web/views/invoices/invoices_status_panel.php */