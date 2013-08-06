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
 * Navigation History
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	library-message_stack
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Navigation_History
{
    /**
     * ci instance
     *
     * @access private
     * @var object
     */
    private $ci = null;

    /**
     * data
     *
     * @access private
     * @var array
     */
    private $data = array();

    /**
     * Navigation History Constructor
     *
     * @access public
     * @return void
     */
    public function __construct($add_current_page = FALSE)
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        //data
        if ($this->ci->session->userdata('navigation_history_data') !== NULL)
        {
            $this->data = $this->ci->session->userdata('navigation_history_data');
        }

        //add current page
        if ($add_current_page === TRUE)
        {
            $this->add_current_page();
        }
    }

    /**
     * Add current page to navigation history
     *
     * @access public
     * @return void
     */
    public function add_current_page() {
        $this->data[] = array('page' => $this->ci->uri->uri_string(),
                              'get' => $this->ci->input->get(),
                              'post' => $this->ci->input->post());

        //set data to session
        $this->ci->session->set_userdata('navigation_history_data', $this->data);
    }

    /**
     * Remove current page
     *
     * @access public
     */
    function remove_current_page() {
        $last_entry_position = sizeof($this->data) - 1;

        if (($last_entry_position >= 0) && ($this->data[$last_entry_position]['page'] == $this->ci->uri->uri_string()))
        {
            unset($this->data[$last_entry_position]);

            if (sizeof($this->data) > 0)
            {
                //set data to session
                $this->ci->session->set_userdata('navigation_history_data', $this->data);
            }
            else
            {
                $this->reset_path();
            }
        }
    }

    /**
     * Has path
     *
     * @access public
     * @param $back back step
     * @return boolean
     */
    function has_path($back = 1) {
        if ( (is_numeric($back) === FALSE) || (is_numeric($back) && ($back < 1)) )
        {
            $back = 1;
        }

        return isset($this->data[sizeof($this->data) - $back]);
    }

    /**
     * Get Path URL
     *
     * @access public
     * @param $back back step
     * @param $exclude
     * @return boolean
     */
    function get_path_url($back = 1)
    {
        if ( (is_numeric($back) === FALSE) || (is_numeric($back) && ($back < 1)) )
        {
            $back = 1;
        }

        return $this->data[sizeof($this->data) - $back]['page'];
    }

    /**
     * Redirect To Path
     */
    function redirect_to_path($back = 1)
    {
        if ($this->has_path($back))
        {
            redirect($this->get_path_url($back));
        }
    }

    /**
     * Reset path
     */
    function reset_path()
    {
        $this->data = array();

        $this->ci->session->unset_userdata('navigation_history_data');
    }

    /**
     * Reset data
     */
    function reset()
    {
        $this->reset_path();
    }
}
// END Navigation_History

/* End of file navigation_history.php */
/* Location: ./system/tomatocart/libraries/navigation_history.php */