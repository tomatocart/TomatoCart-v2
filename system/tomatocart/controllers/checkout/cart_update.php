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
 * Cart_Update Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-checkout-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Cart_Update extends TOC_Controller {
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
	 * @param string
	 * @return void
	 */
	public function index()
	{
		$products = $this->input->post('products');
		
		//if products is an array
		if (($products !== FALSE) && is_array($products)) 
		{
			foreach ($products as $products_id => $quantity) 
			{
			    //get variants from product id string
			    $variants = parse_variants_from_id_string($products_id);
			    //update shopping cart
				$this->shopping_cart->add($products_id, $variants, $quantity, 'update');
			}
		}

		redirect('checkout/shopping_cart');
	}
}

/* End of file cart_update.php */
/* Location: ./system/tomatocart/controllers/checkout/cart_update.php */