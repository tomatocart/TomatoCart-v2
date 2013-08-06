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

Ext.define('Toc.tax_classes.TaxRatesGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.title = '<?php echo lang('section_tax_rates'); ?>';
    config.region = 'east';
    config.split = true;
    config.minWidth = 280;
    config.maxWidth = 360;
    config.width = 300;
    config.border = false;
    
    config.store = Ext.create('Ext.data.Store', {
      fields: ['tax_rates_id', 'tax_priority', 'tax_rate', 'geo_zone_name'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url: '<?php echo site_url('tax_classes/list_tax_rates'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    config.selModel = Ext.create('Ext.selection.CheckboxModel');
    
    config.columns = [
      { header: '<?php echo lang('table_heading_tax_rate_zone'); ?>', dataIndex: 'geo_zone_name', flex: 1},
      { header: '<?php echo lang('table_heading_tax_rate_priority'); ?>', dataIndex: 'tax_priority', width: 70},
      { header: '<?php echo lang('table_heading_tax_rate'); ?>', dataIndex: 'tax_rate', width: 80, align: 'right'},
      {
        xtype:'actioncolumn', 
        width: 60,
        header: '<?php echo lang("table_heading_action"); ?>',
        items: [{
          iconCls: 'icon-action icon-edit-record',
          tooltip: TocLanguage.tipEdit,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', {'record': rec, 'taxClassId': this.taxClassId, 'taxClassTitle': this.taxClassTitle});
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
        iconCls: 'add',
        handler: function() {this.fireEvent('create', this.taxClassId);},
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
      { text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onRefresh,
        scope: this
      }
    ];
    
    this.taxClassId = null;
    this.taxClassTitle = null;
    
    this.addEvents({'create': true, 'notifysuccess': true, 'edit': true});
    
    this.callParent([config]);
  },
  
  iniGrid: function(record) {
    this.taxClassId = record.get('tax_class_id');
    this.taxClassTitle = record.get('tax_class_title');
    
    this.getStore().getProxy().extraParams['tax_class_id'] = this.taxClassId;
    this.getStore().load();
  },
  
  onDelete: function(record) {
    var taxRatesId = record.get('tax_rates_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: '<?php echo site_url('tax_classes/delete_tax_rate'); ?>',
            params: {
              rateId: taxRatesId
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
      keys.push(item.get('tax_rates_id'));
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
              url: '<?php echo site_url('tax_classes/delete_tax_rates'); ?>',
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
  
  onRefresh: function() {
    this.getStore().load();
  }
});

/* End of file tax_rates_grid.php */
/* Location: ./templates/base/web/views/tax_classes/tax_rates_grid.php */