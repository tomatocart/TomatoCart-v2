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

/**
 * Logoff Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Logoff extends TOC_Controller {
    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Default Function
     *
     * @access public
     * @return void
     */
    public function index()
    {
        //set page title
        $this->template->set_title(lang('sign_out_heading'));
        
        //breadcrumb
        $this->template->set_breadcrumb(lang('breadcrumb_sign_out'), site_url('account/logoff'));

        //reset shopping cart
        $this->shopping_cart->reset();

        //reset customer
        $this->customer->reset();

        //reset wishlist
        $this->wishlist->reset();

        //build view
        $this->template->build('account/logoff');
    }
}

/* End of file logoff.php */
/* Location: ./system/tomatocart/controllers/account/logoff.php */