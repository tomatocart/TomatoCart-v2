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
 * Feature Products Manager Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Feature_Products_Manager extends TOC_Controller
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
        
        $this->load->model('feature_products_manager_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List feature products
     *
     * @access public
     * @return string
     */
    public function list_products()
    {
        $this->load->library('category_tree');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $current_category_id = end(explode( '_', ($this->input->get_post('categories_id') ? $this->input->get_post('categories_id') : 0)));
        
        //get the sub categories
        $in_categories = array();
        if ($current_category_id > 0)
        {
            $this->category_tree->set_breadcrumb_usage(FALSE);
            
            $in_categories = array($current_category_id);
            
            foreach($this->category_tree->get_tree($current_category_id) as $category)
            {
                $in_categories[] = $category['id'];
            }
        }
        
        $products = $this->feature_products_manager_model->get_products($start, $limit, $in_categories);
        
        $records = array();
        if ($products !== NULL)
        {
            foreach($products as $product)
            {
                $records[] = array('products_id'   => $product['products_id'],
                                   'products_name' => $product['products_name'],
                                   'sort_order'    => $product['sort_order']);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records, 
                                                    EXT_JSON_READER_TOTAL => $this->feature_products_manager_model->get_total($in_categories))));
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
        
        $records = array(array('id' => 0,
                               'text' => lang('top_category')));
        
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
     * Delete the product
     *
     * @access public
     * @return string
     */
    public function delete_product()
    {
        if ($this->feature_products_manager_model->delete($this->input->post('products_id')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the products
     *
     * @access public
     * @return string
     */
    public function delete_products()
    {
        $products_ids = json_decode($this->input->post('batch'));
        
        $error = FALSE;
        
        if (count($products_ids) > 0)
        {
            foreach($products_ids as $id)
            {
                if ( ! $this->feature_products_manager_model->delete($id))
                {
                    $error = TRUE;
                    break;
                }
            }
        }
        else
        {
            $error = TRUE;
        }
        
        if ($error === FALSE) 
        {      
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        } else 
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Update the sort order of the feature product
     *
     * @access public
     * @return string
     */
    public function update_sort_order()
    {
        if ($this->feature_products_manager_model->save($this->input->post('products_id'), $this->input->post('sort_value')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
}

/* End of file feature_products_manager.php */
/* Location: ./system/controllers/feature_products_manager.php */