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
 * Extensions_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-departments-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Extensions_Model extends CI_Model
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
     * Get the installed modules of the specified groups
     *
     * @access public
     * @param $group
     * @return	The modules
     */
    public function get_modules($group)
    {
        $result = $this->db->select('code')->from('extensions')->where('modules_group', $group)->get();

        $modules = FALSE;
        foreach($result->result_array() as $row)
        {
            $modules[] = $row['code'];
        }

        return $modules;
    }

    /**
     * Get the installed modules of the specified groups
     *
     * @access public
     * @param $group
     * @param $code
     * @return mixed
     */
    public function get_module($group, $code)
    {
        $result = $this->db->select('params')->from('extensions')->where('modules_group', $group)->where('code', $code)->get();

        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            return $data;
        }

        return FALSE;
    }

    /**
     * Install extension module
     *
     * @access public
     * @param $data
     * @return boolean
     */
    public function install($data) {
        return $this->db->insert('extensions', $data);
    }
}
/* End of file extensions_model.php */
/* Location: ./system/tomatocart/models/extensions_model.php */