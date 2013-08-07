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
 * Administrators Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Administrators extends TOC_Controller
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
        
        $this->load->model('administrators_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the administrators
     *
     * @access public
     * @return string
     */
    public function list_administrators()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $admins = $this->administrators_model->get_administrators($start, $limit);
        
        $response = array(EXT_JSON_READER_TOTAL => $this->administrators_model->get_total(),
                          EXT_JSON_READER_ROOT => $admins);
                          
        $this->output->set_output(json_encode($response));
    }
    
    // ----------------------------------------------------- -------------------
    
    /**
     * Get the accessible module
     *
     * @access public
     * @return string
     */
    public function get_accesses()
    {
        if ($this->input->get_post('aID') > 0)
        {
            $modules = $this->load_access_modules($this->input->get_post('aID'));  
        
        }
        else
        {
            $global = $this->input->get_post('global') == 'on' ? TRUE : FALSE;
          
            $modules = $this->get_modules($global);
        }
        
        $this->output->set_output(json_encode($modules));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the administrator
     *
     * @access public
     * @return string
     */
    public function save_administrator()
    {
        $this->load->library('access');
        
        $data = array('username' => $this->input->post('user_name'), 
                      'password' => $this->input->post('user_password'), 
                      'email_address' => $this->input->post('email_address'));
        
        $modules = json_decode($this->input->post('modules'));
        
        //verifiy that the global access is checked
        if ($this->input->post('access_globaladmin') === 'on')
        {
            $modules = array('*');
        }
        
        $admin_id = $this->input->post('aID');
        $admin_data = $this->session->userdata('admin_data');
        switch ($this->administrators_model->save($admin_id, $data, $modules))
        {
            case 1:
                if (is_numeric($admin_id) && $admin_id == $admin_data['id'])
                {
                    $admin_data['access'] = $this->access->get_user_levels($admin_id);
                    
                    $this->session->set_userdata($admin_data);
                }
                
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
                break;
              
            case -1:
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
                break;
              
            case -2:
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_username_already_exists'));
                break;
            
            case -3:
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_email_format'));
                break;
              
            case -4:
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_email_already_exists'));
                break;
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the administrator
     *
     * @access public
     * @return string
     */
    public function delete_administrator()
    {
        if ($this->administrators_model->delete($this->input->post('adminId')))
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
     * Batch delete the administrators
     *
     * @access public
     * @return string
     */
    public function delete_administrators()
    {
        $ids = json_decode($this->input->post('batch'));
        
        $error = FALSE;
        
        foreach($ids as $id)
        {
            if ( ! $this->administrators_model->delete($id))
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
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Load the administrator
     *
     * @access public
     * @return string
     */
    public function load_administrator()
    {
        $data = $this->administrators_model->get_data($this->input->post('aID'));
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Load the accessible modules for the administrator
     *
     * @access private
     * @param $administrators_id
     * @return array
     */
    private function load_access_modules($administrators_id)
    {
        $modules = $this->administrators_model->get_modules($administrators_id);
        
        //verify that the global access is selected
        $global_access = FALSE;
        if ($modules !== NULL)
        {
            foreach($modules as $module)
            {
                if ($module['module'] == '*')
                {
                    $global_access = TRUE;
                    break;
                }
            }
        }
        
        //get all the accesible modules
        $access_modules = array();
        if ($global_access == TRUE)
        {
            $access_modules = $this->get_modules(TRUE);
        }
        else
        {
            if ($modules !== NULL)
            {
                foreach($modules as $module)
                {
                    $access_modules[] = strtolower(str_replace(' ', '_', $module['module']));
                }
            
                $access_modules = $this->get_modules(FALSE, $access_modules);
            }
        }
        
        return $access_modules;
    }
        
    // ------------------------------------------------------------------------
    
    /**
     * Get all the modules
     *
     * @access private
     * @param $global
     * @param $modules
     * @return array
     */
    private function get_modules($global = FALSE, $modules = array())
    {
        $this->load->library('access');
        $this->load->helper('directory');
        
        $access_DirectoryListing = directory_map(APPPATH . 'modules/access', 1);
        
        //get the information of all the accessible modules
        $access_modules = array();
        foreach($access_DirectoryListing as $file)
        {
            $module = substr($file, 0, strrpos($file, '.'));
            
            //create the accessible module object
            $module_class = 'TOC_Access_' . ucfirst($module);
            
            if ( ! class_exists($module_class)) 
            {
                $this->lang->ini_load('access/' . $module . '.php');
                
                $this->load->file(APPPATH . 'modules/access/' . $module . '.php');
            }
            
            $module_obj = new $module_class();
            
            if (is_object($module_obj))
            {
                $title = $module_obj->get_group_title($module_obj->get_group());
                
                $access_modules[$title][] = array('id' => $module_obj->get_module(),
                                                  'text' => $module_obj->get_title(),
                                                  'leaf' => TRUE, 
                                                  'checked' => ($global == TRUE || in_array($module_obj->get_module(), $modules)) ? TRUE : FALSE);
            }
        }
        
        ksort($access_modules);
        
        //build the data for the tree panel
        $access_options = array(); 
        $count = 1;
        foreach ($access_modules as $group => $modules) 
        {
            $access_option['id'] = $count;
            $access_option['text'] = $group;
            
            $childrens = array();
            foreach($modules as $module) 
            {
                $childrens[] = $module;
            }
      
            $access_option['children'] = $childrens;
            
            $access_options[] = $access_option;
            $count++;
        }
        
        return $access_options;
    }
}

/* End of file administrators.php */
/* Location: ./system/controllers/administrators.php */