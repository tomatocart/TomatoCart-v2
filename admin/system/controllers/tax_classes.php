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
 * Tax Classes Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Tax_Classes extends TOC_Controller
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
        
        $this->load->model('tax_classes_model');
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * List the tax classes
     *
     * @access public
     * @return string
     */
    public function list_tax_classes()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $tax_classes = $this->tax_classes_model->get_tax_classes($start, $limit);
        
        $record = array();
        if ($tax_classes !== NULL)
        {
            foreach($tax_classes as $tax_class)
            {
                $tax_total_rates = $this->tax_classes_model->get_total_rates($tax_class['tax_class_id']);
                
                $records[] = array('tax_class_id' => $tax_class['tax_class_id'],
                                   'tax_class_title' => $tax_class['tax_class_title'],
                                   'tax_total_rates' => $tax_total_rates);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->tax_classes_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * List the tax rates with the tax class id
     *
     * @access public
     * @return string
     */
    public function list_tax_rates()
    {
        $tax_rates = $this->tax_classes_model->get_tax_rates($this->input->get_post('tax_class_id'));
        
        $records = array();
        if ($tax_rates !== NULL)
        {
            $records = $tax_rates;
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Delete the tax class
     *
     * @access public
     * @return string
     */
    public function delete_tax_class()
    {
        $error = FALSE;
        $feedback = array();
        
        $check_products = $this->tax_classes_model->get_products($this->input->post('tax_class_id'));
        
        //the tax class is using by some products
        if ($check_products > 0)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('delete_warning_tax_class_in_use'), $check_products);
        }
        
        if ($error === FALSE)
        {
            if ($this->tax_classes_model->delete($this->input->post('tax_class_id')))
            {
                $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Save the tax class
     *
     * @access public
     * @return string
     */
    public function save_tax_class()
    {
        $data = array('tax_class_title' => $this->input->post('tax_class_title'), 
                      'tax_class_description' => $this->input->post('tax_class_description'));
        
        if ($this->tax_classes_model->save($this->input->post('tax_class_id'), $data))
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
     * List the zone groups
     *
     * @access public
     * @return string
     */
    public function list_geo_zones()
    {
        $zones = $this->tax_classes_model->get_zones();
        
        $records = array();
        if ($zones !== NULL)
        {
            $records = $zones;
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * save the tax rate
     *
     * @access public
     * @return string
     */
    public function save_tax_rate()
    {
        $data = array('tax_zone_id' => $this->input->post('geo_zone_id'), 
                      'tax_class_id' => $this->input->post('tax_class_id'), 
                      'tax_rate' => $this->input->post('tax_rate'), 
                      'tax_description' => $this->input->post('tax_description'), 
                      'tax_priority' => $this->input->post('tax_priority'));
        
        if ($this->tax_classes_model->save_entry($this->input->post('tax_rates_id'), $data))
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
     * Load the tax class
     *
     * @access public
     * @return string
     */
    public function load_tax_class()
    {
        $data = $this->tax_classes_model->get_data($this->input->post('tax_class_id'));
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the tax rate
     *
     * @access public
     * @return string
     */
    public function delete_tax_rate()
    {
        if ($this->tax_classes_model->delete_entry($this->input->post('rateId')))
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
     * Batch delete the tax rate
     *
     * @access public
     * @return string
     */
    public function delete_tax_rates()
    {
        $error = FALSE;
        
        $batch = $this->input->post('batch');
        $tax_rates_ids = json_decode($batch);
        
        if (count($tax_rates_ids) > 0)
        {
            foreach($tax_rates_ids as $id)
            {
                if ($this->tax_classes_model->delete_entry($id) === FALSE)
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
     * Load the tax rate
     *
     * @access public
     * @return string
     */
    public function load_tax_rate() 
    {
        $data = $this->tax_classes_model->get_entry_data($this->input->post('tax_rates_id'));
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file tax_classes.php */
/* Location: ./system/controllers/tax_classes.php */