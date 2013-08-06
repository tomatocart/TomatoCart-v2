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
 * Categories Purchased Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Categories_Purchased_Model extends CI_Model
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
     * Get the categories
     *
     * @access public
     * @param $categories_id
     * @return mixed
     */
    public function get_categories($categories_id)
    {
        $result = $this->db
        ->select('cd.categories_name, c.parent_id, cd.categories_id')
        ->from('categories c')
        ->join('categories_description cd', 'c.categories_id = cd.categories_id')
        ->where(array('c.parent_id' => $categories_id, 'cd.language_id' => lang_id()))
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the products ids
     *
     * @access public
     * @param $categories
     * @return mixed
     */
    public function get_products_ids($categories)
    {
        $result = $this->db
        ->select('products_id')
        ->from('products_to_categories')
        ->where_in('categories_id', $categories)
        ->group_by('products_id')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            $products = array();
            foreach($result->result_array() as $product)
            {
                $products[] = $product['products_id'];
            }
            
            return $products;
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get purchased products in the categories
     *
     * @access public
     * @param $products_ids
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
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
        
        $result = $this->db->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
      
        return NULL;
    }
}

/* End of file categories_purchased_model.php */
/* Location: ./system/models/categories_purchased_model.php */