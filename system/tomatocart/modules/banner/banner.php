<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package      TomatoCart
 * @author       TomatoCart Dev Team
 * @copyright    Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html
 * @link         http://tomatocart.com
 * @since        Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Module Banner Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Mod_Banner extends TOC_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'banner';

    /**
     * Template Module Author Name
     *
     * @access private
     * @var string
     */
    var $author_name = 'TomatoCart';

    /**
     * Template Module Author Url
     *
     * @access private
     * @var string
     */
    var $author_url = 'http://www.tomatocart.com';

    /**
     * Template Module Version
     *
     * @access private
     * @var string
     */
    var $version = '1.0';

    /**
     * Template Module Parameter
     *
     * @access private
     * @var string
     */
    var $params = array(
        //MODULE_BANNER_IMAGE_GROUPS
        array('name' => 'MODULE_BANNER_IMAGE_GROUPS',
              'title' => 'Banner Image Group', 
              'type' => 'combobox',
              'mode' => 'remote',
              'value' => '',
              'description' => 'The banner groups to choose',
              'module' => 'slide_images',
              'action' => 'get_image_groups'),
    
        //MODULE_BANNER_SLIDE_WIDTH
        array('name' => 'MODULE_BANNER_SLIDE_WIDTH',
              'title' => 'Banner Width', 
              'type' => 'numberfield',
              'value' => '940',
              'description' => 'Banner Wdith'),
        
        //MODULE_BANNER_SLIDE_HEIGHT
        array('name' => 'MODULE_BANNER_SLIDE_HEIGHT',
              'title' => 'Banner Height', 
              'type' => 'numberfield',
              'value' => '210',
              'description' => 'Slideshow Height'));

    /**
     * Slideshow Module Constructor
     *
     * @access public
     * @param string
     */
    public function __construct($config)
    {
        parent::__construct();
        
        if (!empty($config) && is_string($config))
        {
            $this->config = json_decode($config, true);
        }

        $this->title = lang('box_banner_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of slideshow module
     */
    public function index()
    {
        //load model
        $this->load_model('banner');
        
        //load products
        $banner = $this->banner->get_banner($this->config['MODULE_BANNER_IMAGE_GROUPS']);
        if ($banner != NULL)
        {
            $data['image'] = $banner['image'];
            $data['image_url'] = $banner['image_url'];
            $data['description'] = $banner['description'];
            $data['width'] = $this->config['MODULE_BANNER_SLIDE_WIDTH'];
            $data['height'] = $this->config['MODULE_BANNER_SLIDE_HEIGHT'];
            
            //load view
            return $this->load_view('index.php', $data);
        } 
        
        return NULL;
    }
}

/* End of file banner.php */
/* Location: ./system/tomatocart/modules/banner/banner.php */