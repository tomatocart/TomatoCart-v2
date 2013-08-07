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

Ext.define('Toc.products.ProductsGrid', {
  extend: 'Ext.grid.GridPanel',
  
  statics: {
    renderStatus : function(status) {
      if(status == 1) {
        return '<img class="img-button" src="<?php echo icon_status_url('icon_status_green.gif'); ?>" />&nbsp;<img class="img-button btn-status-off" style="cursor: pointer" src="<?php echo icon_status_url('icon_status_red_light.gif'); ?>" />';
      }else {
        return '<img class="img-button btn-status-on" style="cursor: pointer" src="<?php echo icon_status_url('icon_status_green_light.gif'); ?>" />&nbsp;<img class="img-button" src= "<?php echo icon_status_url('icon_status_red.gif'); ?>" />';
      }
    },
    
    renderFrontPageStatus: function(status) {
      if(status == 1) {
        return '<img class="img-button" src="<?php echo icon_status_url('icon_status_green.gif'); ?>" />&nbsp;<img class="img-button btn-front-status-off" style="cursor: pointer" src="<?php echo icon_status_url('icon_status_red_light.gif'); ?>" />';
      }else {
        return '<img class="img-button btn-front-status-on" style="cursor: pointer" src="<?php echo icon_status_url('icon_status_green_light.gif'); ?>" />&nbsp;<img class="img-button" src= "<?php echo icon_status_url('icon_status_red.gif'); ?>" />';
      }
    }
  },
  
  constructor: function(config) {
    var statics = this.statics();
    
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    config.region = 'center';
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        {name: 'products_id'},
        {name: 'products_name'},
        {name: 'products_frontpage'},
        {name: 'products_status'},
        {name: 'products_price', type: 'string'},
        {name: 'products_quantity', type: 'int'}
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('products/list_products'); ?>',
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
      { header: '<?php echo lang('table_heading_products'); ?>', dataIndex: 'products_name', sortable: true, flex: 1},
      { header: '<?php echo lang('table_heading_frontpage'); ?>', align: 'center', renderer: statics.renderFrontPageStatus, dataIndex: 'products_frontpage'},
      { header: '<?php echo lang('table_heading_status'); ?>', align: 'center', renderer: statics.renderStatus, sortable: true, dataIndex: 'products_status'},
      { header: '<?php echo lang('table_heading_price'); ?>', align: 'right', dataIndex: 'products_price', sortable: true},
      { header: '<?php echo lang('table_heading_quantity'); ?>', align: 'right', dataIndex: 'products_quantity', sortable: true},
      {
        xtype:'actioncolumn', 
        width: 60,
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
    
    config.txtSearch = Ext.create('Ext.form.TextField', {
      width:160,
      paramName: 'search'
    });
    
    config.listeners = {
      itemclick: this.onClick
    };
    
    config.tbar = [
      {
        text: TocLanguage.btnAdd,
        iconCls:'add',
        handler: function() {this.fireEvent('create');},
        scope: this
      }, 
      '-', 
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
      config.txtSearch,
      ' ', 
      {
        iconCls : 'search',
        handler : this.onSearch,
        scope : this
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
    var productsId = record.get('products_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url: '<?php echo site_url('products/delete_product'); ?>',
            params: {
              products_id: productsId
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
    Ext.each(selections, function(item) {
      keys.push(item.get('products_id'));
    });
    
    if (keys.length > 0) {
      var batch = Ext.JSON.encode(keys);
      
      Ext.MessageBox.confirm(
        TocLanguage.msgWarningTitle, 
        TocLanguage.msgDeleteConfirm,
        function(btn) {
          if (btn == 'yes') {
            Ext.Ajax.request({
              url: '<?php echo site_url('products/delete_products'); ?>',
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
    var module;
    
    var btn = e.getTarget(".img-button");
    
    if (!Ext.isEmpty(btn)) {
      action = btn.className.replace(/img-button btn-/, '').trim();

      if (action !== 'img-button') {
        var productsId = this.getStore().getAt(index).get('products_id');
        
        if (action.indexOf('front') !== -1) {
          module = 'set_frontpage';
          
          action = action.replace(/front-/, '').trim();
        }else {
          module = 'set_status';
        }
        
        switch(action) {
          case 'status-off':
          case 'status-on':
            flag = (action == 'status-on') ? 1 : 0;
            this.onAction(module, productsId, index, flag);

            break;
        }
      }
    }
  },
  
  onAction: function(action, productsId, index, flag) {
    Ext.Ajax.request({
      url: '<?php echo site_url('products'); ?>/' + action,
      params: {
        products_id: productsId,
        flag: flag
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          var store = this.getStore();
          if(action == 'set_frontpage') {
            store.getAt(index).set('products_frontpage', flag);
          } else {
            store.getAt(index).set('products_status', flag);
          }
          
          store.getAt(index).commit();
          
          this.fireEvent('notifysuccess', result.feedback);
        }else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
        }
      },
      scope: this
    });
  },
  
  onSearch: function(){
    var filter = this.txtSearch.getValue() || null;
    var store = this.getStore();
          
    store.getProxy().extraParams['search'] = filter;
    store.load();
  },
  
  onRefresh: function() {
    this.getStore().load();
  },
  
  refreshGrid: function (categoriesId) {
    var store = this.getStore();

    store.getProxy().extraParams['categories_id'] = categoriesId;
    
    //reset the start page
    store.currentPage = 1
    store.load();
  }
});

/* End of file products_grid.php */
/* Location: ./templates/base/web/views/products/products_grid.php */