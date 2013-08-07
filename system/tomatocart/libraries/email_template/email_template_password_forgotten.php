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
class toC_Email_Template_password_forgotten extends TOC_Email_Template_Module 
{
    /**
     * Email Template Name
     *
     * @access private
     * @var string
     */
    var $template_name = 'password_forgotten';
    
    /**
     * Email Template Keywords
     *
     * @access private
     * @var array
     */
    protected $keywords = array( '%%greeting_text%%',
                                 '%%customer_first_name%%',
                                 '%%customer_last_name%%',
                                 '%%customer_ip_address%%',
                                 '%%customer_password%%',
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
     * Set Data
     * 
     * @access public
     * @param $first_name
     * @param $last_name
     * @param $ip_address
     * @param $password
     * @param $gender
     * @param $customer_email
     * @return void
     */
    function set_data($first_name, $last_name, $ip_address, $password, $gender, $customer_email){
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->ip_address = $ip_address;
        $this->password = $password;
        $this->gender = $gender;

        $this->add_recipient($this->first_name . ' ' . $this->last_name, $customer_email);
    }

    /**
     * Build message
     *
     * @access public
     * @return void
     */
    function build_message() {
        // build the message content
        if ((config('ACCOUNT_GENDER') > -1) && isset($this->gender)) {
            if ($this->gender == 'm') {
                $greeting_text = sprintf(lang('email_addressing_gender_male'), $this->last_name) . "<br /><br />";
            } else {
                $greeting_text = sprintf(lang('email_addressing_gender_female'), $this->first_name) . "<br /><br />";
            }
        } else {
            $greeting_text = sprintf(lang('email_addressing_gender_unknown'), $this->first_name . ' ' . $this->last_name) . "<br /><br />";
        }

        $replaces = array($greeting_text, $this->first_name, $this->last_name, $this->ip_address, $this->password, config('STORE_NAME'), config('STORE_OWNER_EMAIL_ADDRESS'));

        var_dump($this->keywords);
        var_dump($replaces);
        $this->title = str_replace($this->keywords, $replaces, $this->title);
        $this->content = str_replace($this->keywords, $replaces, $this->content);
    }
}

/* End of file email_template_password_forgotten.php */
/* Location: ./system/tomatocart/libraries/email_template/email_template_password_forgotten.php */