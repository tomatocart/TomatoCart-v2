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
 * Index Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-checkout-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Index extends TOC_Controller {

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
     * Default Controller
     *
     * @access public
     * @return void
     */
    public function index($view = NULL)
    {
        //if there is no products in the shopping cart then redirect to shopping cart
        if (!$this->shopping_cart->has_contents()) {
            redirect('shopping_cart');
        } else {
            //check the products stock in the cart
            if (config('STOCK_ALLOW_CHECKOUT') == '-1') {
                $products = $this->shopping_cart->get_products();

                foreach($products as $product) {
                    if ($this->shopping_cart->is_in_stock($product['id']) === FALSE) {
                        redirect('shopping_cart');
                    }
                }
            }
        }

        if ($this->shopping_cart->has_billing_method())
        {
            // load selected payment module
            $this->load->library('payment');
            $payment = $this->payment->load_payment_module($this->shopping_cart->get_billing_method('id'));

            $payment_error = $payment->get_error();

            if (is_array($payment_error) && !empty($payment_error))
            {
                $this->message_stack->add('payment_error_msg', '<strong>' . $payment_error['title'] . '</strong> ' . $payment_error['error']);
            }
        }

        //page title
        $this->set_page_title(lang('checkout'));

        //breadcrumb
        $this->template->set_breadcrumb(lang('breadcrumb_checkout'), site_url('checkout'));

        //load template
        $this->template->build('checkout/checkout', NULL);
    }
}

/* End of file index.php */
/* Location: ./system/tomatocart/controllers/checkout/index.php */