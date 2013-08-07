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
 * Products Expected Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
Class Products_Expected_Model extends CI_Model
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
     * Get the expected products
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_products($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('p.products_id, p.products_date_available, pd.products_name')
            ->from('products p')
            ->join('products_description pd', 'p.products_id = pd.products_id')
            ->where('pd.language_id', lang_id())
            ->where('p.products_date_available IS NOT NULL')
            ->order_by('p.products_date_available');
        
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
     * Save the data avaialble of the expected product
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save_date_available($id, $data)
    {
        $this->db->update('products', array('products_date_available' => (date('Y-m-d') < $data['date_available'] ? $data['date_available'] : null)), 
                                      array('products_id' => $id));
                                      
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the data of the exepected product
     *
     * @access public
     * @param $id
     * @return array
     */
    public function get_data($id) 
    {
        $data = array();
        
        $result = $this->db
            ->select('p.*, pd.*, ptoc.*')
            ->from('products p')
            ->join('products_description pd', 'p.products_id = pd.products_id', 'left')
            ->join('products_to_categories ptoc', 'ptoc.products_id = p.products_id', 'left')
            ->where(array('p.products_id' => $id, 'pd.language_id' => lang_id()))
            ->get();
            
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();
        }
        
        return $data;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the total number of expected products
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        $result = $this->db
            ->select('p.products_id')
            ->from('products p')
            ->join('products_description pd', 'p.products_id = pd.products_id')
            ->where('pd.language_id', lang_id())
            ->where('p.products_date_available IS NOT NULL')
            ->get();
        
        return $result->num_rows();
    }
}

/* End of file products_expected_model.php */
/* Location: ./system/models/products_expected_model.php */