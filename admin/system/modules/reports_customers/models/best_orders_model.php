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
 * @filesource system/modules/reports_customers/models/best_orders_model.php
 */

class Best_Orders_Model extends CI_Model
{
  public function get_orders($start_date, $end_date, $start, $limit)
  {
    $this->query($start_date, $end_date);
    
    $Qorders = $this->db
    ->order_by('value desc')
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qorders->result_array();
  }
  
  public function get_total($start_date, $end_date)
  {
    $this->query($start_date, $end_date);
    
    $Qorders = $this->db->get();
    
    return $Qorders->num_rows();
  }
  
  private function query($start_date, $end_date)
  {
    $this->db
    ->select('o.orders_id, o.customers_id, o.customers_name, ot.value, o.date_purchased')
    ->from('orders o')
    ->join('orders_total ot', 'o.orders_id = ot.orders_id')
    ->where('ot.class', 'total');
    
    if (!empty($start_date))
    {
      $this->db->where('o.date_purchased >=', $start_date);
    }
    
    if (!empty($end_date))
    {
      $this->db->where('o.date_purchased <=', $end_date);
    }
  }
}

/* End of file best_orders_model.php */
/* Location: system/modules/reports_customers/models/best_orders_model.php */