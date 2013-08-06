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

Ext.define('Toc.currencies.CurrenciesGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields: ['currencies_id', 'title', 'code', 'value', 'example'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('currencies/list_currencies'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.selModel = Ext.create('Ext.selection.CheckboxModel');
    
    config.columns = [
      { header: '<?php echo lang('table_heading_currencies'); ?>', dataIndex: 'title', flex: 1},
      { header: '<?php echo lang('table_heading_code'); ?>', align: 'right', dataIndex: 'code', width: 100},
      { header: '<?php echo lang('table_heading_value'); ?>', align: 'right', dataIndex: 'value', width: 100},
      { header: '<?php echo lang('table_heading_example'); ?>', align: 'right', dataIndex: 'example', width: 100},
      {
        xtype:'actioncolumn', 
        width: 80,
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
        },{ 
          iconCls: 'icon-action icon-update-exchange-rates',
          tooltip: '<?php echo lang('button_update_currency_exchange_rates'); ?>',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('updaterates', rec);
          },
          scope: this
        }]
      }
    ];
    
    config.tbar = [
      {
        text: TocLanguage.btnAdd,
        iconCls: 'add',
        handler: function() {this.fireEvent('create');},
        scope: this
      }, 
      '-',
      {
      text: TocLanguage.btnDelete,
        iconCls: 'remove',
        handler: this.onBatchDelete,
        scope: this
      },
      '-', 
      {
        text: TocLanguage.btnRefresh,
        iconCls:'refresh',
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
    
    this.addEvents({'create': true, 'edit': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onDelete: function(record) {
    var currenciesId = record.get('currencies_id');
    var code = record.get('code');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: '<?php echo site_url('currencies/delete_currency'); ?>',
            params: {
              cID: currenciesId,
              code: code
            },
            callback: function (options, success, response) {
              var result = Ext.decode(response.responseText);
              
              if (result.success == true) {
                this.fireEvent('notifysuccess', result.feedback);
                this.onRefresh();
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
  
  onBatchDelete: function() {
    var selections = this.selModel.getSelection();
    
    keys = [];
    Ext.each(selections, function(item) {
      keys.push(item.get('currencies_id'));
    });
    
    if (keys.length > 0) {
      var batch = Ext.JSON.encode(keys);
      
      Ext.MessageBox.confirm(
        TocLanguage.msgWarningTitle, 
        TocLanguage.msgDeleteConfirm,
        function(btn) {
          if (btn == 'yes') {
            Ext.Ajax.request({
              waitMsg: TocLanguage.formSubmitWaitMsg,
              url: '<?php echo site_url('currencies/delete_currencies'); ?>',
              params: {
                batch: batch
              },
              callback: function(options, success, response) {
                var result = Ext.decode(response.responseText);
                
                if (result.success == true) {
                  this.fireEvent('notifysuccess', result.feedback);
                  
                  this.onRefresh();
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
    } else {
      Ext.MessageBox.alert(TocLanguage.msgInfoTitle, TocLanguage.msgMustSelectOne);
    }
  },
  
  onUpdateRates: function(rec) {
    Ext.Ajax.request({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      url: '<?php echo site_url('currencies/update_currency_rates'); ?>',
      params: {
        currencies_id: rec.get('currencies_id')
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          this.fireEvent('notifysuccess', result.feedback);
          
          this.onRefresh();
        } else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
        }
      }, 
      scope: this
    });
  },
  
  onRefresh: function() {
    this.getStore().load();
  }
});

/* End of file currencies_grid.php */
/* Location: ./templates/base/web/views/currencies/currencies_grid.php */