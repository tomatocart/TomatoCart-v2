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
 * Access Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Access
{
    /**
     * Cached data
     *
     * @access private
     * @var object
     */
    private $_ci = NULL;

    /**
     * Menu Group
     *
     * @access protected
     * @var string
     */
    protected $_group = 'misc';

    /**
     * Icon
     *
     * @access protected
     * @var string
     */
    protected $_icon = 'configure.png';

    /**
     * Title
     *
     * @access protected
     * @var string
     */
    protected $_title;

    /**
     * Module
     *
     * @access protected
     * @var string
     */
    protected $_module;

    /**
     * Sort Order
     *
     * @access protected
     * @var int
     */
    protected $_sort_order = 0;

    /**
     * Sub Group
     *
     * @access protected
     * @var int
     */
    protected $_subgroups;

    /**
     * Default constructor
     *
     * @access public
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->_ci = & get_instance();

        // Load the Sessions class
        $this->_ci->load->library('session');

        //get directory helpder
        $this->_ci->load->helper('directory');

        //get access model
        $this->_ci->load->model('access_model');

        //get admin data
        $admin_data = $this->_ci->session->userdata('admin_data');

        //get admin id
        $this->_user_id = $admin_data['id'];
    }

    /**
     * Get modules
     *
     * @access public
     * @param $id user id
     * @return array
     */
    public function get_user_levels()
    {
        $modules = $this->_ci->access_model->get_user_levels($this->_user_id);

        //if it is all modules
        if (in_array('*', $modules))
        {
            $modules = array();

            $modules_map = directory_map(APPPATH . 'modules/access');

            if (!empty($modules_map))
            {
                foreach($modules_map as $module)
                {
                    $modules[] = substr($module, 0, strrpos($module, '.'));
                }
            }
        }

        return $modules;
    }

    /**
     * Get all modules
     *
     * @access public
     * @return array
     */
    public function get_levels()
    {
        $access = array();

        $this->_ci->load->helper('directory');

        $modules = $this->_ci->access_model->get_user_levels($this->_user_id);

        $access = array();
        foreach($modules as $module)
        {
            if ( file_exists(APPPATH . 'modules/access/' . $module . '.php') )
            {
                $this->_ci->lang->ini_load('access/' . $module . '.php');

                $module_class = 'TOC_Access_' . ucfirst($module);

                if ( !class_exists( $module_class ) ) {
                    include(APPPATH . 'modules/access/' . $module . '.php');
                }

                $module_obj = new $module_class();

                $data = array('module' => $module,
                              'icon' => $module_obj->get_icon(), 
                              'title' => $module_obj->get_title(), 
                              'subgroups' => $module_obj->get_sub_groups());

                if ( !isset( $access[$module_obj->get_group()][$module_obj->get_sort_order()] ) )
                {
                    $access[$module_obj->get_group()][$module_obj->get_sort_order()] = $data;
                }
                else
                {
                    $access[$module_obj->get_group()][] = $data;
                }
            }
        }

        ksort($access);
        foreach ( $access as $group => $links )
        {
            ksort($access[$group]);
        }

        return $access;
    }

    /**
     * Get group title
     *
     * @access public
     * @param $group
     * @return string
     */
    public function get_group_title($group)
    {
        $this->_ci->lang->ini_load('access/groups/' . $group . '.php');

        return $this->_ci->lang->line('access_group_' . $group . '_title');
    }

    /**
     * Get group title
     *
     * @access public
     * @return string
     */
    public function get_module()
    {
        return $this->_module;
    }

    /**
     * Get group
     *
     * @access public
     * @return string
     */
    public function get_group()
    {
        return $this->_group;
    }

    /**
     * Get icon
     *
     * @access public
     * @return string
     */
    public function get_icon()
    {
        return $this->_icon;
    }

    /**
     * Get title
     *
     * @access public
     * @return string
     */
    public function get_title()
    {
        return $this->_title;
    }

    /**
     * Get sort order
     *
     * @access public
     * @return string
     */
    public function get_sort_order()
    {
        return $this->_sort_order;
    }

    /**
     * Get sub groups
     *
     * @access public
     * @return string
     */
    public function get_sub_groups()
    {
        return $this->_subgroups;
    }
}
