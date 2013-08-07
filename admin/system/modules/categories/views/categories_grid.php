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
 * @filesource modules/categories/views/categories_grid.php
 */
?>

Ext.define('Toc.categories.CategoriesGrid', {
  extend: 'Ext.grid.Panel',
  
  statics: {
    renderStatus : function(status) {
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
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'categories_id', 
        'categories_name',
        'status',
        'path'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'categories',
          action: 'list_categories'
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
      { header: '<?php echo lang('table_heading_categories'); ?>', dataIndex: 'categories_name', sortable: true, flex: 1},
      { header: '<?php echo lang('table_heading_status'); ?>', align: 'center', renderer: statics.renderStatus, dataIndex: 'status'},
      {
        xtype: 'actioncolumn', 
        width: 80,
        header: '<?php echo lang("table_heading_action"); ?>',
        items: [{
          tooltip: TocLanguage.tipEdit,
          iconCls: 'icon-action icon-edit-record',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', rec);
          },
          scope: this
        },
        {
          iconCls: 'icon-action icon-move-record',
          tooltip: TocLanguage.tipMove,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('movecategory', rec);
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
    
    config.search = Ext.create('Ext.form.TextField', {
      width: 150,
      paramName: 'search'
    });
    
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
        text: TocLanguage.btnMove,
        iconCls: 'icon-move-record',
        handler: this.onBathMove,
        scope: this
      }, 
      '-',
      {
        text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onSearch,
        scope: this
      }, 
      '->',
      config.search,
      '',
      {
        iconCls: 'search',
        handler: this.onSearch,
        scope: this
      }
    ];
    
    config.listeners = {
      itemclick: this.onClick
    };
    
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.store,
      dock: 'bottom',
      displayInfo: true
    }];
    
    this.addEvents({'deletesuccess': true, 'create': true, 'movecategory': true, 'batchmovecategories': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  refreshGrid: function (categoriesId) {
    var store = this.getStore();

    store.getProxy().extraParams['categories_id'] = categoriesId;
    store.load();
  },
  
  onDelete: function(record) {
    var categoriesId = record.get('categories_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: Toc.CONF.CONN_URL,
            params: {
              module: 'categories',
              action: 'delete_category',
              categories_id: categoriesId
            },
            callback: function (options, success, response) {
              var result = Ext.decode(response.responseText);
              
              if (result.success == true) {
                this.fireEvent('deletesuccess', result.feedback);
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
      keys.push(item.get('categories_id'));
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
                module: 'categories',
                action: 'delete_categories',
                batch: batch
              },
              callback: function(options, success, response) {
                var result = Ext.decode(response.responseText);
                
                if (result.success == true) {
                  this.fireEvent('deletesuccess', result.feedback);
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
  
  onBathMove: function() {
    var selections = this.selModel.getSelection();
    
    keys = [];
    Ext.each(selections, function(item) {
      keys.push(item.get('categories_id'));
    });
    
    if (keys.length > 0) {
      this.fireEvent('batchmovecategories', keys);
    }else {
      Ext.MessageBox.alert(TocLanguage.msgInfoTitle, TocLanguage.msgMustSelectOne);
    }
  },
  
  onSearch: function () {
    var filter = this.search.getValue() || null;
    var store = this.getStore();
    store.getProxy().extraParams['search'] = filter;
    
    store.load();
  },
  
  onClick: function(view, record, item, index, e) {
    var action = false;
    var module = 'set_status';
    
    var btn = e.getTarget(".img-button");
    if (!Ext.isEmpty(btn)) {
      action = btn.className.replace(/img-button btn-/, '').trim();

      if (action != 'img-button') {
        var categoriesId = this.getStore().getAt(index).get('categories_id');
        
        switch(action) {
          case 'status-off':
            flag = (action == 'status-on') ? 1 : 0;
            
            Ext.MessageBox.confirm(
              TocLanguage.msgWarningTitle, 
              TocLanguage.msgDisableProducts, 
              function (btn) {
                if (btn == 'no') {
                  this.onAction(module, categoriesId, flag, 0, index);
                } else{
                  this.onAction(module, categoriesId, flag, 1, index);
                }
              }, 
              this
            );  
            
            break;               
          case 'status-on':
            flag = (action == 'status-on') ? 1 : 0;
            
            this.onAction(module, categoriesId, flag, 0, index);
            break;
        }
      }
    }
  },
  
  onAction: function(action, categoriesId, flag, product_flag, index) {
    Ext.Ajax.request({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'categories',
        action: action,
        categories_id: categoriesId,
        flag: flag,
        product_flag: product_flag
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          var store = this.getStore();
          
          store.getAt(index).set('status', flag);
          
          this.fireEvent('notifysuccess', result.feedback);
        }
      },
      scope: this
    });
  }
});

/* End of file categories_grid.php */
/* Location: ./system/modules/categories/views/categories_grid.php */