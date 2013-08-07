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
 * Address Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-address-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Address_Model extends CI_Model
{

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get countries
     *
     * @access public
     * @return array
     */
    public function get_countries()
    {
        $result = $this->db->select('*')->from('countries')->order_by('countries_name')->get();

        if ($result->num_rows() > 0)
        {
            $countries = array();
            foreach ($result->result_array() as $row)
            {
                $countries[] = array('id' => $row['countries_id'],
                                     'name' => $row['countries_name'],
                                     'iso_2' => $row['countries_iso_code_2'],
                                     'iso_3' => $row['countries_iso_code_3'],
                                     'format' => $row['address_format']);
            }

            return $countries;
        }

        return NULL;
    }
     
    /**
     * Get country name
     *
     * @access public
     * @param $id
     * @return string
     */
    public function get_country_name($id)
    {
        $result = $this->db->select('countries_name')->from('countries')->where('countries_id', $id)->get();

        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            return $data['countries_name'];
        }

        return NULL;
    }

    /**
     * Get country iso code2
     *
     * @access public
     * @param $id
     * @return string
     */
    public function get_country_iso_code2($id)
    {
        $result = $this->db->select('countries_iso_code_2')->from('countries')->where('countries_id', $id)->get();

        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            return $data['countries_iso_code_2'];
        }

        return NULL;
    }

    /**
     * Get country iso code3
     *
     * @access public
     * @param $id
     * @return string
     */
    public function get_country_iso_code3($id)
    {
        $result = $this->db->select('countries_iso_code_3')->from('countries')->where('countries_id', $id)->get();

        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            return $data['countries_iso_code_3'];
        }

        return NULL;
    }

    /**
     * Get format
     *
     * @access public
     * @param $id
     * @return string
     */
    public function get_format($id)
    {
        $result = $this->db->select('address_format')->from('countries')->where('countries_id', $id)->get();

        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            return $data['address_format'];
        }

        return NULL;
    }

    /**
     * Get zone name
     *
     * @access public
     * @param $id
     * @return string
     */
    public function get_zone_name($id)
    {
        $result = $this->db->select('zone_name')->from('zones')->where('zone_id', $id)->get();

        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            return $data['zone_name'];
        }

        return NULL;
    }

    /**
     * Get zone code
     *
     * @access public
     * @param $id
     * @return string
     */
    public function get_zone_code($id)
    {
        $result = $this->db->select('zone_code')->from('zones')->where('zone_id', $id)->get();

        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            return $data['zone_code'];
        }

        return NULL;
    }

    /**
     * Get zones
     *
     * @access public
     * @param $id
     * @return array
     */
    function get_zones($id = null)
    {
        $zones_array = array();

        $this->db->select('z.zone_id, z.zone_code, z.zone_name, z.zone_country_id, c.countries_name')
                  ->from('zones z')
                  ->join('countries c', 'z.zone_country_id = c.countries_id', 'inner');
         
        if (!empty($id)) {
            $this->db->where('z.zone_country_id', $id);
        }

        $result = $this->db->order_by('c.countries_name, z.zone_name')->get();

        if ($result->num_rows() > 0)
        {
            $zones_array = $result->result_array();
        }
        
        return $zones_array;
    }

    /**
     * Get states
     *
     * @access public
     * @param int
     * @return array
     */
    public function get_states($countries_id)
    {
        $result = $this->db->select('zone_code, zone_name')->from('zones')->where('zone_country_id', (int) $countries_id)->order_by('zone_name')->get();

        if ($result->num_rows() > 0)
        {
            $states = array();
            foreach ($result->result_array() as $row)
            {
                $states[] = array('id' => $row['zone_code'], 'text' => $row['zone_name']);
            }

            return $states;
        }

        return NULL;
    }

    /**
     * Check whether a country has zones
     *
     * @access public
     * @param int
     * @return boolean
     */
    public function has_zones($countries_id)
    {
        $result = $this->db->select('zone_id')->from('zones')->where('zone_country_id', (int) $countries_id)->get();

        return ($result->num_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Get zone id from countries_id & state code
     *
     * @access public
     * @param int
     * @param string
     * @return mixed
     */
    public function get_zone_id($countries_id, $state)
    {
        $zone_id = NULL;

        $result = $this->db->select('zone_id')->from('zones')->where('zone_country_id', (int) $countries_id)->where('zone_code', $state)->get();

        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            $zone_id = $row['zone_id'];
        }
        else
        {
            $result = $this->db->select('zone_id')->from('zones')->where('zone_country_id', (int) $countries_id)->where('zone_code', $state, 'after')->get();

            if ($result->num_rows() > 0)
            {
                $row = $result->row_array();
                $zone_id = $row['zone_id'];
            }
        }

        return $zone_id;
    }

    /**
     * Get zone id via geo zone
     *
     * @access public
     * @param int
     * @param int
     * @return mixed
     */
    public function get_zone_id_via_geo_zone($country_id, $geo_zone_id)
    {
        $result = $this->db->select('zone_id')->from('zones_to_geo_zones')->where('zone_country_id', (int) $country_id)->where('geo_zone_id', (int) $geo_zone_id)->get();

        $zones = NULL;
        if ($result->num_rows() > 0)
        {
            foreach ($result->result_array() as $row)
            {
                $zones[] = $row['zone_id'];
            }
        }

        return $zones;
    }
}
/* End of file address_model.php */
/* Location: ./system/tomatocart/models/address_model.php */