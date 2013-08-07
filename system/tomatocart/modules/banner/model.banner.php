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
 * Banner Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Banner_Model extends CI_Model
{
    /**
     * Banner Model Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get Banner
     *
     * @access public
     * @param string $group the image group to be displayed
     * @return array slide array
     */
    public function get_banner($group)
    {
        $result = $this->db->select('image, image_url, description')
            ->from('slide_images')
            ->where('language_id', lang_id())
            ->where('group', $group)
            ->where('status', 1)
            ->order_by('sort_order')
            ->get();

        if ($result->num_rows() > 0)
        {
            $result_array = $result->result_array();
            
            //randomizes the order of the slides
            shuffle($result_array);
            
            //return the first element
            return $result_array[0];
        }

        return NULL;
    }
}

/* End of file model.banner.php */
/* Location: ./system/tomatocart/modules/banner/mod.banner.php */