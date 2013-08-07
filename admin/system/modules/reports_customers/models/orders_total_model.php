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
 * @filesource system/modules/reports_customers/models/orders_total_model.php
 */

class Orders_Total_Model extends CI_Model
{
  public function get_orders($start_date, $end_date, $start, $limit)
  {
    $this->query($start_date, $end_date);
    
    $Qorders = $this->db
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
    ->select('o.orders_id, o.customers_id, o.customers_name, sum(ot.value) as value')
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
    
    $this->db
    ->group_by('o.customers_id')
    ->order_by('value desc');
  }
}


/* End of file orders_total_model.php */
/* Location: system/modules/reports_customers/models/orders_total_model.php */