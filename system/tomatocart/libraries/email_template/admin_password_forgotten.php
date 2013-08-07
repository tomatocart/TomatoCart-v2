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
 * Password forgotten -- Email Template Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class toC_Email_Template_admin_password_forgotten extends TOC_Email_Template_Module
{
    /**
     * Email Template Name
     *
     * @access private
     * @var string
     */
    protected $template_name = 'admin_password_forgotten';
    
    /**
     * Email Template Keywords
     *
     * @access private
     * @var array
     */
    protected $keywords = array( '%%user_name%%',
                                 '%%admin_ip_address%%',
                                 '%%admin_password%%',
                                 '%%store_name%%',
                                 '%%store_owner_email_address%%');

    /**
     * Constructor
     *
     * @access public
     */
    function __construct() {
        parent::__construct($this->template_name);
    }

    /**
     * Set data
     *
     * @access public
     * @param $osC_Customer
     * @param $password
     * @return void
     */
    function set_data($user_name, $ip_address, $password, $admin_email) {
        $this->user_name = $user_name;
        $this->ip_address = $ip_address;
        $this->password = $password;

        $this->add_recipient($this->user_name, $admin_email);
    }

    /**
     * Build message
     *
     * @access public
     * @return void
     */
    function build_message() {
        $replaces = array($this->user_name, $this->ip_address, $this->password, config('STORE_NAME'), config('STORE_OWNER_EMAIL_ADDRESS'));
        
        $this->title = str_replace($this->keywords, $replaces, $this->title);
        $this->email_text = str_replace($this->keywords, $replaces, $this->content);
    }
}

/* End of file email_template_create_account_email.php */
/* Location: ./system/tomatocart/libraries/email_template/email_template_create_account_email.php */