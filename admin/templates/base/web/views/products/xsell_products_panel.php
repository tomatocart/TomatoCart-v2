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

Ext.define('Toc.products.XsellProductsGrid', {
  extend: 'Ext.grid.GridPanel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_xsell_products'); ?>';
    config.productsId = config.productsId || null;
    
    config.store = Ext.create('Ext.data.Store', {
      fields: ['products_id', 'products_name'],
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('products/get_xsell_products'); ?>',
        extraParams: {
          products_id: config.productsId
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
    config.selType = 'cellmodel';
    
    config.columns =[
      Ext.create('Ext.grid.RowNumberer'),
      { header: '<?php echo lang('table_heading_products'); ?>', dataIndex: 'products_name', sortable: true, flex: 1},
      {
        xtype:'actioncolumn', 
        width:50,
        header: '<?php echo lang("table_heading_action"); ?>',
        items: [{
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
    
    this.buildCboProducts(config.productsId);
    
    config.tbar = [
      { 
        text: TocLanguage.btnRefresh,
        iconCls:'refresh',
        handler: this.onRefresh,
        scope: this
      }, 
      '->', 
      this.cboProducts, 
      ' ', 
      {
        text: '<?php echo lang('button_insert'); ?>',
        iconCls : 'add',
        handler: this.addProduct,
        scope: this
      }
    ];
    
    this.callParent([config]);
  },
  
  buildCboProducts: function(productsId) {
    var store = Ext.create('Ext.data.Store', {
      fields: ['id', 'text'],
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('products/get_products'); ?>',
        extraParams: {
          products_id: productsId
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.cboProducts = Ext.create('Ext.form.ComboBox', {
      name: 'xsellproducts',
      store: store,
      emptyText: '<?php echo lang('section_xsell_products'); ?>',
      width: 400,
      queryMode: 'local', 
      displayField: 'text', 
      valueField: 'id',
      pageSize: Toc.CONF.GRID_PAGE_SIZE, 
      editable: false
    }); 
  },
  
  addProduct: function() {
    var productId = this.cboProducts.getValue().toString();
    var productName = this.cboProducts.getRawValue().toString();
    
    if (!Ext.isEmpty(productId)) {
      store = this.getStore();
      
      if (store.findExact('products_id', productId) == -1) {
        Ext.define('xsell', {
          extend: 'Ext.data.Model',
          fields: [
            {name: 'products_id', type: 'string'},
            {name: 'products_name', type: 'string'}
          ]
        });
        
        var xsell_record = Ext.ModelManager.create({
          products_id: productId,
          products_name: productName
        }, 'xsell');
        
        store.add(xsell_record);
      }
    }
  },
  
  onDelete: function(record) {
    this.getStore().remove(record);
  },
  
  onRefresh: function() {
    this.getStore().load();
  },
  
  getXsellProductIds: function() {
    var batch = [];
    
    this.getStore().each(function(record) {
      batch.push(record.get('products_id'));
    });
    
    return batch.join(';');
  }
});

/* End of file xsell_products_panel.php */
/* Location: ./templates/base/web/views/products/xsell_products_panel.php */