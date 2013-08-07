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
 * Customers Groups Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Customers_Groups_Model extends CI_Model
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
     * Get the customers groupds
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_groups($start = NULL, $limit = NULL)
    {
        $result = $this->db
            ->select('c.customers_groups_id, cg.language_id, cg.customers_groups_name,  c.customers_groups_discount, c.is_default')
            ->from('customers_groups c')
            ->join('customers_groups_description cg', 'c.customers_groups_id = cg.customers_groups_id')
            ->where('cg.language_id', lang_id())
            ->order_by('cg.customers_groups_name')
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
     * Save an customer group
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
        
        //editing or adding the customer group
        if (is_numeric($id))
        {
            $this->db->update('customers_groups', 
                              array('customers_groups_discount' => $data['customers_groups_discount'], 'is_default' => $data['is_default']), 
                              array('customers_groups_id' => $id));
        }
        else
        {
            $this->db->insert('customers_groups', array('customers_groups_discount' => $data['customers_groups_discount'], 'is_default' => $data['is_default']));
        }
        
        //check transaction status
        if ($this->db->trans_status() === FALSE)
        {
            $error = TRUE;
        }
        else
        {
            $group_id = is_numeric($id) ? $id : $this->db->insert_id();
        }
        
        if ($error === FALSE)
        {
            //process languages
            foreach(lang_get_all() as $l)
            {
                //editing or adding the customer group
                if (is_numeric($id))
                {
                    $this->db->update('customers_groups_description', 
                                      array('customers_groups_name' => $data['customers_groups_name'][$l['id']]), 
                                      array('customers_groups_id' => $group_id, 'language_id' => $l['id']));
                }
                else
                {
                    $this->db->insert('customers_groups_description', array('customers_groups_id' => $group_id, 
                                                                            'language_id' => $l['id'], 
                                                                            'customers_groups_name' => $data['customers_groups_name'][$l['id']]));
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
            //set the customer group as the default group
            if ($data['is_default'] == 1)
            {
                $this->db->update('customers_groups', array('is_default' => 0));
                
                if ($this->db->trans_status() === TRUE)
                {
                    $this->db->update('customers_groups', array('is_default' => 1), array('customers_groups_id' => $group_id));
                }
            }
            
            //check transaction status
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
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
     * Get data of a customer group
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
            ->select('cg.*, cgd.*')
            ->from('customers_groups cg')
            ->join('customers_groups_description cgd', 'cg.customers_groups_id = cgd.customers_groups_id')
            ->where(array('cg.customers_groups_id' => $id, 'cgd.language_id' => lang_id()))
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Check whether the customer group is used in the system
     *
     * @access public
     * @param $id
     * @return int
     */
    public function get_in_use($id)
    {
        return $this->db->from('customers')->where('customers_groups_id', $id)->count_all_results();
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete a customer group
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        //start transaction
        $this->db->trans_begin();
        
        $this->db->delete('customers_groups', array('customers_groups_id' => $id));
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('customers_groups_description', array('customers_groups_id' => $id));
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
     * Get the info of a customer group
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_info($id)
    {
        $result = $this->db
            ->select('c.customers_groups_id, cg.language_id, cg.customers_groups_name,  c.customers_groups_discount, c.is_default')
            ->from('customers_groups c')
            ->join('customers_groups_description cg', 'c.customers_groups_id = cg.customers_groups_id')
            ->where('c.customers_groups_id', $id)
            ->order_by('cg.customers_groups_name')
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get total number of customer groups
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->count_all('customers_groups');
    }
}

/* End of file customers_groups_model.php */
/* Location: ./system/models/customers_groups_model.php */