<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Orders Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Orders extends TOC_Controller 
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
        
        $this->load->model('orders_model');
        $this->load->helper('products');
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * List the orders
     *
     * @access public
     * @return string
     */
    public function list_orders()
    {
        $this->load->library('currencies');
        $this->load->library('address');
        $this->load->helper('date');
        
        $start = $this->input->get_post('start');
        $limit = $this->input->get_post('limit');
        
        $start = empty($start) ? 0 : $start;
        $limit = empty($limit) ? MAX_DISPLAY_SEARCH_RESULTS : $limit;
        
        $params = array('start' => $start, 'limit' => $limit);
        
        $orders_id = $this->input->get_post('orders_id');
        $customers_id = $this->input->get_post('customers_id');
        $status = $this->input->get_post('status');
        
        if (!empty($orders_id) && is_numeric($orders_id))
        {
            $params['orders_id'] = $orders_id;
        }
        
        if (!empty($customers_id) && is_numeric($customers_id))
        {
            $params['customers_id'] = $customers_id;
        }
        
        if (!empty($status) && is_numeric($status))
        {
            $params['status'] = $status;
        }
        
        $orders = $this->orders_model->get_orders($params);
        $records = array();
        if ($orders != NULL)
        {
            foreach($orders as $order)
            {
                $this->load->library('order', $order['orders_id']);
                
                $order_details = $this->get_order_total($this->order);
                
                $records[] = array('orders_id' => $order['orders_id'],
                                   'customers_name' => $order['customers_name'],
                                   'order_total' => strip_tags($order['order_total']),
                                   'date_purchased' => mdate('%Y-%m-%d', human_to_unix($order['date_purchased'])),
                                   'orders_status_name' => $order['orders_status_name'],
                                   'shipping_address' => $this->address->format($this->order->get_delivery(), '<br />'),
                                   'shipping_method' => $this->order->get_deliver_method(),
                                   'billing_address' => $this->address->format($this->order->get_billing(), '<br />'),
                                   'payment_method' => $this->order->get_payment_method(),
                                   'products' => $order_details['products_table'],
                                   'action_class' => empty($order['invoice_number']) ? 'icon-invoice-record' : 'icon-invoice-gray-record',
                                   'totals' => $order_details['order_total']);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->orders_model->get_totals($orders_id, $customers_id, $status),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * Get the order total table
     *
     * @access public
     * @param $order
     * @return array
     */
    public function get_order_total($order)
    {
        $products_table = '<table width="100%">';
        foreach($order->get_products() as $product)
        {
            $product_info = $product['quantity'] . '&nbsp;x&nbsp;' . $product['name'];
            
            if ( $product['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE ) 
            {
                $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . lang('senders_name') . ': ' . $product['senders_name'] . '</i></nobr>';
                
                if ($product['gift_certificates_type'] == GIFT_CERTIFICATE_TYPE_EMAIL) 
                {
                    $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . lang('senders_email') . ': ' . $product['senders_email'] . '</i></nobr>';
                }
                
                $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . lang('recipients_name') . ': ' . $product['recipients_name'] . '</i></nobr>';
                
                if ($product['gift_certificates_type'] == GIFT_CERTIFICATE_TYPE_EMAIL) 
                {
                    $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . lang('recipients_email') . ': ' . $product['recipients_email'] . '</i></nobr>';
                }
                
                $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . lang('messages') . ': ' . $product['messages'] . '</i></nobr>';
            }
          
          if ( isset($product['variants']) && is_array($product['variants']) && ( sizeof($product['variants']) > 0 ) ) 
          {
              foreach ( $product['variants'] as $variants ) 
              {
                  $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . $variants['groups_name'] . ': ' . $variants['values_name'] . '</i></nobr>';
              }
          }
          
          if ( isset($product['customizations']) && !empty($product['customizations']) ) 
          {
              $product_info .= '<p>';
              
              foreach ($product['customizations'] as $key => $customization) 
              {
                  $product_info .= '<div style="float: left">' . $customization['qty'] . ' x ' . '</div>';
                  $product_info .= '<div style="margin-left: 25px">';
                  
                  foreach ($customization['fields'] as $orders_products_customizations_values_id => $field) 
                  {
                      if ($field['customization_type'] == CUSTOMIZATION_FIELD_TYPE_INPUT_TEXT) 
                      {
                          $product_info .= $field['customization_fields_name'] . ': ' . $field['customization_value'] . '<br />';
                      } 
                      else 
                      {
                          $product_info .= $field['customization_fields_name'] . ': <a href="' . '#">' . '</a>' . '<br />';
                      }
                  }
                  
                  $product_info .= '</div>';
              }
              
              $product_info .= '</p>';
          }
          
          $products_table .= '<tr><td>' . $product_info . '</td><td width="60" valign="top" align="right">' . $this->currencies->display_price_with_tax_rate($product['final_price'], $product['tax'], 1, $this->order->get_currency(), $this->order->get_currency_value()) . '</td></tr>';
        }
        $products_table .= '</table>';
        
        $order_total = '<table width="100%">';
        foreach($order->get_totals() as $total)
        {
            $order_total .= '<tr><td align="right">' . $total['title'] . '&nbsp;&nbsp;&nbsp;</td><td width="60" align="right">' . $total['text'] . '</td></tr>';
        }
        $order_total .= '</table>';
        
        return array('products_table' => $products_table, 'order_total' => $order_total);
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * Delete the order
     *
     * @access public
     * @return string
     */
    public function delete_order()
    {
        $this->load->library('order');
        
        $orders_id = $this->input->post('orders_id');
        $restock = $this->input->post('restock');
        
        if ($restock == 'on')
        {
            $is_restock = TRUE;
        }
        else
        {
            $is_restock = FALSE;
        }
        
        if ($this->order->delete($orders_id, $is_restock))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * Batch delete the orders
     *
     * @access public
     * @return string
     */
    public function delete_orders()
    {
        $this->load->library('order');
        
        $error = FALSE;
        
        $keys = $this->input->post('batch');
        $restock = $this->input->post('restock');
        
        if ($restock == 'on')
        {
            $is_restock = TRUE;
        }
        else
        {
            $is_restock = FALSE;
        }
        
        if (!empty($keys))
        {
            $orders_ids = json_decode($keys);
            
            if (is_array($orders_ids))
            {
                foreach($orders_ids as $id)
                {
                    if (!$this->order->delete($id, $is_restock))
                    {
                        $error = TRUE;
                        break;
                    }
                }
            }
            else
            {
                $error = TRUE;
            }
        }
        else
        {
            $error = TRUE;
        }
        
        if ($error === FALSE)
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * Load the summary data
     *
     * @access public
     * @return string
     */
    public function load_summary_data()
    {
        $this->load->library('order', $this->input->post('orders_id'));
        $this->load->library('address');
        $this->load->helper('html_output');
        $this->load->helper('date');
        
        $response = array();
        
        $response['customer'] = '<p>' . $this->address->format($this->order->get_customer(), '<br />') . '</p>' . 
                                '<p>' . 
                                  icon('telephone.png') . $this->order->get_customer('telephone') . '<br />' . icon('write.png') . $this->order->get_customer('email_address') . 
                                '</p>';
                                  
        $response['shippingAddress'] = '<p>' . $this->address->format($this->order->get_delivery(), '<br />').'</p>';
        $response['billingAddress'] = '<p>' . $this->address->format($this->order->get_billing(), '<br />').'</p>';
        $response['paymentMethod'] = '<p>' . $this->order->get_payment_method() . '</p>';
        
        if ($this->order->is_valid_credit_card())
        {
            $response['paymentMethod'] .= '
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>' . lang('credit_card_type') . '</td>
                  <td>' . $this->order->get_credit_card_details('type') . '</td>
                </tr>
                <tr>
                  <td>' . lang('credit_card_owner_name') . '</td>
                  <td>' . $this->order->get_credit_card_details('owner') . '</td>
                </tr>
                <tr>
                  <td>' . lang('credit_card_number') . '</td>
                  <td>' . $this->order->get_credit_card_details('number') . '</td>
                </tr>
                <tr>
                  <td>' . lang('credit_card_expiry_date') . '</td>
                  <td>' . $this->order->get_credit_card_details('expires') . '</td>
                </tr>
              </table>';
        }
        
        $response['status'] = '<p style="margin-left:10px;">' . 
                                $this->order->get_status() . '<br />' . ( $this->order->get_datelast_modified() > $this->order->get_date_created() ? mdate('%Y/%m/%d', human_to_unix($this->order->get_datelast_modified())) : mdate('%Y/%m/%d', human_to_unix($this->order->get_date_created()))) . 
                              '</p>' . 
                              '<p style="margin-left:10px;">' . 
                                lang('number_of_comments') . ' ' . $this->order->get_number_of_comments() . 
                              '</p>';
                                
        $response['customers_comment'] = $this->order->get_customers_comment();
        $response['admin_comment'] = $this->order->get_admin_comment();
        
        $response['total'] = '<p style="margin-left:10px;">' . $this->order->get_order_total().'</p>' . 
                             '<p style="margin-left:10px;">' . 
                               lang('number_of_products') . ' ' . $this->order->get_number_of_products() . '<br />' . 
                               lang('number_of_items') . ' ' . $this->order->get_number_of_items() . 
                             '</p>';
                               
        $this->output->set_output(json_encode($response));
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * Update the admin comment
     *
     * @access public
     * @return string
     */
    public function update_comment()
    {
        $this->load->library('order');
        
        if ($this->order->update_admin_comment($this->input->post('orders_id'), $this->input->post('admin_comment')))
        {
            $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed')); 
        }
        
        $this->output->set_output(json_encode($response));
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * List the order products
     *
     * @access public
     * @return string
     */
    public function list_order_products()
    {
        $this->load->library('tax');
        $this->load->library('currencies');
        $this->load->library('order', $this->input->get_post('orders_id'));
        
        $records = array();
        foreach($this->order->get_products() as $product)
        {
            $product_info = $product['quantity'] . '&nbsp;x&nbsp;' . $product['name'];
            
            if ( isset($product['variants']) && is_array($product['variants']) && ( sizeof($product['variants']) > 0 ) ) 
            {
                foreach ( $product['variants'] as $variants ) 
                {
                    $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . $variants['groups_name'] . ': ' . $variants['values_name'] . '</i></nobr>';
                }
            }
            
            //ignore the product customizations
            
            //ignore the gift certificate
            
            $records[] = array('products' => $product_info, 
                               'return_quantity' => ($product['return_quantity'] > 0) ? $product['return_quantity'] : '',
                               'sku' => $product['sku'],
                               'tax' => $this->tax->display_tax_rate_value($product['tax']), 
                               'price_net' => $this->currencies->format($product['final_price'], $this->order->get_currency(), $this->order->get_currency_value()), 
                               'price_gross' => $this->currencies->display_price_with_tax_rate($product['final_price'], $product['tax'], 1, $this->order->get_currency(), $this->order->get_currency_value()), 
                               'total_net' => $this->currencies->format($product['final_price'] * $product['quantity'], $this->order->get_currency(), $this->order->get_currency_value()), 
                               'total_gross' => $this->currencies->display_price_with_tax_rate($product['final_price'], $product['tax'], $product['quantity'], $this->order->get_currency(), $this->order->get_currency_value()));
        }
        
        foreach ( $this->order->get_totals() as $totals ) 
        {
            $records[] = array('products' => '', 
                               'sku' => '', 
                               'tax' => '', 
                               'price_net' => '', 
                               'price_gross' => $totals['title'], 
                               'total_net' => '', 
                               'total_gross' => $totals['text']);
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * Get the transaction history
     *
     * @access public
     * @return string
     */
    public function get_transaction_history()
    {
        $this->load->library('order', $this->input->get_post('orders_id'));
        $this->load->helper('date');
        
        $records = array();
        foreach($this->order->get_transaction_history() as $history)
        {
            $records[] = array('date' => mdate('%Y/%m/%d',human_to_unix($history['date_added'])), 
                               'status' => ( !empty($history['status']) ) ? $history['status'] : $history['status_id'], 
                               'comments' => nl2br($history['return_value']));
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * List the history of the order status
     *
     * @access public
     * @return string
     */
    public function list_orders_status()
    {
        $this->load->library('order', $this->input->get_post('orders_id'));
        $this->load->helper('date');
        $this->load->helper('html_output');
        
        $records = array();
        foreach($this->order->get_status_history() as $status_history)
        {
            $records[] = array('date_added' => mdate('%Y/%m/%d', human_to_unix($status_history['date_added'])),
                               'orders_status_history_id' => $status_history['orders_status_history_id'], 
                               'status' => $status_history['status'], 
                               'comments' => nl2br($status_history['comment']), 
                               'customer_notified' => icon((($status_history['customer_notified'] === 1) ? 'checkbox_ticked.gif' : 'checkbox_crossed.gif')));
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * Get the order statuses
     *
     * @access public
     * @return string
     */
    public function get_status()
    {
        $statuses = $this->orders_model->get_status();
        $top = $this->input->get_post('top');
        
        $records = array();
        if (!empty($top))
        {
            $records[] = array('status_id' => '', 'status_name' => lang('all_status'));
        }
        
        if ($statuses != NULL)
        {
            foreach($statuses as $status)
            {
                $records[] = array('status_id' => $status['orders_status_id'], 'status_name' => $status['orders_status_name']);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * Update the orders status
     *
     * @access public
     * @return string
     */
    public function update_orders_status()
    {
        $restock_products = $this->input->post('restock_products');
        $notify_customer = $this->input->post('notify_customer');
        $notify_with_comments = $this->input->post('notify_with_comments');
        
        $data = array('status_id' =>  $this->input->post('status'),
                      'comment' =>  $this->input->post('comment'),
                      'restock_products' => ( !empty($restock_products) && ( $restock_products == '1') ? TRUE : FALSE ),
                      'notify_customer' => ( !empty($notify_customer) && ( $notify_customer == 'on') ? TRUE : FALSE ),
                      'append_comment' => ( !empty($notify_with_comments) && ( $notify_with_comments == 'on') ? TRUE : FALSE ));
        
        if ($this->orders_model->update_status($this->input->post('orders_id'), $data))
        {
            $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        } 
        
        $this->output->set_output(json_encode($response));
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * Create the invoice
     *
     * @access public
     * @return string
     */
    public function create_invoice()
    {
        if ($this->orders_model->create_invoice($this->input->post('orders_id')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
	// ------------------------------------------------------------------------
    
    /**
     * list the currencies
     *
     * @access public
     * @return string
     */
    public function list_currencies() 
    {
		$records = array();
    	
    	foreach ($this->currencies->get_data() as $code => $currency) 
    	{
    		$records[] = array( 'id' => $code, 
								'text' => $currency['title'], 
								'symbol_left' => $currency['symbol_left'], 
    							'symbol_right' => $currency['symbol_right'], 
    							'decimal_places' => $currency['decimal_places']);
    	}
    	
    	$this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
	// ------------------------------------------------------------------------
	
    /**
     * get the customer billing addresses or shipping addresses
     *
     * @access public
     * @return string
     */
    public function get_customer_addresses() 
    {
    	//load libraries
    	$this->load->library('order', $this->input->get('orders_id', TRUE));
    	$this->load->library('customers');
    	
    	//get addresses
    	$addresses = $this->customers->get_addressbook_data($this->order->get_customers_id());
    	
    	//build response
    	$records = array(array('id' => '0', 'text' => lang('add_new_address')));
    	if (count($addresses) > 0)
    	{
    		foreach ($addresses as $address)
    		{
    			$records[] = array( 'id' => $address['address_book_id'], 
    								'text' => $address['firstname'] . ' ' . $address['lastname'] . ',' . $address['company'] . ',' . $address['street_address'] . ',' . $address['suburb'] . ',' . $address['city'] . ',' . $address['postcode'] . ',' . $address['state'] . ',' . $address['country_title']);
    		}
    	}
    	
    	$this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
	// ------------------------------------------------------------------------

    /**
     * list countries
     *
     * @access public
     * @return string
     */
    public function list_countries()
    {
    	//load countries model
    	$this->load->model('countries_model');
    	
    	//get the countries
    	$countires = $this->countries_model->get_countries();
    	
    	//build response
    	$records = array();
    	if ($countires !== NULL)
    	{
    		foreach ($countires as $country)
    		{
    			$records[] = array('countries_id' => $country['countries_id'], 'countries_name' => $country['countries_name']);
    		}
    	}
    	
    	$this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * list zones
     *
     * @access public
     * @return string
     */
    public function list_zones()
    {
    	$zones = array();
    	//check the cuntries id in the params
    	$countries_id = $this->input->get('countries_id', TRUE);
    	if ($countries_id > 0)
    	{
    		//load countries model
    		$this->load->model('countries_model');
    		
    		//get the zones
    		$zones = $this->countries_model->get_zones($countries_id);
    	}
    	
    	$this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $zones)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * list payment methods
     *
     * @access public
     * @return string
     */
    public function list_payment_methods()
    {
    	$this->load->helper('directory');
    	
    	$path_array = array('../system/tomatocart/libraries/payment/');
    	 
    	$payment_methods = array();
    	foreach($path_array as $path)
    	{
    		$directories = directory_map($path, 1, TRUE);
    		
    		foreach ($directories as $file)
    		{
    			if ((strpos($file, '.php') !== FALSE) && ($file != 'payment_module.php'))
    			{
    				$module = substr($file, 0, strpos($file, '.php'));
    				
    				//load language xml file
    				$this->lang->xml_load('modules/payment/' . str_replace('payment_', '', $module));
    				
    				//include path file
    				include_once $path . $file;
    				
    				//get class
    				$class = config_item('subclass_prefix') . $module;
    				$class = str_replace('payment', 'Payment', $class);
    				$class = new $class();
    				
    				//only get the installed payment methods
    				if ($class->is_installed())
    				{
    					$payment_methods[] = array('id' => $class->get_code(), 'text' => $class->get_title());
    				}
    			}
    		}
    	}
    	
    	$this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $payment_methods)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * list orders edit products
     *
     * @access public
     * @return string
     */
    public function list_orders_edit_products()
    {
    	//load libraries
    	$this->load->library('tax');
    	$this->load->library('weight');
    	$this->load->library('order', $this->input->get('orders_id', TRUE));
    	
    	//build response
    	$records = array();
    	foreach ($this->order->get_products() as $products_id_string => $product)
    	{
    		//get the product name
    		$product_info = $product['name'];
    		if ( isset($product['variants']) && is_array($product['variants']) && (sizeof($product['variants']) > 0) )
    		{
    			foreach ($product['variants'] as $variants)
    			{
    				$product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . $variants['groups_name'] . ': ' . $variants['values_name'] . '</i></nobr>';
    			}
    		}
    		
    		//load product library
    		$this->load->library('product', $product['id']);
    		
    		$records[] = array( 'orders_products_id' => $product['orders_products_id'], 
								'products_id' => $product['id'], 
    							'products_type' => $product['type'], 
    							'products' => $product_info, 
    							'quantity' => ($product['quantity'] > 0) ? $product['quantity'] : '', 
    							'qty_in_stock' => $this->product->get_quantity($products_id_string), 
    							'sku' => $product['sku'], 
    							'tax' => $this->tax->display_tax_rate_value($product['tax']), 
    							'price_net' => round($product['final_price'] * $this->order->get_currency_value(), 2), 
    							'price_gross' => $this->currencies->display_price_with_tax_rate($product['final_price'], $product['tax'], 1, $this->order->get_currency(), $this->order->get_currency_value()), 
    							'total_net' => $this->currencies->format($product['final_price'] * $product['quantity'], $this->order->get_currency(), $this->order->get_currency_value()), 
    							'total_gross' => $this->currencies->display_price_with_tax_rate($product['final_price'], $product['tax'], $product['quantity'], $this->order->get_currency(), $this->order->get_currency_value()),
    							'action' => array('class' => 'icon-delete-record', 'qtip' => ''));
    	}
    	
    	//get order totals
    	$order_totals = '<table cellspacing="5" cellpadding="5" width="300" border="0">';
    	foreach ($this->order->get_totals() as $total)
    	{
    		$order_totals .= '<tr><td align="right">' . $total['title'] . '&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="60">' . $total['text'] . '</td></tr>';
    	}
    	$order_totals .= '</table>';
    	
    	//get shipping method
    	$shipping_method = $this->order->get_deliver_method();
    	
    	$this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records, 'totals' => $order_totals, 'shipping_method' => $shipping_method)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * load order
     *
     * @access public
     * @return string
     */
    public function load_order()
    {
    	//load libraries
    	$this->load->library('order', $this->input->post('orders_id', TRUE));
    	
    	//get the customer
    	$customer = $this->order->get_customer();
    	
    	$data = array('customers_name' => str_replace(' ', '&nbsp;', $customer['name']), 
					  'currency' => $this->order->get_currency(), 
    				  'email_address' => $customer['email_address'], 
    				  'payment_method' => $this->order->get_payment_module(), 
    				  'has_payment_method' => $this->order->has_payment_method(), 
    				  'billing_address' => $this->get_address('billing'), 
    				  'shipping_address' => $this->get_address('delivery'));
    	
    	$this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * chnage the currency for the order
     *
     * @access public
     * @return string
     */
    public function change_currency()
    {
    	//load libraries
    	$this->load->library('tax');
    	$this->load->library('weight');
    	
    	//load model
    	$this->load->model('order_model');
    	
    	//default currency value
    	$currency_value = 1;
    	
    	//get the currency value with currency code
    	$currency_code = $this->input->post('currency', TRUE);
    	foreach ($this->currencies->get_data() as $code => $currency)
    	{
    		//find the currency
    		if ($code == $currency_code)
    		{
    			$currency_value = $currency['value'];
    			break;
    		}
    	}
    	
    	//update the currency of the order
    	$orders_id = $this->input->post('orders_id', TRUE);
    	if ($this->order_model->update_currency($orders_id, $currency_code, $currency_value))
    	{
    		
    		/**
	    	 * load shopping cart adapter libray which is extended from order library.
	    	 * The last boolean paramter is used to tell the system to load a extended library.
	    	 * You could find the details under system/core/TOC_Loader.php. We overrided the ci library and _ci_load_class.
	    	 * So, let the libray support the local sub-libraries extended from core tomatocart libraries.
	    	 * Support the core tomatocart library extend from another library.
	    	 * If the tomatocart library was extended from ci library, they will also work as expected.
	    	 */
    		$this->load->library('order', $orders_id, 'shopping_cart', TRUE);
    		
    		//recalculate the order
    		$this->shopping_cart->calculate();
    		
    		//update the order totals
    		if ($this->shopping_cart->update_order_totals())
    		{
    			$response = array('success' => TRUE , 'feedback' => lang('ms_success_action_performed'));
    		}
    		else
    		{
    			$response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    		}
    	}
    	else
    	{
    		$response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    	}
    	
    	$this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save billing / shipping address
     *
     * @access protected
     * @return string
     */
    public function save_address()
    {
    	//get all the post data with xss clean
    	$data = $this->input->post(NULL, TRUE);
    	
    	//get post data with xss clean
    	$data = array(
			'orders_id' => $this->input->post('orders_id', TRUE), 
			'billing_name' => $this->input->post('billing_name', TRUE), 
			'billing_company' => $this->input->post('billing_company', TRUE),
			'billing_street_address' => $this->input->post('billing_street_address', TRUE),
			'billing_suburb' => $this->input->post('billing_suburb', TRUE),
			'billing_city' => $this->input->post('billing_city', TRUE),
			'billing_postcode' => $this->input->post('billing_postcode', TRUE),
			'billing_state' => $this->input->post('billing_state', TRUE),
			'billing_zone_id' => $this->input->post('billing_zone_id', TRUE),
			'billing_state_code' => $this->input->post('billing_state_code', TRUE),
			'billing_country_id' => $this->input->post('billing_countries_id', TRUE),
			'billing_country' => $this->input->post('billing_countries', TRUE),
			'delivery_name' => $this->input->post('shipping_name', TRUE),
			'delivery_company' => $this->input->post('shipping_company', TRUE),
			'delivery_street_address' => $this->input->post('shipping_street_address', TRUE),
			'delivery_suburb' => $this->input->post('shipping_suburb', TRUE),
			'delivery_city' => $this->input->post('shipping_city', TRUE),
			'delivery_postcode' => $this->input->post('shipping_postcode', TRUE),
			'delivery_state' => $this->input->post('shipping_state', TRUE),
			'delivery_zone_id' => $this->input->post('shipping_zone_id', TRUE),
			'delivery_state_code' => $this->input->post('shipping_state_code', TRUE),
			'delivery_country_id' => $this->input->post('shipping_countries_id', TRUE),
			'delivery_country' => $this->input->post('shipping_countries', TRUE)
		);
    	
    	/**
    	 * load shopping cart adapter libray which is extended from order library.
    	 * The last boolean paramter is used to tell the system to load a extended library.
    	 * You could find the details under system/core/TOC_Loader.php. We overrided the ci library and _ci_load_class.
    	 * So, let the libray support the local sub-libraries extended from core tomatocart libraries.
    	 * Support the core tomatocart library extend from another library.
    	 * If the tomatocart library was extended from ci library, they will also work as expected.
    	 */
    	$this->load->library('order', $data['orders_id'], 'shopping_cart', TRUE);
    	
    	if ($this->shopping_cart->update_order_info($data) === TRUE)
    	{
    		$response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    	}
    	else
    	{
    		$response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    	}
    	
    	$this->output->set_output(json_encode($response));
    }


    // ------------------------------------------------------------------------
    
    /**
     * Update payment method
     *
     * @access public
     * @return string
     */
    public function update_payment_method()
    {
    	/**
    	 * load shopping cart adapter libray which is extended from order library.
    	 * The last boolean paramter is used to tell the system to load a extended library.
    	 * You could find the details under system/core/TOC_Loader.php. We overrided the ci library and _ci_load_class.
    	 * So, let the libray support the local sub-libraries extended from core tomatocart libraries.
    	 * Support the core tomatocart library extend from another library.
    	 * If the tomatocart library was extended from ci library, they will also work as expected.
    	 */
    	$this->load->library('order', $this->input->post('orders_id', TRUE), 'shopping_cart', TRUE);
    	
    	//ignore the store credit. It is necessary to be added later
    	if ($this->shopping_cart->update_payment_method($this->input->post('payment_method', TRUE)))
    	{
    		$response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    	}
    	else
    	{
    		$response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    	}
    	
    	$this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List shipping methods
     *
     * @access public
     * @return string
     */
    public function list_shipping_methods()
    {
    	/**
    	 * load shopping cart adapter libray which is extended from order library.
    	 * The last boolean paramter is used to tell the system to load a extended library.
    	 * You could find the details under system/core/TOC_Loader.php. We overrided the ci library and _ci_load_class.
    	 * So, let the libray support the local sub-libraries extended from core tomatocart libraries.
    	 * Support the core tomatocart library extend from another library.
    	 * If the tomatocart library was extended from ci library, they will also work as expected.
    	 */
    	$this->load->library('order', $this->input->get('orders_id', TRUE), 'shopping_cart', TRUE);
    	
    	//load libraries
    	$this->load->library('shipping');
    	
    	//calculate
    	$this->shopping_cart->calculate();
    	
    	//unset the shipping quotes stored in the session
    	$cart_contents = $this->session->userdata('cart_contents');
    	unset($cart_contents['shipping_quotes']);
    	
    	//use the cheapest shpping quote
    	if ($this->shopping_cart->has_shipping_method() === FALSE)
    	{
    		$this->shopping_cart->set_shipping_method($this->shipping->get_cheapest_quote());
    	}
    	
    	//build the response
    	$records = array();
    	
    	//get all of the shipping quotes
    	$shipping_quotes = $this->shipping->get_quotes();
    	
    	if (count($shipping_quotes) > 0)
    	{
    		foreach ($shipping_quotes as $quote)
    		{
    			$module = $quote['module'];
    			
    			if (isset($quote['icon']) && ! empty($quote['icon']))
    			{
    				$module .= '&nbsp;<img src="' . $quote['icon'] . '" />';
    			}
    			
    			//add the quote title row
    			$records[] = array(
					'title' => '<b>' . $module . '</b>',
					'code' => $quote['id'],
					'price' => '',
					'action' => array()
				);
    			
    			//add the quote error row
    			if (isset($quote['error']))
    			{
    				$records[] = array(
						'title' => '&nbsp;&nbsp;--&nbsp;<i>' . $quote['error'] . '</i>',
						'code' => $quote['id'] . '_error',
						'price' => '',
						'action' => array()
    				);
    			}
    			else
    			{
    				//add the quote methods
    				foreach ($quote['methods'] as $method)
    				{
    					$records[] = array(
							'title' => '&nbsp;&nbsp;--&nbsp;<i>' . $method['title'] . '</i>',
							'code' => $quote['id'] . '_' . $method['id'],
							'price' => $this->currencies->display_price($method['cost'], $quote['tax_class_id'], 1, $this->shopping_cart->get_currency()),
    						'action' => array('class' => 'icon-add-record', 'qtip' => '')		
						);
    				}
    			}
    		}
    	}
    	
    	$this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save shipping method
     *
     * @access public
     * @return string
     */
    public function save_shipping_method()
    {
    	/**
    	 * load shopping cart adapter libray which is extended from order library.
    	 * The last boolean paramter is used to tell the system to load a extended library.
    	 * You could find the details under system/core/TOC_Loader.php. We overrided the ci library and _ci_load_class.
    	 * So, let the libray support the local sub-libraries extended from core tomatocart libraries.
    	 * Support the core tomatocart library extend from another library.
    	 * If the tomatocart library was extended from ci library, they will also work as expected.
    	 */
    	$this->load->library('order', $this->input->post('orders_id', TRUE), 'shopping_cart', TRUE);
    	
    	//load libraries
    	$this->load->library('shipping');
    	 
    	//load models
    	$this->load->model('extensions_model');
    	
    	//save shipping method
    	if ($this->shipping->has_quotes())
    	{
    		$shipping_code = $this->input->post('code', TRUE);
    		
    		//the shipping code should be like flat_flat.
    		if ( !empty($shipping_code) && strpos($shipping_code, '_'))
    		{
    			//load the shipping module
    			list($module, $method) = explode('_', $shipping_code);
    			$module = 'shipping_' . $module;
    			$this->load->library('shipping/' . $module);
    			
    			//check whether it is installed and enabled
    			if ($this->$module->is_installed() && $this->$module->is_enabled())
    			{
    				//get the shipping quote
    				$quote = $this->shipping->get_quote($shipping_code);
    				
    				//check error
    				if (isset($quote['error']))
    				{
    					$this->shopping_cart->reset_shipping_method();
    				}
    				//update the shipping method and save it to recalculate the order totals
    				else
    				{
    					$this->shopping_cart->set_shipping_method($quote);
    				}
    			}
    			else
    			{
    				$this->shopping_cart->reset_shipping_method();
    			}
    			
    		}
    	}
    	
    	//update order totals in the database
    	$this->shopping_cart->update_order_totals();
    	
    	$this->output->set_output(json_encode(array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'))));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Update sku of the order product
     *
     * @access public
     * @return string
     */
    public function update_sku()
    {
    	if ( $this->orders_model->update_product_sku($this->input->post('orders_products_id', TRUE), $this->input->post('products_sku', TRUE)) )
    	{
    		$response = array('success' => TRUE , 'feedback' => lang('ms_success_action_performed'));
    	}
    	else
    	{
    		$response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    	}
    	
    	$this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Update quantity of the order product
     *
     * @access public
     * @return string
     */
    public function update_quantity()
    {
    	/**
    	 * load shopping cart adapter libray which is extended from order library.
    	 * The last boolean paramter is used to tell the system to load a extended library.
    	 * You could find the details under system/core/TOC_Loader.php. We overrided the ci library and _ci_load_class.
    	 * So, let the libray support the local sub-libraries extended from core tomatocart libraries.
    	 * Support the core tomatocart library extend from another library.
    	 * If the tomatocart library was extended from ci library, they will also work as expected.
    	 */
    	$this->load->library('order', $this->input->post('orders_id', TRUE), 'shopping_cart', TRUE);
    	
    	if ( $this->shopping_cart->update_product_quantity($this->input->post('orders_products_id', TRUE), $this->input->post('quantity', TRUE)) )
    	{
    		$response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    	}
    	else
    	{
    		$response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    	}
    	
    	$this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Update price of the order product
     *
     * @access public
     * @return string
     */
    public function update_price()
    {
    	/**
    	 * load shopping cart adapter libray which is extended from order library.
    	 * The last boolean paramter is used to tell the system to load a extended library.
    	 * You could find the details under system/core/TOC_Loader.php. We overrided the ci library and _ci_load_class.
    	 * So, let the libray support the local sub-libraries extended from core tomatocart libraries.
    	 * Support the core tomatocart library extend from another library.
    	 * If the tomatocart library was extended from ci library, they will also work as expected.
    	 */
    	$this->load->library('order', $this->input->post('orders_id', TRUE), 'shopping_cart', TRUE);
    	
    	if ($this->shopping_cart->update_product_price( $this->input->post('orders_products_id', TRUE), $this->input->post('price'), TRUE) )
    	{
    		$response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    	}
    	else
    	{
    		$response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    	}
    	
    	$this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete order product
     *
     * @access public
     * @return string
     */
    public function delete_product()
    {
    	/**
    	 * load shopping cart adapter libray which is extended from order library.
    	 * The last boolean paramter is used to tell the system to load a extended library.
    	 * You could find the details under system/core/TOC_Loader.php. We overrided the ci library and _ci_load_class.
    	 * So, let the libray support the local sub-libraries extended from core tomatocart libraries.
    	 * Support the core tomatocart library extend from another library.
    	 * If the tomatocart library was extended from ci library, they will also work as expected.
    	 */
    	$this->load->library('order', $this->input->post('orders_id', TRUE), 'shopping_cart', TRUE);
    	
    	$this->load->library('product', $this->input->post('products_id', TRUE));
    	
    	if ( $this->shopping_cart->delete_product($this->input->post('orders_products_id', TRUE)) )
    	{
    		if (count($this->shopping_cart->get_products()) === 0)
    		{
    			//currently, ignore the delete the coupon
//     			$this->shopping_cart->delete_coupon();
				$this->shopping_cart->update_order_totals();
    		}
    		
    		$response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    	}
    	else
    	{
    		$response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    	}
    	
    	$this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the products available to be choosed
     *
     * @access public
     * @return string
     */
    public function list_choose_products()
    {
    	$this->load->model('orders_model');
    	$this->load->library('order', $this->input->get('orders_id', TRUE));
    	$this->load->library('tax');
    	
    	//get the request params
    	$start = $this->input->get('start', TRUE);
    	$limit = $this->input->get('limit', TRUE);
    	
    	$start = empty($start) ? 0 : $start;
    	$limit = empty($limit) ? MAX_DISPLAY_SEARCH_RESULTS : $limit;
    	
    	//get the products available to be choosed
    	$result = $this->orders_model->get_choose_products($start, $limit);
    	
    	//set the currency
    	$this->session->set_userdata('currency', $this->order->get_currency());
    	
    	//build the response
    	$records = array();
    	if (count($result['products']) > 0)
    	{
    		foreach ($result['products'] as $product)
    		{
    			$object_name = 'product_' . $product['products_id'];
    			$this->load->library('product', $product['products_id'], $object_name);
    			
    			if ( ! $this->$object_name->has_variants())
    			{
    				//currently, ignore the gift certificate

    				$records[] =  array(
						'products_id' => $product['products_id'],
	    				'products_name' => $this->$object_name->get_title(),
						'products_type' => $this->$object_name->get_product_type(),
						'products_sku' => $this->$object_name->get_sku(),
						'products_price' => $this->$object_name->get_price_formated(),
						'products_quantity' => $this->$object_name->get_quantity(),
						'new_qty' => $product['products_moq'],
						'has_variants' => FALSE
					);
    			}
    			//variants products
    			else
    			{
    				$records[] = array(
						'products_id' => $product['products_id'],
	    				'products_name' => $this->$object_name->get_title(),
						'products_type' => NULL,
						'products_sku' => NULL,
						'products_price' => NULL,
						'products_quantity' => NULL,
						'new_qty' => NULL,
						'has_variants' => TRUE
					);
    				
    				//add the variants products
    				foreach ($this->$object_name->get_variants() as $product_id_string => $variant)
    				{
    					$variants = '';
    					foreach ($variant['groups_name'] as $groups_name => $values_name)
    					{
    						$variants .= '&nbsp;&nbsp;&nbsp;<i>' . $groups_name . ' : ' . $values_name . '</i><br />';
    					}
    					
    					$records[] = array(
							'products_id' => $product_id_string,
							'products_name' => $variants,
							'products_type' => $this->$object_name->get_product_type(),
							'products_sku' => $this->$object_name->get_sku(parse_variants_from_id_string($product_id_string)),
							'products_price' => $this->currencies->format($this->$object_name->get_price(parse_variants_from_id_string($product_id_string), $this->order->get_currency())),
							'products_quantity' => $variant['quantity'],
							'new_qty' => $product['products_moq'],
							'has_variants' => FALSE			
						);
    				}
    			}
    		}
    	}
    	
    	//unset currency in the session data
    	$this->session->unset_userdata('currency');
    	
    	$response = array(EXT_JSON_READER_TOTAL => $result['total'], EXT_JSON_READER_ROOT => $records);
    	
    	$this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Add order product
     *
     * @access public
     * @return string
     */
    public function add_product()
    {
    	/**
    	 * load shopping cart adapter libray which is extended from order library.
    	 * The last boolean paramter is used to tell the system to load a extended library.
    	 * You could find the details under system/core/TOC_Loader.php. We overrided the ci library and _ci_load_class.
    	 * So, let the libray support the local sub-libraries extended from core tomatocart libraries.
    	 * Support the core tomatocart library extend from another library.
    	 * If the tomatocart library was extended from ci library, they will also work as expected.
    	 */
    	$this->load->library('order', $this->input->post('orders_id', TRUE), 'shopping_cart', TRUE);
    	
    	$this->load->library('tax');
    	
    	//currently, ignore the gift certificate
    	
    	if ( $this->shopping_cart->add_product( $this->input->post('products_id', TRUE), $this->input->post('new_qty', TRUE) ) )
    	{
    		$response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    	}
    	else
    	{
    		$response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    	}
    	
    	$this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * load order
     *
     * @access protected
     * @return string
     */
    protected function get_address($type)
    {
    	$method = "get_{$type}";
    	
    	$address = str_replace(',', ' ', $this->order->$method('name')) . ',' .
				   $this->order->$method('company') . ',' .
				   $this->order->$method('street_address') . ',' .
				   $this->order->$method('suburb') . ',' .
				   $this->order->$method('city') . ',' .
				   $this->order->$method('postcode') . ',' .
				   $this->order->$method('state') . ',' .
				   $this->order->$method('country_title');
    	
    	return $address;
    }

}


/* End of file orders.php */
/* Location: ./system/controllers/orders.php */