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

require_once 'email_template_module.php';

/**
 * Admin Create Purchase Order Email -- Email Template Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Email_Template_admin_create_purchase_order extends TOC_Email_Template_Module
{
	/**
	 * Email Template Name
	 *
	 * @access protected
	 * @var string
	 */
	protected $template_name = 'admin_create_purchase_order';

	/**
	 * Hold replaces data of keywords
	 *
	 * @access protected
	 * @var array
	 */
	protected $data = array();

	/**
	 * Email Template Keywords
	 *
	 * @access private
	 * @var array
	*/
	protected $keywords = array(
		'%%vendors_name%%',
		'%%purchase_order_products%%',
		'%%order_number%%',
		'%%total_amount%%',
		'%%store_name%%',
		'%%store_ower_email_address%%'
	);

	/**
	 * Constructor
	 *
	 * @access public
	*/
	public function __construct()
	{
		parent::__construct($this->template_name);
	}

	/**
	 * Set data
	 *
	 * @access public
	 * @param int 
	 * @param string
	 * @param string
	 * @param array
	 * @param int
	 * @return void
	 */
	public function set_data($purchase_orders_no, $vendors_name, $vendors_email_address, $products, $total_amount)
	{
		$this->data = array(
			'vendors_name' => $vendors_name,
			'vendors_email_address' => $vendors_email_address,
			'purchase_order_products' => $products,
			'total_amount' => $total_amount,
			'order_number' => $purchase_orders_no
		);

		$this->add_recipient($vendors_name, $vendors_email_address);
	}

	/**
	 * Build message
	 *
	 * @access public
	 * @return void
	 */
	public function build_message() {
		$replaces = array(
			$this->data['vendors_name'], 
			$this->data['purchase_order_products'], 
			$this->data['order_number'], 
			$this->data['total_amount'], 
			config('STORE_NAME'), 
			config('STORE_OWNER_EMAIL_ADDRESS')
		);

		$this->title = str_replace($this->keywords, $replaces, $this->title);
		$this->content = str_replace($this->keywords, $replaces, $this->content);
	}
}

/* End of file email_template_admin_create_purchase_order.php */
/* Location: ./system/tomatocart/libraries/email_template/email_template_admin_create_purchase_order.php */