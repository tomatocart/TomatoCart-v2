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
 * Customer Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-model
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com
 */

class Customer_Model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the customer data
	 *
	 * @access public
	 * @param int
	 * @return mixed
	 */
	public function get_customer_data($customer_id)
	{
		if (is_numeric($customer_id) && ($customer_id > 0))
		{
			$result = $this->db
			->select('c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_email_address, c.customers_default_address_id, c.customers_groups_id, c.customers_credits, cg.customers_groups_discount, ab.entry_country_id, ab.entry_zone_id')
			->from('customers c')
			->join('customers_groups cg', 'c.customers_groups_id = cg.customers_groups_id', 'left')
			->join('address_book ab', 'c.customers_default_address_id = ab.address_book_id', 'left')
			->where(array('c.customers_id' => $customer_id, 'ab.customers_id' => $customer_id))
			->get();
			
			return $result->row_array();
		}
		
		return NULL;
	}
}

/* End of file customer_model.php */
/* Location: ./system/models/customer_model.php */