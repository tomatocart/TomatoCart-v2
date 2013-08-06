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
 * Address_Book_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-account-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Address_Book_Model extends CI_Model
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
     * Save the address book
     *
     * @access public
     * @param array
     * @param int
     * @param int
     * @param boolean
     * @return boolean
     */
    public function save($data, $customers_id, $id = NULL, $primary = FALSE)
    {
        $result = FALSE;
        
        $gender = isset($data['gender']) ? $data['gender'] : $data['entry_gender'];
        $company = isset($data['company']) ? $data['company'] : $data['entry_company'];
        $firstname = isset($data['firstname']) ? $data['firstname'] : $data['entry_firstname'];
        $lastname = isset($data['lastname']) ? $data['lastname'] : $data['entry_lastname'];
        $street_address = isset($data['street_address']) ? $data['street_address'] : $data['entry_street_address'];
        $suburb = isset($data['suburb']) ? $data['suburb'] : $data['entry_suburb'];
        $postcode = isset($data['postcode']) ? $data['postcode'] : $data['entry_postcode'];
        $city = isset($data['city']) ? $data['city'] : $data['entry_city'];
        $state = isset($data['state']) ? $data['state'] : $data['entry_state'];
        $country = isset($data['country_id']) ? $data['country_id'] : $data['entry_country_id'];
        $zone_id = isset($data['zone_id']) ? $data['zone_id'] : $data['entry_zone_id'];
        $telephone = isset($data['telephone_number']) ? $data['telephone_number'] : $data['entry_telephone'];
        $fax = isset($data['fax']) ? $data['fax'] : $data['entry_fax'];
        
        //process data
        $address['customers_id'] = $this->customer->get_id();
        $address['entry_gender'] = ((config('ACCOUNT_GENDER') > -1) && isset($gender) && (($gender == 'm') || ($gender == 'f'))) ? $gender : '';
        $address['entry_company'] = (config('ACCOUNT_COMPANY') > -1) ? $company : '';
        $address['entry_firstname'] = $firstname;
        $address['entry_lastname'] = $lastname;
        $address['entry_street_address'] = $street_address;
        $address['entry_suburb'] = (config('ACCOUNT_SUBURB') > -1) ? $suburb : '';
        $address['entry_postcode'] = (config('ACCOUNT_POST_CODE') > -1) ? $postcode : '';
        $address['entry_city'] = $city;
        $address['entry_state'] = (config('ACCOUNT_STATE') > -1) ? ((isset($zone_id) && ($zone_id > 0)) ? '' : $state) : '';
        $address['entry_country_id'] = $country;
        $address['entry_zone_id'] = (config('ACCOUNT_STATE') > -1) ? ((isset($zone_id) && ($zone_id > 0)) ? $zone_id : 0) : '';
        $address['entry_telephone'] = (config('ACCOUNT_TELEPHONE') > -1) ? $telephone : '';
        $address['entry_fax'] = (config('ACCOUNT_FAX') > -1) ? $fax : '';
        
        //update or insert the address book
        if (is_numeric($id))
        {
            $result = $this->db->update('address_book', $address, array('address_book_id' => $id, 'customers_id' => (int) $customers_id));
        }
        else
        {
            $address['customers_id'] = (int) $customers_id;
            $result = $this->db->insert('address_book', $address);
        }
        
        //set the primary address
        if ($primary === TRUE)
        {
            if (is_numeric($id) === FALSE)
            {
                $id = $this->db->insert_id();
            }
            
            if ($this->set_primary_address($id, $customers_id))
            {
                //update the customer data
                $this->customer->set_country($data['entry_country_id']);
                $this->customer->set_zone($data['entry_zone_id']);
                $this->customer->set_default_address($id);
                
                $result = TRUE;
            }
        }
        
        return $result;
    }
    
    /**
     * Set the primary address
     *
     * @access public
     * @param int
     * @param int
     * @return boolean
     */
    public function set_primary_address($id, $customers_id)
    {
        if (is_numeric($id) && ($id > 0))
        {
            return $this->db->update('customers', array('customers_default_address_id' => (int) $id), array('customers_id' => (int) $customers_id));
        }
        
        return FALSE;
    }
    
    /**
     * Get the the address of the customer
     *
     * @access public
     * @param int
     * @param int
     * @return mixed
     */
    public function get_address($customers_id = NULL, $address_book_id)
    {
        $this->db->select('ab.address_book_id, ab.entry_gender as gender, ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_postcode as postcode, ab.entry_city as city, ab.entry_zone_id as zone_id, ab.entry_telephone as telephone, z.zone_code as zone_code, z.zone_name as zone_name, ab.entry_country_id as country_id, c.countries_name as countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format, ab.entry_state as state, ab.entry_fax as fax')
            ->from('address_book as ab')
            ->join('zones as z', 'ab.entry_zone_id = z.zone_id', 'left')
            ->join('countries as c', 'ab.entry_country_id = c.countries_id', 'left')
            ->where('ab.address_book_id', (int) $address_book_id);
            
        if ($customers_id !== NULL) {
            $this->db->where('ab.customers_id', (int) $customers_id);
        }
        
        $result = $this->db->get();

        $address_book = NULL;
        if ($result->num_rows() > 0)
        {
            $address_book = $result->row_array();
        }
        
        return $address_book;
    }
    
    /**
     * Get format of the address book
     *
     * @access public
     * @param mixed
     * @param string
     * @return string
     */
    public function format($address, $new_line = "\n")
    {
        $address_format = $address['address_format'];
        if (empty($address_format)) 
        {
            $address_format = ":name\n:street_address\n:postcode :city\n:country";
        }
        
        $find_array = array('/\:name\b/',
                            '/\:street_address\b/',
                            '/\:suburb\b/',
                            '/\:city\b/',
                            '/\:postcode\b/',
                            '/\:state\b/',
                            '/\:zone_code\b/',
                            '/\:country\b/');
        
        $replace_array = array(output_string_protected($address['firstname'] . ' ' . $address['lastname']),
                               output_string_protected($address['street_address']),
                               output_string_protected($address['suburb']),
                               output_string_protected($address['city']),
                               output_string_protected($address['postcode']),
                               output_string_protected($address['zone_name']),
                               output_string_protected($address['zone_code']),
                               output_string_protected($address['countries_name']));
                               
        $formated = preg_replace($find_array, $replace_array, $address_format);
        
        if ((config('ACCOUNT_COMPANY') > -1) && !empty($address['company'])) 
        {
            $formated = output_string_protected($address['company']) . $new_line . $formated;
        }
        
        if ($new_line != "\n") 
        {
            $formated = str_replace("\n", $new_line, $formated);
        }
        
        return $formated;
    }

    /**
     * Get the the addresses of the customer
     *
     * @access public
     * @param int
     * @return array
     */
    public function get_addresses($customers_id) 
    {
        $result = $this->db->select('ab.address_book_id, ab.entry_gender as gender, ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_postcode as postcode, ab.entry_city as city, ab.entry_zone_id as zone_id, ab.entry_telephone as telephone, z.zone_code as zone_code, z.zone_name as zone_name, ab.entry_country_id as country_id, c.countries_name as countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format, ab.entry_state as state, ab.entry_fax as fax')
            ->from('address_book as ab')
            ->join('zones as z', 'ab.entry_zone_id = z.zone_id', 'left')
            ->join('countries as c', 'ab.entry_country_id = c.countries_id', 'left')
            ->where('ab.customers_id', (int) $customers_id)
            ->get();

        if ($result->num_rows() > 0)
        {
            $address_books = array();
            foreach ($result->result_array() as $row)
            {
                $address_books[] = $row;
            }
            
            return $address_books;
        }

        return NULL;
    }

    /**
     * Get the country data
     *
     * @access public
     * @param int
     * @return array
     */
    public function get_country_data($countries_id)
    {
        $result = $this->db->select('countries_name, countries_iso_code_2, countries_iso_code_3, address_format')->from('countries')->where('countries_id', (int) $countries_id)->get();

        $country = NULL;
        if ($result->num_rows() > 0)
        {
            $country = $result->row_array();
        }

        return $country;
    }

    /**
     * Get the zone data
     *
     * @access public
     * @param int
     * @return array
     */
    public function get_zone_data($zone_id)
    {
        $result = $this->db->select('zone_code, zone_name')->from('zones')->where('zone_id', (int) $zone_id)->get();

        $zone = NULL;
        if ($result->num_rows() > 0)
        {
            $zone = $result->row_array();
        }

        return $zone;
    }
    
    /**
     * Get the number of address books for current customer
     *
     * @access public
     * @return int
     */
    public function number_of_entries($customers_id)
    {
        return $this->db->from('address_book')->where('customers_id', (int) $customers_id)->count_all_results();
    }
    
    /**
     * delete the address book
     *
     * @access public
     * @param int
     * @param int
     * @return bool
     */
    public function delete($address_book_id, $customers_id)
    {
        return $this->db->delete('address_book', array('address_book_id' => (int) $address_book_id, 'customers_id' => (int) $customers_id));
    }
    
    /**
     * check the address book
     *
     * @access public
     * @param int
     * @param int
     * @return boolean
     */
    public function check($address_book_id, $customers_id)
    {
        $result = $this->db
            ->select('address_book_id')
            ->from('address_book')
            ->where(array('address_book_id' => (int) $address_book_id, 'customers_id' => (int) $customers_id))
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
}
/* End of file address_book_model.php */
/* Location: ./system/tomatocart/models/address_book_model.php */