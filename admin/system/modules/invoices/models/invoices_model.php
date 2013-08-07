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
 * @filesource system/modules/invoices/models/invoices_model.php
 */

class Invoices_Model extends CI_Model
{
  public function get_invoices($start, $limit, $orders_id, $customers_id, $status)
  {
    $this->query($orders_id, $customers_id, $status);
    
    $Qinvoices = $this->db
    ->order_by('o.date_purchased desc, o.last_modified desc, o.invoice_number desc')
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qinvoices->result_array();
  }
  
  public function get_total($orders_id, $customers_id, $status)
  {
    $this->query($orders_id, $customers_id, $status);
    
    $Qinvoices= $this->db->get();
    
    return $Qinvoices->num_rows();
  }
  
  private function query($orders_id, $customers_id, $status)
  {
    $this->db
    ->select('o.orders_id, o.customers_ip_address, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.invoice_number, o.invoice_date, s.orders_status_name, ot.text as order_total')
    ->from('orders o')
    ->join('orders_total ot', 'o.orders_id = ot.orders_id and ot.class = "total" and o.invoice_number IS NOT NULL')
    ->join('orders_status s', 'o.orders_status = s.orders_status_id')
    ->where('s.language_id', lang_id());
    
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
  }
}

/* End of file invoices_model.php */
/* Location: system/modules/invoices/models/invoices_model.php */