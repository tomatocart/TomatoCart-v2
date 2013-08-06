<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize, creative CMS
 *
 * @package   Ionize
 * @author    Ionize Dev Team
 * @license   http://ionizecms.com/doc-license
 * @link    http://ionizecms.com
 * @since   Version 0.9.0
 */

// ------------------------------------------------------------------------

/**
 * Ionize, creative CMS Settings Model
 *
 * @package   Ionize
 * @subpackage  Models
 * @category  Admin settings
 * @author    Ionize Dev Team
 */

class Tax_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

    public function get_tax_class()
    {
        $result = $this->db
            ->select('tax_class_id, tax_class_title')
            ->from('tax_class')
            ->order_by('tax_class_title')
            ->get();
            
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
  
  public function get_tax_rate($zone_country_id, $zone_id, $tax_class_id)
  {
    $qry = $this->db
    ->select_sum('tax_rate')
    ->from('tax_rates as tr')
    ->join('zones_to_geo_zones as za', 'tr.tax_zone_id = za.geo_zone_id', 'left')
    ->join('geo_zones as tz', 'tz.geo_zone_id = tr.tax_zone_id', 'left')
    ->where('za.zone_country_id is null', NULL, FALSE)
    ->or_where('za.zone_country_id', 0)
    ->or_where('za.zone_country_id', $zone_country_id)
    ->where('za.zone_id is null', NULL, FALSE)
    ->or_where('za.zone_id', 0)
    ->or_where('za.zone_id', $zone_id)
    ->where('tr.tax_class_id = ' . $tax_class_id)
    ->group_by('tr.tax_priority')
    ->get();

    $tax_rates = FALSE;
    if ($qry->num_rows() > 0)
    {
      $tax_rates = $qry->result_array();
    }

    return $tax_rates;
  }
  
  public function get_tax_rate_description($zone_country_id, $zone_id, $tax_class_id)
  {
    $qry = $this->db
    ->select('tax_description')
    ->from('tax_rates as tr')
    ->join('zones_to_geo_zones as za', 'tr.tax_zone_id = za.geo_zone_id', 'left')
    ->join('zones_geo_zones as tz', 'tz.geo_zone_id = tr.tax_zone_id', 'left')
    ->where('za.zone_country_id is null', NULL, FALSE)
    ->or_where('za.zone_country_id', 0)
    ->or_where('za.zone_country_id', $zone_country_id)
    ->where('za.zone_id is null', NULL, FALSE)
    ->or_where('za.zone_id', 0)
    ->or_where('za.zone_id', $zone_id)
    ->where('tr.tax_class_id = ' . $tax_class_id)
    ->group_by('tr.tax_priority')
    ->get();
              
    $descriptions = FALSE;
    if ($qry->num_rows() > 0) 
    {
      $descriptions = $qry->result_array();
    }
    
    return $descriptions;
  }
  
}

/* End of file tax_model.php */
/* Location: admin/system/models/tax_model.php */