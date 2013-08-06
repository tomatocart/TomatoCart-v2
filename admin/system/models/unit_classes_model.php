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
 * Unit Classes Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Unit_Classes_Model extends CI_Model
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
     * Get the quantity unit classes
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_classes($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('quantity_unit_class_id,  quantity_unit_class_title')
            ->from('quantity_unit_classes')
            ->where('language_id', lang_id());
            
        if ($limit !== NULL && $start !== NULL)
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
     * Whether there is any product using the quantity unit class
     *
     * @access public
     * @param $id
     * @return int
     */
    public function get_total_products($id)
    {
        return $this->db->where('quantity_unit_class', $id)->from('products')->count_all_results();
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the quantity unit class
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->delete('quantity_unit_classes', array('quantity_unit_class_id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the quantity unit classes
     *
     * @access public
     * @param $id
     * @param $data
     * @param $default
     * @return boolean
     */
    public function save($id = NULL, $data, $default = FALSE)
    {
        $error = FALSE;
        
        //start transaction
        $this->db->trans_begin();
        
        //editing or adding the unit class
        if (is_numeric($id))
        {
            $unit_class_id = $id;
        }
        else
        {
            $result = $this->db->select_max('quantity_unit_class_id')->from('quantity_unit_classes')->get();
            
            $max_unit = $result->row_array();
            $result->free_result();
            
            $unit_class_id = $max_unit['quantity_unit_class_id'] + 1;
        }
        
        //languages
        foreach(lang_get_all() as $l)
        {
            //editing or adding the unit class
            if (is_numeric($id))
            {
                $this->db->update('quantity_unit_classes', 
                                  array('quantity_unit_class_title' => $data['unit_class_title'][$l['id']]), 
                                  array('quantity_unit_class_id' => $unit_class_id, 'language_id' => $l['id']));
            }
            else
            {
                $this->db->insert('quantity_unit_classes', 
                                  array('quantity_unit_class_id' => $unit_class_id, 
                                        'language_id' => $l['id'], 
                                        'quantity_unit_class_title' => $data['unit_class_title'][$l['id']]));
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
            if ($default === TRUE)
            {
                $this->db->update('configuration', array('configuration_value' => $unit_class_id), array('configuration_key' => 'DEFAULT_UNIT_CLASSES'));
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
     * Get the data of the quantity unity class with the id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_classes_infos($id)
    {
        $result = $this->db
            ->select('language_id, quantity_unit_class_title')
            ->from('quantity_unit_classes')
            ->where('quantity_unit_class_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the total number of the quantity unit classes
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->where('language_id', lang_id())->from('quantity_unit_classes')->count_all_results();
    }
}

/* End of file unit_classes_model.php */
/* Location: ./system/models/unit_classes_model.php */