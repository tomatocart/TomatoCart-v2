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
 * @filesource orders_grid.php
 */
?>

Ext.define('Toc.orders.OrdersGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.autoScroll = true;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'orders_id',
        'customers_name',
        'order_total',
        'date_purchased',
        'orders_status_name',
        'shipping_address',
        'shipping_method',
        'billing_address',
        'payment_method',
        'products',
        'action_class',
        'totals'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'orders',
          action: 'list_orders'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.selModel = Ext.create('Ext.selection.CheckboxModel');
    
    config.columns =[
      {header: 'ID', dataIndex: 'orders_id', width: 30, align: 'center'},
      { header: '<?php echo lang('table_heading_customers'); ?>', dataIndex: 'customers_name', flex: 1},
      { header: '<?php echo lang('table_heading_order_total'); ?>', dataIndex: 'order_total', width: 120, align: 'right'},
      { header: '<?php echo lang('table_heading_date_purchased'); ?>', dataIndex: 'date_purchased', align: 'center', width: 120, sortable: true},
      { header: '<?php echo lang('table_heading_status'); ?>', dataIndex: 'orders_status_name', align: 'center', width: 120},
      {
        xtype:'actioncolumn', 
        width: 80,
        header: '<?php echo lang("table_heading_action"); ?>',
        items: [{
          iconCls: 'icon-action icon-view-record',
          tooltip: '<?php echo lang('tip_view_order');?>',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('view', rec);
          },
          scope: this
        },
        {
          tooltip: '<?php echo lang('tip_create_invoice');?>',
          getClass: this.getCreateInvoiceClass,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            if (rec.get('action_class') != 'icon-invoice-gray-record')
            {
              this.onInvoice(rec);
            }
          },
          scope: this
        },
        {
            tooltip: TocLanguage.tipDelete,
            getClass: this.getDeleteClass,
            handler: function(grid, rowIndex, colIndex) {
              var rec = grid.getStore().getAt(rowIndex);
              
              this.fireEvent('delete', rec);
            },
            scope: this                
        }]
      }
    ];
    
    config.plugins = [
      {
        ptype: 'rowexpander',
        rowBodyTpl : [
        
          '<div class="order_details">',
            '<table width="98%">',
             '<tr>',
               '<td width="25%">',
                 '<strong><?php echo lang('subsection_shipping_address'); ?></strong>',
                 '<p>{shipping_address}</p>',
                 '<strong><?php echo lang('subsection_delivery_method'); ?></strong>',
                 '<p>{shipping_method}</p>',
               '</td>',
               '<td width="25%">',
                 '<strong><?php echo lang('subsection_billing_address'); ?></strong>',
                 '<p>{billing_address}</p>',
                 '<strong><?php echo lang('subsection_payment_method'); ?></strong>',
                 '<p>{payment_method}</p>',
               '</td>',
               '<td>',
                 '<strong><?php echo lang('subsection_products'); ?></strong>',
                 '<div class="order_products">{products}</div>',
                 '<div class="order_totals">{totals}</div>',
               '</td>',
             '</tr>',
           '</table>',
         '</div>'
        ]
      }
    ];
    
    var dsStatus = Ext.create('Ext.data.Store', {
      fields:[
        'status_id',
        'status_name'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'orders',
          action: 'get_status',
          top: '1'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      listeners: {
        load: function() {
          this.cboStatus.setValue('');
        },
        scope: this
      },
      autoLoad: true
    });
    
    config.cboStatus = Ext.create('Ext.form.ComboBox', {
      labelWidth: 50,
      fieldLabel: '<?php echo lang('operation_heading_filter_status'); ?>', 
      store: dsStatus, 
      name: 'status', 
      displayField: 'status_name', 
      valueField: 'status_id', 
      editable: false, 
      forceSelection: true,
      queryMode: 'local'
    });
    
    this.txtOrderId = Ext.create('Ext.form.TextField', {
      width: 120,
      emptyText: '<?php echo lang('operation_heading_order_id'); ?>'
    });
    
    this.txtCustomerId = Ext.create('Ext.form.TextField', {
      width: 120,
      emptyText: '<?php echo lang('operation_heading_customer_id'); ?>'
    });
    
    config.tbar = [
      {
        text: TocLanguage.btnDelete,
        iconCls:'remove',
        handler: this.onBatchDelete,
        scope: this
      },
      '-',
      { 
        text: TocLanguage.btnRefresh,
        iconCls:'refresh',
        handler: this.onRefresh,
        scope: this
      },
      '->',
      this.txtOrderId,
      ' ',
      this.txtCustomerId,
      ' ',
      config.cboStatus,
      ' ',
      {
        name: 'search',
        handler: this.onSearch,
        iconCls: 'search',
        scope: this
      }
    ];
    
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.store,
      dock: 'bottom',
      displayInfo: true
    }];
    
    this.addEvents({'delete': true, 'batchdelete': true, 'notifysuccess': true, 'view': true});
    
    this.callParent([config]);
  },
  
  onBatchDelete: function() {
    var selections = this.selModel.getSelection();
    
    if (selections.length > 0) {
      var orders = [];
      var keys = [];
      Ext.each(selections, function(item) {
        keys.push(item.get('orders_id'));
        
        orders.push('#' + item.get('orders_id') + ': ' + item.get('customers_name'));
      });
      
      this.fireEvent('batchdelete', {'ordersIds': keys, 'orders': orders});
    }else {
      Ext.MessageBox.alert(TocLanguage.msgInfoTitle, TocLanguage.msgMustSelectOne);
    }
  },
  
  onInvoice: function(record) {
    var ordersId = record.get('orders_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      '<?php echo lang('create_invoice_confirmation');?>',
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url: Toc.CONF.CONN_URL,
            params: {
              module: 'orders',
              action: 'create_invoice',
              orders_id: ordersId
            },
            callback: function(options, success, response) {
              var result = Ext.decode(response.responseText);
              
              if (result.success == true) {
                this.onRefresh();
                
                this.fireEvent('notifysuccess', result.feedback);
              } else {
                Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
              }
            },
            scope: this
          });   
        }
      }, 
      this
    );
  },
  
  getDeleteClass: function(v, meta, rec) {
    switch (rec.get('action_class')) {
      case 'icon-invoice-record':
        return 'icon-action icon-delete-record';
        break;
        
      default:
        return 'icon-action-hide';
    }
  },
  
  getCreateInvoiceClass: function(v, meta, rec) {
    switch (rec.get('action_class')) {
      case 'icon-invoice-record':
        return 'icon-action icon-invoice-record';
        break;
        
      default:
        return 'icon-action icon-invoice-gray-record';
    }
  },
  
  onSearch: function() {
    var proxy = this.getStore().getProxy();
    
    proxy.extraParams['orders_id'] = this.txtOrderId.getValue() || null;
    proxy.extraParams['customers_id'] = this.txtCustomerId.getValue() || null;
    proxy.extraParams['status'] = this.cboStatus.getValue() || null;
    
    this.getStore().load();
  },
  
  onRefresh: function() {
    this.getStore().load();
  }
});

/* End of file orders_grid.php */
/* Location: ./system/modules/orders/views/orders_grid.php */
