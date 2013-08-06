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

class Specials_Model extends CI_Model
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
     * Get special products
     * 
     * @access public
     * @return array
     */
    public function get_special_products() {
        $result = $this->db->select('p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, pd.products_keyword, s.specials_new_products_price as special_price, i.image ')
            ->from('products p')
            ->join('products_images i', 'p.products_id = i.products_id', 'left')
            ->join('products_description pd', 'p.products_id = pd.products_id', 'inner')
            ->join('specials s', 's.products_id = p.products_id', 'inner')
            ->where('p.products_status = 1')
            ->where('pd.language_id', lang_id())
            ->where('i.default_flag = 1')
            ->where('s.status = 1')
            ->order_by('s.specials_date_added desc')
            ->limit(config('MAX_DISPLAY_SPECIAL_PRODUCTS'))
            ->get();

        $products = array();
        if ($result->num_rows() > 0)
        {
            $products = $result->result_array();
        }

        return $products;
    }
}

/* End of file specials_model.php */
/* Location: ./system/tomatocart/models/specials_model.php */