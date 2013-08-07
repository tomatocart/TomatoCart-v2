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
 * OrderTotal Class
 *
 * This class is the parent class for all order total modules
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Order_Total_Module {

    /**
     * Module group
     * 
     * @access private
     * @var string
     */
    private $group = 'order_total';
    
    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    protected $ci = NULL;

    /**
     * order total module code
     *
     * @access protected
     * @var string
     */
    protected $code = NULL;

    /**
     * order total module title
     *
     * @access protected
     * @var string
     */
    protected $title = NULL;

    /**
     * order total module description
     *
     * @access protected
     * @var string
     */
    protected $description = NULL;

    /**
     * order total module status
     *
     * @access protected
     * @var boolean
     */
    protected $status = NULL;

    /**
     * order total module sort order
     *
     * @access protected
     * @var int
     */
    protected $sort_order = 0;

    /**
     * order total module sort order
     *
     * @access protected
     * @var int
     */
    protected $output = array();

    /**
     * order total module configuration
     *
     * @access protected
     * @var array
     */
    protected $config = array();

    /**
     * order total module configuration parameters
     *
     * @access protected
     * @var array
     */
    protected $params = array();

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();
        
        // Load extension model
        $this->ci->load->model('extensions_model');
        
        // Get extension params
        $data = $this->ci->extensions_model->get_module('order_total', $this->code);

        // Load data
        if ($data !== NULL) 
        {
            $this->config = json_decode($data['params'], TRUE);
        }
    }
    
    /**
     * Install module
     * 
     * @access public
     * @return boolean
     */
    public function install() {
        //load extensions model
        $this->ci->load->model('extensions_model');
        
        //check whether the module is installed
        $data = $this->ci->extensions_model->get_module($this->group, $this->code);
        
        if ($data == NULL) {
            $config = array();
            
            if (isset($this->params) && is_array($this->params)) {
                foreach ($this->params as $param) {
                    $config[$param['name']] = $param['value'];
                }
            }
            
            $data = array(
                'title' => $this->title,
                'code' => $this->code,
                'author_name' => '',
                'author_www' => '',
                'modules_group' => $this->group,
                'params' => json_encode($config));
            
            $result = $this->ci->extensions_model->install($data);
            
            //insert language definition
            if ($result) {
                $languages_all = $this->ci->lang->get_all();
                
                foreach ($languages_all as $l)
                {
                    $xml_file = '../system/tomatocart/language/' . $l['code'] . '/modules/' . $this->group . '/' . $this->code . '.xml';
                    $this->ci->lang->import_xml($xml_file, $l['id']);
                }
            }
            
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Uninstall module
     * 
     * @access public
     * @return boolean
     */
    public function uninstall() {
        //load extensions model
        $this->ci->load->model('extensions_model');

        $result = $this->ci->extensions_model->uninstall($this->group, $this->code);
        
        //remove language definition
        if ($result) {
            $languages_all = $this->ci->lang->get_all();
            
            foreach ($languages_all as $l)
            {
                $xml_file = '../system/tomatocart/language/' . $l['code'] . '/modules/' . $this->group . '/' . $this->code . '.xml';
                $this->ci->lang->remove_xml($xml_file, $l['id']);
            }
            
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Get OrderTotal Module Code
     *
     * @access public
     * @return string order total module code
     */
    public function get_code()
    {
        return $this->code;
    }

    /**
     * Get OrderTotal Module Title
     *
     * @return string order total module title
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * Get OrderTotal Module Description
     *
     * @access public
     * @return string order total module description
     */
    public function get_description()
    {
        return $this->description;
    }

    /**
     * Get OrderTotal Module Configurations
     *
     * @access public
     * @return array order total module configurations
     */
    public function get_config()
    {
        return $this->config;
    }

    /**
     * Get OrderTotal Module Parameters
     *
     * @access public
     * @return array order total module paramers
     */
    public function get_params()
    {
        return $this->params;
    }
    
    /**
     * Whether the payment module is installed
     *
     * @access public
     * @return boolean payment module installed
     */
    public function is_installed()
    {
        return is_array($this->config) && !empty($this->config);
    }
        
    /**
     * Whether the order total module is enabled
     *
     * @access public
     * @return boolean order total module status
     */
    public function is_enabled()
    {
        return $this->status;
    }

    /**
     * Get order total module sort order
     *
     * @access public
     * @return int order total module sort order
     */
    public function get_sort_order()
    {
        return $this->sort_order;
    }

    /**
     * Get order total module output
     *
     * @access public
     * @return array order total module output
     */
    public function get_output()
    {
        return $this->output;
    }
}

/* End of file order total_module.php */
/* Location: ./system/tomatocart/libraries/order_total/order_total_module.php */