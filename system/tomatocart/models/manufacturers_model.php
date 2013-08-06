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
 * Manufacturers Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-manufacturers-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Manufacturers_Model extends CI_Model
{
    
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get manufacturers
     *
     * @access public
     * @return array
     */
    public function get_manufacturers($categories_id = null)
    {
        if (isset($categories_id) && !empty($categories_id)) {
            $result = $this->db
                ->select('m.manufacturers_id id, m.manufacturers_name name, m.manufacturers_image image')
                ->from('products p')
                ->join('products_to_categories p2c', 'p.products_id = p2c.products_id', 'inner')
                ->join('manufacturers m', 'p.manufacturers_id = m.manufacturers_id', 'inner')
                ->where('p.products_status', 1)
                ->where_in('p2c.categories_id', $categories_id)
                ->order_by('manufacturers_name')
                ->distinct()
                ->get();
        } else {
            $result = $this->db
                ->select('manufacturers_id, manufacturers_name, manufacturers_image')
                ->from('manufacturers')
                ->order_by('manufacturers_name')
                ->get();
        }
        
        if ($result->num_rows() > 0) 
        {
            return $result->result_array();
        }
        
        return NULL;
    }
}


/* End of file manufacturers_model.php */
/* Location: ./system/tomatocart/models/manufacturers_model.php */