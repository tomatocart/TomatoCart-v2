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
 * Reports Products Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Reports_Products extends TOC_Controller
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
     * @return string
     */
    public function get_categories()
    {
        $this->load->library('category_tree');
        
        $records = array(array('id' => '0', 'text' => lang('top_category')));
        foreach ($this->category_tree->get_tree() as $value) 
        {
            $category_id = strval($value['id']);
            $margin = 0;
            
            if (strpos($category_id, '_') !== FALSE)
            {
                $n = count(explode('_', $category_id)) - 1;
                
                $margin = $n * 10;
            }
            
            $records[] = array('id' => $value['id'],
                               'text' => $value['title'], 
                               'margin' => $margin);
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * List the products purchased
     *
     * @access public
     * @return string
     */
    public function list_products_purchased()
    {
        $this->load->model('products_purchased_model');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        $categories_id = $this->input->get_post('categories_id');
        $start_date = $this->input->get_post('start_date');
        $end_date = $this->input->get_post('end_date');
        
        $products_purchased = $this->products_purchased_model->get_products($categories_id, $start_date, $end_date, $start, $limit);
        
        $records = array();
        if ($products_purchased != NULL)
        {
            foreach($products_purchased as $product)
            {
                $records[] = array('products_id' => $product['products_id'],
                                   'products_name' => $product['products_name'],
                                   'quantity' => $product['quantity'],
                                   'final_price' => (float)$product['final_price'],
                                   'total' => (float)$product['total'],
                                   'average_price' => (float)($product['total']/$product['quantity']));
            }
        }
        else
        {
            $records[] = array('products_id' => 0,
                               'products_name' => lang('none'),
                               'quantity' =>0,
                               'final_price' => 0,
                               'total' => 0,
                               'average_price' => 0);
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->products_purchased_model->get_total($categories_id, $start_date, $end_date),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the languages
     *
     * @access public
     * @return string
     */
    public function get_languages()
    {
        $records = array();
        foreach(lang_get_all() as $l)
        {
            $records[] = array('id' => $l['id'], 'text' => $l['name']);
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * List the products viewed
     *
     * @access public
     * @return string
     */
    public function list_products_viewed()
    {
        $this->load->model('products_viewed_model');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        $categories_id = $this->input->get_post('categories_id');
        $language_id = $this->input->get_post('language_id');
        
        $products_viewed = $this->products_viewed_model->get_products_viewed($categories_id, $language_id, $start, $limit);
        
        $records = array();
        if ($products_viewed != NULL)
        {
            foreach($products_viewed as $product)
            {
                $records[] = array('products_id' => $product['products_id'],
                                   'products_name' => $product['products_name'],
                                   'products_viewed' => intval($product['products_viewed']),
                                   'language' => show_image($product['code']));
            }
        }
        else
        {
            $records[] = array('products_id' => 0,
                               'products_name' => lang('none'),
                               'products_viewed' => 0,
                               'language' => lang('none'));
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->products_viewed_model->get_total($categories_id, $language_id),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * List the categories purchased
     *
     * @access public
     * @return string
     */
    public function list_categories_purchased()
    {
        $this->load->library('category_tree');
        
        $this->load->model('categories_purchased_model');
        
        $start_date = $this->input->get_post('start_date');
        $end_date = $this->input->get_post('end_date');
        
        $categories_id = $this->input->get_post('categories_id');
        $categories_id = end(explode('_', empty($categories_id) ? 0 : $categories_id));
        
        $categories = $this->categories_purchased_model->get_categories($categories_id);
        
        $records = array();
        if ($categories != NULL)
        {
            foreach($categories as $category)
            {
                $sub_categories = array();
                $this->category_tree->get_children($category['categories_id'], $sub_categories);
                
                $categories_ids = array();
                if (!empty($sub_categories))
                {
                    foreach($sub_categories as $sub_category)
                    {
                        $categories_ids[] = $sub_category['id'];
                    }
                }
                $categories_ids[] = $category['categories_id'];
                
                $products_ids = $this->categories_purchased_model->get_products_ids($categories_ids);
                
                if ($products_ids != NULL)
                {
                    $category_products = $this->categories_purchased_model->get_category_products($products_ids, $start_date, $end_date);
                    
                    if ($category_products != NULL)
                    {
                        $records[] = array('categories_id' => $category['categories_id'], 
                                           'total' => (float)$category_products['total'], 
                                           'quantity' => intval($category_products['quantity']), 
                                           'categories_name' => $category['categories_name'], 
                                           'path' => $this->category_tree->build_breadcrumb($category['categories_id']));
                    }
                }
                else
                {
                    $records[] = array('categories_id' => 0, 
                                       'total' => 0, 
                                       'quantity' => 0, 
                                       'categories_name' => $category['categories_name'], 
                                       'path' => $this->category_tree->build_breadcrumb($category['categories_id']));
                }
            }
        }
        else
        {
            $records[] = array('categories_id' => 0, 
                               'total' => 0, 
                               'quantity' => 0, 
                               'categories_name' => lang('none'), 
                               'path' => lang('none'));
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * List the low stock products
     *
     * @access public
     * @return string
     */
    public function list_low_stock()
    {
        $this->load->model('low_stock_model');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        $categories_id = $this->input->get_post('categories_id');
        
        $products = $this->low_stock_model->get_products($categories_id, $start, $limit);
        
        if ($products == NULL)
        {
            $products = array('products_id' => 0, 'products_name' => lang('none'), 'products_quantity' => 0);
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->low_stock_model->get_total($categories_id),
                                                    EXT_JSON_READER_ROOT => $products)));
    }
}

/* End of file reports_products.php */
/* Location: ./system/controllers/reports_products.php */