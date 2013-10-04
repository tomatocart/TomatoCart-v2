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
 * Admin Create Account Email -- Email Template Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Email_Template_admin_create_account_email extends TOC_Email_Template_Module
{
	/**
	 * Email Template Name
	 *
	 * @access private
	 * @var string
	 */
	protected $template_name = 'admin_create_account_email';

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
		'%%greeting_text%%',
		'%%customer_first_name%%',
		'%%customer_last_name%%',
		'%%customer_email_address%%',
		'%%password%%',
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
	 * @param string
	 * @return void
	 */
	public function set_data($first_name, $last_name, $email_address, $password, $gender)
	{
		$this->data = array(
			'first_name' => $first_name,
			'last_name' => $last_name,
			'email_address' => $email_address,
			'password' => $password,
			'gender' => $gender
		);

		$this->add_recipient($first_name . ' ' . $last_name, $email_address);
	}

	/**
	 * Build message
	 *
	 * @access public
	 * @return void
	 */
	public function build_message() {
		// build the message content
		if ((config('ACCOUNT_GENDER') > -1) && isset($this->data['gender']) && ! empty($this->data['gender'])) {
			if ($this->data['gender'] == 'm') {
				$greeting_text = sprintf(lang('email_greet_mr'), $this->data['last_name']) . "<br /><br />";
			} else {
				$greeting_text = sprintf(lang('email_greet_ms'), $this->data['last_name']) . "<br /><br />";
			}
		} else {
			$greeting_text = sprintf(lang('email_greet_general'), $this->first_name . ' ' . $this->_last_name) . "<br /><br />";
		}

		$replaces = array(
			$greeting_text, 
			$this->data['first_name'], 
			$this->data['last_name'], 
			$this->data['email_address'], 
			$this->data['password'], 
			config('STORE_NAME'), 
			config('STORE_OWNER_EMAIL_ADDRESS')
		);

		$this->title = str_replace($this->keywords, $replaces, $this->title);
		$this->content = str_replace($this->keywords, $replaces, $this->content);
	}
}

/* End of file email_template_admin_create_account_email.php */
/* Location: ./system/tomatocart/libraries/email_template/email_template_admin_create_account_email.php */