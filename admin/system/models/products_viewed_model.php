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
 * Products Viewed Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Products_Viewed_Model extends CI_Model
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
     * Get the products viewed
     *
     * @access public
     * @param $categories_id
     * @param $language_id
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_products_viewed($categories_id, $language_id, $start, $limit)
    {
        $this->query($categories_id, $language_id);
            
        $result = $this->db
        ->order_by('pd.products_viewed desc')
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
     * Get total number of the products viewed
     *
     * @access public
     * @param $categories_id
     * @param $language_id
     * @return int
     */
    public function get_total($categories_id, $language_id)
    {
        $this->query($categories_id, $language_id);
        
        $result = $this->db->get();
        
        return $result->num_rows();
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Build The query
     *
     * @access private
     * @param $categories_id
     * @param $language_id
     * @return void
     */
    private function query($categories_id, $language_id)
    {
        $this->load->library('category_tree');
        
        if (!empty($categories_id))
        {
            $categories_id = end(explode('_', $categories_id));
            
            $sub_categories = array();
            $this->category_tree->get_children($categories_id, $sub_categories);
            
            $categories = array();
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
        ->select('p.products_id, pd.products_name, pd.products_viewed, l.name, l.code')
        ->from('products p')
        ->join('products_description pd', 'p.products_id = pd.products_id')
        ->join('languages l', 'l.languages_id = pd.language_id');
        
        if (isset($products_ids) && !empty($products_ids))
        {
            $this->db->where_in('p.products_id', $products_ids);
        }
        
        if (!empty($language_id))
        {
            $this->db->where('l.languages_id', $language_id);
        }
    }
}

/* End of file products_viewed_model.php */
/* Location: ./system/models/products_viewed_model.php */