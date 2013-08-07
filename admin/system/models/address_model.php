<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource
 */

class Address_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_address($address_id)
  {
    $Qaddress = $this->db
    ->select('ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_city as city, ab.entry_postcode as postcode, ab.entry_state as state, ab.entry_zone_id as zone_id, ab.entry_country_id as country_id, z.zone_code as zone_code, c.countries_name as country_title')
    ->from('address_book ab')
    ->join('zones z', 'ab.entry_zone_id = z.zone_id', 'left')
    ->join('countries c', 'ab.entry_country_id = c.countries_id', 'left')
    ->where('ab.address_book_id', $address_id)
    ->get();
    
    return $Qaddress->row_array();
  }
  
  public function get_zone_info($zone_id)
  {
    $Qzone = $this->db
    ->select('zone_name, zone_code')
    ->from('zones')
    ->where('zone_id', $zone_id)
    ->get();
    
    return $Qzone->row_array();
  }
  
  public function get_country_name($id)
  {
    $Qcountry = $this->db
    ->select('countries_name, address_format')
    ->from('countries')
    ->where('countries_id', $id)
    ->get();
    
    return $Qcountry->row_array();
  }
  
  public function get_countries()
  {
    $Qcountries = $this->db
    ->select('*')
    ->from('countries')
    ->order_by('countries_name')
    ->get();
    
    return $Qcountries->result_array();
  }
  
  public function get_zones($id = NULL)
  {
    $this->db
    ->select('z.zone_code, z.zone_id, z.zone_country_id, z.zone_name, c.countries_name')
    ->from('zones z')
    ->join('countries c', 'z.zone_country_id = c.countries_id');
    
    if (!empty($id))
    {
      $this->db->where('z.zone_country_id', $id);
    }
    
    $Qzones = $this->db
    ->order_by('c.countries_name, z.zone_name')
    ->get();
    
    return $Qzones->result_array();
  }
}

/* End of file address_model.php */
/* Location: ./system/models/address_model.php */