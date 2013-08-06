<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Tax Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-model
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Tax_Model extends CI_Model
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
     * Get tax rate
     * 
     * @access public
     * @param $zone_country_id
     * @param $zone_id
     * @param $tax_class_id
     * @return
     */
    public function get_tax_rate($zone_country_id, $zone_id, $tax_class_id)
    {
        $qry = $this->db
            ->select('sum(tax_rate) as tax_rate')
            ->from('tax_rates as tr')
            ->join('zones_to_geo_zones as za', 'tr.tax_zone_id = za.geo_zone_id', 'left')
            ->join('geo_zones as tz', 'tz.geo_zone_id = tr.tax_zone_id', 'left')
            ->where('(za.zone_country_id is null or za.zone_country_id = 0 or za.zone_country_id = ' . (int) $zone_country_id . ')')
            ->where('(za.zone_id is null or za.zone_id = 0 or za.zone_id = ' . (int) $zone_id . ')')
            ->where('tr.tax_class_id', (int) $tax_class_id)
            ->group_by('tr.tax_priority')
            ->get();
            
        $tax_rates = NULL;
        if ($qry->num_rows() > 0)
        {
            $tax_rates = $qry->result_array();
        }

        return $tax_rates;
    }

    /**
     * Get tax rate description
     * 
     * @access public
     * @param $zone_country_id
     * @param $zone_id
     * @param $tax_class_id
     * @return
     */
    public function get_tax_rate_description($zone_country_id, $zone_id, $tax_class_id)
    {
        $qry = $this->db
            ->select('tax_description')
            ->from('tax_rates as tr')
            ->join('zones_to_geo_zones as za', 'tr.tax_zone_id = za.geo_zone_id', 'left')
            ->join('geo_zones as tz', 'tz.geo_zone_id = tr.tax_zone_id', 'left')
            ->where('(za.zone_country_id is null or za.zone_country_id = 0 or za.zone_country_id = ' . (int) $zone_country_id . ')')
            ->where('(za.zone_id is null or za.zone_id = 0 or za.zone_id = ' . (int) $zone_id . ')')
            ->where('tr.tax_class_id = ' . (int) $tax_class_id)
            ->group_by('tr.tax_priority')
            ->get();

        $descriptions = NULL;
        if ($qry->num_rows() > 0)
        {
            $rows = $qry->result_array();
            
            foreach ($rows as $row)
            {
                $descriptions[] = $row['tax_description'];
            }
        }

        return $descriptions;
    }

}

/* End of file tax_model.php */
/* Location: ./system/tomatocart/models/tax_model.php */