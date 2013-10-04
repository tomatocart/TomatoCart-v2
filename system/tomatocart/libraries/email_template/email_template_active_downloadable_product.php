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
 * Active Downloadable Product -- Email Template Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Email_Template_active_downloadable_product extends TOC_Email_Template_Module
{
	/**
	 * Email Template Name
	 *
	 * @access private
	 * @var string
	 */
	protected $template_name = 'active_downloadable_product';

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
		'%%customer_name%%',
		'%%customer_email_address%%',
		'%%downloadable_products%%',
		'%%download_link%%',
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
	 * @param string 
	 * @param string
	 * @param string
	 * @return void
	 */
	public function set_data($customer_name, $email_address, $download_products)
	{
		$this->data = array(
			'customer_name' => $customer_name,
			'email_address' => $email_address,
			'downloadable_products' => $download_products
		);

		$this->add_recipient($customer_name, $email_address);
	}

	/**
	 * Build message
	 *
	 * @access public
	 * @return void
	 */
	public function build_message() 
	{
		$replaces = array(
			$this->data['customer_name'],
			$this->data['email_address'],
			$this->data['downloadable_products'],
			site_url('account/orders'),
			config('STORE_NAME'),
			config('STORE_OWNER_EMAIL_ADDRESS')
		);
		
		$this->title = str_replace($this->keywords, $replaces, $this->title);
		$this->content = str_replace($this->keywords, $replaces, $this->content);
	}
}

/* End of file email_template_active_downloadable_product.php */
/* Location: ./system/tomatocart/libraries/email_template/email_template_active_downloadable_product.php */