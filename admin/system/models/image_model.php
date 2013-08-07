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
 * Image Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Image_Model extends CI_Model 
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
     * @return mixed
     */
    public function get_groups()
    {
        $result = $this->db
            ->select('*')
            ->from('products_images_groups')
            ->where('language_id', lang_id())
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Set an image as the default image
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function set_as_default($id)
    {
        $result = $this->db
            ->select('products_id')
            ->from('products_images')
            ->where('id', $id)
            ->get();
        
        $image = $result->row_array();
        
        $result->free_result();
        
        if (count($image) > 0)
        {
            $this->db->update('products_images', array('default_flag' => 0), array('products_id' => $image['products_id'], 'default_flag' => 1));
            
            $this->db->update('products_images', array('default_flag' => 1), array('id' => $id));
            
            if ($this->db->affected_rows() > 0)
            {
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the image name
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_image_name($id)
    {
        $result = $this->db
          ->select('image')
          ->from('products_images')
          ->where('id', $id)
          ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Delete an image
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->delete('products_images', array('id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the info of the article image
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_articles_image($id)
    {
        $result = $this->db
            ->select('articles_image')
            ->from('articles')
            ->where('articles_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the products images
     *
     * @access public
     * @return mixed
     */
    public function get_products_images()
    {
        $result = $this->db
            ->select('image')
            ->from('products_images')
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
}

/* End of file image_model.php */
/* Location: ./system/models/image_model.php */