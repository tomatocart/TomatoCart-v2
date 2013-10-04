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
 * Out Of Stock Alerts -- Email Template Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Email_Template_out_of_stock_alerts extends TOC_Email_Template_Module
{
	/**
	 * Email Template Name
	 *
	 * @access protected
	 * @var string
	 */
	protected $template_name = 'out_of_stock_alerts';

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
		'%%products_name%%',
		'%%products_variants%%',
		'%%products_quantity%%'
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
	 * @param string
	 * @param int
	 * @return void
	 */
	public function set_data($products_name, $products_quantity, $products_variants = '')
	{
		$this->data = array(
			'products_quantity' => $products_quantity,
			'products_name' => $products_name,
			'products_variants' => $products_variants
		);

		$this->add_recipient(config('STORE_NAME'), config('STORE_OWNER_EMAIL_ADDRESS'));
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
			$this->data['products_name'],
			$this->data['products_variants'],
			$this->data['products_quantity']
		);
		
		$this->title = str_replace($this->keywords, $replaces, $this->title);
		$this->content = str_replace($this->keywords, $replaces, $this->content);
	}
}

/* End of file email_template_out_of_stock_alerts.php */
/* Location: ./system/tomatocart/libraries/email_template/email_template_out_of_stock_alerts.php */