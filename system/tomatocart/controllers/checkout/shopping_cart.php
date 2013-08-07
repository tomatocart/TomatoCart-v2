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
 * Shopping Cart Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-checkout-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Shopping_Cart extends TOC_Controller 
{

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
     * List shopping cart contents
     *
     * @access public
     * @return void
     */
    public function index()
    {
        //page title
        $this->set_page_title(lang('shopping_cart_heading'));
        
        //
        $this->lang->db_load('products');

        //breadcrumb
        $this->template->set_breadcrumb(lang('breadcrumb_checkout_shopping_cart'), site_url('shopping_cart'));
        
        //assign data
        $data['has_contents'] = $this->shopping_cart->has_contents();
        $data['has_stock'] = $this->shopping_cart->has_stock();

        //products
        $contents = $this->shopping_cart->get_contents();
        if (sizeof($contents) > 0)
        {
            $data['products'] = $contents;
        }

        $data['order_totals'] = $this->shopping_cart->get_order_totals();
         
        $this->template->build('checkout/shopping_cart', $data);
    }
}

/* End of file shopping_cart.php */
/* Location: ./system/tomatocart/controllers/checkout/shopping_cart.php */