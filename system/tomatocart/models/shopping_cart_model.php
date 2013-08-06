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
 * Shopping_Cart_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-departments-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Shopping_Cart_Model extends CI_Model
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
     * Get shopping cart contents
     * 
     * @access public
     * @param $customers_id
     * @return mixed
     */
    public function get_contents($customers_id)
    {
        $result = $this->db->select('products_id, customers_basket_quantity, customers_basket_date_added')->from('customers_basket')->where('customers_id', $customers_id)->get();

        $contents = NULL;
        if ($result->num_rows() > 0)
        {
            foreach ($result->result_array() as $row)
            {
                $contents[] = $row;
            }
        }

        return $contents;
    }
    
    /**
     * Get shopping cart content
     * 
     * @access public
     * @param $customers_id
     * @param $products_id_string
     * @return array
     */
    public function get_content($customers_id, $products_id_string) 
    {
        $result = $this->db->select('products_id, customers_basket_quantity')
                           ->from('customers_basket')
                           ->where('customers_id', $customers_id)
                           ->where('products_id', $products_id_string)
                           ->get();

        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
    /**
     * Update shopping cart content
     * 
     * @access public
     * @param $customers_id
     * @param $products_id_string
     * @param $quantity
     * @return array
     */
    public function update_content($customers_id, $products_id_string, $quantity) 
    {
        $this->db->where(array('customers_id' => $customers_id, 'products_id' => $products_id_string));
        $this->db->set('customers_basket_date_added', 'NOW()', FALSE);
        
        return $this->db->update('customers_basket', array('customers_basket_quantity' => $quantity));
    }
    
    /**
     * Insert shopping cart content
     * 
     * @access public
     * @param $customers_id
     * @param $products_id
     * @param $quantity
     * @param $price
     * @return boolean
     */
    public function insert_content($customers_id, $products_id, $quantity, $price)
    {
        $this->db->set('customers_basket_date_added', 'NOW()', FALSE);
        
        return $this->db->insert('customers_basket', array('customers_basket_quantity' => $quantity,
                                                           'customers_id' => $customers_id,
                                                           'products_id' => $products_id,
                                                           'final_price' => $price));
    }

    /**
     * Delete shopping cart content
     * 
     * @access public
     * @param $products_id
     * @return boolean
     */
    public function delete_content($customers_id, $products_id = NULL)
    {
        if ($products_id === NULL) 
        {
            return $this->db->delete('customers_basket', array('customers_id' => $customers_id));
        } 
        else 
        {
            return $this->db->delete('customers_basket', array('customers_id' => $customers_id, 'products_id' => $products_id));
        }
    }

    /**
     * Delete complete content
     * 
     * @access public
     * @param $customers_id
     * @return boolean
     */
    public function delete($customers_id)
    {
        return $this->db->delete('customers_basket', array('customers_id' => $customers_id));
    }
    
    /**
     * Get variant data

     * @param $products_id
     * @param $variant_group_id
     * @param $variant_value_id
     */
    public function get_variants_data($products_id, $variant_group_id, $variant_value_id) {
        $result = $this->db->select('pvg.products_variants_groups_name, pvv.products_variants_values_name')
                           ->from('products_variants pv')
                           ->join('products_variants_entries pve', 'pv.products_variants_id = pve.products_variants_id')
                           ->join('products_variants_groups pvg', 'pve.products_variants_groups_id = pvg.products_variants_groups_id')
                           ->join('products_variants_values pvv', 'pve.products_variants_values_id = pvv.products_variants_values_id')
                           ->where('pv.products_id', $products_id)
                           ->where('pve.products_variants_groups_id', $variant_group_id)
                           ->where('pve.products_variants_values_id', $variant_value_id)
                           ->where('pvg.language_id', lang_id())
                           ->where('pvv.language_id', lang_id())
                           ->get();

        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }

        return NULL;
    }
}

/* End of file shopping_cart_model.php */
/* Location: ./system/tomatocart/models/shopping_cart_model.php */