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
 * @filesource ./system/modules/newsletters/views/newsletters_grid.php
 */
?>

Ext.define('Toc.newsletters.NewslettersGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'newsletters_id', 
        'title',
        'size',
        'module',
        'sent',
        'action_class'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'newsletters',
          action: 'list_newsletters'
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
      {header: '<?php echo lang('table_heading_newsletters'); ?>', dataIndex: 'title', flex: 1},
      {header: '<?php echo lang('table_heading_size'); ?>', width: 60, align: 'center', dataIndex: 'size'},
      {header: '<?php echo lang('table_heading_module'); ?>', width: 140, align: 'center', dataIndex: 'module'},
      {header: '<?php echo lang('table_heading_sent'); ?>', width: 60, align: 'center', dataIndex: 'sent'},
      {
        xtype: 'actioncolumn', 
        width: 80,
        header: '<?php echo lang("table_heading_action"); ?>',
        items: [{
          tooltip: TocLanguage.tipEdit,
          getClass: this.getEditClass,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', rec);
          },
          scope: this
        },
        {
          tooltip: '<?php echo lang('icon_log'); ?>',
          getClass: this.getLogClass,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('log', rec);
          },
          scope: this
        },
        {
          tooltip: '<?php echo lang('icon_email_send'); ?>',
          getClass: this.getEmailClass,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.onSendEmails(rec);
          },
          scope: this
        },
        {
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
    
    this.addEvents({'notifysuccess': true, 'create': true, 'edit': true, 'sendmails': true, 'log': true, 'sendnewsletters': true});
    
    this.callParent([config]);
  },
  
  onDelete: function(record) {
    var newslettersId = record.get('newsletters_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: Toc.CONF.CONN_URL,
            params: {
              module: 'newsletters',
              action: 'delete_newsletter',
              newsletters_id: newslettersId
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
      keys.push(item.get('newsletters_id'));
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
              url: Toc.CONF.CONN_URL,
              params: {
                module: 'newsletters',
                action: 'delete_newsletters',
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
  
  onSendEmails: function(record) {
    var module = record.get('module');
    var newslettersId = record.get('newsletters_id');
    
    switch(module) {
      case 'email':
        this.fireEvent('sendemails', newslettersId);
        break;
      
      case 'newsletter':
        this.fireEvent('sendnewsletters', newslettersId); 
        break;
    }
  },
  
  onRefresh: function() {
    this.getStore().load();
  },
  
  getEditClass: function(v, meta, rec) {
    switch (rec.get('action_class')) {
      case 'icon-log-record':
        return 'icon-action';
        break;
        
      default:
        return 'icon-action icon-edit-record';
    }
  },
  
  getLogClass: function(v, meta, rec) {
    switch (rec.get('action_class')) {
      case 'icon-log-record':
        return 'icon-action icon-log-record';
        break;
        
      default:
        return 'icon-action-hide';
    }
  },
  
  getEmailClass: function(v, meta, rec) {
    switch (rec.get('action_class')) {
      case 'icon-send-email-record':
        return 'icon-action icon-send-email-record';
        break;
        
      default:
        return 'icon-action-hide';
    }
  }
});

/* End of file newsletters_grid.php */
/* Location: ./system/modules/newsletters/views/newsletters_grid.php */
