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
class toC_Email_Template_share_wishlist extends TOC_Email_Template_Module
{
    /**
     * Email Template Name
     *
     * @access private
     * @var string
     */
    var $template_name = 'share_wishlist';

    /**
     * Email Template Keywords
     *
     * @access private
     * @var array
     */
    var $keywords = array( '%%from_name%%',
                           '%%from_email_address%%',
                           '%%to_email_address%%',
                           '%%message%%',
                           '%%wishlist_url%%',
                           '%%store_name%%',
                           '%%store_address%%',
                           '%%store_owner_email_address%%');

    /**
     * Constructor
     *
     * @access public
     */
    function __construct()
    {
        parent::__construct($this->template_name);
    }

    /**
     * Set Data
     *
     * @access public
     * @return void
     */
    function set_data($from_name, $from_email_address, $to_email_address, $message, $wishlist_url)
    {
        $this->from_name = $from_name;
        $this->from_email_address = $from_email_address;
        $this->to_email_address = $to_email_address;
        $this->message = $message;
        $this->wishlist_url = $wishlist_url;

        $emails = explode(',', $this->to_email_address);
        foreach ($emails as $email) 
        {
            if (validate_email_address($email)) 
            {
                $this->add_recipient('', $email);
            }
        }
    }

    /**
     * Build message
     *
     * @access public
     * @return void
     */
    function build_message() 
    {
        $replaces = array($this->from_name, $this->from_email_address, $this->to_email_address, $this->message, $this->wishlist_url, config('STORE_NAME'), config('HTTP_SERVER') . config('DIR_WS_CATALOG'), config('STORE_OWNER_EMAIL_ADDRESS'));

        $this->title = str_replace($this->keywords, $replaces, $this->title);
        $this->content = str_replace($this->keywords, $replaces, $this->content);
        
        echo $this->content;
    }
}
?>