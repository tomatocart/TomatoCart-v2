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
 * Zone Groups Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Zone_Groups_Model extends CI_Model
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
     * Get all the geo zones
     *
     * @access public
     * @return mixed
     */
    public function get_all_geo_zones()
    {
        $result = $this->db
            ->select('*')
            ->from('geo_zones')
            ->order_by('geo_zone_name')
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the geo zones
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_geo_zones($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('*')
            ->from('geo_zones')
            ->order_by('geo_zone_name');
        
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
     * Get total number of zone entries in the zone group
     *
     * @access public
     * @param $geo_zone_id
     * @return int
     */
    public function get_entries($geo_zone_id)
    {
        return $this->db->where('geo_zone_id', $geo_zone_id)->from('zones_to_geo_zones')->count_all_results();
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the info of the zone entries in the zone group
     *
     * @access public
     * @param $geo_zone_id
     * @return mixed
     */
    public function get_zone_entries_info($geo_zone_id)
    {
        $result = $this->db
            ->select('z2gz.association_id, z2gz.zone_country_id countries_id, c.countries_name, z2gz.zone_id, z2gz.geo_zone_id, z2gz.last_modified, z2gz.date_added, z.zone_name')
            ->from('zones_to_geo_zones z2gz')
            ->join('countries c', 'z2gz.zone_country_id = c.countries_id', 'left')
            ->join('zones z', 'z2gz.zone_id = z.zone_id', 'left')
            ->where('z2gz.geo_zone_id', $geo_zone_id)
            ->order_by('c.countries_name, z.zone_name')
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get all the countries
     *
     * @access public
     * @return mixed
     */
    public function get_countries()
    {
        $result = $this->db
            ->select('countries_name,countries_id')
            ->from('countries')
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the zones in the country
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_zones($id)
    {
        $result = $this->db
            ->select('zone_id,zone_name')
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
     * Save the zone in the zone group
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save_entry($id = NULL, $data)
    {
        if (is_numeric($id))
        {
            $this->db->update('zones_to_geo_zones',
                array('zone_country_id' => $data['country_id'],
                      'zone_id' => $data['zone_id'], 
                      'last_modified' => date('Y-m-d H:i:s')), 
                array('association_id' => $id));
        }
        else
        {
            $this->db->insert('zones_to_geo_zones',
                array('zone_country_id' => $data['country_id'],
                      'zone_id' => $data['zone_id'],
                      'geo_zone_id' => $data['group_id'],
                      'date_added' => date('Y-m-d H:i:s')));
        }

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }

        return FALSE;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Delete the zone
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete_entry($id)
    {
        $this->db->delete('zones_to_geo_zones', array('association_id' => $id));

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }

        return FALSE;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the data of the zone entry
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_entry_data($id)
    {
        $result = $this->db
            ->select('z2gz.*, c.countries_name, z.zone_name')
            ->from('zones_to_geo_zones z2gz')
            ->join('countries c', 'z2gz.zone_country_id = c.countries_id', 'left')
            ->join('zones z', 'z2gz.zone_id = z.zone_id', 'left')
            ->where('z2gz.association_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }

        return NULL;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Save the zone group
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save($id = NULL, $data)
    {
        if (is_numeric($id))
        {
            $this->db->update('geo_zones',
                array('geo_zone_name' => $data['zone_name'],
                      'geo_zone_description' => $data['zone_description'], 
                      'last_modified' => date('Y-m-d H:i:s')), 
                array('geo_zone_id' => $id));
        }
        else
        {
            $this->db->insert('geo_zones',
                array('geo_zone_name' => $data['zone_name'],
                      'geo_zone_description' => $data['zone_description'], 
                      'date_added' => date('Y-m-d H:i:s')));
        }

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }

        return FALSE;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get data of the zone group
     *
     * @access public
     * @param $geo_zone_id
     * @param $key
     * @return mixed
     */
    public function get_data($geo_zone_id, $key = NULL)
    {
        $result = $this->db
            ->select('*')
            ->from('geo_zones')
            ->where('geo_zone_id', $geo_zone_id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();
            
            $data['total_entries'] = $this->db->where('geo_zone_id', $geo_zone_id)->from('zones_to_geo_zones')->count_all_results();
            
            if ($key === NULL)
            {
                return $data;
            }
            else
            {
                return $data[$key];
            }
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Check the tax rates of zone group
     *
     * @access public
     * @param $id
     * @return int
     */
    public function get_tax_rates($id)
    {
        $result = $this->db
            ->select('tax_zone_id')
            ->from('tax_rates')
            ->where('tax_zone_id', $id)
            ->limit(1)
            ->get();

        return $result->num_rows();
    }
    
    // ------------------------------------------------------------------------

    /**
     * Delete the zone group
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        //start transaction
        $this->db->trans_begin();

        //delete the zone entries in the zone group
        $this->db->delete('zones_to_geo_zones', array('geo_zone_id' => $id));

        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            //delete the zone group
            $this->db->delete('geo_zones', array('geo_zone_id' => $id));
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
     * Get the total number of the zone groups
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->count_all('geo_zones');
    }
}

/* End of file zone_groups_model.php */
/* Location: ./system/models/zone_groups_model.php */