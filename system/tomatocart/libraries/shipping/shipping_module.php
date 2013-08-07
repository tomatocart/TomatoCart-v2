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
 * Shipping Module Class
 *
 * This class is the parent class for all shipping modules
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Shipping_Module {

    /**
     * Module group
     *
     * @access private
     * @var string
     */
    private $group = 'shipping';

    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    protected $ci = NULL;

    /**
     * shipping module code
     *
     * @access protected
     * @var string
     */
    protected $code = NULL;

    /**
     * shipping module icon
     *
     * @access protected
     * @var string
     */
    protected $icon = NULL;

    /**
     * shipping module title
     *
     * @access protected
     * @var string
     */
    protected $title = NULL;

    /**
     * shipping module description
     *
     * @access protected
     * @var string
     */
    protected $description = NULL;

    /**
     * shipping module status
     *
     * @access protected
     * @var boolean
     */
    protected $status = FALSE;

    /**
     * shipping module sort order
     *
     * @access protected
     * @var int
     */
    protected $sort_order = 0;

    /**
     * shipping module configuration
     *
     * @access protected
     * @var array
     */
    protected $config = array();

    /**
     * shipping module configuration parameters
     *
     * @access protected
     * @var array
     */
    protected $params = array();

    /**
     * shipping module quotes
     *
     * @access protected
     * @var array
     */
    protected $quotes = array();

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
        $data = $this->ci->extensions_model->get_module('shipping', $this->code);

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
     * Get Shipping Module Code
     *
     * @access public
     * @return string shipping module code
     */
    public function get_code()
    {
        return $this->code;
    }

    /**
     * Get Shipping Module Title
     *
     * @access public
     * @return string shipping module title
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * Get Shipping Module Description
     *
     * @access public
     * @return string shipping module description
     */
    public function get_description()
    {
        return $this->description;
    }

    /**
     * Get Shipping Module Configurations
     *
     * @access public
     * @return array shipping module configurations
     */
    public function get_config()
    {
        return $this->config;
    }

    /**
     * Get Shipping Module Parameters
     *
     * @access public
     * @return array shipping module paramers
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
     * Whether the shipping module is enabled
     *
     * @access public
     * @return boolean shipping module status
     */
    public function is_enabled()
    {
        return $this->status;
    }

    /**
     * Get shipping module sort order
     *
     * @access public
     * @return int shipping module sort order
     */
    public function get_sort_order()
    {
        return $this->sort_order;
    }

    /**
     * Get shipping module quotes
     *
     * @access public
     * @return array shipping module quotes
     */
    public function get_quote()
    {
        return $this->quotes;
    }
}

/* End of file shipping_module.php */
/* Location: ./system/tomatocart/libraries/shipping/shipping_module.php */