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
 * Zone Groups Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
Class Zone_Groups extends TOC_Controller
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
        
        $this->load->model('zone_groups_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the zone groups
     *
     * @access public
     * @return void
     */
    public function list_zone_groups()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $zones = $this->zone_groups_model->get_geo_zones($start, $limit);
        
        $records = array();
        if ($zones !== NULL)
        {
            foreach($zones as $zone)
            {
                //get the zone entries in the zone group
                $entries = $this->zone_groups_model->get_entries($zone['geo_zone_id']);
                
                $records[] = array( 'geo_zone_id' => $zone['geo_zone_id'],
                                    'geo_zone_name' => $zone['geo_zone_name'],
                                    'geo_zone_entries' => $entries);    
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->zone_groups_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * List the zone entries in the zone group
     *
     * @access public
     * @return string
     */
    public function list_zone_entries()
    {
        $entries = $this->zone_groups_model->get_zone_entries_info($this->input->get_post('geo_zone_id'));
        
        $records = array();
        if ($entries !== NULL)
        {
            foreach($entries as $entry)
            {
                $records[] = array('geo_zone_entry_id' => $entry['association_id'],
                                   'countries_id' => $entry['countries_id'],
                                   'zone_id' => $entry['zone_id'],
                                   'countries_name' => (($entry['countries_id'] > 0) ? $entry['countries_name'] : lang('all_countries')),
                                   'zone_name' => (($entry['zone_id'] > 0) ? $entry['zone_name'] : lang('all_zones')));
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Get all the countries
     *
     * @access public
     * @return string
     */
    public function get_countries()
    {
        $entries = $this->zone_groups_model->get_countries();
        
        $records = array(array('countries_id' => '0',
                               'countries_name' => lang('all_countries')));
        
        if ($entries !== NULL)
        {
            foreach($entries as $entry)
            {
                $records[] = $entry;
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Get all the zones in the country
     *
     * @access public
     * @return string
     */
    public function get_zones()
    {
        $entries = $this->zone_groups_model->get_zones($this->input->get_post('countries_id'));
        
        $records = array(array('zone_id' => '0',
                               'zone_name' => lang('all_zones')));
          
        if ($entries !== NULL)
        {
            foreach($entries as $entry)
            {
                $records[] = $entry;
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Save the zone in the zone group
     *
     * @access public
     * @return string
     */
    public function save_zone_entry()
    {
        $data = array('group_id' => $this->input->post('geo_zone_id'), 
                      'country_id' => $this->input->post('countries_id'), 
                      'zone_id' => $this->input->post('zone_id'));
        
        if ($this->zone_groups_model->save_entry($this->input->post('geo_zone_entry_id'), $data))
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
     * Delete the zone
     *
     * @access public
     * @return string
     */
    public function delete_zone_entry()
    {
        if ($this->zone_groups_model->delete_entry($this->input->post('geo_zone_entry_id')))
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
     * Batch delete the zones
     *
     * @access public
     * @return string
     */
    public function delete_zone_entries()
    {
        $error = FALSE;
        
        $entries_ids = json_decode($this->input->post('batch'));
        
        if (count($entries_ids) > 0)
        {
            foreach($entries_ids as $id)
            {
                if ($this->zone_groups_model->delete_entry($id) === FALSE)
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
            $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
        } 
        else 
        {
            $response = array('success' => FALSE ,'feedback' => lang('ms_error_action_not_performed'));               
        }
        
        $this->output->set_output(json_encode($response));
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Load the zone
     *
     * @access public
     * @return string
     */
    public function load_zone_entry()
    {
        $data = $this->zone_groups_model->get_entry_data($this->input->post('geo_zone_entry_id'));
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Save the zone group
     *
     * @access public
     * @return string
     */
    public function save_zone_group()
    {
        $data = array('zone_name' => $this->input->post('geo_zone_name'), 
                      'zone_description' => $this->input->post('geo_zone_description'));
        
        if ($this->zone_groups_model->save($this->input->post('geo_zone_id'), $data))
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
     * Load the zone group
     *
     * @access public
     * @return string
     */
    public function load_zone_group()
    {
        $data = $this->zone_groups_model->get_data($this->input->post('geo_zone_id'));
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Delete the zone group
     *
     * @access public
     * @return string
     */
    public function delete_zone_group()
    {
        $error = FALSE;
        $feedback = array();
        
        $check_tax_rates = $this->zone_groups_model->get_tax_rates($this->input->post('geo_zone_id'));
        
        //zone group using inthe tax classes
        if ($check_tax_rates > 0)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('delete_warning_group_in_use_tax_rate'), $check_tax_rates);
        }
        
        if ($error === FALSE)
        {
            if ($this->zone_groups_model->delete($this->input->post('geo_zone_id')))
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
}

/* End of file zone_groups.php */
/* Location: ./system/controllers/zone_groups.php */