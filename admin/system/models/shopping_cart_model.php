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
 * Shopping Cart Adapter Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-model
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com
 */
class Shopping_Cart_Model extends CI_Model
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
	 * Update tax of the orders product
	 *
	 * @access public
	 * @param int
	 * @param int
	 * @param float
	 * @return bool
	 */
	public function update_product_tax($orders_id, $orders_products_id, $tax)
	{
		$this->db->update('orders_products', array('products_tax' => $tax), array('orders_id' => $orders_id, 'orders_products_id' => $orders_products_id));
		
		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Update the order totals
	 *
	 * @access public
	 * @param int
	 * @param array
	 * @return bool
	 */
	
	public function update_order_totals($orders_id, $modules = array())
	{
		//check the order total modules
		if (count($modules) < 1)
		{
			return FALSE;
		}
		
		//start transaction
		$error = FALSE;
		
		$this->db->trans_begin();
			
		//delete the original order totals
		$this->db->delete('orders_total', array('orders_id' => $orders_id));
			
		//check transaction status
		if ($this->db->trans_status() === FALSE)
		{
			$error = TRUE;
		}
		//insert the new order totals
		else
		{
			foreach ($modules as $module)
			{
				$this->db->insert('orders_total', array('orders_id' => $orders_id, 
														'title' => $module['title'], 
														'text' => $module['text'], 
														'value' => $module['value'], 
														'class' => $module['code'], 
														'sort_order' => $module['sort_order']));
				
				//check transaction status
				if ($this->db->trans_status() === FALSE)
				{
					$error = TRUE;
					
					break;
				}
			}
		}
	
		if ($error === FALSE)
		{
			//commit
			$this->db->trans_commit();
			
			return TRUE;
		}
		
		//rollback
		$this->db->trans_rollback();
		
		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * update order
	 *
	 * @access public
	 * @param array
	 * @return bool
	 */
	public function update_order($data)
	{
		//get the billing country information
		$billing_country_info = $this->get_country_info($data['billing_country_id']);
		
		if ($billing_country_info !== NULL)
		{
			$data['billing_country_iso2'] = $billing_country_info['countries_iso_code_2'];
			$data['billing_country_iso3'] = $billing_country_info['countries_iso_code_3'];
			$data['billing_address_format'] = $billing_country_info['address_format'];
		}
		
		//get the shipping country information
		$shipping_country_info = $this->get_country_info($data['delivery_country_id']);
		
		if ($shipping_country_info !== NULL)
		{
			$data['delivery_country_iso2'] = $shipping_country_info['countries_iso_code_2'];
			$data['delivery_country_iso3'] = $shipping_country_info['countries_iso_code_3'];
			$data['delivery_address_format'] = $shipping_country_info['address_format'];
		}
		
		//update the order
		return $this->db->update('orders', $data, array('orders_id' => $data['orders_id']));
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Update payment method
	 *
	 * @access public
	 * @param int
	 * @param string
	 * @param string
	 * @return bool
	 */
	public function update_payment_method($orders_id, $payment_method, $payment_module)
	{
		return $this->db->update('orders', array('payment_method' => $payment_method, 'payment_module' => $payment_module), array('orders_id' => $orders_id));
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Update quantity of order product
	 *
	 * @access public
	 * @param int
	 * @param int
	 * @param int
	 * @return bool
	 */
	public function update_product_quantity($orders_products_id, $quantity)
	{
		return $this->db->update('orders_products', array('products_quantity' => $quantity), array('orders_products_id' => $orders_products_id));
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the country information
	 *
	 * @access prototected
	 * @param int
	 * @return mixed
	 */
	protected function get_country_info($country_id)
	{
		//get the billing country information
		$result = $this->db
		->select('countries_name, countries_iso_code_2, countries_iso_code_3, address_format')
		->from('countries')
		->where('countries_id', $country_id)
		->get();
		
		return $result->row_array();
	}
}

/* End of file shopping_cart_adpter_model.php */
/* Location: ./system/models/shopping_cart_adpter_model.php */