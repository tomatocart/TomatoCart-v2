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

Ext.define('Toc.orders.OrdersEditDialog', {
	extend: 'Ext.Window',
	
	constructor: function(config) {
		config = config || {};
  
		config.id = 'orders-edit-dialog-win';
		config.title = '<?php echo lang('heading_orders_title'); ?>';
		config.width = 850;
		config.height = 600;
		config.layout = 'fit';
		config.modal = true;
		config.iconCls = 'icon-orders-win';
		config.items = this.buildForm(config.ordersId, config.outStockProduct);
		
		config.buttons = [
			{
				text: TocLanguage.btnClose,
				handler: function() { 
					this.close();
				},
				scope: this
			}
	    ];
    
    	this.addEvents({'updateSuccess': true, 'editShippingMethod': true});
    
    	this.callParent([config]);
	},
	
	buildForm: function(ordersId, outStockProduct) {
		var pnlOrdersStatus = Ext.create('Toc.orders.OrdersStatusPanel', {ordersId: ordersId});
		this.frmOrderEdit = Ext.create('Toc.orders.OrdersEditPanel', {ordersId: ordersId, outStockProduct: outStockProduct});
		
		this.frmOrderEdit.on('updateSuccess', function(feedback) {
			this.fireEvent('updateSuccess', feedback);
		}, this);
		
		this.frmOrderEdit.on('editShippingMethod', function(ordersId, grdProducts) {
			this.fireEvent('editShippingMethod', ordersId, grdProducts);
		}, this);
		
		this.tabOrders = Ext.create('Ext.TabPanel', {
		 	activeTab: 0,
		 	border: false,
		 	autoScroll: true,
			items: [this.frmOrderEdit, pnlOrdersStatus]
		});
		
		return this.tabOrders;    
	}
});

/* End of file orders_edit_dialog.php */
/* Location: ./templates/base/web/views/orders/orders_edit_dialog.php */