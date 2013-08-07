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
 * Reviews Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Reviews_Model extends CI_Model
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
     * Get the reviews
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_reviews($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('r.reviews_id, r.products_id, r.date_added, r.last_modified, r.reviews_rating, r.reviews_status, pd.products_name, l.code as languages_code')
            ->from('reviews r')
            ->join('products_description pd', 'r.products_id = pd.products_id and r.languages_id = pd.language_id', 'left')
            ->join('languages l', 'r.languages_id = l.languages_id')
            ->order_by('r.date_added desc');
            
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
     * Set the status of the review
     *
     * @access public
     * @param $id
     * @param $flag
     * @return bollean
     */
    public function set_status($id, $flag)
    {
        $this->db->update('reviews', array('reviews_status' => $flag), array('reviews_id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the data of the review
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
            ->select('r.*, pd.products_name')
            ->from('reviews r')
            ->join('products_description pd', 'r.products_id = pd.products_id and r.languages_id = pd.language_id')
            ->where('r.reviews_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------    
    
    /**
     * Get the average ratings
     *
     * @access public
     * @param $products_id
     * @return mixed
     */
    public function get_avg_rating($products_id)
    {
        $result = $this->db
            ->select_avg('reviews_rating')
            ->from('reviews')
            ->where('products_id', $products_id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the customers ratings
     *
     * @access public
     * @param $reviews_id
     * @return mixed
     */
    public function get_customers_ratings($reviews_id)
    {
        $result = $this->db
            ->select('r.customers_ratings_id, r.ratings_id, r.ratings_value, rd.ratings_text')
            ->from('customers_ratings r')
            ->join('ratings_description rd', 'r.ratings_id = rd.ratings_id')
            ->where(array('r.reviews_id' => $reviews_id, 'rd.languages_id' => lang_id()))
            ->order_by('r.customers_ratings_id')
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the review
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save($id, $data)
    {
        $error = FALSE;
        
        //start transaction
        $this->db->trans_begin();
        
        $this->db->update('reviews',
            array('reviews_text' => $data['review'], 
                  'reviews_rating' => $data['rating'], 
                  'reviews_status' => $data['reviews_status']), 
            array('reviews_id' => $id));

        //check transaction status
        if ($this->db->trans_status === FALSE)
        {
            $error = TRUE;
        }

        //process ratings
        if ($error === FALSE)
        {
            if ($data['ratings'] !== NULL)
            {
                foreach($data['ratings'] as $customers_ratins_id => $value)
                {
                    $this->db->update('customers_ratings',
                        array('ratings_value' => $value),
                        array('customers_ratings_id' => $customers_ratins_id));
                    
                    //check transaction status
                    if ($this->db->trans_status() === FALSE)
                    {
                        $error = TRUE;
                        break;
                    }
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
     * Delete the review
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        //start transaction
        $this->db->trans_begin();
        
        $this->db->delete('reviews', array('reviews_id' => $id));
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('customers_ratings', array('reviews_id' => $id));
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
     * Get the total number of reviews
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->count_all('reviews');
    }
}

/* End of file reviews.php */
/* Location: ./system/models/reviews_model.php */