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
 * Image library
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-library
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
Class TOC_Image {
    /**
     * The image groups
     *
     * @access private
     * @var array
     */
    private $_groups;
    
    /**
     * Reference to CodeIgniter instance
     *
     * @access private
     * @var object
     */
    private $ci;
    
    /**
     * The image group title
     *
     * @access private
     * @var string
     */
    private $_title;
  
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();
        
        $this->ci->load->model('image_model');
        $this->_groups = array();
        
        $image_groups = $this->ci->image_model->get_groups();
        
        if ($image_groups !== NULL)
        {
            foreach($image_groups as $image_group)
            {
                $this->_groups[$image_group['id']] = $image_group;
            }
        }
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Get the image group id based on the code
     *
     * @access public
     * @param $code
     * @return int
     */
    public function get_id($code) 
    {
        foreach ($this->_groups as $group) 
        {
            if ($group['code'] == $code) 
            {
                return $group['id'];
            }
        }
    
        return 0;
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Get the image group code based on the id
     *
     * @access public
     * @param $id
     * @return string
     */
    public function get_code($id) 
    {
        return $this->_groups[$id]['code'];
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Get the image group width based on the code
     *
     * @access public
     * @param $code
     * @return int
     */
    public  function get_width($code) 
    {
        return $this->_groups[$this->get_id($code)]['size_width'];
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Get the image group height based on the code
     *
     * @access public
     * @param $code
     * @return int
     */
    public function get_height($code) 
    {
        return $this->_groups[$this->get_id($code)]['size_height'];
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Check the image group based on the code
     *
     * @access public
     * @param $code
     * @return boolean
     */
    public function exists($code) 
    {
        return isset($this->_groups[$this->get_id($code)]);
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Show the product image
     *
     * @access public
     * @param $image
     * @param $title
     * @param $parameters
     * @param $group
     * @param $type
     * @return string
     */
    public function show($image, $title, $parameters = '', $group = '', $type = 'products') 
    {
        if (empty($group) || ! $this->exists($group)) 
        {
            $group = $this->get_code(DEFAULT_IMAGE_GROUP_ID);
        }
    
        $group_id = $this->get_id($group);
    
        $width = $height = '';
    
        if (($this->_groups[$group_id]['force_size'] == '1') || empty($image)) 
        {
            $width = $this->_groups[$group_id]['size_width'];
            $height = $this->_groups[$group_id]['size_height'];
        }
    
        if (empty($image))
        {
            $image = 'no_image.png';
        } 
        else 
        {
            $image = $type . '/' . $this->_groups[$group_id]['code'] . '/' . $image;
        }
    
        if ($type == 'products')
        {
            $parameters .= 'class="productImage"';
        }
        
        return image(IMGHTTPPATH . $image, $title, $width, $height, $parameters);
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get all the image groups
     *
     * @access public
     * @return array
     */
    public function get_groups()
    {
        return $this->_groups;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Resize the image
     *
     * @access public
     * @param $image
     * @param $group_id
     * @param $type
     * @return boolean
     */
    public function resize($image, $group_id, $type = 'products') 
    {
        //ensure that the image group directory is existed
        if ( ! file_exists(ROOTPATH . 'images/' . $type . '/' . $this->_groups[$group_id]['code'])) 
        {
            @mkdir(ROOTPATH . 'images/' . $type . '/' . $this->_groups[$group_id]['code'], 0777);
        }
        
        $original_image = ROOTPATH . 'images/' . $type . '/' . $this->_groups[1]['code'] . '/' . $image;
        $dest_image = ROOTPATH . 'images/' . $type . '/' . $this->_groups[$group_id]['code'] . '/' . $image;
        
        //verify that the product image is existing in the original image group and then resize it
        if (file_exists($original_image)) 
        {
            toc_gd_resize($original_image, $dest_image, $this->_groups[$group_id]['size_width'], $this->_groups[$group_id]['size_height']);
            
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Set the default image
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function set_as_default($id)
    {
        return $this->ci->image_model->set_as_default($id);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the image
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $image = $this->ci->image_model->get_image_name($id);
        
        if (!empty($image) && is_array($image))
        {
            $image_name = $image['image'];
            
            foreach ($this->_groups as $group) 
            {
                @unlink(ROOTPATH. 'images/products/' . $group['code'] . '/' . $image_name);
            }
            
           //remove watermark file
            if (file_exists(ROOTPATH. 'images/products/' . $this->_groups[1]['code'] . '/watermark_' . $image_name)) 
            {
                @unlink(DROOTPATH . 'images/products/' . $this->_groups[1]['code'] . '/watermark_' . $image_name);
            }
            
            return $this->ci->image_model->delete($id);
        }
    }
    
    // ------------------------------------------------------------------------

    /**
     * Delete the image of an article
     *
     * @access public
     * @param $id
     * @return void
     */
    public function delete_articles_image($id)
    {
        $image = $this->ci->image_model->get_articles_image($id);
        
        foreach($this->_groups as $group)
        {
            @unlink(ROOTPATH . 'images/articles/' . $group['code'] . '/' . $image['articles_image']);
        }
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
        return $this->ci->image_model->get_products_images();
    }
}

/* End of file image.php */
/* Location: ./system/libraries/image.php */