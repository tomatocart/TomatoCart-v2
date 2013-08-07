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
 * Specials Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Specials extends TOC_Controller
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
        
        $this->load->model('specials_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the special products
     *
     * @access public
     * @return string
     */
    public function list_specials()
    {
        $this->load->library('currencies');
        $this->load->library('category_tree');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        $search = $this->input->get_post('search');
        $manufacturers_id = $this->input->get_post('manufacturers_id');
        
        //get the categories
        $current_category_id = end(explode( '_' , $this->input->get_post('category_id') ? $this->input->get_post('category_id') : 0));
        $in_categories = array();
        if ($current_category_id > 0)
        {
            $this->category_tree->set_breadcrumb_usage(FALSE);
            
            $in_categories[] = $current_category_id;
            foreach($this->category_tree->get_tree($current_category_id) as $category)
            {
                $in_categories[] = $category['id'];
            }
        }
        
        $specials = $this->specials_model->get_specials($start, $limit, $search, $manufacturers_id, $in_categories);
        
        $records = array();
        if ($specials !== NULL)
        {
            foreach($specials as $special)
            {
                $new_price = array('specials_new_products_price' => '<span class="oldPrice">' . $this->currencies->format($special['products_price']) . '</span> <span class="specialPrice">' . $this->currencies->format($special['specials_new_products_price']) . '</span>');
                $records[] = array_merge($special, $new_price);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->specials_model->get_total($search, $manufacturers_id, $in_categories),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the manufacturers
     *
     * @access public
     * @return string
     */
    public function list_manufacturers()
    {
        $this->load->model('manufacturers_model');
        
        $entries = $this->manufacturers_model->get_manufacturers_data();
        
        $records = array(array('manufacturers_id' => '',
                               'manufacturers_name' => lang('top_manufacturers')));
        
        $records = array_merge($records, $entries);
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the categories
     *
     * @access public
     * @return string
     */
    public function list_categories()
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
     * Save the special product
     *
     * @access public
     * @return string
     */
    public function save_specials()
    {
        $data = array('products_id' => $this->input->post('products_id'), 
                      'specials_price' => $this->input->post('specials_new_products_price'),
                      'start_date' => $this->input->post('start_date'), 
                      'expires_date' => $this->input->post('expires_date'), 
                      'specials_date_added' => $this->input->post('specials_date_added'), 
                      'status' => $this->input->post('status') ? 1 : 0);
        
        if ($this->specials_model->save($this->input->post('specials_id'), $data))
        {
            $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));   
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the products
     *
     * @access public
     * @return string
     */
    public function list_products()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $this->load->model('products_model');
        $tax_classes = $this->products_model->get_tax_classes();
        
        $products = $this->specials_model->get_products($start, $limit);
        
        $records = array();
        if ($products !== NULL)
        {
            foreach($products as $product)
            {
                if ($product['products_tax_class_id'] > 0)
                {
                    foreach($tax_classes as $tax_class)
                    {
                        if ($tax_class['id'] == $product['products_tax_class_id'])
                        {
                            $rate = $tax_class['rate'];
                        }
                        
                        break;
                    }
                }
                else
                {
                    $rate = 0;
                }
                
                $records[] = array('products_id' => $product['products_id'],
                                   'products_name' => $product['products_name'],
                                   'rate' => $rate);
            }
        }
        
        $this->output->set_output(json_encode( array(EXT_JSON_READER_TOTAL => $this->specials_model->get_total_products(),
                                                     EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the special product
     *
     * @access public
     * @return string
     */
    public function delete_special()
    {
        if ($this->specials_model->delete($this->input->post('specials_id')))
        {
            $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));   
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Batch delete the special products
     *
     * @access public
     * @return string
     */
    public function delete_specials()
    {
        $error = FALSE;
        
        $specials_ids = json_decode($this->input->post('batch'));
        
        if (count($specials_ids) > 0)
        {
            foreach($specials_ids as $id)
            {
                if ( ! $this->specials_model->delete($id))
                {
                    $error = TRUE;
                    break;
                }
            }
        }
        
        if ($error === FALSE)
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
     * Load the special product
     *
     * @access public
     * @return string
     */
    public function load_specials()
    {
        $this->load->helper('date');
        
        $data = $this->specials_model->get_data($this->input->post('specials_id'));
        
        if ($data !== NULL)
        {
            $data['start_date'] = mdate('%Y-%m-%d', human_to_unix($data['start_date']));
            $data['expires_date'] = mdate('%Y-%m-%d', human_to_unix($data['expires_date']));
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file specials.php */
/* Location: ./system/controllers/specials.php */