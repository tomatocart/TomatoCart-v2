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

Ext.define('Toc.customers.CustomersGrid', {
  extend: 'Ext.grid.Panel',
  
  statics: {
    renderStatus: function(status) {
      if(status == 1) {
        return '<img class="img-button" src="<?php echo icon_status_url('icon_status_green.gif'); ?>" />&nbsp;<img class="img-button btn-status-off" style="cursor: pointer" src="<?php echo icon_status_url('icon_status_red_light.gif'); ?>" />';
      }else {
        return '<img class="img-button btn-status-on" style="cursor: pointer" src="<?php echo icon_status_url('icon_status_green_light.gif'); ?>" />&nbsp;<img class="img-button" src= "<?php echo icon_status_url('icon_status_red.gif'); ?>" />';
      }
    }
  },
  
  constructor: function(config) {
    var statics = this.statics();
    
    config = config || {};
    
    config.region = 'center';
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'customers_id',
        'customers_lastname',
        'customers_firstname',
        'customers_credits',
        {name: 'date_account_created', type: 'date', dateFormat: 'Y-m-d H:i:s'},
        'customers_status',
        'customers_info'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('customers/list_customers'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.columns =[
      { header: '<?php echo lang('table_heading_last_name'); ?>', dataIndex: 'customers_lastname', flex: 1},
      { header: '<?php echo lang('table_heading_first_name'); ?>', dataIndex: 'customers_firstname', width: 85},
      { header: '<?php echo lang('table_heading_date_created'); ?>', renderer: Ext.util.Format.dateRenderer('m/d/Y'), dataIndex: 'date_account_created', width: 85, align: 'center'},
      { header: '<?php echo lang('table_heading_customers_credits'); ?>', dataIndex: 'customers_credits', width: 100, align: 'center'},
      { header: '<?php echo lang('table_heading_customers_status'); ?>', dataIndex: 'customers_status', renderer: statics.renderStatus, width: 80, align: 'center'},
      {
        xtype:'actioncolumn', 
        width:80,
        header: '<?php echo lang("table_heading_action"); ?>',
        items: [{
          iconCls: 'icon-action icon-edit-record',
          tooltip: TocLanguage.tipEdit,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', rec);
          },
          scope: this
        },{
            iconCls: 'icon-action icon-delete-record',
            tooltip: TocLanguage.tipDelete,
            handler: function(grid, rowIndex, colIndex) {
              var rec = grid.getStore().getAt(rowIndex);
              
              this.onDelete(rec);
            },
            scope: this                
        }]
      }
    ];
    
    config.plugins = [{
      ptype: 'rowexpander',
      rowBodyTpl : [
        '<div style="padding: 10px;">',
            '<table width="100%" cellspacing="5">',
                '<tbody>',
                    '<tr>',
                        '<td width="150">{customers_info.field_gender.label}</td>',
                        '<td>{customers_info.field_gender.value}</td>',
                    '</tr>',
                    '<tr>',
                        '<td>{customers_info.field_email_address.label}</td>',
                        '<td>{customers_info.field_email_address.value}</td>',
                    '</tr>',
                     '<tr>',
                        '<td>{customers_info.field_customers_group.label}</td>',
                        '<td>{customers_info.field_customers_group.value}</td>',
                    '</tr>',
                     '<tr>',
                        '<td>{customers_info.field_number_of_logons.label}</td>',
                        '<td>{customers_info.field_number_of_logons.value}</td>',
                    '</tr>',
                     '<tr>',
                        '<td>{customers_info.field_date_last_logon.label}</td>',
                        '<td>{customers_info.field_date_last_logon.value}</td>',
                    '</tr>',
                '</tbody>',
            '</table>',
        '</div>'
      ]
    }];
    
    config.listeners = {
      itemclick: this.onClick
    };
    
    config.search = Ext.create('Ext.form.TextField', {name: 'search', width: 130});
    
    config.tbar = [
    {
      text: TocLanguage.btnAdd,
      iconCls: 'add',
      handler: function() {
        this.fireEvent('create');
      },
      scope: this
    },
    '-',
    { 
      text: TocLanguage.btnRefresh,
      iconCls: 'refresh',
      handler: this.onRefresh,
      scope: this
    },
    '->',
    config.search,
    '',
    {
      iconCls: 'search',
      handler: this.onSearch,
      scope: this
    }];
   
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.store,
      dock: 'bottom',
      displayInfo: true
    }];  
    
    this.addEvents({'selectchange' : true, 'create' : true, 'edit': true, 'notifysuccess': true});  
    
    this.callParent([config]);
  },
  
  onRefresh: function() {
    this.getStore().load();
  },
  
  onDelete: function(record) {
    var customersId = record.get('customers_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url : '<?php echo site_url('customers/delete_customer'); ?>',
            params: {
              customers_id: customersId
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
  
  onSearch: function () {
    var filter = this.search.getValue() || null;
    var store = this.getStore();

    store.getProxy().extraParams['search'] = filter;
    store.load();
  },
  
  onClick: function(view, record, item, index, e) {
    if (!e.getTarget(".icon-action"))
    {
      this.fireEvent('selectchange', record);
    }
    
    var action = false;
    var module = 'set_status';
  
    if (index !== false) {
      var btn = e.getTarget(".img-button");
      
      if (btn) {
        action = btn.className.replace(/img-button btn-/, '').trim();
      }

      if (action != 'img-button') {
        var record = this.getStore().getAt(index);
        var customersId = record.get('customers_id');
        
        switch(action) {
          case 'status-off':
          case 'status-on':
            flag = (action == 'status-on') ? 1 : 0;
            this.onAction(module, customersId, index, flag);

            break;
        }
      }
    }
  },
  
  onAction: function(action, customersId, index, flag) {
    Ext.Ajax.request({
      url: '<?php echo site_url('customers'); ?>/' + action,
      params: {
        customers_id: customersId,
        flag: flag
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          var store = this.getStore();
          
          store.getAt(index).set('customers_status', flag);
          
          this.fireEvent('notifysuccess', result.feedback);
        }
      },
      scope: this
    });
  }
});

/* End of file customers_grid.php */
/* Location: ./templates/base/web/views/customers/customers_grid.php */