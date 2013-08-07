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
 * Tax Classes Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Tax_Classes_Model extends CI_Model
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
     * Get the tax classes
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_tax_classes($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('tax_class_id, tax_class_title, tax_class_description, last_modified, date_added')
            ->from('tax_class')
            ->order_by('tax_class_title');
            
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
     * Get the total number of tax rates with the tax class id
     *
     * @access public
     * @param $id
     * @return int
     */
    public function get_total_rates($id)
    {
        return $this->db->where('tax_class_id', $id)->from('tax_rates')->count_all_results();
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Get the tax rates with the tax class id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_tax_rates($id)
    {
        $result = $this->db
            ->select('r.tax_rates_id, r.tax_priority, r.tax_rate, r.tax_description, r.date_added, r.last_modified, z.geo_zone_id, z.geo_zone_name')
            ->from('tax_rates r')
            ->join('geo_zones z', 'r.tax_zone_id = z.geo_zone_id')
            ->where('r.tax_class_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Get the data of the tax class with the id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
            ->select('*')
            ->from('tax_class')
            ->where('tax_class_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();
            $data['total_tax_rates'] = $this->db->where('tax_class_id', $id)->from('tax_rates')->count_all_results();
            
            return $data;
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Whether there is any product using the tax class
     *
     * @access public
     * @param $id
     * @return int
     */
    public function get_products($id)
    {
        $result = $this->db
            ->select('products_id')
            ->from('products')
            ->where('products_tax_class_id', $id)
            ->limit(1)
            ->get();
        
        return $result->num_rows();
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Delete the tax class with the id
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        //start transaction
        $this->db->trans_begin();
        
        //delete the tax rates
        $this->db->delete('tax_rates', array('tax_class_id' => $id));
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('tax_class', array('tax_class_id' => $id));
        }
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            //commit
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        //rollback
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Save the tax class with its id
     *
     * @access public
     * @param $id
     * #param $data
     * @return boolean
     */
    public function save($id = NULL, $data)
    {
        //editing or adding the tax class
        if (is_numeric($id))
        {
            $data['last_modified'] = date('Y-m-d H:i:s');
            $this->db->update('tax_class', $data, array('tax_class_id' => $id));
        }
        else
        {
            $data['date_added'] = date('Y-m-d H:i:s');
            $this->db->insert('tax_class', $data);
        }
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
  
    // ------------------------------------------------------------------------

    /**
     * Get the zone groups
     *
     * @access public
     * @return mixed
     */
    public function get_zones()
    {
        $result = $this->db
            ->select('geo_zone_id, geo_zone_name')
            ->from('geo_zones')
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Save the tax rate
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save_entry($id = NULL, $data)
    {
        //editing or adding the tax rate
        if (is_numeric($id))
        {
            $data['last_modified'] = date('Y-m-d H:i:s');
            
            $this->db->update('tax_rates', $data, array('tax_rates_id' => $id));
        }
        else
        {
            $data['date_added'] = date('Y-m-d H:i:s');
            
            $this->db->insert('tax_rates', $data);
        }
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Delete the tax rate with the id
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete_entry($id)
    {
        $this->db->delete('tax_rates', array('tax_rates_id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Get the data of the tax rate with the id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_entry_data($id)
    {
        $result = $this->db
            ->select('r.*, tc.tax_class_title, z.geo_zone_id, z.geo_zone_name')
            ->from('tax_rates r')
            ->join('tax_class tc', 'r.tax_class_id = tc.tax_class_id')
            ->join('geo_zones z', 'r.tax_zone_id = z.geo_zone_id')
            ->where('r.tax_rates_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the total number of tax classes
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->count_all('tax_class');
    }
}

/* End of file tax_classes_model.php */
/* Location: ./system/models/tax_classes_model.php */