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

Ext.define('Toc.orders.OrdersChooseProductGrid', {
	extend: 'Ext.grid.Panel',
	
	constructor: function(config) {
		config = config || {};
		
		config.border = false;
		config.autoScroll = true;
		config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
		
		config.plugins = [
			Ext.create('Ext.grid.plugin.CellEditing', {
				clicksToEdit: 1
			})
		];
		
		config.store = Ext.create('Ext.data.Store', {
			fields: [
				'products_id',
				'products_name',
		        'products_type',
		        'products_sku',
		        'products_price',
		        'products_quantity',
		        'new_qty',
		        'has_variants'
			],
			pageSize: Toc.CONF.GRID_PAGE_SIZE,
			proxy: {
				type: 'ajax',
				url : '<?php echo site_url('orders/list_choose_products'); ?>',
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
		
		config.columns = [
			{ header: '<?php echo lang('table_heading_products'); ?>', dataIndex: 'products_name', flex: 1},
			{ header: '<?php echo lang('table_heading_product_sku'); ?>', dataIndex: 'products_sku', align: 'center'},
			{ header: '<?php echo lang('table_heading_price'); ?>', dataIndex: 'products_price', align: 'center'},
			{ header: '<?php echo lang('table_heading_quantity_in_stock'); ?>', dataIndex: 'products_quantity', align: 'center'},
			{ header: '<?php echo lang('table_heading_quantity'); ?>', dataIndex: 'new_qty', align: 'center', editor: 'numberfield'},
			{
				xtype:'actioncolumn', 
				width: 40,
				items: [{
					getClass: this.getIconClass,
					handler: this.onAddProduct,
					scope: this
				}]
			}
		];
		
		config.dockedItems = [{
			xtype: 'pagingtoolbar',
			store: config.store,
	      	dock: 'bottom',
	      	displayInfo: true
	    }];
	    
	    config.listeners = {
		 	beforeedit: this.onBeforeEdit,
			edit: this.onAfterEdit,
			scope: this
		};
		
		this.addEvents({'saveSuccess': true});
	    
	    this.callParent([config]);
	},
	
	onBeforeEdit: function(e) {
		if (e.record.get('has_variants')) {
			return false;
		}
		
		return true;
	},
	
	onAfterEdit: function(editor, e) {
		var qty = e.record.get('products_quantity');
		var new_qty = e.record.get('new_qty');
    
		if (this.verifyQuantity(qty, new_qty)) {
			e.record.commit();
			
			return true;	
		}
		
		return false;
	},
	
	getIconClass: function(v, meta, rec) {
		if (rec.get('has_variants')) {
			return '';
		}
		
		return 'icon-action add';
	},
	
	getTooltip: function(v, meta, rec) {
		if (rec.get('has_variants')) {
			return '';
		}
		
		return TocLanguage.btnAdd;
	},
	
	onAddProduct: function(grid, rowIndex, colIndex) {
		var rec = grid.getStore().getAt(rowIndex);
		
		if (rec.get('has_variants')) {
			return;
		}
		
		var products_id = rec.get('products_id');
		var products_type = rec.get('products_type');
		var qty = rec.get('products_quantity');
		var new_qty = rec.get('new_qty');
		
		var params = {
			orders_id: this.ordersId,
			products_id: products_id,
			new_qty: new_qty
		};
		
		//currently, ignore the gitft certificate
		
		if (this.verifyQuantity(qty, new_qty)) {
			Ext.Ajax.request({
				waitMsg: TocLanguage.formSubmitWaitMsg,
				url: '<?php echo site_url('orders/add_product'); ?>',
				params: params,
				callback: function (options, success, response) {
					var result = Ext.decode(response.responseText);
					
					if (result.success == true) {
						this.fireEvent('saveSuccess', result.feedback);
						this.close();
					}else {
						Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
					}
				},
				scope: this
			});
		}
	},
	
	verifyQuantity: function(quantity, new_qty) {
		var new_qty = parseInt(new_qty);
		var quantity = parseInt(quantity);
		
		if (new_qty <= 0 || isNaN(new_qty)) {
			Ext.Msg.alert('<?php echo lang('error_wrong_quantity');?>');
			
			return false;
		}
		
		<?php if (STOCK_ALLOW_CHECKOUT == '-1') { ?>
		if (new_qty > quantity) {
			Ext.Msg.alert('<?php echo lang('error_max_stock_value_reached'); ?>');
			
			return =  false;
		}
		<?php }elseif (STOCK_CHECK == '1') { ?>
		if (new_qty > quantity) {
			return confirm('<?php echo lang('warning_max_stock_value_reached');?>');
		}
		<?php }?>
		
		return true;
	}
});

/* End of file orders_choose_product_grid.php */
/* Location: ./templates/base/web/views/orders/orders_choose_product_grid.php */