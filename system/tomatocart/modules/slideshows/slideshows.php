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
 * Module Slideshow Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Mod_Slideshows extends TOC_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'slideshows';

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
        //MODULE_SLIDESHOW_IMAGE_GROUPS
        array('name' => 'MODULE_SLIDESHOW_IMAGE_GROUPS',
              'title' => 'Slideshow Image Group', 
              'type' => 'combobox',
              'mode' => 'remote',
              'value' => '',
              'description' => 'The image groups to choose',
              'url' => 'slide_images/get_image_groups'),
    
        //MODULE_SLIDESHOW_PLAY_INTERVAL
        array('name' => 'MODULE_SLIDESHOW_PLAY_INTERVAL',
              'title' => 'Slideshow Play Interval', 
              'type' => 'numberfield',
              'value' => '3000',
              'description' => 'Slideshow Play Interval'),
    
        //MODULE_SLIDESHOW_DISPLAY_CAROUSEL_CONTROL
        array('name' => 'MODULE_SLIDESHOW_DISPLAY_CAROUSEL_CONTROL',
              'title' => 'Display carousel control', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'true',
              'description' => 'Display carousel control button',
              'values' => array(
                  array('id' => 'true', 'text' => 'True'), 
                  array('id' => 'false', 'text' => 'False'))),
                  
        //MODULE_SLIDESHOW_DISPLAY_SLIDE_INFO
        array('name' => 'MODULE_SLIDESHOW_DISPLAY_SLIDE_INFO',
              'title' => 'Display slide info', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'true',
              'description' => 'Display slide info',
              'values' => array(
                  array('id' => 'true', 'text' => 'True'), 
                  array('id' => 'false', 'text' => 'False'))));

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

        $this->title = lang('box_slide_show_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of slideshow module
     */
    public function index()
    {
        //Create a rand div id for slideshow
        $mid = 'slides_' . rand();
        
        //load products
        $slides = $this->slideshows->get_slides($this->config['MODULE_SLIDESHOW_IMAGE_GROUPS']);
        if (count($slides) > 0)
        {
            $data['mid'] = $mid;
            $data['play_interval'] = $this->config['MODULE_SLIDESHOW_PLAY_INTERVAL'];
            $data['display_carousel_control'] = $this->config['MODULE_SLIDESHOW_DISPLAY_CAROUSEL_CONTROL'];
            $data['display_slide_info'] = $this->config['MODULE_SLIDESHOW_DISPLAY_SLIDE_INFO'];
            
            foreach($slides as $slide)
            {
                $data['images'][] = array(
                    'image_src' => $slide['image'],
                    'image_link' => $slide['image_url'],
                    'image_info' => $slide['description']);
            }
            
            //load view
            return $this->load_view('index.php', $data);
        } 
        
        return NULL;
    }
}

/* End of file slideshows.php */
/* Location: ./system/tomatocart/modules/slideshows/slideshows.php */