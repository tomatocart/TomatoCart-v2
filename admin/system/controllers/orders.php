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
        
        $response['total'] = '<p style="margin-left:10px;">' . $this->order->get_total().'</p>' . 
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
}


/* End of file orders.php */
/* Location: ./system/controllers/orders.php */