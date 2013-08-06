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
 * Address Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Address 
{
    /**
     * Cached data
     *
     * @access private
     * @var object
     */
    private $ci = NULL;
    
    /**
     * Default constructor
     *
     * @access public
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci = & get_instance();

        $this->ci->load->model('address_model');
    }

    public function format($address, $new_line = "\n")
    {
        $this->ci->load->helper('string');

        $address_format = '';

        if (is_numeric($address))
        {
            $address = $this->ci->address_model->get_address($address);
        }

        $firstname = $lastname = '';

        if (isset($address['firstname']) && !empty($address['firstname'])) {
            $firstname = $address['firstname'];
            $lastname = $address['lastname'];
        } elseif (isset($address['name']) && !empty($address['name'])) {
            $firstname = $address['name'];
        }

        $state = $address['state'];
        $state_code = $address['zone_code'];

        if (isset($address['zone_id']) && is_numeric($address['zone_id']) && ($address['zone_id'] > 0)) {
            $zone_info = $this->ci->address_model->get_zone_info($address['zone_id']);
            $state = $zone_info['zone_name'];
            $state_code = $zone_info['zone_code'];
        }

        $country = $address['country_title'];

        if (isset($address['country_id']) && is_numeric($address['country_id']) && ($address['country_id'] > 0))
        {
            $country_info = $this->ci->address_model->get_country_name($address['country_id']);
        }

        if (empty($country) && isset($address['country_id']) && is_numeric($address['country_id']) && ($address['country_id'] > 0)) {
            $country = $country_info['countries_name'];
        }

        if (isset($address['format'])) {
            $address_format = $address['format'];
        } elseif (isset($address['country_id']) && is_numeric($address['country_id']) && ($address['country_id'] > 0)) {
            $address_format = $country_info['address_format'];
        }

        if (empty($address_format)) {
            $address_format = ":name\n:street_address\n:postcode :city\n:country";
        }

        $find_array = array('/\:name\b/',
                          '/\:street_address\b/',
                          '/\:suburb\b/',
                          '/\:city\b/',
                          '/\:postcode\b/',
                          '/\:state\b/',
                          '/\:state_code\b/',
                          '/\:country\b/');

        $replace_array = array(output_string_protected($firstname . ' ' . $lastname),
        output_string_protected($address['street_address']),
        output_string_protected($address['suburb']),
        output_string_protected($address['city']),
        output_string_protected($address['postcode']),
        output_string_protected($state),
        output_string_protected($state_code),
        output_string_protected($country));
         
        $formated = preg_replace($find_array, $replace_array, $address_format);

        if ( (ACCOUNT_COMPANY > -1) && !empty($address['company']) ) {
            $company = output_string_protected($address['company']);

            $formated = $company . $new_line . $formated;
        }

        if ($new_line != "\n") {
            $formated = str_replace("\n", $new_line, $formated);
        }

        return $formated;
    }

    public function get_countries()
    {
        $countries = $this->ci->address_model->get_countries();

        $result = array();
        if (!empty($countries))
        {
            foreach($countries as $country)
            {
                $result[] = array('id' => $country['countries_id'],
                          'name' => $country['countries_name'],
                          'iso_2' => $country['countries_iso_code_2'],
                          'iso_3' => $country['countries_iso_code_3'],
                          'format' => $country['address_format']);
            }
        }

        return  $result;
    }

    public function get_zones($country_id = NULL)
    {
        $zones = $this->ci->address_model->get_zones($country_id);

        $result = array();
        if (!empty($zones))
        {
            foreach($zones as $zone)
            {
                $result[] = array('id' => $zone['zone_id'],
                          'code' => $zone['zone_code'],
                          'name' => $zone['zone_name'], 
                          'country_id' => $zone['zone_country_id'], 
                          'country_name' => $zone['countries_name']);
            }
        }

        return $result;
    }

    public function get_country_name($id)
    {
        $country_info = $this->ci->address_model->get_country_name($id);

        if (!empty($country_info))
        {
            return $country_info['countries_name'];
        }

        return FALSE;
    }

    public function get_zone_name($id)
    {
        $zone_info = $this->ci->address_model->get_zone_info($id);

        if (!empty($zone_info))
        {
            return $zone_info['zone_name'];
        }

        return FALSE;

    }
}


/* End of file address.php */
/* Location: ./system/library/address.php */