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
 * Images Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Images extends TOC_Controller
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
        
        $this->load->library('image');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the images
     *
     * @access public
     * @return string
     */
    public function list_images()
    { 
        $records = array(
            array('module' => lang('images_check_title'), 'run' => 'checkimages'), 
            array('module' => lang('images_resize_title'), 'run' => 'resizeimages')
        );
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Check the images
     *
     * @access public
     * @return string
     */
    public function check_images()
    {
        $counter = array();
        
        $products_images = $this->image->get_products_images();
        
        $images_groups = $this->image->get_groups();
        
        //get the total number of images in each image group
        if ($products_images !== NULL)
        {
            foreach($products_images as $image)
            {
                foreach($images_groups as $group)
                {
                    if ( ! isset($counter[$group['id']]['records']))
                    {
                        $counter[$group['id']]['records'] = 0;
                    }
                    
                    if ( ! isset($counter[$group['id']]['existing']))
                    {
                        $counter[$group['id']]['existing'] = 0;
                    }
                  
                    $counter[$group['id']]['records']++;
                    
                    if (file_exists(ROOTPATH . '/images/products/' . $group['code'] . '/' . $image['image']))
                    {
                        $counter[$group['id']]['existing']++;
                    }
                }
            }
        }
        
        $records = array();
        foreach($counter as $group_id => $value)
        {
            $records[] = array('group' => $images_groups[$group_id]['title'], 'count' => $value['existing'] . ' / ' . $value['records']);
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the image groups
     *
     * @access public
     * @return string
     */
    public function get_image_groups()
    {
        $images_groups = $this->image->get_groups();
        
        $records = array();
        foreach($images_groups as $group)
        {
            
            if ((int)$group['id'] !== 1)
            {
                $records[] = array('text' => $group['title'], 'id' => $group['id']);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the images resized
     *
     * @access public
     * @return string
     */
    public function list_images_resize_result()
    {
        ini_set('max_execution_time', 1800);
        
        $overwrite = FALSE;
        
        if ($this->input->get_post('overwrite') == '1')
        {
            $overwrite = TRUE;
        }
        
        $groups = json_decode($this->input->get_post('groups'));
        
        $products_images = $this->image->get_products_images();
        $images_groups = $this->image->get_groups();
        
        //resize the products images in each image group
        $counter = array();
        if ($products_images !== NULL)
        {
            foreach($products_images as $image)
            {
                foreach($images_groups as $group)
                {
                    if ((int)$group['id'] !== 1 && in_array($group['id'], $groups))
                    {
                        if ( ! isset($counter[$group['id']]))
                        {
                            $counter[$group['id']] = 0;
                        }
                        
                        //resize the product image only if the image need to be overwritted or is not existed
                        if ($overwrite === TRUE || ! file_exists(ROOTPATH . 'images/products/' . $group['code'] . '/' . $image['image']))
                        {
                            if ($this->image->resize($image['image'], $group['id']))
                            {
                                $counter[$group['id']]++;
                            }
                        }
                    }
                }
            }
        }
        
        $records = array();
        foreach($counter as $group_id => $value)
        {
            $records[] = array('group' => $images_groups[$group_id]['title'], 'count' => $value);
        }
        
        $this->output->set_output(json_encode($records));
    }
}

/* End of file images.php */
/* Location: ./system/controllers/images.php */