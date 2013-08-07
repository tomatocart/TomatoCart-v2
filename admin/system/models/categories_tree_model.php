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
 * @filesource modules/categories/models/categories_tree_model.php
 */

class Categories_Tree_Model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  function get_categories_products_count()
  {
    $query = $this->db
      ->select('p2c.categories_id, count(*) as total')
      ->from('products as p')
      ->join('products_to_categories as p2c', 'p2c.products_id = p.products_id' , 'inner')
      ->where('p.products_status = 1')
      ->group_by('p2c.categories_id')
      ->get();

    if ($query->num_rows() > 0)
    {
      $totals = array();
      foreach ($query->result_array() as $row) 
      {
        $totals[$row['categories_id']] = $row['total'];
      }
      
      return $totals;
    }

    return FALSE;
  }

  function get_all($load_all_categories = FALSE)
  {
    $this->db
    ->select('c.categories_id, c.parent_id, c.categories_image, cd.categories_name, cd.categories_url, cd.categories_page_title, cd.categories_meta_keywords, cd.categories_meta_description')
    ->from('categories as c')
    ->join('categories_description as cd', 'c.categories_id = cd.categories_id', 'inner')
    ->where('cd.language_id = ' . lang_id());

    if ($load_all_categories === FALSE)
    {
      $this->db->where('c.categories_status = 1');
    }

    $query = $this->db->order_by('c.parent_id, c.sort_order, cd.categories_name')->get();

    $categories = array();
    if ($query->num_rows() > 0)
    {
      $categories = $query->result_array();
    }

    return $categories;
  }


  public function has_products($cpath)
  {
    $query = $this->db
    ->select('p.products_id')
    ->from('products_to_categories as ptc')
    ->join('products as p', 'ptc.products_id = p.products_id', 'left')
    ->where('p.products_status = 1 and categories_id = ' . $cpath)
    ->get();

    if ($query->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }
}
/* End of file categories_tree_model.php */
/* Location: ./system/models/categories_tree_model.php */