<?php
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 * 
 * @package     TomatoCart
 * @author      TomatoCart Dev Team
 * @copyright   Copyright (c) 2009 - 2013, TomatoCart. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl.html
 * @link        http://tomatocart.com
 * @since       2.0.0
 * @filesource  
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * TomatoCart Language Helpers
 * 
 * @package     TomatoCart
 * @subpackage  Helpers
 * @category    Helpers
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com
 */

// ------------------------------------------------------------------------

if ( ! function_exists('lang'))
{
    /**
     * Lang
     * 
     * @param   string $line    The language line
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function lang($line)
    {
        return ci()->lang->line($line);
    }
}

if ( ! function_exists('lang_all'))
{
    /**
     * Get all the languages.
     * 
     * @return  array
     * 
     * @since   2.0.0
     */
    function lang_all()
    {
        return ci()->lang->get_all();
    }
}

if ( ! function_exists('lang_charset'))
{
    /**
     * Get the charset of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function lang_charset()
    {
        return ci()->lang->get_charset();
    }
}

if ( ! function_exists('lang_code'))
{
    /**
     * Get the language code of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function lang_code()
    {
        return ci()->lang->get_code();
    }
}

if ( ! function_exists('lang_country_iso'))
{
    /**
     * Get the country iso.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function lang_country_iso()
    {
        return ci()->lang->get_country_iso();
    }
}

if ( ! function_exists('lang_id'))
{
    /**
     * Get the language id of the current language.
     * 
     * @return  integer
     * 
     * @since   2.0.0
     */
    function lang_id()
    {
        return ci()->lang->get_id();
    }
}

if ( ! function_exists('lang_implode'))
{
    /**
     * Lang implode
     * 
     * @param   string  $line
     * @param   array   $pieces
     * @param   string  $glue
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function lang_implode($line, $pieces, $glue = '<br />')
    {
        return lang($line) . $glue . implode($glue, $pieces);
    }
}

if ( ! function_exists('lang_locale'))
{
    /**
     * Get the locale of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function lang_locale()
    {
        return ci()->lang->get_locale();
    }
}

if ( ! function_exists('lang_name'))
{
    /**
     * Get the language name of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function lang_name()
    {
        return ci()->lang->get_name();
    }
}

if ( ! function_exists('lang_sprintf'))
{
    /**
     * Lang sprintf
     * 
     * @param   array   $variables
     * 
     * @return  string  
     * 
     * @since   2.0.0
     */
    function lang_sprintf($line, $variables = array())
    {
        array_unshift($variables, lang($line));

        return call_user_func_array('sprintf', $variables);
    }
}

if ( ! function_exists('lang_text_direction'))
{
    /**
     * Get the text direction of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function lang_text_direction()
    {
        return ci()->lang->get_all();
    }
}

/* End of file TOC_language_helper.php */
/* Location: ./admin/system/helpers/TOC_language_helper.php */