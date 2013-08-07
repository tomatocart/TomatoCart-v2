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
 * Configurations Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Configurations_Model extends CI_Model
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
    
// ------------------------------------------------------------------------
    
    /**
     * Get the configurations
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_configurations($id)
    {
        $result = $this->db
        ->select('configuration_id, configuration_key, configuration_title, configuration_description, configuration_value, use_function, set_function')
        ->from('configuration')
        ->where('configuration_group_id', $id)
        ->order_by('sort_order')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Save the configurations
     *
     * @access public
     * @param $id
     * @param $value
     * @return boolean
     */
    public function save($id, $value)
    {
        $this->db->update('configuration', array('configuration_value' => $value) , array('configuration_id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
}

/* End of file configuration_model.php */
/* Location: ./system/models/configuration_model.php */