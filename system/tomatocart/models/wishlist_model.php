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
 * Wishlist_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-departments-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Wishlist_Model extends CI_Model
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
     * insert a new wishlist for a customers
     *
     * @access public
     * @param int $customers_id
     * @param string $wishlists_token
     * @return
     */
    public function insert_wishlist($customers_id, $wishlists_token)
    {
        $this->db->set('customers_id',$customers_id);
        $this->db->set('wishlists_token',$wishlists_token);

        if ($this->db->insert('wishlists'))
        {
            return $this->db->insert_id();
        }

        return NULL;
    }

    /**
     * add a product to a wishlist
     *
     * @param int $wishlists_id
     * @param int $products_id
     * @param string $comments
     */
    public function add_product_to_wishlist($wishlists_id, $products_id, $comments = NULL)
    {
        $this->db->set('wishlists_id', $wishlists_id);
        $this->db->set('products_id', $products_id);
        $this->db->set('date_added', 'now()', FALSE);
        $this->db->set('comments', empty($comments) ? '' : $comments);

        return $this->db->insert('wishlists_products');
    }

    /**
     * Delete wishlist product
     *
     * @access public
     * @param $wishlists_id
     * @param $products_id
     * @return boolean
     */
    public function delete_product($wishlists_id, $products_id = NULL)
    {
        $this->db->where('wishlists_id', $wishlists_id);

        if($products_id !== NULL)
        {
            $this->db->where('products_id',$products_id);
        }

        return $this->db->delete('wishlists_products');
    }

    /**
     * update a wishlist product comment by id
     *
     * @access public
     * @param int $wishlist_id
     * @param int $products_id
     * @param string $comment
     * @return void
     */
    public function update_wishlist_product($wishlists_id, $products_id, $comment)
    {
        $this->db->set('comments', $comment);
        $this->db->where('wishlists_id', $wishlists_id);
        $this->db->where('products_id', $products_id);

        return $this->db->update('wishlists_products');
    }

    /**
     * Get wishlist by customers id
     *
     * @access public
     * @return void
     */
    public function get_wishlist_by_customers_id($customers_id)
    {
        $this->db->where('customers_id', $customers_id);
        $result = $this->db->get('wishlists');

        if($result->num_rows() > 0)
        {
            return $result->row_array();
        }

        return NULL;
    }

    /**
     * Update wishlist
     *
     * @access public
     * @param $wishlist_id
     * @param $customers_id
     * @param $token
     * @return void
     */
    public function update_wishlist($wishlists_id, $customers_id, $token)
    {
        $this->db->set('customers_id', $customers_id);
        $this->db->set('wishlists_token', $token);
        $this->db->where('wishlists_id', $wishlists_id);

        return $this->db->update('wishlists');
    }

    /**
     * get all products in the wishlist
     * @param int $wishlist_id
     * @return boolean
     */
    public function get_wishlist_products($wishlists_id)
    {
        $result = $this->db->select()->from('wishlists_products')->where('wishlists_id', $wishlists_id)->get();

        if($result->num_rows() > 0)
        {
            return $result->row_array();
        }

        return NULL;
    }

    /**
     * Delete wishlist
     *
     * @access public
     * @param $wishlists_id
     * @return boolean
     */
    public function delete_wishlist($wishlists_id)
    {
        $this->db->where('wishlists_id', $wishlists_id);
        return $this->db->delete('wishlists');
    }

    /**
     * get wishlist by customer token
     * @param string $token
     */
    public function get_wishlist_by_token($token)
    {
        $this->db->where('wishlists_token', $token);
        
        $result = $this->db->get('wishlists');
        if($result->num_rows() > 0){
            return $result->first_row('array');
        }else{
            return FALSE;
        }
    }

    /**
     * Get all products in the wishlist
     *
     * @param int $wishlist_id
     * @return boolean
     */
    public function get_products_by_token($token)
    {
        $result = $this->db->select('products_id, date_added, comments')->from('wishlists w')->join('wishlists_products p', 'w.wishlists_id = p.wishlists_id')->where('wishlists_token', $token)->get();

        if($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }
}
/* End of file wishlist_model.php */
/* Location: ./system/tomatocart/models/wishlist_model.php */