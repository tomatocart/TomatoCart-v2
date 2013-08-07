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
 * Products Purchased Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Products_Purchased_Model extends CI_Model
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
     * Get the purchased products
     *
     * @access public
     * @param $categories_id
     * @param $start_date
     * @param $end_date
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_products($categories_id, $start_date, $end_date, $start, $limit)
    {
        $this->query($categories_id, $start_date, $end_date);
        
        $result = $this->db
        ->limit($limit, $start)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the total products purchased
     *
     * @access public
     * @param $categories_id
     * @param $start_date
     * @param $end_date
     * @return int
     */
    public function get_total($categories_id, $start_date, $end_date)
    {
        $this->query($categories_id, $start_date, $end_date);
        
        $result = $this->db->get();
        
        return $result->num_rows();
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Build the query
     *
     * @access private
     * @param $categories_id
     * @param $start_date
     * @param $end_date
     * @return void
     */
    private function query($categories_id, $start_date, $end_date) 
    {
        $this->load->library('category_tree');
        
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
            
            $result = $this->db
            ->select('products_id')
            ->from('products_to_categories')
            ->where_in('categories_id', $categories)
            ->group_by('products_id')
            ->get();
            
            $products_ids = array();
            if ($result->num_rows() > 0)
            {
                foreach($result->result_array() as $product)
                {
                    $products_ids[] = $product['products_id'];
                }
            }
            $result->free_result();
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
        
        $this->db->group_by('op.products_id')->order_by('total desc');
    }
}

/* End of file products_purchased_model.php */
/* Location: ./system/models/products_purchased_model.php */