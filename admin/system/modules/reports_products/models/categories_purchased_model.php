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
 * @filesource system/modules/reports_products/models/categories_purchased_model.php
 */

class Categories_Purchased_Model extends CI_Model
{
  public function get_categories($categories_id)
  {
    $Qcategories = $this->db
    ->select('cd.categories_name, c.parent_id, cd.categories_id')
    ->from('categories c')
    ->join('categories_description cd', 'c.categories_id = cd.categories_id')
    ->where(array('c.parent_id' => $categories_id, 'cd.language_id' => lang_id()))
    ->get();
    
    return $Qcategories->result_array();
  }
  
  public function get_products_ids($categories)
  {
    $Qproducts = $this->db
    ->select('products_id')
    ->from('products_to_categories')
    ->where_in('categories_id', $categories)
    ->group_by('products_id')
    ->get();
    
    $products = array();
    if ($Qproducts->num_rows() > 0)
    {
      foreach($Qproducts->result_array() as $product)
      {
        $products[] = $product['products_id'];
      }
    }
    $Qproducts->free_result();
    
    return $products;
  }
  
  public function get_category_products($products_ids, $start_date, $end_date)
  {
    $this->db
    ->select('sum(op.products_quantity) as quantity, sum(op.final_price*op.products_quantity) as total')
    ->from('orders_products op')
    ->join('orders o', 'o.orders_id = op.orders_id')
    ->where_in('op.products_id', $products_ids);
    
    if (!empty($start_date))
    {
      $this->db->where('o.date_purchased >=', $start_date);
    }
    
    if (!empty($end_date))
    {
      $this->db->where('o.date_purchased <=', $end_date);
    }
    
    $Qcategory_products = $this->db->get();
    
    return $Qcategory_products->row_array();
  }
}

/* End of file categories_purchased_model.php */
/* Location: system/modules/reports_products/models/categories_purchased_model.php */