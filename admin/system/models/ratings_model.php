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
 * Ratings Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Ratings_Model extends CI_Model
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
     * Get the ratings
     *
     * @access public
     * @return mixed
     */
    public function get_ratings($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('r.ratings_id, r.status, rd.ratings_text')
            ->from('ratings r')
            ->join('ratings_description rd', 'r.ratings_id = rd.ratings_id')
            ->where('rd.languages_id', lang_id());
            
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
     * Set the status of the rating
     *
     * @access public
     * @param $id
     * @param $status
     * @return boolean
     */
    public function set_status($id, $status)
    {
        $this->db->update('ratings', array('status' => $status), array('ratings_id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the rating
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save($id = NULL, $data)
    {
        $error = FALSE;
        
        //start transaction
        $this->db->trans_begin();
        
        //editing or adding the rating
        if (is_numeric($id))
        {
            $this->db->update('ratings', array('status' => $data['status']), array('ratings_id' => $id));
        }
        else
        {
            $this->db->insert('ratings', array('status' => $data['status']));
        }
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            $ratings_id = is_numeric($id) ? $id : $this->db->insert_id();
            
            //process languages
            foreach(lang_get_all() as $l)
            {
                //editing or adding the rating
                if (is_numeric($id))
                {
                    $this->db->update('ratings_description', 
                        array('ratings_text' => $data['ratings_text'][$l['id']]), 
                        array('ratings_id' => $id, 'languages_id' => $l['id']));
                }
                else
                {
                    $this->db->insert('ratings_description', 
                        array('ratings_id' => $ratings_id, 
                              'languages_id' => $l['id'], 
                              'ratings_text' => $data['ratings_text'][$l['id']]));
                }
                
                //check transaction status
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
                    break;
                }
            }
        }
        
        if ($error === FALSE)
        {
            //commit
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        //rollback
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the rating
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        //start transaction
        $this->db->trans_begin();
        
        $this->db->delete('ratings', array('ratings_id' => $id));
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('ratings_description', array('ratings_id' => $id));
        }
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('categories_ratings', array('ratings_id' => $id));
        }
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('customers_ratings', array('ratings_id' => $id));
        }
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            //commit
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        //rollback
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the data of the ratings
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
            ->select('r.status, rd.ratings_text, rd.languages_id')
            ->from('ratings r')
            ->join('ratings_description rd', 'r.ratings_id = rd.ratings_id')
            ->where('r.ratings_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the total number of the ratings
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->count_all('ratings');
    }
}

/* End of file ratings_model.php */
/* Location: ./system/models/ratings_model.php */