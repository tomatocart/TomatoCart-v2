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
 * order status updated -- Email Template Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Email_Template_admin_order_status_updated extends TOC_Email_Template_Module
{
	/**
	 * Email Template Name
	 *
	 * @access protected
	 * @var string
	 */
	protected $template_name = 'admin_order_status_updated';
	
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
		'%%order_comment%%',
		'%%new_order_status%%',
		'%%customer_name%%',
		'%%store_name%%',
		'%%store_owner_email_address%%'
	);
	
	/**
	 * Hold replaces data of keywords
	 *
	 * @access protected
	 * @var array
	 */
	protected $data = array();
	
	/**
	 * Constructor
	 *
	 * @access public
	 */
	public function __construct() 
	{
		parent::__construct($this->template_name);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Set data
	 *
	 * @access public
	 * @param int
	 * @param string
	 * @param string
	 * @param boolean
	 * @param string
	 * @param int
	 * @param string
	 * @param string
	 * @return void
	 */
	public function set_data($order_number, $invoice_link, $date_ordered, $append_comment, $order_comment, $new_order_status, $customer_name, $customers_email_address)
	{
		$this->data = array(
			'order_number' => $order_number, 
			'invoice_link' => $invoice_link, 
			'date_ordered' => $date_ordered,
			'order_comment' => $order_comment,
			'new_order_status' => $new_order_status,
			'append_comment' => $append_comment,
			'customer_name' => $customer_name
		);
		
		$this->add_recipient($this->customer_name, $customers_email_address);
	}
	
	/**
	 * Build message
	 *
	 * @access public
	 * @return void
	 */
	public function build_message() 
	{
		if ($this->data['append_comment'] === FALSE)
		{
			$this->data['order_comment'] = '';
		}
		
		$replaces = array(
			$this->data['order_number'], 
			$this->data['invoice_link'], 
			$this->data['date_ordered'], 
			$this->data['order_comment'], 
			$this->data['new_order_status'], 
			$this->data['customer_name'], 
			config('STORE_NAME'), 
			config('STORE_OWNER_EMAIL_ADDRESS')
		);
		
		$this->title = str_replace($this->keywords, $replaces, $this->title);
		$this->email_text = str_replace($this->keywords, $replaces, $this->content);
	}
}
