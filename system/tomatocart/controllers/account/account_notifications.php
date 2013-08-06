<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Account_Notifications Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Account_Notifications extends TOC_Controller {

	/**
	 * Constructor
	 *
	 * @access public
	 * @param string
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Default Function
	 *
	 * @access public
	 * @param string
	 */
	public function index()
	{
		$data['title'] = 'My Product Notifications';
		$data['text_title'] = 'My Product Notifications';
		$data['text_product_notifications'] = 'My Product Notifications';
		$data['text_product_description'] = ' The product notification list allows you to stay up to date on products you find of interest.<br><br>To be up to date on all product changes, select <b>Global Product Notifications</b>.';
		$data['text_global'] = 'Global Product Notifications';
		$data['text_global_notifications'] = 'Global Product Notifications';
		$data['text_global_description'] = 'Recieve notifications on all available products.';
		$data['text_product_notifications_products'] = 'Product Notifications';
		$data['text_product_notifications_products_description'] = 'To remove a product notification, clear the products checkbox and click on Continue.';
		$data['text_product_notifications_products_none'] = 'There are currently no products marked to be notified on.<br><br>To add products to your product notification list, click on the notification link available on the detailed product information page.';
		$data['text_continue'] = 'Continue';

		$data['notifications_products'] = array(
		array('product_id' => '19' , 'product_name' => 'SONY DSC-T700(g) DIGITAL VIDEO CAMERA'),
		array('product_id' => '15' , 'product_name' => 'Toshiba Satellite A355-S6921'),
		);

		$data['value_global'] = 'product_global';
		$data['value_global_product_notifications'] = 0;

		$data['link_save'] = '';
		$data['link_continue'] = '';

		$this->template->build('account/account_notifications', $data);
	}
}

/* End of file account_notifications.php */
/* Location: ./system/tomatocart/controllers/account/account_notifications.php */