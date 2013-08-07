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
 * Customers Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-model
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com
 */
class Customers_Model extends CI_Model
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
    
    // --------------------------------------------------------------------

    /**
     * Get customers
     *
     * @access public
     * @param $start
     * @param $limit
     * @param $search
     * @return mixed
     */
    public function get_customers($start = NULL, $limit = NULL, $search = NULL)
    {
        $this->db
            ->select('c.customers_id, c.customers_credits, c.customers_gender, c.customers_lastname, c.customers_firstname, c.customers_email_address, c.customers_status, c.customers_ip_address, c.date_account_created, c.number_of_logons, c.date_last_logon, cgd.customers_groups_name')
            ->from('customers c')
            ->join('customers_groups_description cgd', 'c.customers_groups_id = cgd.customers_groups_id and cgd.language_id = ' . lang_id(), 'left');

        //keywords
        if ($search !== NULL)
        {
            $this->db->like('c.customers_lastname', $search)->or_like('c.customers_firstname', $search)->or_like('c.customers_email_address', $search);
        }
        
        $this->db->order_by('c.customers_lastname, c.customers_firstname');
        
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
    
    // --------------------------------------------------------------------

    /**
     * Delete customer
     *
     * @access public
     * @param $id
     * @param $delete_reviews
     * @return boolean
     */
    public function delete($id, $delete_reviews = TRUE)
    {
        //start transaction
        $this->db->trans_start();

        //delete reviews
        if ($delete_reviews === TRUE)
        {
            $this->db->delete('reviews', array('customers_id' => $id));
        }
        //update the customers id to NULL in the reviews table
        else
        {
            $result = $this->db->select('reviews_id')->from('reviews')->where('customers_id', $id)->get();

            if ($result->num_rows > 0)
            {
                $this->db->update('reviews', array('customers_id' => NULL), array('customers_id' => $id));
            }
            
            $result->free_result();
        }
        
        //delete address book
        $this->db->delete('address_book', array('customers_id' => $id));
        
        //delete customers credits history
        $this->db->delete('customers_credits_history', array('customers_id' => $id));

        //delete wishlist products
        $tbl_wishlist_products = $this->db->protect_identifiers('wishlists_products', TRUE);
        $tbl_wishlist = $this->db->protect_identifiers('wishlists', TRUE);

        $this->db->query('delete from ' . $tbl_wishlist_products . 'where wishlists_id = (select wishlists_id from ' . $tbl_wishlist . ' where customers_id = ?)', array((int) $id));

        //delete wishlist
        $this->db->delete('wishlists', array('customers_id' => $id));
        
        //delete customers basket
        $this->db->delete('customers_basket', array('customers_id' => $id));
        
        //delete products notifications
        $this->db->delete('products_notifications', array('customers_id' => $id));
        
        //delete customer
        $this->db->delete('customers', array('customers_id' => $id));
        
        //complete transaction
        $this->db->trans_complete();
        
        //check transaction status
        if ($this->db->trans_status() === FALSE)
        {
            return FALSE;
        } 
        
        return TRUE;
    }
    
    // --------------------------------------------------------------------

    /**
     * Set customer status
     *
     * @access public
     * @param $customers_id
     * @param $flag
     * @return boolean
     */
    public function set_status($customers_id, $flag)
    {
        return $this->db->update('customers', array('customers_status' => $flag), array('customers_id' => $customers_id));
    }
    
    // --------------------------------------------------------------------

    /**
     * Get totals
     * 
     * @access public
     * @param $search
     * @return int
     */
    public function get_totals($search)
    {
        if (empty($search))
        {
            return $this->db->count_all('customers');
        }
        else
        {
            $result = $this->db
                ->select('customers_id')
                ->from('customers')
                ->like('customers_lastname', $search)
                ->or_like('customers_firstname', $search)
                ->or_like('customers_email_address', $search)->get();

            return $result->num_rows();
        }
    }
    
    // --------------------------------------------------------------------

    /**
     * Get customers groups
     *
     * @access public
     * @return mixed
     */
    public function get_customers_groups()
    {
        $result = $this->db
            ->select('cg.customers_groups_id, cg.is_default, cgd.customers_groups_name')
            ->from('customers_groups cg')
            ->join('customers_groups_description cgd', 'cg.customers_groups_id = cgd.customers_groups_id', 'inner')
            ->where('language_id', lang_id())
            ->order_by('cg.customers_groups_id')
            ->get();

        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }
    
    // --------------------------------------------------------------------

    /**
     * Check customer account
     * 
     * @access public
     * @param $email_address
     * @param $customers_id
     * @return boolean
     */
    public function check($email_address, $customers_id = NULL)
    {
        $this->db
            ->select('customers_id')
            ->from('customers')
            ->where('customers_email_address', $email_address);

        if (is_numeric($customers_id))
        {
            $this->db->where('customers_id !=', $customers_id);
        }

        $result = $this->db->get();

        if ($result->num_rows() > 0) 
        {
            return TRUE;
        }

        return FALSE;
    }
    
    // --------------------------------------------------------------------

    /**
     * Save customer data
     *
     * @access public
     * @param $id
     * @param $data
     * @param $send_email
     * @return boolean
     */
    public function save($id = NULL, $data, $send_email = true)
    {
        //start transaction
        $this->db->trans_start();
        
        $customer = array('customers_gender' => $data['gender'],
                          'customers_firstname' => $data['firstname'], 
                          'customers_lastname' => $data['lastname'], 
                          'customers_email_address' => $data['email_address'], 
                          'customers_dob' => $data['dob'],
                          'customers_newsletter' => $data['newsletter'],
                          'customers_groups_id' => ( $data['customers_groups_id'] == '' ) ? NULL : $data['customers_groups_id'], 
                          'customers_status' => $data['status']);
        
        //update customer data
        if (is_numeric($id))
        {
            $customer['date_account_last_modified'] = date('Y-m-d');

            $this->db->update('customers', $customer, array('customers_id' => $id));
        }
        //insert customer data
        else
        {
            $customer['number_of_logons'] = 0;
            $customer['date_account_created'] = date('Y-m-d');

            $this->db->insert('customers', $customer);
        }
        
        //customers password
        if ( ! empty($data['customers_password']))
        {
            $customers_id = is_numeric($id) ? $id : $this->db->insert_id();
            $this->db->update('customers', array('customers_password' => encrypt_password($data['customers_password'])), array('customers_id' => $customers_id));
        }
        
        //complete transaction
        $this->db->trans_complete();
        
        //check transaction status
        if ($this->db->trans_status() === FALSE)
        {
            return FALSE;
        } 
        
        return TRUE;
    }
    
    // --------------------------------------------------------------------

    /**
     * Get customer data
     * 
     * @access public
     * @param $id
     * @param $key
     * @return mixed
     */
    public function get_data($id, $key = NULL)
    {
        $res_customer = $this->db
            ->select('c.*, cg.*, ab.*')
            ->from('customers c')
            ->join('address_book ab', 'c.customers_default_address_id = ab.address_book_id and c.customers_id = ab.customers_id', 'left')
            ->join('customers_groups_description cg', 'c.customers_groups_id = cg.customers_groups_id and cg.language_id = ' . lang_id(), 'left')
            ->where('c.customers_id', $id)
            ->get();
            
        if ($res_customer->num_rows() > 0) 
        {
            $data = $res_customer->row_array();
            $data['customers_full_name'] = $data['customers_firstname'] . ' ' . $data['customers_lastname'];

            //review
            $res_total = $this->db->select('count(*) as total')->from('reviews')->where('customers_id', $id)->get();
            
            if ($res_total->num_rows() > 0) 
            {
                $total = $res_total->row_array();
                
                $data['total_reviews'] = $total['total'];
            }

            //get specific value
            if ( ! empty($key))
            {
                return $data[$key];
            }
    
            return $data;
        }

        return NULL;
    }
    
    // --------------------------------------------------------------------

    /**
     * Get address book data
     * 
     * @access public
     * @param $customers_id
     * @param $address_book_id
     * @return mixed
     */
    public function get_addressbook_data($customers_id, $address_book_id = NULL)
    {
        $this->db
            ->select('ab.address_book_id, ab.entry_gender as gender, ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_city as city, ab.entry_postcode as postcode, ab.entry_state as state, ab.entry_zone_id as zone_id, ab.entry_country_id as country_id, ab.entry_telephone as telephone_number, ab.entry_fax as fax_number, z.zone_code as zone_code, c.countries_name as country_title')
            ->from('address_book ab')
            ->join('zones z', 'ab.entry_zone_id = z.zone_id', 'left')
            ->join('countries c', 'ab.entry_country_id = c.countries_id', 'left')
            ->where('ab.customers_id', $customers_id);

        if (is_numeric($address_book_id))
        {
            $this->db->where('ab.address_book_id', $address_book_id);
        }

        $result = $this->db->get();
        
        if ($result->num_rows() > 0)
        {
            //if get single address book
            if ( is_numeric($address_book_id) )
            {
                return $result->row_array();
            }
    
            return $result->result_array();
        }

        return NULL;
    }

    // --------------------------------------------------------------------
    
    /**
     * Delete address
     * 
     * @access public
     * @param $id
     * @param $customer_id
     * @return boolean
     */
    public function delete_address($id, $customer_id = null)
    {
        $where = array('address_book_id' => $id);

        if (is_numeric($customer_id)) {
            $where['customers_id'] = $customer_id;
        }

        return $this->db->delete('address_book', $where);
    }
    
    // --------------------------------------------------------------------

    /**
     * Get country zones
     * 
     * @access public
     * @param $country_id
     * @param $zone_code
     * @return mixed
     */
    public function get_zones($country_id, $zone_code)
    {
        $result = $this->db
            ->select('zone_id')
            ->from('zones')
            ->where(array('zone_country_id' => $country_id, 'zone_code' => $zone_code))
            ->get();
        
        if ($result->num_rows() > 0) 
        {
            return $result->result_array();
        }

        return NULL;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get state zones
     * 
     * @access public
     * @param $country_id
     * @return int
     */
    public function get_state_zones($country_id)
    {
        $result = $this->db
            ->select('zone_id')
            ->from('zones')
            ->where('zone_country_id', $country_id)
            ->get();
            
        return $result->num_rows();
    }

    // --------------------------------------------------------------------
    
    /**
     * Get zone likes
     * 
     * @access public
     * @param $country_id
     * @param $zone_name
     * @return mixed
     */
    public function get_zone_likes($country_id, $zone_name)
    {
        $result = $this->db
            ->select('zone_id')
            ->from('zones')
            ->where('zone_country_id', $country_id)
            ->like('zone_name', $zone_name, 'after')
            ->get();

        if ($result->num_rows() > 0) 
        {
            return $result->result_array();
        }
        
        return NULL;
    }

    // --------------------------------------------------------------------
    /**
     * Save address
     * 
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save_address($id = NULL, $data)
    {
        $insert_address_book_id = NULL;
        
        //start transaction
        $this->db->trans_start();

        $address_book = array('entry_gender' => $data['gender'],
                              'entry_company' => $data['company'], 
                              'entry_firstname' => $data['firstname'], 
                              'entry_lastname' => $data['lastname'], 
                              'entry_street_address' => $data['street_address'], 
                              'entry_suburb' => $data['suburb'], 
                              'entry_postcode' => $data['postcode'], 
                              'entry_city' => $data['city'], 
                              'entry_state' => $data['state'], 
                              'entry_country_id' => $data['country_id'], 
                              'entry_zone_id' => $data['zone_id'], 
                              'entry_telephone' => $data['telephone'], 
                              'entry_fax' => $data['fax']);

        //editing or adding the address book
        if (is_numeric($id))
        {
            $this->db->update('address_book', $address_book, array('address_book_id' => $id, 'customers_id' => $data['customer_id']));
        }
        else
        {
            $address_book['customers_id'] = $data['customer_id'];
            $this->db->insert('address_book', $address_book);
            
            $insert_address_book_id = $this->db->insert_id();
        }
        
        //if customer does not have default address then we set it
        $customer = $this->get_data($data['customer_id']);
        if (($customer['customers_default_address_id'] < 1) || ($data['primary'] === TRUE))
        {
            $address_book_id = is_numeric($id) ? $id : $insert_address_book_id;

            $this->db->update('customers', array('customers_default_address_id' => $address_book_id), array('customers_id' => $data['customer_id']));
        }

        //complete transaction
        $this->db->trans_complete();
        
        //check transaction status
        if ($this->db->trans_status() === FALSE)
        {
            return FALSE;
        } 
        
        return TRUE;
    }
}

/* End of file customers_model.php */
/* Location: ./system/models/customers_model.php */