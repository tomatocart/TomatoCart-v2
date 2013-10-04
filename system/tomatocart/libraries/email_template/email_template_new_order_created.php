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
 * New Order Created -- Email Template Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Email_Template_new_order_created extends TOC_Email_Template_Module
{
	/**
	 * Email Template Name
	 *
	 * @access protected
	 * @var string
	 */
	protected $template_name = 'new_order_created';

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
	 * @access protected
	 * @var array
	*/
	protected $keywords = array(
		'%%order_number%%',
		'%%invoice_link%%',
		'%%date_ordered%%',
		'%%order_details%%',
		'%%delivery_address%%',
		'%%billing_address%%',
		'%%order_status%%',
		'%%order_comments%%',
		'%%store_name%%',
		'%%store_owner_email_address%%'
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
	 * @return void
	 */
	public function set_data($orders_id)
	{
		$this->data = array('orders_id' => $orders_id);
	}

	/**
	 * Build message
	 *
	 * @access public
	 * @return void
	 */
	public function build_message() 
	{
		//load the email template model
		$this->ci->load->model('order_model');
		
		//get the order data
		$order = $this->ci->order_model->query($this->data['orders_id']);
		
		if (count($order) > 0)
		{
			//add recipient
			$this->add_recipient($order['customers_name'], $order['customers_email_address']);
			
			//invoice link
			$invoice_link = site_url('account/orders/' . $this->data['orders_id']);
			
			//order details
			$order_details = lang('email_order_products') . '<br />' . lang('email_order_separator') . '<br />';
			$products = $order['products'];
			if (count($products) > 0)
			{
				foreach ($products as $product)
				{
					$order_details .= $product['qty'] . ' x ' . $product['name'] . ' (' . $product['sku'] . ') = ' . $this->currencies->display_price_with_tax_rate($product['final_price'], $product['tax'], $product['qty'], $order['currency'], $order['currency_value']) . "<br />";
					
					//product variants
					if (count($product['variants']) > 0)
					{
						foreach ($product['variants'] as $variant)
						{
							$order_details .= "\t" . $variant['groups_name'] . ': ' . $variant['values_name'] . "<br />";
						}
					}
				}
			}
			
			$order_details .= lang('email_order_separator') . "<br />";
			
			//order totals
			if (count($order['totals']) > 0)
			{
				foreach ($order['totals'] as $order_total)
				{
					$order_details .= strip_tags($order_total['title'] . ' ' . $order_total['text']) . "<br />";
				}
			}
			
			//delivery address
			$delivery_address = '';
			if ($order['delivery'] !== FALSE)
			{
				$delivery_address = address_format($order['delivery'], "<br />");
			}
			
			//billing address
			$billing_address = address_format($order['billing'], "<br />");
			
			//order status
			$order_status = $order['orders_status_name'];
			
			//order comments
			$order_comments = '';
			if (count($order['status_history']) > 0)
			{
				foreach ($order['status_history'] as $order_status_history)
				{
					$order_comments .= get_date_long($order_status_history['date_added']) . "<br />\t" . wordwrap(str_replace("<br />", "<br />\t", $order_status_history['comments']), 60, "<br />\t", 1) . "<br /><br />";
				}
			}
		}
		
		$replaces = array($this->data['orders_id'], $invoice_link, get_date_long($order['date_purchased']), $order_details, $delivery_address, $billing_address, $order_status, $order_comments, config('STORE_NAME'), config('STORE_OWNER_EMAIL_ADDRESS'));
		
		$this->title = str_replace($this->keywords, $replaces, $this->title);
		$this->content = str_replace($this->keywords, $replaces, $this->content);
	}
}

/* End of file email_template_new_order_created.php */
/* Location: ./system/tomatocart/libraries/email_template/email_template_new_order_created.php */