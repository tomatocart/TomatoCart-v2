<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Customers Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Modules_Shipping extends TOC_Controller
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

    /**
     * Get all template modules
     */
    public function list_shippings()
    {
        $this->load->helper('directory');

        $path_array = array('../system/tomatocart/libraries/shipping/');

        $modules = array();
        $loaded = array();
        foreach($path_array as $path) {
            $directories = directory_map($path, 1, TRUE);

            foreach ($directories as $file)
            {
                if ((strpos($file, '.php') !== FALSE) && ($file != 'shipping_module.php'))
                {
                    $module = substr($file, 0, strpos($file, '.php'));

                    //load language xml file
                    $this->lang->xml_load('modules/shipping/' . str_replace('shipping_', '', $module));

                    //include path file
                    include_once $path . $file;

                    //get class
                    $class = config_item('subclass_prefix') . $module;
                    $class = str_replace('shipping', 'Shipping', $class);
                    
                    $class = new $class();

                    if ($class->is_installed())
                    {
                        $edit_cls = 'icon-edit-record';
                        $install_cls = 'icon-uninstall-record';
                    }
                    else
                    {
                        $edit_cls = 'icon-edit-gray-record';
                        $install_cls = 'icon-install-record';
                    }

                    //$loaded[] = $directory;
                    $modules[] = array(
                    	'code' => $class->get_code(), 
                    	'title' => $class->get_title(), 
                    	'sort_order' => ($class->is_installed() ? $class->get_sort_order() : ''), 
                    	'is_installed' => $class->is_installed(),
  						'edit_cls' => $edit_cls,
  						'install_cls' => $install_cls);
                }
            }
        }

        $this->output->set_output(json_encode($modules));
    }

    /**
     * Uninstall template
     */
    public function install()
    {
        //load model class
        $this->load->model('extensions_model');

        //get code
        $code = $this->input->post('code');

        include_once '../system/tomatocart/libraries/shipping/shipping_' . $code . '.php';
        $class = config_item('subclass_prefix') . 'Shipping_'. $code;

        $class = new $class();
        
        if ($class->install()) {
            $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
        } else {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    /**
     * Uninstall template
     */
    public function uninstall()
    {
        $this->load->model('extensions_model');

        $code = $this->input->post('code');

        include_once '../system/tomatocart/libraries/shipping/shipping_' . $code . '.php';
        $class = config_item('subclass_prefix') . 'Shipping_'. $code;

        $class = new $class();
        
        if ($class->uninstall()) {
            $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
        } else {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }
    
    function save()
    {
        $this->load->model('extensions_model');
        
        $code = $this->input->post('code');
        $configurations = $this->input->post('configuration');
        
        if ($this->extensions_model->save('shipping', $code, json_encode($configurations))) 
        {
            $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
        } 
        else 
        {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }

    /**
     * Get configuration parameters
     * 
     * @access public
     * @return string
     */
    function get_configuration_options()
    {
        $code = $this->input->post('code');

        //load language xml file
        $this->lang->xml_load('modules/shipping/' . $code);
        
        //include file
        require_once '../system/tomatocart/libraries/shipping/shipping_' . $code . '.php';

        //instantiate class
        $class = config_item('subclass_prefix') . 'Shipping_' . $code;
        $module = new $class();
        
        $params = $module->get_params();
        $configs = $module->get_config();

        $keys = array();
        foreach ($params as $param) 
        {
            $control = array(
                'name' => 'configuration[' . $param['name'] . ']',
                'type' => $param['type'],
                'title' => $param['title'],
                'value' => $this->get_config_value($param['name'], $configs, $param['value']),
                'description' => $param['description']
            );
            
            if ($param['type'] == 'combobox')
            {
                $control['mode'] = $param['mode'];
                
                if ($param['mode'] == 'local') 
                {
                    $control['values'] = $param['values'];
                }
                else
                {
                    $control['action'] = $param['action'];
                }
            }
            
            $keys[] = $control;
        }

        $this->output->set_output(json_encode($keys));
    }
    
    private function get_config_value($name, $configs, $default) 
    {
        if (is_array($configs)) 
        {
            if (isset($configs[$name]))
            {
                return $configs[$name];
            }
        }
        
        return $default;
    }
}

/* End of file customers.php */
/* Location: ./system/tomatocart/controllers/customers.php */