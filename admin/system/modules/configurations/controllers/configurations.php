<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource
 */ 

class Configurations extends TOC_Controller 
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('configurations_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('configurations_grid');
  }
  
  public function list_configurations()
  {
    $this->load->helper('core');
    
    $configurations = $this->configurations_model->get_configurations($this->input->get_post('gID'));
    
    $keys = array();
    if (!empty($configurations))
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
    
    return $keys;
  }
  
  public function get_countries()
  {
    $this->load->library('address');
    
    $countries = array();
    foreach($this->address->get_countries() as $country)
    {
      $countries[] = array('id' => $country['id'], 'text' => $country['name']);
    }
    
    return array(EXT_JSON_READER_ROOT => $countries);
  }
  
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
    
    return array(EXT_JSON_READER_ROOT => $zones);
  }
  
  public function save_configurations()
  {
    if ( $this->configurations_model->save($this->input->post('cID'), $this->input->post('configuration_value')) ) 
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
} 