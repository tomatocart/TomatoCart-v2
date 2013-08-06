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
 * @filesource system/modules/reports_products/models/products_purchased_model.php
 */

class Products_Purchased_Model extends CI_Model
{
  public function get_products($categories_id, $start_date, $end_date, $start, $limit)
  {
    $this->query($categories_id, $start_date, $end_date);
    
    $Qproducts_purchased = $this->db
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qproducts_purchased->result_array();
  }
  
  public function get_total($categories_id, $start_date, $end_date)
  {
    $this->query($categories_id, $start_date, $end_date);
    
    $Qtotal = $this->db->get();
    
    return $Qtotal->num_rows();
  }
  
  private function query($categories_id, $start_date, $end_date) 
  {
    if (!empty($categories_id))
    {
      $categories_id = end(explode('_', $categories_id));
      
      $sub_categories = array();
      $this->category_tree->get_children($categories_id, $sub_categories);
      
      $categories= array();
      if (!empty($sub_categories))
      {
        foreach($sub_categories as $category)
        {
          $categories[] = $category['id'];
        }
      }
      $categories[] = $categories_id;
      
      $Qproducts_ids = $this->db
      ->select('products_id')
      ->from('products_to_categories')
      ->where_in('categories_id', $categories)
      ->group_by('products_id')
      ->get();
      
      $products_ids = array();
      if ($Qproducts_ids->num_rows() > 0)
      {
        foreach($Qproducts_ids->result_array() as $product)
        {
          $products_ids[] = $product['products_id'];
        }
      }
      $Qproducts_ids->free_result();
    }
    
    $this->db
    ->select('op.products_id, op.products_name, sum(op.products_quantity) as quantity, op.final_price, sum(op.final_price*op.products_quantity) as total')
    ->from('orders_products op')
    ->join('orders o', 'op.orders_id = o.orders_id');
    
    if (isset($products_ids) && !empty($products_ids))
    {
      $this->db->where_in('op.products_id', $products_ids);
    }
    
    if (!empty($start_date))
    {
      $this->db->where('o.date_purchased >=', $start_date);
    }
    
    if (!empty($end_date))
    {
      $this->db->where('o.date_purchased <=', $end_date);
    }
    
    $this->db
    ->group_by('op.products_id')
    ->order_by('total desc');
  }
}

/* End of file products_purchased_model.php */
/* Location: system/modules/reports_products/models/products_purchased_model.php */