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
 * TOC Sub Order library - It is an adapter of shopping cart in the front end
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
*/
class TOC_SUB_Order extends TOC_Order
{
	/**
	 * Constructor
	 *
	 * @access public
	 * @param $order_id
	 * @return void
	 */
	public function __construct($order_id = '')
	{
		parent::__construct($order_id);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Check whether there are products in current shoppong cart adapter
	 *
	 * @access public
	 * @return bool
	 */
	public function has_contents()
	{
		return ! empty($this->_contents);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * The total number of physical items in current shoppong cart adapter
	 *
	 * @access public
	 * @return int
	 */
	public function number_of_physical_items()
	{
		$total = 0;
		
		if ($this->has_contents())
		{
			foreach ($this->_contents as $product)
			{
				if( ($product['type'] == PRODUCT_TYPE_SIMPLE) || ($product['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE && $product['gc_data']['type'] == GIFT_CERTIFICATE_TYPE_PHYSICAL) )
				{
					$total += $product['quantity'];
				}
			}
		}
		
		return $total;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * The total number of items in current shoppong cart adapter
	 *
	 * @access public
	 * @return int
	 */
	public function number_of_items()
	{
		$total = 0;
		
		if ($this->has_contents())
		{
			foreach (array_keys($this->_contents) as $products_id)
			{
				$total += $this->get_quantity($products_id);
			}
		}
		
		return $total;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get quantity of a product
	 *
	 * @access public
	 * @param $products_id
	 * @return int
	 */
	public function get_quantity($products_id)
	{
		if (isset($this->_contents[$products_id]))
		{
			return $this->_contents[$products_id]['quantity'];
		}
		
		return 0;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Verify whether the product is existing in current shoppong cart adapter
	 *
	 * @access public
	 * @param $products_id
	 * @return int
	 */
	public function exists($products_id)
	{
		return isset($this->_contents[$products_id]);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the products in current shoppong cart adapter
	 *
	 * @access public
	 * @return array
	 */
	public function get_products()
	{
		return $this->_contents;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the sub total
	 *
	 * @access public
	 * @return int
	 */
	public function get_sub_total()
	{
		return $this->_sub_total;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Check the total
	 *
	 * @access public
	 * @return bool
	 */
	public function is_total_zero()
	{
		return ($this->_total == 0);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the weight
	 *
	 * @access public
	 * @return bool
	 */
	public function get_weight()
	{
		return $this->_weight;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the content type
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_type()
	{
		if ($this->has_contents())
		{
			$products = array_values($this->_contents);
			
			foreach ($products as $product)
			{
				if ( ($product['type'] == PRODUCT_TYPE_SIMPLE) || ($product['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE && $product['gc_data']['type'] == GIFT_CERTIFICATE_TYPE_PHYSICAL) )
				{
					switch ($this->_content_type)
					{
						case 'virtual':
							$this->_content_type = 'mixed';
						
							return $this->_content_type;
							break;
						default:
							$this->_content_type = 'physical';
							break;
					}
				}
				else
				{
					switch ($this->_content_type) 
					{
						case 'physical':
							$this->_content_type = 'mixed';
					
							return $this->_content_type;
							break;
						default:
							$this->_content_type = 'virtual';
							break;
					}
				}
			}
		}
		
		return $this->_content_type;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Verify whether there are any varians products
	 *
	 * @access public
	 * @param $products_id
	 * @return bool
	 */
	public function has_variants($products_id)
	{
		return isset($this->_contents[$products_id]['variants']) && ! empty($this->_contents[$products_id]['variants']);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the variants of a product
	 *
	 * @access public
	 * @param $products_id
	 * @return mixed
	 */
	public function get_variants($products_id)
	{
		if (isset($this->_contents[$products_id]['variants']) && ! empty($this->_contents[$products_id]['variants']))
		{
			return $this->_contents[$products_id]['variants'];
		}
		
		return NULL;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Verify whether the product is in stock
	 *
	 * @access public
	 * @param $products_id
	 * @return bool
	 */
	public function is_in_stock($products_id)
	{
		$this->_ci->load->library('product', get_product_id($products_id));
		
		if ( ($this->_ci->product->get_quantity($products_id) -  $this->_contents[$products_id]['quantity']) < 0 )
		{
			if ($this->_products_in_stock === TRUE)
			{
				$this->_products_in_stock = FALSE;
			}
			
			return FALSE;
		}
		
		return TRUE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Check the stock
	 *
	 * @access public
	 * @return bool
	 */
	public function has_stock()
	{
		return $this->_products_in_stock;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Check the shipping address
	 *
	 * @access public
	 * @return bool
	 */
	public function has_shipping_address()
	{
		return isset($this->_shipping_address) && isset($this->_shipping_address['id']);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the shipping address
	 *
	 * @access public
	 * @param string
	 * @return mixed
	 */
	public function get_shipping_address($key = '')
	{
		if ( ! empty($key))
		{
			return $this->_shipping_address[$key];
		}
		
		return $this->_shipping_address;
	}
	
	/**
	 * Get the billing address
	 *
	 * @access public
	 * @param string
	 * @return mixed
	 */
	public function get_billing_address($key = '')
	{
		if ( ! empty($key))
		{
			return $this->_billing_address[$key];
		}
		
		return $this->_billing_address;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * get the tax address
	 *
	 * @access public
	 * @param string
	 * @return string
	 */
	public function get_taxing_address($key)
	{
		//virtual product
		if ($this->get_content_type() == 'virtual')
		{
			return $this->get_billing_address($key);
		}
		
		return $this->get_shipping_address($key);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * set the shipping method
	 *
	 * @access public
	 * @param array
	 * @param bool
	 * @return void
	 */
	public function set_shipping_method($shipping_method, $calculate_total = TRUE)
	{
		$this->shipping_method = $shipping_method;
		
		if ($calculate_total === TRUE)
		{
			$this->calculate(FALSE);
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * check whether there is any shipping method
	 *
	 * @access public
	 * @return bool
	 */
	public function has_shipping_method()
	{
		return ! empty($this->shipping_method);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * get the shipping method
	 *
	 * @access public
	 * @param string
	 * @return mixed
	 */
	public function get_shipping_method($key = '')
	{
		if ( ! empty($key))
		{
			return $this->shipping_method[$key];
		}
		
		return  $this->shipping_method;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set the billing method
	 *
	 * @access public
	 * @param array
	 * @return void
	 */
	public function set_billing_method($method)
	{
		$this->_billing_method = $method;
		
		$this->calculate();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * get the order totals
	 *
	 * @access public
	 * @return mixed
	 */
	public function get_order_totals()
	{
		return $this->_order_totals;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * update the orders total
	 *
	 * @access public
	 * @return void
	 */
	public function update_order_totals()
	{
		//load model
		$this->_ci->load->model('shopping_cart_model');
		
		return $this->_ci->shopping_cart_model->update_order_totals($this->_order_id, $this->_order_totals);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get tax groups
	 *
	 * @access public
	 * @return array
	 */
	function get_tax_groups()
	{
		return $this->_tax_groups;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get tax amount
	 *
	 * @access public
	 * @param int
	 * @return void
	 */
	function get_tax_amount($amount)
	{
		 $this->_tax += $amount;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Add tax group
	 *
	 * @access public
	 * @param string
	 * @param int
	 * @return void
	 */
	function add_tax_group($group, $amount)
	{
		if (isset($this->_tax_groups[$group]))
		{
			$this->_tax_groups[$group] += $amount;
		}
		else
		{
			$this->_tax_groups[$group] = $amount;
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * add to total
	 *
	 * @access public
	 * @param int
	 * @return void
	 */
	function add_to_total($amount)
	{
		$this->_total += $amount;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * calculate the current shopping cart adapter
	 *
	 * @access public
	 * @param $set_shipping
	 * @return mixed
	 */
	public function calculate($set_shipping = TRUE)
	{
		
		//ignore the store credit, add it later
		
		//load the customer libaries
		$this->_ci->load->library(array('customer', 'weight', 'tax', 'order_total'));
		$this->_ci->customer->set_customer_data($this->get_customers_id());
		
		//put the order currency code in the session for order total modules and shipping modules
		$this->_ci->session->set_userdata('currency', $this->get_currency());
		
		//load model
		$this->_ci->load->model('shopping_cart_model');
		
		//reset the data
		$this->_sub_total = 0;
		$this->_total = 0;
		$this->_weight = 0;
		$this->_tax = 0;
		$this->_tax_groups = array();
		$this->_shipping_boxes_weight = 0;
		$this->_shipping_boxes = 0;
		$this->_shipping_quotes = array();
		$this->_order_totals = array();
		
		if ($this->has_contents())
		{
			//start to calaculate the order total
			foreach ($this->_contents as $products_id => $data)
			{
				//weight calculation
				$products_weight = $this->_ci->weight->convert($data['weight'], $data['weight_class_id'], SHIPPING_WEIGHT_UNIT);
				$this->_weight += $products_weight * $data['quantity'];
				
				//tax rate calculation
				$tax_rate = $this->_ci->tax->get_tax_rate($data['tax_class_id'], $this->get_taxing_address('country_id'), $this->get_taxing_address('zone_id'));
				$tax_description = $this->_ci->tax->get_tax_rate_description($data['tax_class_id'], $this->get_taxing_address('country_id'), $this->get_taxing_address('zone_id'));
				
				//update tax to database
				$this->_contents[$products_id]['tax'] = $tax_rate;
				$this->_ci->shopping_cart_model->update_product_tax($this->_order_id, $this->_contents[$products_id]['orders_products_id'], $tax_rate);
				
				//calculate the product shown price
				$shown_price = $this->_ci->currencies->add_tax_rate_to_price($data['final_price'], $tax_rate, $data['quantity']);
				
				//calculate the tax, sub total and total
				$this->_sub_total += $shown_price;
				$this->_total += $shown_price;
				
				//tax amount
				if (DISPLAY_PRICE_WITH_TAX == '1')
				{
					$tax_amount = $shown_price - ($shown_price / (($tax_rate < 10) ? '1.0' . str_replace('.', '', $tax_rate) : '1.' . str_replace('.', '', $tax_rate)));
				}
				else
				{
					$tax_amount = ($tax_rate / 100) * $shown_price;
					
					//no matter the tax is displayed or not, tax should not be add to total
					$this->_total += $tax_amount;
				}
				$this->_tax += $tax_amount;
				
				//add to tax groups
				if (isset($this->_tax_groups[$tax_description]))
				{
					$this->_tax_groups[$tax_description] += $tax_amount;
				}
				else
				{
					$this->_tax_groups[$tax_description] = $tax_amount;
				}
			}
			
			//shipping weight
			$this->_shipping_boxes_weight = $this->_weight;
			$this->_shipping_boxes = 1;
			
			//weight of typical packaging of small to medium packages greater than weight of the Larger packages(percentage increase)
			if (SHIPPING_BOX_WEIGHT >= ($this->_shipping_boxes_weight * SHIPPING_BOX_PADDING / 100))
			{
				$this->_shipping_boxes_weight = $this->_shipping_boxes_weight + SHIPPING_BOX_WEIGHT;
			}
			else
			{
				$this->_shipping_boxes_weight = $this->_shipping_boxes_weight + ($this->_shipping_boxes_weight * SHIPPING_BOX_PADDING / 100);
			}
			
			//greater than the Maximum Package Weight. Need to split into many boxes
			if ($this->_shipping_boxes_weight > SHIPPING_MAX_WEIGHT)
			{
				$this->_shipping_boxes = ceil($this->_shipping_boxes_weight / SHIPPING_MAX_WEIGHT);
				$this->_shipping_boxes_weight = $this->_shipping_boxes_weight / $this->_shipping_boxes;
			}
			
			//calculate shipping
			if ($set_shipping === TRUE)
			{
				//unset the shipping quotes stored in the session
				$cart_contents = $this->_ci->session->userdata('cart_contents');
				unset($cart_contents['shipping_quotes']);
				
				//check if this order already have a delivery method
				if ( ! empty($this->_deliver_module))
				{
					$this->_ci->load->library('shipping', $this->_deliver_module);
					$this->set_shipping_method($this->_ci->shipping->get_quote(), FALSE);
				}
				//cheaptest shipping
				elseif ( ! $this->has_shipping_method() || ($this->get_shipping_method('is_cheapest') === TRUE) )
				{
					$this->_ci->load->library('shipping');
					$this->set_shipping_method($this->_ci->shipping->get_cheapest_quote(), FALSE);
				}
				//current shipping
				else
				{
					$this->_ci->load->library('shipping', $this->get_shipping_method('id'));
					$this->set_shipping_method($this->_ci->shipping->get_quote(), FALSE);
				}
			}
		}
		
		//order total
		$this->_order_totals = $this->_ci->order_total->get_result();
		
		//ignore the store credit. Need to add it later
		
		//unset session data
		$this->_ci->session->unset_userdata(array('currency', 'customer_data'));
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Update the infomation of current order
	 *
	 * @access public
	 * @param array
	 * @return bool
	 */
	public function update_order_info($data)
	{
		//load model
		$this->_ci->load->model('shopping_cart_model');
		
		//update
		if ($this->_ci->shopping_cart_model->update_order($data) === TRUE)
		{
			$this->_get_summary($this->_order_id);
			$this->calculate();
			$this->update_order_totals();
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Update the infomation of current order
	 *
	 * @access public
	 * @param string
	 * @param bool
	 * @return bool
	 */
	public function update_payment_method($payment_code, $pay_with_store_credit = FALSE)
	{
		$this->_ci->load->model(array('extensions_model', 'shopping_cart_model'));
		
		//get the installed payment modules
		$installed_modules = $this->_ci->extensions_model->get_modules('payment');
		
		//find the payment method title
		$payment_method = '';
		if (count($installed_modules) > 0)
		{
			foreach ($installed_modules as $code => $method)
			{
				if ($code == $payment_code)
				{
					$payment_method = $method;
					
					break;
				}
			}
		}
		
		//ingore the store credit. It is necessary to add it later.
		//$this->setUseStoreCredit($pay_with_store_credit);
		$this->set_billing_method(array('id' => $payment_code, 'title' => $payment_method));
		$this->update_order_totals();
		
		//update payment method in the database
		$payment_method = implode(',', $this->get_cart_billing_methods());
		$payment_module = implode(',', $this->get_cart_billing_modules());
		
		return $this->_ci->shopping_cart_model->update_payment_method($this->_order_id, $payment_method, $payment_module);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get billing method
	 *
	 * @access public
	 * @param string
	 * @return mixed
	 */
	public function get_billing_method($key = '')
	{
		if (empty($key))
		{
			return $this->_billing_method;
		}
		
		return $this->_billing_method[$key];
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Reset billing method
	 *
	 * @access public
	 * @param bool
	 * @return void
	 */
	public function reset_billing_method($calculate = TRUE)
	{
		$this->_billing_method = array();
		
		if ($calculate == TRUE)
		{
			$this->calculte();
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Has billing method
	 *
	 * @access public
	 * @return bool
	 */
	public function has_billing_method()
	{
		return ! empty($this->_billing_method);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get cart billing methods
	 *
	 * @access public
	 * @return array
	 */
	public function get_cart_billing_methods()
	{
		$payment_methods = array();
		
		//ignore the store credit
// 		if ($this->isUseStoreCredit()) 
// 		{
// 			$payment_methods[] = $osC_Language->get('store_credit_title');
// 		}

		if ($this->has_billing_method())
		{
			$payment_methods[] = $this->get_billing_method('title');
		}
		
		return $payment_methods;
	}
}


/* End of file TOC_SUB_order.php */
/* Location: ./system/library/TOC_SUB_order.php */