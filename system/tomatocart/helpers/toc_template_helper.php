<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
 * TomatoCart Template Helpers
 *
 * @package		TomatoCart
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

/**
 * Get sub categories
 *
 * @access public
 * @param $number
 * @param $currency_code
 * @return string
 */
if( ! function_exists('get_sub_categories'))
{
    function get_sub_categories()
    {
        //get ci instance
        $ci = get_instance();

        //get cpath
        $categories_id = $ci->registry->get('cpath');

        //get categories
        $data = array();
        $ci->category_tree->get_children($categories_id, $data);

        $categories = array();
        foreach ($data as $category) {
            $cpath = explode('_', $category['id']);
            $id = end($cpath);

            $categories[] = array(
                'id' => $id,
                'cpath' => $category['id'],
                'name' => $category['info']['name'],
                'image' => $category['info']['image'],
                'url' => site_url('cpath/' . $category['id'])
            );
        }

        return $categories;
    }
}

/**
 * Get manufacturers
 *
 * @access public
 * @param $number
 * @param $currency_code
 * @return string
 */
if( ! function_exists('get_manufacturers'))
{
    function get_manufacturers()
    {
        //get ci instance
        $ci = get_instance();

        //get cpath
        $sub_categories = get_sub_categories();

        //get categories id array
        $categories = array($ci->registry->get('cpath'));
        foreach ($sub_categories as $category) {
            $categories[] = $category['id'];
        }

        //load manufacturers_model
        $ci->load->model('manufacturers_model');

        //get manufacturers
        $manufacturers = $ci->manufacturers_model->get_manufacturers($categories);

        //fomat the data
        for($i = 0; $i < count($manufacturers); $i++) {
            $manufacturers[$i]['url'] = site_url('manufacturer/' . $manufacturers[$i]['id']);
        }

        return $manufacturers;
    }
}



/**
 * Get template logo
 *
 * @access public
 * @return html
 */
if ( ! function_exists('get_logo'))
{
    function get_logo()
    {
        //get ci instance
        $CI =& get_instance();
        $CI->load->helper('directory');

        $map = directory_map('images');
        
        if (is_array($map) && count($map) > 0) {
            foreach ($map as $image)
            {
                if (is_string($image) && ! is_dir($image))
                {
                    if (strpos($image, '.') !== FALSE) 
                    {
                        $parts = explode('.', $image);

                        if ($parts[0] == 'logo_' . config('DEFAULT_TEMPLATE'))
                        {
                            return base_url('images/' . $image);
                        }
                    }

                }
            }
        }

        return base_url('images/store_logo.png');
    }
}

/* End of file toc_template_helper.php */
/* Location: ./system/tomatocart/helpers/toc_template_helper.php */