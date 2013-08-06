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
 * Newsletters Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Newsletters_Model extends CI_Model
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
     * Get the newsletters
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_newsletters($start, $limit)
    {
        $result = $this->db
        ->select('newsletters_id, title, length(content) as content_length, module, date_added, date_sent, status, locked')
        ->from('newsletters')
        ->order_by('date_added desc')
        ->limit($limit, $start)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }

// ------------------------------------------------------------------------

    /**
     * Delete the newsletter
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->trans_begin();
        
        $this->db->delete('newsletters', array('newsletters_id' => $id));
        
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('newsletters', array('newsletters_id' => $id));
        }
        
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Save the newsletter
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
            $this->db->update('newsletters', $data, array('newsletters_id' => $id));
        }
        else
        {
            $data['date_added'] = date('Y-m-d H:i:s');
            $data['status'] = 0;
            
            $this->db->insert('newsletters', $data);
        }
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the data of the newsletter
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
        ->select('*')
        ->from('newsletters')
        ->where('newsletters_id', $id)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the customers
     *
     * @access public
     * @return mixed
     */
    public function get_customers()
    {
        $result = $this->db
        ->select('customers_id, customers_firstname, customers_lastname, customers_email_address')
        ->from('customers')
        ->order_by('customers_lastname')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the total number of customers
     *
     * @access public
     * @param $newsletters_id
     * @param $customers_ids
     * @return int
     */
    public function get_total_customers($newsletters_id, $customers_ids)
    {
        $result = $this->query_customers($newsletters_id, $customers_ids);
        
        return $result->num_rows();
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the total number of customers
     *
     * @access public
     * @param $newsletters_id
     * @param $customers_ids
     * @return mixed
     */
    public function get_audiences($newsletters_id, $customers_ids)
    {
        $result = $this->query_customers($newsletters_id, $customers_ids);
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Log the email addresses for the newsletter that was sent out
     *
     * @access public
     * @param $newsletters_id
     * @param $email_address
     * @return boolean
     */
    public function log($newsletters_id, $email_address)
    {
        $this->db->insert('newsletters_log', array('newsletters_id' => $newsletters_id, 
                                                   'email_address' => $email_address, 
                                                   'date_sent' => date('Y-m-d H:i:s')));
        
        if ($this->db->affected_rows() === 1)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Update the date_sent field for the newsletter that was sent out successfully
     *
     * @access public
     * @param $newsletters_id
     * @return boolean
     */
    public function update($newsletters_id)
    {
        $this->db->update('newsletters', array('date_sent' => date('Y-m-d H:i:s'), 'status' => 1), 
                                         array('newsletters_id' => $newsletters_id));
                                         
        if ($this->db->affected_rows() === 1)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the logs
     *
     * @access public
     * @param $start
     * @param $limit
     * @param $newsletters_id
     * @return mixed
     */
    public function get_logs($start, $limit, $newsletters_id)
    {
        $result = $this->db
        ->select('email_address, date_sent')
        ->from('newsletters_log')
        ->where('newsletters_id', $newsletters_id)
        ->order_by('date_sent desc')
        ->limit($limit, $start)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get total number of the recipients of the newsletter
     *
     * @access public
     * @param $newsletters_id
     * @return int
     */
    public function get_newsletters_recipients($newsletters_id)
    {
        $result = $this->query_newsletters_recipients($newsletters_id);
        
        return $result->num_rows();
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get recipients of the newsletter
     *
     * @access public
     * @param $newsletters_id
     * @return mixed
     */
    public function get_recipients($newsletters_id)
    {
        $result = $this->query_newsletters_recipients($newsletters_id);
        
        if ($result->num_rows())
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the total number of the logs
     *
     * @access public
     * @param $newsletters_id
     * @return int
     */
    public function get_total_logs($newsletters_id)
    {
        return $this->db->where('newsletters_id', $newsletters_id)->from('newsletters_log')->count_all_results();
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the total number of the newsletters
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->count_all('newsletters');
    }
    
// ------------------------------------------------------------------------

    /**
     * Build the query to get the recipients
     * 
     * @access private
     * @param $newsletters_id
     * @return resource
     */
    private function query_newsletters_recipients($newsletters_id)
    {
       $result = $this->db
        ->select('c.customers_firstname, c.customers_lastname, c.customers_email_address')
        ->from('customers c')
        ->join('newsletters_log nl', 'c.customers_email_address = nl.email_address and nl.newsletters_id = ' . $newsletters_id, 'left')
        ->where('c.customers_newsletter', 1)
        ->where('nl.email_address is null')
        ->get();
        
        return $result;
    }
    
// ------------------------------------------------------------------------

    /**
     * Build the query to get customers
     * 
     * @access private
     * @param $newsletters_id
     * @param $customers_ids
     * @return resource
     */
    private function query_customers($newsletters_id, $customers_ids)
    {
        $this->db
        ->select('c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address')
        ->from('customers c')
        ->join('newsletters_log nl', 'c.customers_email_address = nl.email_address and nl.newsletters_id = ' . $newsletters_id, 'left');
        
        if (!in_array('***', $customers_ids))
        {
            $this->db->where_in('c.customers_id', $customers_ids);
        }
        
        $this->db->where('nl.email_address is null');
        
        $result = $this->db->get();
        
        return $result;
    }
}

/* End of file newsletters_model.php */
/* Location: ./system/models/newsletters_model.php */