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
 * Image Groups Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Image_Groups_Model extends CI_Model
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
     * Get the image groups
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_image_groups($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('id, title')
            ->from('products_images_groups')
            ->where('language_id', lang_id())
            ->order_by('title');
            
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
     * Save the image group
     *
     * @access public
     * @param $id
     * @param $data
     * @param $default
     * @return boolean
     */
    public function save($id = NULL, $data, $default = FALSE)
    {
        //editing or adding the products images groups
        if (is_numeric($id))
        {
            $group_id = $id;
        }
        else
        {
            $result = $this->db
                ->select_max('id')
                ->from('products_images_groups')
                ->get();
            
            $group = $result->row_array();
            
            $group_id = $group['id'] + 1;
            
            $result->free_result();
        }
        
        $error = FALSE;
        
        //start transaction
        $this->db->trans_begin();
        
        //process languages
        foreach(lang_get_all() as $l)
        {
            $image_group = $data;
            
            $image_group['title'] = $data['title'][$l['id']];
            $image_group['force_size'] = $data['force_size'] === TRUE ? 1 : 0;
            
            if (is_numeric($id))
            {
                $this->db->update('products_images_groups', $image_group, array('id' => $id, 'language_id' => $l['id']));
            }
            else
            {
                $image_group['id'] = $group_id;
                $image_group['language_id'] = $l['id'];
                
                $this->db->insert('products_images_groups', $image_group);
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
            //update the default image group
            if ($default === TRUE)
            {
                $this->db->update('configuration', array('configuration_value' => $group_id), array('configuration_key' => 'DEFAULT_IMAGE_GROUP_ID'));
                
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
     * Delete the image group with id
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->delete('products_images_groups', array('id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get data of the image group with the id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
            ->select('*')
            ->from('products_images_groups')
            ->where('id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the total number of the image groups
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        $result = $this->db
            ->select('id')
            ->from('products_images_groups')
            ->where('language_id', lang_id())
            ->get();
        
        return $result->num_rows();
    }
}

/* End of file image_groups_model.php */
/* Location: ./system/models/image_groups_model.php */