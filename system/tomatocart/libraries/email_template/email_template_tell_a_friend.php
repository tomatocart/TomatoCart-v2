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
 * Tell A Friend -- Email Template Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Email_Template_tell_a_friend extends TOC_Email_Template_Module
{
	/**
	 * Email Template Name
	 *
	 * @access protected
	 * @var string
	 */
	protected $template_name = 'tell_a_friend';

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
		'%%from_name%%',
		'%%from_email_address%%',
		'%%to_name%%',
		'%%to_email_address%%',
		'%%message%%',
		'%%product_name%%',
		'%%store_name%%',
		'%%store_address%%',
		'%%store_owner_email_address%%',
		'%%product_link%%'
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
	 * @param string
	 * @param string
	 * @param string
	 * @return void
	 */
	public function set_data($from_name, $from_email_address, $to_name, $to_email_address, $message, $product_name, $product_link)
	{
		$this->data = array(
			'from_name' => $from_name,
			'from_email_address' => $from_email_address,
			'to_name' => $to_name,
			'to_email_address' => $to_email_address,
			'message' => $message,
			'product_name' => $product_name,
			'product_link' => $product_link
		);

		$this->add_recipient($to_name, $to_email_address);
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
			$this->data['from_name'],
			$this->data['from_email_address'],
			$this->data['to_name'],
			$this->data['to_email_address'],
			$this->data['message'],
			$this->data['product_name'],
			config('STORE_NAME'),
			site_url(),
			config('STORE_OWNER_EMAIL_ADDRESS'),
			$this->data['product_link']
		);
		
		$this->title = str_replace($this->keywords, $replaces, $this->title);
		$this->content = str_replace($this->keywords, $replaces, $this->content);
	}
}

/* End of file email_template_tell_a_friend.php */
/* Location: ./system/tomatocart/libraries/email_template/email_template_tell_a_friend.php */