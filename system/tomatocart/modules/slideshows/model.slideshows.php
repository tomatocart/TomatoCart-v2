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
 * Slideshows Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Slideshows_Model extends CI_Model
{
    /**
     * Slideshows Model Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get Slides
     *
     * @access public
     * @param string $group the slides group to be displayed
     * @return array slides array
     */
    public function get_slides($group)
    {
        $result = $this->db->select('image, image_url, description')
            ->from('slide_images')
            ->where('language_id', lang_id())
            ->where('group', $group)
            ->where('status', 1)
            ->order_by('sort_order')
            ->get();

        $slides = array();
        if ($result->num_rows() > 0)
        {
            $slides = $result->result_array();
        }

        return $slides;
    }
}

/* End of file model.slideshows.php */
/* Location: ./system/tomatocart/modules/slideshows/mod.slideshows.php */