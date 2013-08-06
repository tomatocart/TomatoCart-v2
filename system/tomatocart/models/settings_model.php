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
 * Departments_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-departments-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Settings_Model extends CI_Model
{
    
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }


    /**
     * Get the settings
     * Don't retrieves the language depending settings
     *
     * @return	The settings array
     */
    function get_settings()
    {
        $settings = array();

        $result = $this->db->select("configuration_key, configuration_value")->from("configuration")->get();
        foreach ($result->result_array() as $row){
            $settings[$row['configuration_key']] = $row['configuration_value'];
        }

        return $settings;
    }
}

/* End of file settings_model.php */
/* Location: ./system/tomatocart/models/settings_model.php */