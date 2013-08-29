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

Ext.define('Toc.orders.OrdersEditProductsGrid', {
	extend: 'Ext.grid.Panel',
  
	constructor: function(config) {
		config = config || {};
    
		config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
		config.border = true;
		config.width = 792;
    
		config.plugins = [
			Ext.create('Ext.grid.plugin.CellEditing', {
				clicksToEdit: 1
			})
		];
    
		config.store = Ext.create('Ext.data.Store', {
			fields:[
				'orders_products_id',
				'products_id',
				'products_type',
				'orders_id',
				'products',
				'quantity',
				'qty_in_stock',
				'sku',
				'tax',
				'price_net',
				'price_gross',
				'total_net',
				'total_gross',
				'shipping_method',
				'action'
			],
			pageSize: Toc.CONF.GRID_PAGE_SIZE,
			proxy: {
				type: 'ajax',
				url : '<?php echo site_url('orders/list_orders_edit_products'); ?>',
				extraParams: {
					orders_id: config.ordersId
				},
				reader: {
					type: 'json',
					root: Toc.CONF.JSON_READER_ROOT,
					totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
				}
			},
			autoLoad: true
		});
		
		//Here we can not use the system default currency,
		//the order's currency has to be used instead to format the price.
		var formatPrice = function(price) {
			var code = config.cboCurrencies.getValue();
			var store = config.cboCurrencies.store;
			var value = price;
		    
			store.each(function(record){
				id = record.get('id');
		
				if (id == code) {
					var symbol_left = record.get('symbol_left');
					var symbol_right = record.get('symbol_right');
					var decimal_places = record.get('decimal_places');
					var decimal_sep = ".";
					var thousand_sep = ",";
		    
					var m = /(\d+)(?:(\.\d+)|)/.exec(price + ""),
					x = m[1].length > 3 ? m[1].length % 3 : 0;
		    
					value = symbol_left + " "
						+ (price < 0? '-' : '') // preserve minus sign
						+ (x ? m[1].substr(0, x) + thousand_sep : "")
						+ m[1].substr(x).replace(/(\d{3})(?=\d)/g, "$1" + thousand_sep)
						+ (decimal_places? decimal_sep + (+m[2] || 0).toFixed(decimal_places).substr(2) : "")
						+ ((symbol_right != null) ? (" " + symbol_right) : '');
				}
			});
		    
			return value;
		};
		
		var outStockProducts = config.outStockProduct;
		var i = 0;
    
		config.selModel = Ext.create('Ext.selection.CheckboxModel');
    
		config.columns =[
			{
				id: 'orders_edit_products', 
				header: '<?php echo lang('table_heading_products');?>', 
				flex: 1,
				dataIndex: 'products', 
				renderer: function(val) {
					if(outStockProducts != null && outStockProducts.length > 0) {
						var products_id = config.ds.getAt(i++).data['products_id'];
						for(var j = 0; j < outStockProducts.length; j++) {
							if(outStockProducts[j] == products_id) { 
								return val + '<br/><span style="color:red;"><?php echo lang('table_heading_out_products_stock');?></span>';
							}       
						}
					}
        
					return val;
				}
			},
			{header: '<?php echo lang('table_heading_product_sku'); ?>', dataIndex: 'sku', width: 80, align: 'right', editor: 'textfield'},
			{header: '<?php echo lang('table_heading_product_qty'); ?>', dataIndex: 'quantity', width: 60, align: 'center', editor: Ext.create('Ext.form.NumberField', {allowNegative: false, allowDecimals: false})},
			{header: '<?php echo lang('table_heading_tax'); ?>', dataIndex: 'tax', align: 'center', width: 50,},
			{header: '<?php echo lang('table_heading_price_net'); ?>', dataIndex: 'price_net', align: 'right', width: 80, editor: 'numberfield', renderer: formatPrice},
			{header: '<?php echo lang('table_heading_price_gross'); ?>', dataIndex: 'price_gross', align: 'right', width: 80},
			{header: '<?php echo lang('table_heading_total_gross'); ?>', dataIndex: 'total_gross', align: 'right', width: 80},
			{
				xtype:'actioncolumn', 
				width: 140,
				items: [
					{
						iconCls: 'icon-action icon-delete-record',
						tooltip: TocLanguage.tipDelete,
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);
            
							this.onDelete(rec);
						},
						scope: this                
					}
				]
			}
    	];
    	
		config.listeners = {
			afteredit: this.onAfterEdit,
			scope: this
		};
		
		this.addEvents({'delete': true});
    
		this.callParent([config]);
	},
	
	onAfterEdit: function() {
	
	},
	
	onDelete: function(record) {
	}
});

/* End of file orders_edit_products_grid.php */
/* Location: ./templates/base/web/views/orders/orders_edit_products_grid.php */