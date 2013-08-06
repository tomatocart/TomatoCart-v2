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
 * Customer Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	library
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Admin
{
    /**
     * ci instance
     *
     * @access private
     * @var string
     */
    protected $ci = NULL;

    /**
     * admin data
     *
     * @access private
     * @var array
     */
    protected $data = array();

    /**
     * Toc Customer Constructor
     * 
     * @access public
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        // Grab the customer data array from the session table, if it exists
        if ($this->ci->session->userdata('admin_data') !== FALSE)
        {
            $this->data = $this->ci->session->userdata('admin_data');
        }

        log_message('debug', "TOC Admin Class Initialized");
    }

    /**
     * Get admin id
     *
     * @access public
     * @return mixed $value
     */
    public function get_id()
    {
        if (isset($this->_data['id']) && is_numeric($this->_data['id']))
        {
            return $this->_data['id'];
        }

        return FALSE;
    }

    /**
     * Get name
     *
     * @access public
     * @return mixed $value
     */
    public function get_name()
    {
        static $name = NULL;

        if (empty($name))
        {
            $name = $this->_data['name'];
        }

        return $name;
    }

    /**
     * Check whether admin is logged in
     *
     * @access public
     * @return mixed $value
     */
    public function is_logged_on()
    {
        static $logged_on = NULL;

        if (is_null($logged_on))
        {
            $logged_on = isset($this->data['id']) && !empty($this->data['id']);
        }

        return $logged_on;
    }

    /**
     * Get settings
     *
     * @access public
     * @return mixed $value
     */
    public function get_settings()
    {
        static $settings = null;

        if (is_null($settings))
        {
            if (isset($this->_data['settings']))
            {
                $settings = $this->_data['settings'];
            }
        }

        return $settings;
    }

    /**
     * Get email address
     *
     * @access public
     * @return mixed $value
     */
    public function get_email_address()
    {
        static $email_address = null;

        if (is_null($email_address))
        {
            if (isset($this->_data['email_address']))
            {
                $email_address = $this->_data['email_address'];
            }
        }

        return $email_address;
    }

    /**
     * Perform login action and assign email and passwrod data.
     *
     * @access public
     * @param string $email
     * @param string $password
     */
    public function login($email)
    {
        $data = $this->ci->admin_model->get_data($email);

        //if customer data is not null
        if ($data !== FALSE)
        {
            $this->data = array();

            $this->data['id'] = $data['id'];
            $this->data['name'] = $data['user_name'];
            $this->data['settings'] = $data['user_settings'];
            $this->data['email_address'] = $data['email_address'];

            //set data to session
            $this->ci->session->set_userdata('admin_data', $this->data);
        }
        //if user data is not found and session is not empty then reset session
        else if ($this->ci->session->userdata('admin_data') !== FALSE)
        {
            $this->reset();
        }
    }

    /**
     * Reset admin & session data.
     * 
     * @access public
     */
    public function reset()
    {
        //clean customer data
        $this->data = array();

        //clean session data
        $this->ci->session->unset_userdata('admin_data');
    }
}
// END Customer Class

/* End of file customer.php */
/* Location: ./system/tomatocart/libraries/customer.php */