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
 * Manufacturers Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Manufacturers extends TOC_Controller 
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
        
        $this->load->model('manufacturers_model');
    }

    // ------------------------------------------------------------------------
    
    /**
     * List the manufacturers
     *
     * @access public
     * @return string
     */
    public function list_manufacturers()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $manufacturers = $this->manufacturers_model->get_manufacturers($start, $limit);
        
        $records = array();
        if ($manufacturers !== NULL)
        {
            foreach($manufacturers as $manufacturer)
            {
                $clicked = $this->manufacturers_model->get_sum_clicks($manufacturer['manufacturers_id']);
                
                $records[] = array('manufacturers_id' => $manufacturer['manufacturers_id'], 
                                   'manufacturers_name' => $manufacturer['manufacturers_name'], 
                                   'url_clicked' => $clicked);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->manufacturers_model->get_totals(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the manufacturer
     *
     * @access public
     * @return string
     */
    public function delete_manufacturer()
    {
        if ($this->manufacturers_model->delete($this->input->post('manufacturers_id')))
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
     * Batch delete the manufacturers
     *
     * @access public
     * @return string
     */
    public function delete_manufacturers()
    {
        $error = FALSE;
        
        $batch = $this->input->post('batch');
        $manufacturers_ids = json_decode($batch);
        
        if (count($manufacturers_ids) > 0)
        {
            foreach($manufacturers_ids as $manufacturers_id)
            {
                if ( ! $this->manufacturers_model->delete($manufacturers_id))
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
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));  
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the manufacturer
     *
     * @access public
     * @return string
     */
    public function save_manufacturer()
    {
        $this->load->helper('html_output');
        
        //search engine friendly urls
        $formatted_urls = array();
        $urls = $this->input->post('manufacturers_friendly_url');
        
        if (is_array($urls) && count($urls) > 0)
        {
            foreach($urls as $languages_id => $url)
            {
                $url = format_friendly_url($url);
                
                //if the friendly url is empty, set it with the manufacturer's name
                if (empty($url))
                {
                    $url = format_friendly_url($this->input->post('manufacturers_name'));
                }
                
                $formatted_urls[$languages_id] = $url;
            }
        }
        
        $data = array('name' => $this->input->post('manufacturers_name'),
                      'image' => 'manufacturers_image',
                      'friendly_url' => $formatted_urls,
                      'url' => $this->input->post('manufacturers_url'),
                      'page_title' => $this->input->post('page_title'),
                      'meta_keywords' => $this->input->post('meta_keywords'),
                      'meta_description' => $this->input->post('meta_description'));
        
        if ($this->manufacturers_model->save($this->input->post('manufacturers_id'), $data))
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
     * Load the manufacturer
     *
     * @access public
     * @return string
     */
    public function load_manufacturer()
    {
        $data = $this->manufacturers_model->get_data($this->input->post('manufacturers_id'));
        
        $manufacturers_info = $this->manufacturers_model->get_info($this->input->post('manufacturers_id'));
        
        if ($manufacturers_info !== NULL)
        {
            foreach($manufacturers_info as $manufacturer_info)
            {
                $data['manufacturers_url[' . $manufacturer_info['languages_id'] . ']'] = $manufacturer_info['manufacturers_url'];
                $data['manufacturers_friendly_url[' . $manufacturer_info['languages_id'] . ']'] = $manufacturer_info['manufacturers_friendly_url'];
                $data['page_title[' . $manufacturer_info['languages_id'] . ']'] = $manufacturer_info['manufacturers_page_title'];
                $data['meta_keywords[' . $manufacturer_info['languages_id'] . ']'] = $manufacturer_info['manufacturers_meta_keywords'];
                $data['meta_description[' . $manufacturer_info['languages_id'] . ']'] = $manufacturer_info['manufacturers_meta_description'];
            }
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file manufacturers.php */
/* Location: ./system/controllers/manufacturers.php */