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
 * Product Variants Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Product_Variants_Model extends CI_Model
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
     * Get the variants groups
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_variants_groups($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('products_variants_groups_id, products_variants_groups_name')
            ->from('products_variants_groups')
            ->where('language_id', lang_id())
            ->order_by('products_variants_groups_name');
            
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
     * Get the total number of the variants values in the variants group
     *
     * @access public
     * @param $groups_id
     * @return int
     */
    public function get_total_entries($groups_id)
    {
        return $this->db->where('products_variants_groups_id', $groups_id)->from('products_variants_values_to_products_variants_groups')->count_all_results();
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the variants values in the variants group
     *
     * @access public
     * @param $groups_id
     * @return mixed
     */
    public function get_variants_entries($groups_id)
    {
        $result = $this->db
            ->select('pvv.products_variants_values_id, pvv.products_variants_values_name')
            ->from('products_variants_values pvv')
            ->join('products_variants_values_to_products_variants_groups pvv2pvg', 'pvv2pvg.products_variants_values_id = pvv.products_variants_values_id')
            ->where(array('pvv2pvg.products_variants_groups_id' => $groups_id, 'pvv.language_id' => lang_id()))
            ->order_by('pvv.products_variants_values_name')
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the data of the product variants value
     *
     * @access public
     * @param $id
     * @param $language_id
     * @return mixed
     */
    public function get_entry_data($id, $language_id = NULL)
    {
        $language_id = empty($language_id) ? lang_id() : $language_id;
        
        $result = $this->db
            ->select('*')
            ->from('products_variants_values')
            ->where(array('products_variants_values_id' => $id, 'language_id' => $language_id))
            ->get();
        
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();
            $result->free_result();
            
            $data['total_products'] = $this->db
                ->where('products_variants_values_id', $data['products_variants_values_id'])
                ->from('products_variants_entries')
                ->count_all_results();
            
            return $data;
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the product variants value from the variants group
     *
     * @access public
     * @param $id
     * @param $group_id
     * @return boolean
     */
    public function delete_entry($id, $group_id)
    {
        $error = FALSE;
        
        //start transaction
        $this->db->trans_begin();
        
        //delete the variants value
        $this->db->delete('products_variants_values', array('products_variants_values_id' => $id));
        
        //delete the variants value from the variants group
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('products_variants_values_to_products_variants_groups', 
                array('products_variants_groups_id' => $group_id, 
                      'products_variants_values_id' => $id));
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
     * Save the products variants value
     *
     * @access public
     * @param $id
     * @param $data
     * @return bolean
     */
    public function save_entry($id, $data)
    {
        $error = FALSE;
        
        //editing or adding the products variants value
        if (is_numeric($id))
        {
            $entry_id = $id;
        }
        else
        {
            $result = $this->db
                ->select_max('products_variants_values_id')
                ->from('products_variants_values')
                ->get();
            
            $max_values = $result->row_array();
            $entry_id = $max_values['products_variants_values_id'] + 1;
            
            $result->free_result();
        }
        
        //start transaction
        $this->db->trans_begin();
        
        //process languages
        foreach(lang_get_all() as $l)
        {
            //editing or adding the products variants value
            if (is_numeric($id))
            {
                $this->db->update('products_variants_values',
                    array('products_variants_values_name' => $data['name'][$l['id']]), 
                    array('products_variants_values_id' => $entry_id, 'language_id' => $l['id']));
            }
            else
            {
                $this->db->insert('products_variants_values',
                    array('products_variants_values_id' => $entry_id, 
                          'language_id' => $l['id'], 
                          'products_variants_values_name' => $data['name'][$l['id']]));
            }
            
            //check transaction status
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
                break;
            }
        }
        
        //attach the new products variants value with the variants group
        if ($error === FALSE)
        {
            if ( ! is_numeric($id))
            {
                $this->db->insert('products_variants_values_to_products_variants_groups',
                    array('products_variants_groups_id' => $data['products_variants_groups_id'], 
                          'products_variants_values_id' => $entry_id));

                //check transaction status
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
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
     * Get the data of the products variants value
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_entries_data($id)
    {
        $result = $this->db
            ->select('*')
            ->from('products_variants_values')
            ->where('products_variants_values_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
  
    // ------------------------------------------------------------------------
    
    /**
     * Get the data of the variants group
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_groups_data($id)
    {
        $result = $this->db
            ->select('*')
            ->from('products_variants_groups')
            ->where('products_variants_groups_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * save the variants group
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save($id = NULL, $data)
    {
        $error = FALSE;
        
        //editing or adding the product variants group
        if (is_numeric($id))
        {
            $group_id = $id;
        }
        else
        {
            $result = $this->db
                ->select_max('products_variants_groups_id')
                ->from('products_variants_groups')
                ->get();
            
            $max_groups = $result->row_array();
            $result->free_result();
            
            $group_id = $max_groups['products_variants_groups_id'] + 1;
        }
        
        //start transaction
        $this->db->trans_begin();
        
        //process languages
        foreach(lang_get_all() as $l)
        {
            //editing or adding the product variants group
            if (is_numeric($id))
            {
                $this->db->update('products_variants_groups',
                    array('products_variants_groups_name' => $data['name'][$l['id']]), 
                    array('products_variants_groups_id' => $group_id, 'language_id' => $l['id']));
            }
            else
            {
                $this->db->insert('products_variants_groups',
                    array('products_variants_groups_id' => $group_id, 
                          'language_id' => $l['id'], 
                          'products_variants_groups_name' => $data['name'][$l['id']]));
            }
            
            //check transaction status
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
                break;
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
     * delete the variants group
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $error = FALSE;
        
        //start transaction
        $this->db->trans_begin();
        
        $result = $this->db
            ->select('products_variants_values_id')
            ->from('products_variants_values_to_products_variants_groups')
            ->where('products_variants_groups_id', $id)
            ->get();
        
        //delete the variants values in this variants group
        if ($result->num_rows() > 0)
        {
            foreach($result->result_array() as $entry)
            {
                $this->db->delete('products_variants_values', array('products_variants_values_id' => $entry['products_variants_values_id']));
                
                //check transaction status
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
                    break;
                }
            }
            
            $result->free_result();
        }
        
        if ($error === FALSE)
        {
            $this->db->delete('products_variants_values_to_products_variants_groups', array('products_variants_groups_id' => $id));
            
            //check transaction status
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
            }
        }
        
        //delete the variants grpup
        if ($error === FALSE)
        {
            $this->db->delete('products_variants_groups', array('products_variants_groups_id' => $id));
            
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
     * Get the total number of variants products in the variants group
     *
     * @access public
     * @param $groups_id
     * @return int
     */
    public function get_group_products($groups_id)
    {
        return $this->db->where('products_variants_groups_id', $groups_id)->from('products_variants_entries')->count_all_results();
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the total number of variants groups
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->where('language_id', lang_id())->from('products_variants_groups')->count_all_results();
    }
}

/* End of file product_variants_model.php */
/* Location: ./system/models/product_variants_model.php */