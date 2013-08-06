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
 * Popular Search Term Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Popular_Search_Terms_Model extends CI_Model
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
    public function get_popular_search_terms()
    {
        $result = $this->db->select('search_terms_id, text, search_count')->from('search_terms')->where('show_in_terms', 1)->get();

        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }
}

/* End of file model.popular_search_term.php */
/* Location: ./system/tomatocart/modules/new_products_content/model.popular_search_term.php */