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
 * Settings_Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Settings_Model extends CI_Model
{

	/**
	 * Constructor
	 *
	 * @access public
	 * @param string
	 */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Save setting
     *
     * @access public
     * @param $key
     * @param $value
     * @return boolean
     */
    public function save_setting($key, $value)
    {
        return $this->db->update('configuration', array('configuration_value' => $value), array('configuration_key' => $key));
    }
}
/* End of file settings_model.php */
/* Location: ./install/application/models/settings_model.php */