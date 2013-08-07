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
 * Slide Images Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-model
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com
 */
class Slide_Images_Model extends CI_Model
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

    // --------------------------------------------------------------------

    /**
     * Get Images
     *
     * @access public
     * @param $start
     * @param $limit
     * @param $group
     * @return mixed
     */
    public function get_images($start = NULL, $limit = NULL, $group = NULL)
    {
        $this->db->select('*')->from('slide_images')->where('language_id', lang_id());

        if ($group !== NULL)
        {
            $this->db->where('group', $group);
        }
        
        $this->db->order_by('sort_order');
        
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

    // --------------------------------------------------------------------

    /**
     * Get totals
     *
     * @access public
     * @return int
     */
    public function get_totals($group = NULL)
    {
        $this->db->select('image_id')->from('slide_images')->where('language_id', lang_id());
        
        if ($group !== NULL) 
        {
            $this->db->where('group', $group);
        }
        
        $result = $this->db->get();
        
        return $result->num_rows();
    }

    // --------------------------------------------------------------------

    /**
     * Get groups
     *
     * @access public
     * @return mixed
     */
    public function get_groups()
    {
        $result = $this->db->distinct()->select('group')->from('slide_images')->get();

        if ($result->num_rows() > 0)
        {
            $groups = array();
            foreach ($result->result_array() as $row)
            {
                if (!empty($row['group']))
                {
                    $groups[] = $row['group'];
                }
            }

            return $groups;
        }

        return NULL;
    }

    // --------------------------------------------------------------------

    /**
     * Set status
     *
     * @access public
     * @param $id
     * @param $flag
     * @return boolean
     */
    public function set_status($id, $flag)
    {
        return $this->db->update('slide_images', array('status' => $flag), array('image_id' => $id));
    }

    // --------------------------------------------------------------------
    
    /**
     * Get image data
     * 
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        //get image data
        $result = $this->db->select('status, sort_order, group')->from('slide_images')->where(array('image_id' => $id, 'language_id' => lang_id()))->get();

        if ($result->num_rows() > 0) 
        {
            $data = $result->row_array();
            
            //get images description
            $res_info = $this->db->get_where('slide_images', array('image_id' => $id));

            if ($res_info->num_rows() > 0)
            {
                foreach($res_info->result_array() as $info)
                {
                    $data['description[' . $info['language_id'] . ']'] = $info['description'];
                    $data['image_url[' . $info['language_id'] . ']'] = $info['image_url'];
                    $data['slide_image' . $info['language_id']] = $info['image'];
                }
            }
            
            return $data;
        }

        return NULL;
    }

    // --------------------------------------------------------------------
    
    /**
     * Save slide image
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save($id = NULL, $data)
    {
        //load upload library
        $this->load->library('upload');
        $this->upload->initialize(array('upload_path' => ROOTPATH . 'images/', 'allowed_types' => 'gif|jpg|jpeg|png'));
        
        //start transaction
        $this->db->trans_start();

        if (is_numeric($id))
        {
            foreach(lang_get_all() as $l)
            {
                $image_data = array('description' => $data['description'][$l['id']],
                                    'image_url' => $data['image_url'][$l['id']], 
                                    'sort_order' => $data['sort_order'], 
                                    'group' => $data['group'], 
                                    'status' => $data['status']);

                //if new image is uploaded to override the old one
                if ($this->upload->do_upload('image' . $l['id']))
                {
                    $file = $this->upload->data();

                    //delete image
                    $result = $this->db->select('image')->from('slide_images')->where(array('image_id' => $id, 'language_id' => $l['id']))->get();
                    if ($result->num_rows() > 0)
                    {
                        $image = $result->row_array();
                        if (!empty($image))
                        {
                            @unlink($image_path . $image['image']);
                        }
                    }
                    
                    //update image
                    $image_data['image'] = $file['file_name'];
                }

                $this->db->update('slide_images', $image_data, array('language_id' => $l['id'], 'image_id' => $id));
            }
        }
        else
        {
            //get insert image id
            $insert_id = 1;
            $result = $this->db->select_max('image_id')->get('slide_images');
            if ($result->num_rows() > 0) 
            {
                $row = $result->row_array();
                $insert_id = $row['image_id'] + 1;
            }

            //insert image for each language
            foreach(lang_get_all() as $l)
            {
                if ($this->upload->do_upload('image' . $l['id']))
                {
                    $image = $this->upload->data();

                    $this->db->insert('slide_images', array('image_id' => $insert_id,
                                                            'language_id' => $l['id'], 
                                                            'description' => $data['description'][$l['id']], 
                                                            'image' => $image['file_name'], 
                                                            'image_url' => $data['image_url'][$l['id']], 
                                                            'sort_order' => $data['sort_order'], 
                                                            'group' => $data['group'], 
                                                            'status' => $data['status']));
                }
            }
        }
        
        //complete transaction
        $this->db->trans_complete();
        
        //check transaction status
        if ($this->db->trans_status() === FALSE)
        {
            return FALSE;
        } 
        
        return TRUE;
    }

    // --------------------------------------------------------------------
    
    /**
     * Delete slide image
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        //delete image file
        $result = $this->db->get_where('slide_images', array('image_id' => $id));

        $image = $result->row_array();
        if (!empty($image))
        {
            @unlink(ROOTPATH . 'images/' . $image['image']);
        }

        //delete data record
        return $this->db->delete('slide_images', array('image_id' => $id));
    }
}

/* End of file slide_images_model.php */
/* Location: ./system/models/slide_images_model.php */