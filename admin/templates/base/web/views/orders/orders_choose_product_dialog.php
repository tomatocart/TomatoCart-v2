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

Ext.define('Toc.orders.OrdersChooseProductDialog', {
	extend: 'Ext.Window',
	
	constructor: function(config) {
		config = config || {};
		
		config.id = 'orders-choose-product-win';
		config.title = '<?php echo lang('heading_title_choose_product_title'); ?>';
		config.layout = 'fit';
		config.width = 700;
		config.height = 400;
		config.modal = true;
		config.border = false;
		config.iconCls = 'icon-products-win';
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
		this.grdProducts = Ext.create('Toc.orders.OrdersChooseProductGrid', {ordersId: ordersId});
		
		this.grdProducts.on('saveSuccess', function(feedback) {
			this.fireEvent('saveSuccess', feedback);
		}, this);
		
		return this.grdProducts;
	}
});

/* End of file orders_choose_product_dialog.php */
/* Location: ./templates/base/web/views/orders/orders_choose_product_dialog.php */