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
 * @filesource system/modules/reports_products/models/low_stock_model.php
 */

class Low_Stock_Model extends CI_Model
{
  public function get_products($categories_id, $start, $limit)
  {
    $this->query($categories_id);
    
    $QlowStock = $this->db
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $QlowStock->result_array();
  }
  
  public function get_total($categories_id)
  {
    $this->query($categories_id);
    
    $Qtotal = $this->db->get();
    
    return $Qtotal->num_rows();
  }
  
  private function query($categories_id)
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
    ->select('p.products_id, pd.products_name, p.products_quantity')
    ->from('products p')
    ->join('products_description pd', 'p.products_id = pd.products_id')
    ->where(array('pd.language_id' => lang_id(), 'p.products_quantity <=' => STOCK_REORDER_LEVEL));
    
    if (isset($products_ids) && !empty($products_ids))
    {
      $this->db->where_in('p.products_id', $products_ids);
    }
  }
}

/* End of file low_stock_model.php */
/* Location: system/modules/reports_products/models/low_stock_model.php */