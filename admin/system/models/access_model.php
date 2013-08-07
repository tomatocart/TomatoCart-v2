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
 * Access Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Access_Model extends CI_Model
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
     * Get the user levels
     *
     * @access public
     * @param $admin_id
     * @return array
     */
    public function get_user_levels($admin_id)
    {
        $modules = array();
        
        $result = $this->db
        ->select('module')
        ->from('administrators_access')
        ->where('administrators_id', $admin_id)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            foreach ($result->result_array() as $access)
            {
                $modules[]= $access['module'];
            }
        }
        
        $result->free_result();
        
        if ( in_array('*', $modules) )
        {
            $modules = array();
            
            $access_DirectoryListing = directory_map(APPPATH . 'modules/access', 1);
            
            foreach($access_DirectoryListing as $file)
            {
                $modules[] = substr($file, 0, strrpos($file, '.'));
            }
        }
        
        return $modules;
    }
}

/* End of file access_model.php */
/* Location: ./system/models/access_model.php */