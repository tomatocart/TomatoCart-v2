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
 * Tag Cloud Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Tag_Cloud
{
    /**
     * tags
     *
     * @access private
     * @var array
     */
    private $_tags = array();

    /**
     * min font size
     *
     * @access private
     * @var object
     */
    private $min_font_size;

    /**
     * max font size
     *
     * @access private
     * @var object
     */
    private $max_font_size;

    /**
     * row tags
     *
     * @access private
     * @var object
     */
    private $row_tags;

    /**
     * tag class
     *
     * @access private
     * @var object
     */
    private $tag_class;

    /**
     * Constructor
     *
     * @access public
     * @param $tags
     * @param $tag_class
     * @param $max_font_size
     * @param $min_font_size
     * @return void
     */
    public function __construct($tags, $tag_class = 'tag_cloud', $max_font_size = 25, $min_font_size = 9)
    {
        $this->tags = $tags;
        $this->tag_class = $tag_class;
        $this->max_font_size = $max_font_size;
        $this->min_font_size = $min_font_size;
    }

    /**
     * Add tag
     *
     * @access public
     * @param string
     * @return void
     */
    public function add_tag($tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * Add tags
     * 
     * @access public
     * @param $tags
     * @return void
     */
    public function add_tags($tags) 
    {
        if (is_array($tags)) 
        {
            foreach($tags as $tag) 
            {
                $this->tags[] = $tag;
            }
        }
    }

    /**
     * Generate tag cloud
     *
     * @access public
     * @return void
     */
    public function generate_tag_cloud() 
    {
        if (!empty($this->tags)) 
        {
            foreach($this->tags as $tag) 
            {
                if (!isset($min_count)) 
                {
                    $min_count = $tag['count'];
                }
                elseif ($min_count > $tag['count']) 
                {
                    $min_count = $tag['count'];
                }

                if (!isset($max_count)) 
                {
                    $max_count = $tag['count'];
                }
                elseif ($max_count < $tag['count']) 
                {
                    $max_count = $tag['count'];
                }
            }

            $diff = $max_count - $min_count;
            $diff = ($diff == 0) ? 1 : $diff;

            $html = '<div align="center" class="' . $this->tag_class . '">';
            foreach($this->tags as $key => $tag) 
            {
                $size = $this->min_font_size + ($tag['count'] - $min_count) * ($this->max_font_size - $this->min_font_size) / $diff;
                $html .=  '<a style="font-size: ' . floor($size) . 'px' . '" href="' . $tag['url'] . '">' . $tag['tag'] . '</a>&nbsp; ';
            }
            $html .= '</div>';
        }

        return $html;
    }
    

    /**
     * Generate tag cloud
     *
     * @access public
     * @return void
     */
    public function generate_tag_cloud_array() 
    {
        if (!empty($this->tags)) 
        {
            foreach($this->tags as $tag) 
            {
                if (!isset($min_count)) 
                {
                    $min_count = $tag['count'];
                }
                elseif ($min_count > $tag['count']) 
                {
                    $min_count = $tag['count'];
                }

                if (!isset($max_count)) 
                {
                    $max_count = $tag['count'];
                }
                elseif ($max_count < $tag['count']) 
                {
                    $max_count = $tag['count'];
                }
            }

            $diff = $max_count - $min_count;
            $diff = ($diff == 0) ? 1 : $diff;

            $keywords = array();
            foreach($this->tags as $key => $tag) 
            {
                $keywords[] = array(
                    'size' => (int) ($this->min_font_size + ($tag['count'] - $min_count) * ($this->max_font_size - $this->min_font_size) / $diff),
                    'url' => $tag['url'],
                    'tag' => $tag['tag']
                );
            }
            
            return $keywords;
        }

        return NULL;
    }
}