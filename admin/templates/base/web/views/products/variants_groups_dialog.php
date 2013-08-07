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

Ext.define('Toc.products.VariantsGroupsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'variants_group-dialog-win';
    config.title = '<?php echo lang('dialog_variants_groups_heading_title'); ?>';
    config.width = 450;
    config.height = 300;
    config.layout = 'fit';
    config.iconCls = 'icon-product_variants-win';
    
    config.items = this.buildGrid();
    
    config.buttons = [{
      text: TocLanguage.btnAdd,
      handler: function() {
        this.submitForm();
      },
      scope: this
    }, {
      text: TocLanguage.btnClose,
      handler: function() { 
        this.close();
      },
      scope: this
    }];
    
    this.group_ids = config.group_ids || [];
    this.addEvents({'groupChange' :true});
    
    this.callParent([config]);
  },
  
  buildGrid: function() {
    var dsVariantGroups = Ext.create('Ext.data.Store', {
      fields: ['groups_id', 'groups_name'],
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('products/load_variants_groups'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      listeners: {
        load:this.onDsVariantGroupsLoad,
        scope: this
      },
      autoLoad: true
    });
    
    this.grdProductsVariants = Ext.create('Ext.grid.GridPanel', {
      viewConfig: {
        emptyText: TocLanguage.gridNoRecords
      },
      border: false,
      store: dsVariantGroups,
      selModel: Ext.create('Ext.selection.CheckboxModel'),
      selType: 'cellmodel',
      columns: [
        {header: '<?php echo lang('table_heading_attachments_name'); ?>', dataIndex: 'groups_name', flex: 1}
      ],
      tbar: [{
        text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onRefresh,
        scope: this
      }]
    });
    
    return this.grdProductsVariants;
  },
  
  onDsVariantGroupsLoad: function() {
    var rows = [];
    
    if (!Ext.isEmpty(this.group_ids) && Ext.isArray(this.group_ids))
    {
      Ext.each(this.group_ids, function(id){
        var row =  this.grdProductsVariants.getStore().findRecord('groups_id', id);
        rows.push(row);
      }, this);
    }
    
    this.grdProductsVariants.getSelectionModel().select(rows);
  },
  
  submitForm: function() {
    var groups = [];
    var records = this.grdProductsVariants.selModel.getSelection();
    
    Ext.each(records, function(record) {
      var group = {id: record.get('groups_id'), name: record.get('groups_name')};
      
      groups.push(group); 
    });
    
    if (groups.length > 0) {
      this.fireEvent('groupChange', groups);
    }

    this.close();
  },
  
  onRefresh: function() {
    this.grdProductsVariants.getStore().load();
  }
});

/* End of file variants_groups_dialog.php */
/* Location: ./templates/base/web/views/products/variants_groups_dialog.php */