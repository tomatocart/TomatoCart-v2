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
 * Countries Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Countries_Model extends CI_Model
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
     * Get the countries
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_countries($start = NULL, $limit = NULL)
    {
        $this->db
        ->select('countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format')
        ->from('countries');
        
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
     * Get the total zone in the country
     *
     * @access public
     * @return int
     */
    public function get_total_zones($id)
    {
        return $this->db->where('zone_country_id', $id)->from('zones')->count_all_results();
    }
  
    // ------------------------------------------------------------------------

    /**
     * Get the zones with the country id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_zones($id)
    {
        $result = $this->db
            ->select('zone_id,zone_code,zone_name')
            ->from('zones')
            ->where('zone_country_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Check the total number of the address books with the country id
     *
     * @access public
     * @param $id
     * @return int
     */
    public function check_address_book($id)
    {
        return $this->db->where('entry_country_id', $id)->from('address_book')->count_all_results();
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Check the total number of the geo zones with the country id
     *
     * @access public
     * @param $id
     * @return int
     */
    public function check_geo_zones($id)
    {
        return $this->db->where('zone_country_id', $id)->from('zones_to_geo_zones')->count_all_results();
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Delete the country with the country id
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        //start transaction
        $this->db->trans_begin();
        
        //delete the zones in the country
        $this->db->delete('zones', array('zone_country_id' => $id));
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            //delete the country
            $this->db->delete('countries', array('countries_id' => $id));
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
     * Get the total number of the address books with the zone id
     *
     * @access public
     * @param $id
     * @return int
     */
    public function get_zone_address_books($id)
    {
        return $this->db->where('entry_zone_id', $id)->from('address_book')->count_all_results();
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Get the total number of geo zones with the zone id
     *
     * @access public
     * @param $id
     * @return int
     */
    public function get_zone_geo_zones($id)
    {
        return $this->db->where('zone_id', $id)->from('zones_to_geo_zones')->count_all_results();
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Delete the zone with the zone id
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete_zone($id)
    {
        $this->db->delete('zones', array('zone_id' => $id));
        
        if ($this->db->affected_rows() >0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Get the info of the zones that will be deleted with the zone ids
     *
     * @access public
     * @param $zones_ids
     * @return mixed
     */
    public function get_delete_zones($zones_ids)
    {
        $result = $this->db
            ->select('zone_id, zone_name, zone_code')
            ->from('zones')
            ->where_in('zone_id', $zones_ids)
            ->order_by('zone_name')
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Save the country
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save($id = NULL, $data)
    {
        if (is_numeric($id) && $id > 0)
        {
            $this->db->update('countries', $data, array('countries_id' => $id));
        }
        else
        {
            $this->db->insert('countries', $data);
        }
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Load the country with country id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
            ->select('*')
            ->from('countries')
            ->where('countries_id', $id)
            ->get();
        
        $total_zones = $this->db->where('zone_country_id', $id)->from('zones')->count_all_results();
        
        if ($result->num_rows() > 0)
        {
            $data = array_merge($result->row_array(), array('total_zones' => $total_zones));
            
            return $data;
        }
        
        return NULL;
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Save the zone in the country
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save_zone($id = NULL, $data)
    {
        if (is_numeric($id) && $id > 0)
        {
            $this->db->update('zones', $data, array('zone_id' => $id));
        }
        else
        {
            $this->db->insert('zones', $data);
        }
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Get the data of the zone with the zone id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_zone_data($id)
    {
        $result = $this->db
            ->select('*')
            ->from('zones')
            ->where('zone_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Get the total number of the countries
     *
     * @access public
     * @return int
     */
    public function get_totals()
    {
        return $this->db->count_all('countries');
    }
}

/* End of file countries_model.php */
/* Location: ./system/models/countries_model.php */