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
 * Account Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-model
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Account_Model extends CI_Model
{

    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get customer data via email address
     *
     * @access public
     * @param $email
     * @return mixed
     */
    public function get_data($email_address)
    {
        $result = $this->db->select('c.*, date_format(c.customers_dob, "%Y-%m-%d") as dob_days, ab.address_book_id, ab.entry_gender, ab.entry_company, ab.entry_firstname, ab.entry_lastname, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_state, ab.entry_country_id, ab.entry_zone_id, ab.entry_telephone, ab.entry_fax, cg.customers_groups_discount')
            ->from('customers as c')
            ->join('customers_groups as cg', 'c.customers_groups_id = cg.customers_groups_id', 'left')
            ->join('address_book as ab', 'c.customers_id = ab.customers_id AND ab.address_book_id = c.customers_default_address_id', 'left')
            ->where('customers_email_address', $email_address)
            ->get();
        
        $data = NULL;
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();
        }
        
        return $data;
    }
    
    /**
     * Check whether the emails have already been used by other customer
     * 
     * @access public
     * @param $email
     * @return boolean
     */
    public function check_duplicate_entry($email, $customers_id = NULL)
    {
        if ($customers_id == NULL) {
            $result = $this->db
                ->select('customers_id')
                ->from('customers')
                ->where(array('customers_email_address' => $email))
                ->limit(1)
                ->get();
        } else {
            $result = $this->db
                ->select('customers_id')
                ->from('customers')
                ->where(array('customers_email_address' => $email, 'customers_id !=' => $customers_id))
                ->limit(1)
                ->get();
        }

        
        if ($result->num_rows() == 1)
        {
            return TRUE;
        }
        
        return FALSE;
    }

    /**
     * Check user account
     *
     * @access public
     * @param $email email address
     * @param $password user password
     * @return boolean
     */
    public function check_account($email, $password)
    {
        $data = $this->get_data($email);

        if ($data !== FALSE)
        {
            $stack = explode(':', $data['customers_password']);

            if (sizeof($stack) === 2)
            {
                if (md5($stack[1] . $password) == $stack[0])
                {
                    return TRUE;
                }
            }
        }

        return FALSE;
    }
    /**
     * check customer email status
     * 
     * @access public
     * @param string
     * @return boolean
     */
    public function status_check($email)
    {
        $data = $this->get_data($email);

        if ($data !== FALSE)
        {
            return ($data['customers_status'] == '1') ? TRUE : FALSE;
        }
    }

    /**
     * Get store credit
     * 
     * @access public
     * @param $customers_id
     * @return mixed
     */
    public function get_store_credit($customers_id)
    {
        $result = $this->db->select('customers_credits')->from('customers')->where('customers_id', (int) $customers_id)->get();

        $store_credit = FALSE;
        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            $store_credit = $row['customers_credits'];
        }

        return $store_credit;
    }

    /**
     * Get customer address
     * 
     * @access public
     * @param $customers_id
     * @param $address_id
     * @return mixed
     */
    public function get_address($customers_id, $address_id)
    {
        $result = $this->db
        ->select('*')
        ->from('address_book')
        ->where('customers_id', (int) $customers_id)
        ->where('address_book_id', (int) $address_id)
        ->get();

        $data = FALSE;
        if ($query->num_rows() > 0)
        {
            $data = $query->row_array();
        }

        return $data;
    }

    /**
     * Insert customer data
     * 
     * @access public
     * @param $data
     * @return boolean
     */
    public function insert($data)
    {
        return $this->db->insert('customers', $data);
    }
    
    /**
     * Save customer data
     * 
     * @access public
     * @param $data
     * @return boolean
     */
    public function save($data, $customers_id)
    {
        $data['date_account_last_modified'] = now();
        
        return $this->db->update('customers', $data, array('customers_id' => (int) $customers_id));
    }

    /**
     * Update customer last logon
     * 
     * @access public
     * @param int $id
     * @return boolean
     */
    public function update_last_logon($id) 
    {
        return $this->db->update('customers', array( 'date_last_logon' => 'now', 'number_of_logons' => 'number_of_logons+1'), array('customers_id' => (int) $id));
    }
    
    /**
     * Update customers newsletter
     * 
     * @access public
     * @param string
     * @param int
     * @return boolean
     */
    public function update_customers_newsletter($newsletter, $customers_id)
    {
        return $this->db->update('customers', array('customers_newsletter' => $newsletter), array('customers_id' => (int) $customers_id));
    }
    
    /**
     * Save password
     * 
     * @param $customers_id
     * @param $password
     */
    public function save_password($customers_id, $password) {
        return $this->db->update('customers', array('customers_password' => encrypt_password($password), 'date_account_last_modified' => 'now()'), array('customers_id' => (int) $customers_id));
    }
}

/* End of file account_model.php */
/* Location: ./system/tomatocart/models/account_model.php */