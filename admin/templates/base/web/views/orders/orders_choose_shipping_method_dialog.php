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

Ext.define('Toc.orders.OrdersChooseShippingMethodDialog', {
	extend: 'Ext.Window',
	
	constructor: function(config) {
		config = config || {};
		
		config.id = 'orders-shipping-method-win';  
		config.title = '<?php echo lang('heading_title_choose_shipping_method'); ?>';
		config.layout = 'fit';
		config.width = 400;
		config.height = 300;
		config.modal = true;
		config.iconCls = 'icon-orders-win';
		config.items = this.buildGrid(config.ordersId);
		
		config.buttons = [
			{
				text: TocLanguage.btnClose,
				handler: function () {
					this.close();
				},
				scope: this
			}
		];
		
		this.addEvents({'saveSuccess': true});
		
		this.callParent([config]);
	},
	
	buildGrid: function(ordersId) {
		var dsShippingMethod = Ext.create('Ext.data.Store', {
			fields:[
		        'title',
		        'code',
		        'price',
		        'action'
	      	],
	      	pageSize: Toc.CONF.GRID_PAGE_SIZE,
	      	proxy: {
		        type: 'ajax',
		        url : '<?php echo site_url('orders/list_shipping_methods'); ?>',
		        extraParams: {orders_id: ordersId},
		        reader: {
		          type: 'json',
		          root: Toc.CONF.JSON_READER_ROOT,
		          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
		        }
	      	},
	      	autoLoad: true
	    });
	    
	    this.grdShippingMethod = Ext.create('Ext.grid.GridPanel', {
	    	store: dsShippingMethod,
	    	columns: [
	    		{id: 'orders_shipping_methods_title', header: '<?php echo lang('table_heading_shipping_method'); ?>', dataIndex: 'title', flex: 1},
        		{header: '<?php echo lang('table_heading_price'); ?>', dataIndex: 'price', width: 100},
        		{
			        xtype:'actioncolumn', 
			        width: 140,
			        header: '<?php echo lang("table_heading_action"); ?>',
			        items: [
				        {
				          getClass: this.getAddClass,
				          handler: function(grid, rowIndex, colIndex) {
				            var rec = grid.getStore().getAt(rowIndex);
				            
				            this.onChangeShippingMethod(rec);
				          },
				          scope: this
				        }
			        ]
		      	}
	    	]
	    });
	    
	    return this.grdShippingMethod;
	},
	
	onChangeShippingMethod: function(record) {
		Ext.Ajax.request({
			waitMsg: TocLanguage.formSubmitWaitMsg,
			url: '<?php echo site_url('orders/save_shipping_method'); ?>',
			params: {
				code: record.get('code'),
        		orders_id: this.ordersId
			},
			callback: function (options, success, response) {
				var result = Ext.decode(response.responseText);
        
		        if (result.success == true) {
	          		this.fireEvent('saveSuccess', result.feedback);
	          		this.close();
		        } else {
	          		Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
		        }
			},
			scope: this
		});
	},
	
	getAddClass: function(v, meta, rec) {
		var action = rec.get('action');
		
		if (action['class'] === 'icon-add-record') {
			return 'icon-action icon-add-record';
		}
		
		return '';
	}
});