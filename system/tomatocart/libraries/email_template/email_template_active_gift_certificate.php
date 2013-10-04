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
 * Active Gift Certificate -- Email Template Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Email_Template_active_gift_certificate extends TOC_Email_Template_Module
{
	/**
	 * Email Template Name
	 *
	 * @access protected
	 * @var string
	 */
	protected $template_name = 'active_gift_certificate';

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
		'%%sender_name%%',
		'%%sender_email%%',
		'%%recipient_name%%',
		'%%recipient_email%%',
		'%%gift_certificate_amount%%',
		'%%gift_certificate_code%%',
		'%%gift_certificate_message%%',
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
	 * @param string
	 * @param int
	 * @param string
	 * @param string
	 * @return void
	 */
	public function set_data($sender_name, $sender_email, $recipient_name, $recipient_email, $gift_certificate_amount, $gift_certificate_code, $gift_certificate_message)
	{
		$this->data = array(
			'sender_name' => $sender_name,
			'sender_email' => $sender_email,
			'recipient_name' => $recipient_name,
			'recipient_email' => $recipient_email,
			'gift_certificate_amount' => $gift_certificate_amount,
			'gift_certificate_code' => $gift_certificate_code,
			'gift_certificate_message' => $gift_certificate_message
		);

		$this->add_recipient($recipient_name, $recipient_email);
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
			$this->data['sender_name'],
			$this->data['sender_email'],
			$this->data['recipient_name'],
			$this->data['recipient_email'],
			$this->data['gift_certificate_amount'],
			$this->data['gift_certificate_code'],
			$this->data['gift_certificate_message'],
			config('STORE_NAME'),
			config('STORE_OWNER_EMAIL_ADDRESS')
		);
		
		$this->title = str_replace($this->keywords, $replaces, $this->title);
		$this->content = str_replace($this->keywords, $replaces, $this->content);
	}
}

/* End of file email_template_active_gift_certificate.php */
/* Location: ./system/tomatocart/libraries/email_template/email_template_active_gift_certificate.php */