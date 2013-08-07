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
 * Products Expected Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Products_Expected extends TOC_Controller
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
        
        $this->load->model('products_expected_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the expected products 
     *
     * @access public
     * @return string
     */
    public function list_products_expected()
    {
        $this->load->helper('date');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $products = $this->products_expected_model->get_products($start, $limit);
        
        $records = array();
        
        if ($products !== NULL)
        {
            foreach($products as $product)
            {
                $records[] = array('products_id' => $product['products_id'],
                                  'products_name' => $product['products_name'],
                                  'products_date_available' =>  mdate('%Y/%m/%d', human_to_unix($product['products_date_available'])));         
            }
        }
        
       $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->products_expected_model->get_total(),
                                                   EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the expected product
     *
     * @access public
     * @return string
     */
    public function save_products_expected()
    {
        $data = array('date_available' => $this->input->post('products_date_available'));
        
        if ($this->products_expected_model->save_date_available($this->input->post('products_id'), $data))
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
     * Load the expected product
     *
     * @access public
     * @return string
     */
    public function load_products_expected()
    {
        $this->load->helper('date');
        
        $data = $this->products_expected_model->get_data($this->input->post('products_id'));
        
        $data['products_date_available'] = mdate('%Y-%m-%d', human_to_unix($data['products_date_available']));
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file products_expected.php */
/* Location: ./system/controllers/products_expected.php */