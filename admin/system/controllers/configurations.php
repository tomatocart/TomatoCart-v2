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
 * Configurations Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Configurations extends TOC_Controller 
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
        
        $this->load->model('configurations_model');
    }
    
    
// ------------------------------------------------------------------------
    
    /**
     * List the configurations
     *
     * @access public
     * @return string
     */
    public function list_configurations()
    {
        $configurations = $this->configurations_model->get_configurations($this->input->get_post('gID'));
        
        $keys = array();
        if ($configurations != NULL)
        {
            foreach($configurations as $configuration)
            {
                $cfgValue = $configuration['configuration_value'];
                
                if (!empty($configuration['use_function']))
                {
                    $cfgValue = call_config_func($configuration['use_function'], $configuration['configuration_value']);
                }
                
                $control = array();
                if (!empty($configuration['set_function']))
                {
                    $control = call_config_func($configuration['set_function'], $configuration['configuration_value'], $configuration['configuration_key']);
                }
                else
                {
                    $control['type'] = 'textfield';
                    $control['name'] = $configuration['configuration_key'];
                }
                $control['id'] = $configuration['configuration_id'];
                $control['title'] = $configuration['configuration_title'];
                $control['description'] = $configuration['configuration_description'];
                $control['value'] = $cfgValue;
                
                $keys[] = $control;
            }
        }
        
        $this->output->set_output(json_encode($keys));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the countries
     *
     * @access public
     * @return string
     */
    public function get_countries()
    {
        $this->load->library('address');
        
        $countries = array();
        foreach($this->address->get_countries() as $country)
        {
            $countries[] = array('id' => $country['id'], 'text' => $country['name']);
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $countries)));
    }
    
// ------------------------------------------------------------------------

    /**
     * Get the zones
     *
     * @access public
     * @return string
     */
    public function get_zones()
    {
        $this->load->library('address');
        
        $zones = array();
        foreach($this->address->get_zones() as $zone)
        {
            if ($zone['country_id'] == STORE_COUNTRY)
            {
                $zones[] = array('id' => $zone['id'], 'text' => $zone['name'], 'group' => $zone['country_name']);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $zones)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Save the configurations
     *
     * @access public
     * @return string
     */
    public function save_configurations()
    {
        if ($this->configurations_model->save($this->input->post('cID'), $this->input->post('configuration_value'))) 
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

/* End of file configuraitons.php */
/* Location: ./system/controllers/configuraitons.php */