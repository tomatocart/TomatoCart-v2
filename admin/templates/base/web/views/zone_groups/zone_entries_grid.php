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

Ext.define('Toc.zone_groups.ZoneEntriesGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.title = '<?php echo lang('action_heading_new_zone_entry'); ?>';
    config.border = false;
    config.region = 'east';
    config.split = true;
    config.minWidth = 280;
    config.maxWidth = 370;
    config.width = 350;
    
    config.store = Ext.create('Ext.data.Store', {
      fields: ['geo_zone_entry_id', 'countries_id', 'countries_name', 'zone_name', 'zone_id'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('zone_groups/list_zone_entries'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    config.selModel = Ext.create('Ext.selection.CheckboxModel');
    
    config.columns = [
      { header: '<?php echo lang('table_heading_country'); ?>', dataIndex: 'countries_name', flex: 1},
      { header: '<?php echo lang('table_heading_zone'); ?>', dataIndex: 'zone_name'},
      {
        xtype:'actioncolumn', 
        width: 60,
        header: '<?php echo lang("table_heading_action"); ?>',
        items: [{
          iconCls: 'icon-action icon-edit-record',
          tooltip: TocLanguage.tipEdit,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', {'record': rec, 'geoZoneId': this.geoZoneId, 'geoZoneName': this.geoZoneName});
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
        handler: function() {this.fireEvent('create', this.geoZoneId);},
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
        iconCls: 'refresh',
        handler:this.onRefresh,
        scope:this
      }
    ];
    
    this.addEvents({'notifysuccess': true, 'create': true, 'edit': true});
    
    this.geoZoneId = null;
    this.geoZoneName = null;
    
    this.callParent([config]);
  },
  
  iniGrid: function(record) {
    this.geoZoneId = record.get('geo_zone_id');
    this.geoZoneName = record.get('geo_zone_name');
    
    this.getStore().getProxy().extraParams['geo_zone_id'] = this.geoZoneId;
    this.getStore().load();
  },
  
  onDelete: function(record) {
    var geoZoneEntryId = record.get('geo_zone_entry_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: '<?php echo site_url('zone_groups/delete_zone_entry'); ?>',
            params: {
              geo_zone_entry_id: geoZoneEntryId
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
      keys.push(item.get('geo_zone_entry_id'));
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
              url: '<?php echo site_url('zone_groups/delete_zone_entries'); ?>',
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

/* End of file zone_entries_grid.php */
/* Location: ./templates/base/web/views/zone_groups/zone_entries_grid.php */