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
 * Weight Classes Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Weight_Classes_Model extends CI_Model
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
     * Get the weight classes
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_classes($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('weight_class_id, weight_class_key, weight_class_title')
            ->from('weight_classes')
            ->where('language_id', lang_id())
            ->order_by('weight_class_title');
        
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
     * Get the weight classes rules
     *
     * @access public
     * @return mixed
     */
    public function get_rules()
    {
        $result = $this->db
            ->select('weight_class_id, weight_class_title')
            ->from('weight_classes')
            ->where('language_id', lang_id())
            ->order_by('weight_class_title')
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
  
    // ------------------------------------------------------------------------
    
    /**
     * Save the weight class
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
        
        //editing or adding the weight class
        if (is_numeric($id))
        {
            $weight_class_id = $id;
        }
        else
        {
            $result = $this->db
                ->select_max('weight_class_id')
                ->from('weight_classes')
                ->get();
            
            $max_weight_class = $result->row_array();
            
            $result->free_result();
            
            $weight_class_id = $max_weight_class['weight_class_id'] + 1;
        }
      
        //languages
        foreach(lang_get_all() as $l)
        {
            //editing or adding the weight class
            if (is_numeric($id))
            {
                $this->db->update('weight_classes', 
                    array('weight_class_key' => $data['key'][$l['id']], 
                          'weight_class_title' => $data['name'][$l['id']]),
                    array('weight_class_id' => $weight_class_id, 
                          'language_id' => $l['id']));
            }
            else
            {
                $this->db->insert('weight_classes',
                    array('weight_class_id' => $weight_class_id, 
                          'language_id' => $l['id'], 
                          'weight_class_key' => $data['key'][$l['id']], 
                          'weight_class_title' => $data['name'][$l['id']]));
            }
            
            //check transaction status
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
                break;
            }
        }
      
        //process weiht class rules
        if ($error === FALSE)
        {
            //editing or adding the weight class
            if (is_numeric($id))
            {
                $result = $this->db
                    ->select('weight_class_to_id')
                    ->from('weight_classes_rules')
                    ->where(array('weight_class_from_id' => $weight_class_id, 'weight_class_to_id !=' => $weight_class_id))
                    ->get();
                
                if ($result->num_rows() > 0)
                {
                    foreach($result->result_array() as $rule)
                    {
                        $this->db->update('weight_classes_rules', 
                            array('weight_class_rule' => $data['rules'][$rule['weight_class_to_id']]), 
                            array('weight_class_from_id' => $weight_class_id, 
                                  'weight_class_to_id' => $rule['weight_class_to_id']));
                                          
                        //check transaction status
                        if ($this->db->trans_status() === FALSE)
                        {
                            $error = TRUE;
                            break;
                        }
                    }
                }
                
                $result->free_result();
            }
            else
            {
                $result = $this->db
                    ->select('weight_class_id')
                    ->from('weight_classes')
                    ->where(array('weight_class_id !=' => $weight_class_id, 'language_id' => lang_id()))
                    ->get();
              
                if ($result->num_rows() > 0)
                {
                    foreach($result->result_array() as $class)
                    {
                        $this->db->insert('weight_classes_rules', 
                            array('weight_class_from_id' => $class['weight_class_id'], 
                                  'weight_class_to_id' => $weight_class_id, 
                                  'weight_class_rule' => '1'));
                                          
                        //check transaction status
                        if ($this->db->trans_status() === FALSE)
                        {
                            $error = TRUE;
                            break;
                        }
                        
                        if ($error === FALSE)
                        {
                            $this->db->insert('weight_classes_rules', 
                                              array('weight_class_from_id' => $weight_class_id, 
                                                    'weight_class_to_id' => $class['weight_class_id'], 
                                                    'weight_class_rule' => $data['rules'][$class['weight_class_id']]));
                                              
                            //check transaction status
                            if ($this->db->trans_status() === FALSE)
                            {
                                $error = TRUE;
                                break;
                            }
                        }
                    }
                }
                
                $result->free_result();
            }
        }
      
        //handle configuration
        if ($error === FALSE)
        {
            if ($default === TRUE)
            {
                $this->db->update('configuration', 
                    array('configuration_value' => $weight_class_id), 
                    array('configuration_key' => 'SHIPPING_WEIGHT_UNIT'));
                                  
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
     * Get data of the weight class with the id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_infos($id)
    {
        $result = $this->db
            ->select('language_id, weight_class_id, weight_class_key, weight_class_title')
            ->from('weight_classes')
            ->where('weight_class_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get rules of the weight class with the id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_rules_infos($id)
    {
        $result = $this->db
            ->select('r.weight_class_to_id as weight_class_id, r.weight_class_rule, c.weight_class_title, c.weight_class_key')
            ->from('weight_classes_rules r')
            ->join('weight_classes c', 'r.weight_class_to_id = c.weight_class_id')
            ->where(array('r.weight_class_from_id' => $id, 'r.weight_class_to_id !=' => $id, 'c.language_id' => lang_id()))
            ->order_by('c.weight_class_title')
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the weight class with its id
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        //start transaction
        $this->db->trans_begin();
        
        $this->db->where('weight_class_from_id', $id)->or_where('weight_class_to_id', $id)->delete('weight_classes_rules');
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('weight_classes', array('weight_class_id' => $id));
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
     * Whether the weight class is in use
     *
     * @access public
     * @param $weight_classes_id
     * @return int
     */
    public function get_products($weight_classes_id)
    {
        return $this->db->where('products_weight_class', $weight_classes_id)->from('products')->count_all_results();
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the total number of weight classes
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->where('language_id', lang_id())->from('weight_classes')->count_all_results();
    }
}

/* End of file weight_classes_model.php */
/* Location: ./system/models/weight_classes_model.php */