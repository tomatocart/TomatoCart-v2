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
 * Specials Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
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
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the special products
     *
     * @access public
     * @param $start
     * @param $limit
     * @param $search
     * @param $manufacturers_id
     * @param $in_categories
     * @return mixed
     */
    public function get_specials($start = NULL, $limit = NULL, $search = NULL, $manufacturers_id = NULL, $in_categories = array())
    {
        if (count($in_categories) > 0)
        {
            $this->db
                ->select('p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status')
                ->from('specials s')
                ->join('products p', 'p.products_id = s.products_id')
                ->join('products_description pd', 'p.products_id = pd.products_id')
                ->join('products_to_categories p2c', 'p.products_id = p2c.products_id')
                ->where('pd.language_id', lang_id())
                ->where_in('p2c.categories_id', $in_categories);
        }
        else
        {
            $this->db
                ->select('p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status')
                ->from('specials s')
                ->join('products p', 'p.products_id = s.products_id')
                ->join('products_description pd', 'p.products_id = pd.products_id')
                ->where('pd.language_id', lang_id());
        }
        
        if ($search !== NULL)
        {
            $this->db->like('pd.products_name', $search);
        }
        
        if (is_numeric($manufacturers_id))
        {
            $this->db->where('p.manufacturers_id', $manufacturers_id);
        }
        
        $this->db->order_by('pd.products_name');
        
        if ($start !== NULL && $limit !== NULL)
        {
            $this->db->limit($limit, $start);
        }
        
        $result = $this->db->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the special product
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save($id = NULL, $data)
    {
        $error = FALSE;
        
        $result = $this->db
            ->select('products_price')
            ->from('products')
            ->where('products_id', $data['products_id'])
            ->get();
        
        $product = $result->row_array();
        
        $result->free_result();
        
        $specials_price = $data['specials_price'];
        
        if (substr($specials_price, -1) == '%')
        {
            $specials_price = $product['products_price'] - (((double)$specials_price / 100) * $product['products_price']);
        }
        
        //verify the speical price
        if (($specials_price < '0.00') || (isset($product['products_price']) && $specials_price >= $product['products_price']))
        {
            $error = TRUE;
        }
        
        //verify the expires date
        if ($data['expires_date'] < $data['start_date'])
        {
            $error = TRUE;
        }
        
        if ($error === FALSE)
        {
            //editing or adding the specials
            if (is_numeric($id))
            {
                $this->db->update('specials', array('specials_new_products_price' => $specials_price, 
                                                    'specials_last_modified' => date('Y-m-d H:i:s'), 
                                                    'expires_date' => $data['expires_date'], 
                                                    'start_date' => $data['start_date'], 
                                                    'status' => $data['status']), 
                                              array('specials_id' => $id));
            }
            else
            {
                $this->db->insert('specials', array('products_id' => $data['products_id'], 
                                                    'specials_new_products_price' => $specials_price, 
                                                    'specials_date_added' => date('Y-m-d H:i:s'), 
                                                    'expires_date' => $data['expires_date'], 
                                                    'start_date' => $data['start_date'], 
                                                    'status' => $data['status']));
            }
            
            if ($this->db->affected_rows() < 1)
            {
                $error = TRUE;
            }
        }
        
        if ($error === FALSE)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the products
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_products($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('p.products_id, pd.products_name, p.products_tax_class_id')
            ->from('products p')
            ->join('products_description pd', 'p.products_id = pd.products_id')
            ->where(array('pd.language_id' => lang_id(), 'p.products_type !=' => PRODUCT_TYPE_GIFT_CERTIFICATE))
            ->order_by('pd.products_name');
        
        if ($start !== NULL && $limit !== NULL)
        {
            $this->db->limit($limit, $start);
        }
        
        $result = $this->db->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the special product with id
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->delete('specials', array('specials_id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the data of the special product
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
            ->select('p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.start_date, s.expires_date, s.date_status_change, s.status')
            ->from('specials s')
            ->join('products p', 's.products_id = p.products_id')
            ->join('products_description pd', 'p.products_id = pd.products_id')
            ->where(array('s.specials_id' => $id, 'pd.language_id' => lang_id()))
            ->limit(1)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the total number of products
     *
     * @access public
     * @return int
     */
    public function get_total_products()
    {
        return $this->db
                   ->where('products_type !=', PRODUCT_TYPE_GIFT_CERTIFICATE)
                   ->from('products')
                   ->count_all_results();
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the total number of speical products
     *
     * @access public
     * @return int
     */
    public function get_total($search = NULL, $manufacturers_id = NULL, $in_categories = array())
    {
        if (count($in_categories) > 0)
        {
            $this->db
                ->select('p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status')
                ->from('specials s')
                ->join('products p', 'p.products_id = s.products_id')
                ->join('products_description pd', 'p.products_id = pd.products_id')
                ->join('products_to_categories p2c', 'p.products_id = p2c.products_id')
                ->where('pd.language_id', lang_id())
                ->where_in('p2c.categories_id', $in_categories);
        }
        else
        {
            $this->db
                ->select('p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status')
                ->from('specials s')
                ->join('products p', 'p.products_id = s.products_id')
                ->join('products_description pd', 'p.products_id = pd.products_id')
                ->where('pd.language_id', lang_id());
        }
        
        if ($search !== NULL)
        {
            $this->db->like('pd.products_name', $search);
        }
        
        if (is_numeric($manufacturers_id))
        {
            $this->db->where('p.manufacturers_id', $manufacturers_id);
        }
        
        $result = $this->db->get();
        
        return $result->num_rows();
    }
}

/* End of file specials_model.php */
/* Location: ./system/models/specials_model.php */