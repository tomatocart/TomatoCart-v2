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
 * Countries Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Countries extends TOC_Controller
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
        
        $this->load->model('countries_model');
    }
    
    // ------------------------------------------------------------------------   
    
    /**
     * List the countries
     *
     * @access public
     * @return string
     */
    public function list_countries()
    {
        $this->load->helper('html_output');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $countries = $this->countries_model->get_countries($start, $limit);
        
        $records = array();
        if ($countries !== NULL)
        {
            foreach($countries as $country)
            {
                $total_zones = $this->countries_model->get_total_zones($country['countries_id']);
                
                $records[] = array('countries_id' => $country['countries_id'], 
                                   'countries_name' => $country['countries_name'], 
                                   'countries_iso_code' => image('images/worldflags/' . strtolower($country['countries_iso_code_2']) . '.png', $country['countries_name']) . '&nbsp;&nbsp;' . $country['countries_iso_code_2'] . '&nbsp;&nbsp;' . $country['countries_iso_code_3'], 
                                   'total_zones' => $total_zones);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->countries_model->get_totals(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------ 
    
    /**
     * List the zones in the country
     *
     * @access public
     * @return string
     */
    public function list_zones()
    {
        $zones = $this->countries_model->get_zones($this->input->get_post('countries_id'));
        
        $records = array();
        if ($zones !== NULL)
        {
            $records = $zones;
        } 
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------ 
    
    /**
     * Delete the country
     *
     * @access public
     * @return string
     */
    public function delete_country()
    {
        $error = FALSE;
        $feedback = array();
         
        $check_address_book = $this->countries_model->check_address_book($this->input->post('countries_id'));
        
        //country using in the address book
        if ($check_address_book > 0)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('delete_warning_country_in_use_address_book'), $check_address_book);
        }
        
        $check_geo_zones = $this->countries_model->check_geo_zones($this->input->post('countries_id'));
        
        //country using in the tax zone
        if ($check_geo_zones > 0)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('delete_warning_country_in_use_tax_zone'), $check_geo_zones);
        }
        
        if ($error === FALSE)
        {
            if ($this->countries_model->delete($this->input->post('countries_id')))
            {
                $response = array('success' => TRUE,'feedback' => lang('ms_success_action_performed'));
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
     * Delete the zone
     *
     * @access public
     * @return string
     */
    public function delete_zone()
    {
        $error = FALSE;
        $feedback = array();
        
        $address_books = $this->countries_model->get_zone_address_books($this->input->post('zone_id'));
        
        //zone using in the address book
        if ($address_books > 0)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('delete_warning_zone_in_use_address_book'), $address_books);
        }
        
        $geo_zones = $this->countries_model->get_zone_geo_zones($this->input->post('zone_id'));
        
        //zone using in the tax zone
        if ($geo_zones > 0)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('delete_warning_zone_in_use_tax_zone'), $geo_zones);
        }
        
        if ($error === FALSE)
        {
            if ($this->countries_model->delete_zone($this->input->post('zone_id')))
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
     * Delete the zones
     *
     * @access public
     * @return string
     */
    public function delete_zones()
    {
        $error = false;
        $feedback = array();
        $check_tax_zones_flag = array();
        $check_address_book_flag = array();
          
        $batch = $this->input->post('batch');
        
        $zones_ids = json_decode($batch);
        
        $zones = $this->countries_model->get_delete_zones($zones_ids);
        
        if ($zones !== NULL)
        {
            foreach($zones as $zone)
            {
                $address_books = $this->countries_model->get_zone_address_books($zone['zone_id']);
                
                //zone using in the address book
                if ($address_books > 0)
                {
                    $error = TRUE;
                    $check_address_book_flag[] = $zone['zone_name'];
                }
                
                $geo_zones = $this->countries_model->get_zone_geo_zones($zone['zone_id']);
                
                //zone using in the tax zone
                if ($geo_zones > 0)
                {
                    $error = TRUE;
                    $check_tax_zones_flag[] = $zone['zone_name'];
                }
            }
        }
        
        if (count($check_address_book_flag) > 0) 
        {
            $feedback[] = lang('batch_delete_warning_zone_in_use_address_book') . '<p>' . implode(', ', $check_address_book_flag) . '</p>';
        }
        
        if (count($check_tax_zones_flag) > 0) 
        {
            $feedback[] = lang('batch_delete_warning_zone_in_use_tax_zone') . '<p>' . implode(', ', $check_tax_zones_flag) . '</p>';
        }
    
        //delete the zones
        if ($error === FALSE && count($zones_ids) > 0)
        {
            foreach($zones_ids as $id)
            {
                if ($this->countries_model->delete_zone($id) === FALSE)
                {
                    $error = TRUE;
                    break;
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
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------ 
    
    /**
     * Save the country
     *
     * @access public
     * @return string
     */
    public function save_country()
    {
        $data = array('countries_name' => $this->input->post('countries_name'), 
                      'countries_iso_code_2' => $this->input->post('countries_iso_code_2'), 
                      'countries_iso_code_3' => $this->input->post('countries_iso_code_3'), 
                      'address_format' => $this->input->post('address_format'));
        
        if ($this->countries_model->save($this->input->post('countries_id'), $data))
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
     * Load the country
     *
     * @access public
     * @return string
     */
    public function load_country()
    {
        $data = $this->countries_model->get_data($this->input->post('countries_id'));
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------ 
    
    /**
     * Save the zone in the country
     *
     * @access public
     * @return string
     */
    public function save_zone()
    {
        $data = array('zone_name' => $this->input->post('zone_name'), 
                      'zone_code' => $this->input->post('zone_code'), 
                      'zone_country_id' => $this->input->post('countries_id'));
        
        if ($this->countries_model->save_zone($this->input->post('zone_id'), $data))
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
     * Load the zone
     *
     * @access public
     * @return string
     */
    public function load_zone()
    {
        $data = $this->countries_model->get_zone_data($this->input->post('zone_id'));
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
} 

/* End of file countries.php */
/* Location: ./system/controllers/countries.php */