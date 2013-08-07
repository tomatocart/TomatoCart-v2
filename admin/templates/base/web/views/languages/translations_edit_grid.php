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

Ext.define('Toc.languages.TranslationsEditGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.layout = 'fit';
    config.border = false;
    config.region = 'center';
    config.clicksToEdit = 1;
    config.loadMask = true;
    
    this.contentGroup = 'general';
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'languages_definitions_id', 
        'content_group',
        'definition_key',
        'definition_value'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url: '<?php echo site_url('languages/list_translations'); ?>',
        extraParams: {
          languages_id: config.languagesId
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    config.columns =[
      {header: '<?php echo lang('table_heading_definition_key'); ?>', dataIndex: 'definition_key', sortable: true, width: 200},
      {header: '<?php echo lang('table_heading_definition_value'); ?>', width: 130, dataIndex: 'definition_value', flex: 1},
      {
        xtype: 'actioncolumn', 
        width: 120,
        header: '<?php echo lang("table_heading_action"); ?>',
        items: [{
          tooltip: TocLanguage.tipEdit,
          iconCls: 'icon-action icon-edit-record',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', {record: rec, languagesId: config.languagesId, group: this.contentGroup});
          },
          scope: this
        },
        {
          tooltip: TocLanguage.tipDelete,
          iconCls: 'icon-action icon-delete-record',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.onDelete(rec);
          },
          scope: this
        }]
      }
    ];
    
    config.search = Ext.create('Ext.form.TextField', {name: 'search', width: 250});
    
    config.tbar = [
    {
      text: '<?php echo lang('button_add_definition'); ?>',
      iconCls: 'add',
      handler: function() {
        this.fireEvent('adddefinition', {languagesId: config.languagesId, group: this.contentGroup});
      },
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
    
    this.addEvents({'edit': true, 'adddefinition': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onSearch: function () {
    var store = this.getStore();

    store.getProxy().extraParams['search'] = this.search.getValue() || null;
    store.load();
  },
  
  refreshGrid: function(group) {
    this.contentGroup = group;
    
    this.getStore().getProxy().extraParams['group'] = group;
    this.getStore().load();
  },
  
  onDelete: function(record) {
    var languagesDefinitionsId = record.get('languages_definitions_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: '<?php echo site_url('languages/delete_translation'); ?>',
            params: {
              languages_definitions_id: languagesDefinitionsId
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
  
  onRefresh: function() {
    this.getStore().load();
  }
});

/* End of file translations_edit_grid.php */
/* Location: ./templates/base/web/views/languages/translations_edit_grid.php */