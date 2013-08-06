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

Ext.define('Toc.guest_book.GuestBookGrid', {
  extend: 'Ext.grid.GridPanel',
  
  statics: {
    renderPublish:function(status) {
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
    
    config.border = false;
    config.forceFit = true;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:['guest_books_id', 'title', 'email', 'url', 'guest_books_status', 'languages', 'content', 'date_added'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('guest_book/list_guest_books'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.selModel = Ext.create('Ext.selection.CheckboxModel');
    config.selType = 'cellmodel';
    config.columns =[
      { header: '<?php echo lang('table_heading_title'); ?>', dataIndex: 'title', sortable: true},
      { header: '<?php echo lang('table_heading_languages'); ?>', align: 'center', dataIndex: 'languages', width: 80},
      { header: '<?php echo lang('table_heading_date_added'); ?>', align: 'center', dataIndex: 'date_added', width: 100},
      { header: '<?php echo lang('table_heading_guest_books_status'); ?>', align: 'center', dataIndex: 'guest_books_status', width: 100, renderer: statics.renderPublish},
      {
        xtype:'actioncolumn', 
        width:50,
        header: '<?php echo lang("table_heading_action"); ?>',
        items: [{
          iconCls: 'icon-action icon-edit-record',
          tooltip: TocLanguage.tipEdit,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', {'record': rec, 'grd': this});
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
    
    config.tbar = [
      {
        text: TocLanguage.btnAdd,
        handler: function() {this.fireEvent('create', {grd: this});},
        iconCls: 'add',
        scope: this
      },
      '-', 
      {
        text: TocLanguage.btnDelete,
        handler: this.onBatchDelete,
        iconCls: 'remove',
        scope: this
      },
      '-',
      { 
        text: TocLanguage.btnRefresh,
        handler: this.onRefresh,
        iconCls: 'refresh',
        scope: this
      } 
    ];
    
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.store,
      dock: 'bottom',
      displayInfo: true
    }];
    
    config.listeners = {
      itemclick: this.onClick
    };
    
    this.addEvents({'notifysuccess': true, 'edit': true, 'create': true});
    
    this.callParent([config]);
  },
  
  onDelete: function(record) {
    var guestBooksId = record.get('guest_books_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url: '<?php echo site_url('guest_book/delete_guest_book'); ?>',
            params: {
              guest_books_id: guestBooksId
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
  
  onBatchDelete: function() {
    var selections = this.selModel.getSelection();
    
    keys = [];
    Ext.Array.each(selections, function(item, index, allItems) {
      keys.push(item.get('guest_books_id'));
    });
    
    if (keys.length > 0) {
      var batch = Ext.JSON.encode(keys);
      
      Ext.MessageBox.confirm(
        TocLanguage.msgWarningTitle, 
        TocLanguage.msgDeleteConfirm,
        function(btn) {
          if (btn == 'yes') {
            Ext.Ajax.request({
              url: '<?php echo site_url('guest_book/delete_guest_books'); ?>',
              params: {
                batch: batch
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
    } else {
      Ext.MessageBox.alert(TocLanguage.msgInfoTitle, TocLanguage.msgMustSelectOne);
    }
  },
  
  onClick: function(view, record, item, index, e) {
    var action = false;
  
    if (index !== false) {
      var btn = e.getTarget(".img-button");
      
      if (btn) {
        action = btn.className.replace(/img-button btn-/, '').trim();
      }

      if (action != 'img-button') {
        var guestBooksId = this.getStore().getAt(index).get('guest_books_id');
        var module = 'set_status';
        
        switch(action) {
          case 'status-off':
          case 'status-on':
            flag = (action == 'status-on') ? 1 : 0;
            this.onAction(module, guestBooksId, index, flag);

            break;
        }
      }
    }
  },
  
  onAction: function(action, guestBooksId, index, flag) {
    Ext.Ajax.request({
      url: '<?php echo site_url('guest_book'); ?>/' + action,
      params: {
        guest_books_id: guestBooksId,
        flag: flag
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          var store = this.getStore();
          store.getAt(index).set('guest_books_status', flag);
          store.getAt(index).commit();
          
          this.fireEvent('notifysuccess', result.feedback);
        }
      },
      scope: this
    });
  },
  
  onRefresh: function() {
    this.getStore().load();
  }
});

/* End of file guest_book_grid.php */
/* Location: ./templates/base/web/views/guestbook/guest_book_grid.php */