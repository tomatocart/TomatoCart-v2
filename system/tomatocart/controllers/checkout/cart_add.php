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
 * Cart_Add Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-checkout-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Cart_Add extends TOC_Controller {
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
     * @param $id products id
     * @param $variants variants
     * @return void
     */
    public function index($id, $variants = NULL)
    {
        if (is_numeric($id))
        {
            $product = load_product_library($id);

            //if product is found
            if ($product->is_valid())
            {
                if ($variants === NULL) 
                {
                    $variants = $this->input->post('variants');
                } 
                else 
                {
                    $variants = parse_variants_string($variants);
                }
                
                //quantity
                $quantity = $this->input->post('quantity');

                //add to shopping cart
                $this->shopping_cart->add($id, $variants, $quantity);
            }
        }

        //redirect to shopping cart
        redirect('shopping_cart');
    }
}

/* End of file cart_add.php */
/* Location: ./system/tomatocart/controllers/checkout/cart_add.php */