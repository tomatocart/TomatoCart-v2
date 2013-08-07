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
 * Orders Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Orders_Model extends CI_Model 
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
    
// ------------------------------------------------------------------------
    
    /**
     * Get the orders
     *
     * @access public
     * @param $params
     * @return mixed
     */
    public function get_orders($params)
    {
        $this->db
        ->select('o.invoice_number, o.tracking_no, o.orders_id, o.customers_ip_address, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total')
        ->from('orders o')
        ->join('orders_total ot', 'o.orders_id = ot.orders_id')
        ->join('orders_status s', 'o.orders_status = s.orders_status_id')
        ->where(array('ot.class' => 'total', 's.language_id' => lang_id()));
        
        if (isset($params['orders_id']))
        {
            $this->db->where('o.orders_id', $params['orders_id']);
        }
        
        if (isset($params['customers_id']))
        {
            $this->db->where('o.customers_id', $params['customers_id']);
        }
        
        if (isset($params['status']))
        {
            $this->db->where('s.orders_status_id', $params['status']);
        }
        
        $result = $this->db
        ->order_by('o.date_purchased desc, o.last_modified desc, o.orders_id desc')
        ->limit($params['limit'], $params['start'])
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the order statuses
     *
     * @access public
     * @return mixed
     */
    public function get_status()
    {
        $result = $this->db
        ->select('orders_status_id, orders_status_name')
        ->from('orders_status')
        ->where('language_id', lang_id())
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------

    /**
     * Update the order status
     *
     * @access public
     * @return boolean
     */
    public function update_status($id, $data)
    {
        $this->load->library('orders_status');
        $this->load->library('order');
        $this->load->library('product');
        $this->load->helper('date');
        
        $error = FALSE;
        
        $this->db->trans_begin();
        
        $orders_status = $this->orders_status->get_data($data['status_id']);
        
        if (isset($orders_status['downloads_flag']) && $orders_status['downloads_flag'] == 1) 
        {
            //ignore the active downloadable products
        }
        
        if (isset($orders_status['gift_certificates_flag']) && $orders_status['gift_certificates_flag'] == 1) 
        {
            //ignore the active gift certificates
        }
        
        if (($data['status_id'] == ORDERS_STATUS_CANCELLED) && ($data['restock_products'] == TRUE)) 
        {
            $result = $this->db
            ->select('orders_products_id, products_id, products_type, products_quantity')
            ->from('orders_products')
            ->where('orders_id', $id)
            ->get();
            
            if ($result->num_rows() > 0)
            {
                foreach($result->result_array() as $product)
                {
                    $result = $this->product->restock($id, $product['orders_products_id'], $product['products_id'], $product['products_quantity']);
                    
                    if ($result == FALSE)
                    {
                        $error = TRUE;
                        break;
                    }
                }
            }
            
            $result->free_result();
        }
          
        if ($error === FALSE)
        {
            $this->db->update('orders', array('orders_status' => $data['status_id'], 'last_modified' => date('Y-m-d h:i:s')), array('orders_id' => $id));
            
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
            }
        }
        
        if ($error === FALSE)
        {
            $this->db->insert('orders_status_history', array(
              'orders_id' => $id,
              'orders_status_id' => $data['status_id'],
              'date_added' => date('Y-m-d h:i:s'),
              'customer_notified' => $data['notify_customer'] === true ? '1' : '0',
              'comments' => $data['comment']
            ));
            
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
            }
        }
        
        if (($error === FALSE) && ($data['notify_customer'] === TRUE))
        {
            $result = $this->db
            ->select('o.customers_name, o.customers_email_address, s.orders_status_name, o.date_purchased')
            ->from('orders o')
            ->join('orders_status s', 'o.orders_status = s.orders_status_id')
            ->where(array('s.language_id' => lang_id(), 'o.orders_id' => $id))
            ->get();
            
            $order = $result->row_array();
            
            //ignore send emails
        }
        
        if ($error === FALSE)
        {
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Create the invoice
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function create_invoice($id)
    {
        $result = $this->db
        ->select_max('invoice_number')
        ->from('orders')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            $max_invoice = $result->row_array();
            
            $invoice_number = $max_invoice['invoice_number'] + 1;
            $invoice_number = ($invoice_number > INVOICE_START_NUMBER) ? $invoice_number : INVOICE_START_NUMBER;
            
            $this->db->update('orders', array('invoice_number' => $invoice_number, 'invoice_date' => date('Y-m-d H:i:s')), array('orders_id' => $id));
            
            if ($this->db->affected_rows() > 0)
            {
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the total number of the orders
     *
     * @access public
     * @param $orders_id
     * @param $customers_id
     * @param $status
     * @return int
     */
    public function get_totals($orders_id, $customers_id, $status)
    {
        $this->db
        ->select('o.orders_id')
        ->from('orders o')
        ->join('orders_total ot', 'o.orders_id = ot.orders_id')
        ->join('orders_status s', 'o.orders_status = s.orders_status_id')
        ->where(array('ot.class' => 'total', 's.language_id' => lang_id()));
        
        if (!empty($orders_id))
        {
            $this->db->where('o.orders_id', $orders_id);
        }
        
        if (!empty($customers_id))
        {
            $this->db->where('o.customers_id', $customers_id);
        }
        
        if (!empty($status))
        {
            $this->db->where('s.orders_status_id', $status);
        }
        
        $result = $this->db->get();
        
        return $result->num_rows();
    }
}

/* End of file orders_model.php */
/* Location: ./system/models/orders_model.php */