<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource
 */

class Orders_Model extends CI_Model {
  public function __construct()
  {
    parent::__construct();
  }
  
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
    
    $Qorders = $this->db
    ->order_by('o.date_purchased desc, o.last_modified desc, o.orders_id desc')
    ->limit($params['limit'], $params['start'] > 0 ? $params['start'] -1 : $params['start'])
    ->get();
    
    return $Qorders->result_array();
  }
  
  public function get_countries()
  {
    $Qentries = $this->db
    ->select('countries_name,countries_id')
    ->from('countries')
    ->get();
    
    return $Qentries->result_array();
  }
  
  public function get_zones($countries_id)
  {
    $Qentries = $this->db
    ->select('zone_id, zone_code, zone_name')
    ->from('zones')
    ->where('zone_country_id', $countries_id)
    ->get();
    
    return $Qentries->result_array();
  }
  
  public function get_status()
  {
    $Qstatus = $this->db
    ->select('orders_status_id, orders_status_name')
    ->from('orders_status')
    ->where('language_id', lang_id())
    ->get();
    
    return $Qstatus->result_array();
  }
  
  public function update_status($id, $data)
  {
    $ci = & get_instance();
    
    $error = FALSE;
    
    $this->db->trans_begin();
    
    $orders_status = $this->orders_status->get_data($data['status_id']);
    
    if (isset($orders_status['downloads_flag']) && $orders_status['downloads_flag'] == 1) 
    {
//      $this->order->active_downloadables($id);
    }
    
    if (isset($orders_status['gift_certificates_flag']) && $orders_status['gift_certificates_flag'] == 1) 
    {
//      $this->order->active_gift_certificates($id);
    }
    
    if (($data['status_id'] == ORDERS_STATUS_CANCELLED) && ($data['restock_products'] == true)) 
    {
      $Qproducts = $this->db
      ->select('orders_products_id, products_id, products_type, products_quantity')
      ->from('orders_products')
      ->where('orders_id', $id)
      ->get();
      
      if ($Qproducts->num_rows() > 0)
      {
        foreach($Qproducts->result_array() as $product)
        {
          $result = $this->product->restock($id, $product['orders_products_id'], $product['products_id'], $product['products_quantity']);
          
          if ($result == FALSE)
          {
            $error = TRUE;
            break;
          }
        }
      }
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
      $Qorder = $this->db
      ->select('o.customers_name, o.customers_email_address, s.orders_status_name, o.date_purchased')
      ->from('orders o')
      ->join('orders_status s', 'o.orders_status = s.orders_status_id')
      ->where(array('s.language_id' => lang_id(), 'o.orders_id' => $id))
      ->get();
      
      $order = $Qorder->row_array();
      
      Email_Templates::get_email_template('admin_order_status_updated');
      
      if (is_object($ci->admin_order_status_updated))
      {
        $ci->admin_order_status_updated->set_data($id, 'www.tomatocart.com', mdate('%Y/%m/%d', human_to_unix($order['date_purchased'])), $data['append_comment'], $data['comment'], $order['orders_status_name'], $order['customers_name'], $order['customers_email_address']);
        $ci->admin_order_status_updated->build_message();
        $ci->admin_order_status_updated->send_email();
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function create_invoice($id)
  {
    $Qcheck = $this->db
    ->select_max('invoice_number')
    ->from('orders')
    ->get();
    
    $max_invoice = $Qcheck->row_array();
    
    $invoice_number = $max_invoice['invoice_number'] + 1;
    $invoice_number = ($invoice_number > INVOICE_START_NUMBER) ? $invoice_number : INVOICE_START_NUMBER;
    
    $this->db->update('orders', array('invoice_number' => $invoice_number, 'invoice_date' => date('Y-m-d H:i:s')), array('orders_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
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
    
    $Qtotal = $this->db->get();
    
    return $Qtotal->num_rows();
  }
}


/* End of file orders_model.php */
/* Location: ./system/modules/customers/models/orders_model.php */
