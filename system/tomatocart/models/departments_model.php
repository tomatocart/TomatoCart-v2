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

class Departments_Model extends CI_Model
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
     * Get the list of departments
     *
     * @access public
     * @return array
     */
    public function get_listing() 
    {
        $result = $this->db
            ->select('d.departments_id, dd.departments_title, d.departments_email_address, dd.departments_description')
            ->from('departments d')
            ->join('departments_description dd', 'd.departments_id = dd.departments_id', 'inner')
            ->where('dd.languages_id', lang_id())
            ->order_by('dd.departments_title')
            ->get();
        
        return $result->result_array();
    }
}

/* End of file departments_model.php */
/* Location: ./system/tomatocart/models/departments_model.php */