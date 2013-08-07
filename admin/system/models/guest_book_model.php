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
 * Guest Book Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Guest_Book_Model extends CI_Model 
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
     * Get Guest Books
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_guest_books($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('guest_books_id, title, email, guest_books_status, gb.languages_id, content, date_added, l.code')
            ->from('guest_books gb')
            ->join('languages l', 'l.languages_id = gb.languages_id', 'inner')
            ->order_by('guest_books_id desc');
          
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
     * Get the data of one guest book
     * 
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
            ->select('*')
            ->from('guest_books')
            ->where('guest_books_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the data of one guestbook
     * 
     * @access public
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if (isset($data['guest_books_id']) && $data['guest_books_id'] > 0)
        {
            $this->db->update('guest_books', $data, array('guest_books_id' => $data['guest_books_id']));
        }
        else
        {
            $this->db->insert('guest_books', $data);
        }
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete guest book
     * 
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->delete('guest_books', array('guest_books_id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Batch delete guest books
     * 
     * @access public
     * @param $ids
     * @return boolean
     */
    public function batch_delete($ids)
    {
        $this->db->where_in('guest_books_id', $ids);
        $this->db->delete('guest_books');
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Set the status of one guestbook
     * 
     * @access public
     * @param $guest_book_id
     * @param $flag
     * @return boolean
     */
    public function set_status($guest_book_id, $flag)
    {
        $this->db->update('guest_books', array('guest_books_status' => $flag), array('guest_books_id' => $guest_book_id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the total number of guest books
     * 
     * @access public
     * @return int
     */
    public function get_totals()
    {
        return $this->db->count_all('guest_books');
    }
} 

/* End of file guest_book_model.php */
/* Location: ./system/models/guest_book_model.php */