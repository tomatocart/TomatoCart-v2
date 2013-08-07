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
 * TomatoCart Languages Helpers
 *
 * @package		TomatoCart
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

/**
 * Gets a translation
 *
 * @access public
 * @param $key The key to the translation
 * @return string translation
 */
if( ! function_exists('lang'))
{
    function lang($key)
    {
        $CI =& get_instance();

        return $CI->lang->line($key);
    }
}

/**
 * Get default language id
 *
 * @access public
 * @return int
 */
if ( ! function_exists('lang_id'))
{
    function lang_id()
    {
        $CI =& get_instance();

        return $CI->lang->get_id();
    }
}

/**
 * Get default language name
 *
 * @access public
 * @return string
 */
if ( ! function_exists('lang_name'))
{
    function lang_name()
    {
        $CI =& get_instance();

        return $CI->lang->get_name();
    }
}

/**
 * Get default language code
 *
 * @access public
 * @return string
 */
if ( ! function_exists('lang_code'))
{
    function lang_code()
    {
        $CI =& get_instance();

        return $CI->lang->get_code();
    }
}

/**
 * Get default language image
 *
 * @access public
 * @return string
 */
if ( ! function_exists('lang_image'))
{
    function lang_image()
    {
        $CI =& get_instance();
        $lang_code = $CI->lang->get_code();
        
        return image_url('worldflags/' . strtolower(substr($lang_code, 3)) . '.png');
    }
}

/**
 * Get all languages
 *
 * @access public
 * @return array
 */
if ( ! function_exists('get_languages'))
{
    function get_languages()
    {
        $CI =& get_instance();
        $languages = $CI->lang->get_languages();

        return $languages;
    }
}


/* End of file toc_languages_helper.php */
/* Location: ./system/tomatocart/helpers/toc_languages_helper.php */