<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package      TomatoCart
 * @author       TomatoCart Dev Team
 * @copyright    Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html
 * @link         http://tomatocart.com
 * @since        Version 2.0
 * @filesource
*/

// ------------------------------------------------------------------------

/**
 * New Products Content Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class New_Products_Model extends CI_Model
{
    /**
     * New Products Content Model Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get Latest Products
     *
     * @access public
     * @param int $count number of products to be displayed
     * @return array new products array
     */
    public function get_new_products($count) 
    {
        $result = $this->db->select('p.*, pd.*, m.*, i.image, s.specials_new_products_price as specials_price, f.products_id as featured_products_id')
                           ->from('products as p')
                           ->join('products_description as pd', 'p.products_id = pd.products_id and pd.language_id =' . lang_id(), 'inner')
                           ->join('products_to_categories as p2c', 'p.products_id = p2c.products_id', 'inner')
                           ->join('products_frontpage as f', 'p.products_id = f.products_id', 'left')
                           ->join('products_images as i', 'p.products_id = i.products_id and i.default_flag = 1', 'left')
                           ->join('categories as c', 'p2c.categories_id = c.categories_id', 'inner')
                           ->join('specials as s', 'p.products_id = s.products_id and s.status = 1 and s.start_date <= now() and s.expires_date >= now()', 'left')
                           ->join('manufacturers as m', 'p.manufacturers_id = m.manufacturers_id', 'left')
                           ->join('manufacturers_info as mi', 'm.manufacturers_id = mi.manufacturers_id and mi.languages_id = ' . lang_id(), 'left')
                           ->where('p.products_status', 1)
                           ->order_by('p.products_id', 'desc')
                           ->limit($count)
                           ->get();

        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
}

/* End of file mod.new_products_content.php */
/* Location: ./system/tomatocart/modules/new_products_content/mod.new_products_content.php */