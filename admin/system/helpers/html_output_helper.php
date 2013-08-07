<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource
 */

// ------------------------------------------------------------------------

if ( ! function_exists('format_friendly_url') )
{
    function format_friendly_url($string) {
        $url = strtolower($string);
        $url = preg_replace('#[^0-9a-z]+#i', '-', $url);
        $url = trim($url, '-');

        return $url;
    }
}

// ------------------------------------------------------------------------

/**
 * Generate an internal URL address for the administration side
 *
 * @param string $page The page to link to
 * @param string $parameters The parameters to pass to the page (in the GET scope)
 * @access public
 */

if ( ! function_exists('href_link_admin'))
{
    function href_link_admin($page = null, $parameters = null)
    {
        if (ENABLE_SSL === TRUE)
        {
            $link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . DIR_FS_ADMIN;
        } else
        {
            $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_FS_ADMIN;
        }

        $link .= $page . '?' . $parameters;

        while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') )
        {
            $link = substr($link, 0, -1);
        }

        return $link;
    }
}

// ------------------------------------------------------------------------

/**
 * Generate an icon(16x16) for the grid
 *
 * @param string name of the icon
 * @param string $parameters The parameters to pass to the page (in the GET scope)
 * @access public
 */

if ( ! function_exists('icon'))
{
    function icon($icon = null, $template = 'base', $type = 'web')
    {
        return '<img src="' . base_url('templates/' . $template . '/' . $type . '/images/icons/16x16/' . $icon) . '" />';
    }
}

// ------------------------------------------------------------------------

/**
 * Generate an url of icon(16x16) for the grid
 *
 * @access public
 * @param string the url of the icon
 * @param string the template
 * @param string the template type web/mobile
 */

if ( ! function_exists('icon_url'))
{
    function icon_url($icon = null, $template = 'base', $type = 'web')
    {
        return base_url('templates/' . $template . '/' . $type . '/images/icons/16x16/' . $icon);
    }
}

// ------------------------------------------------------------------------

/**
 * Generate an green status flag for the grid
 *
 * @access public
 * @param string name of the icon
 * @param string the template
 * @param string the template type web/mobile
 * @return string
 */

if ( ! function_exists('icon_status_url'))
{
    function icon_status_url($icon, $template = 'base', $type = 'web')
    {
        return base_url('templates/' . $template . '/' . $type . '/images/' . $icon);
    }
}

// ------------------------------------------------------------------------

/**
 * Generate css background for the world flag
 *
 * @access public
 * @param string name of the icon
 * @param string the template
 * @param string the template type web/mobile
 * @return string
 */

if ( ! function_exists('worldflag_url'))
{
    function worldflag_url($country_iso)
    {
        return 'background: url(../../../images/worldflags/' . $country_iso . '.png) no-repeat right center !important;';
    }
}

// ------------------------------------------------------------------------


if( ! function_exists('image'))
{
    function image($image, $title = null, $width = 0, $height = 0, $parameters = null)
    {
        if (empty($image)) {
            return false;
        }

        if (!is_numeric($width)) {
            $width = 0;
        }

        if (!is_numeric($height)) {
            $height = 0;
        }

        $image = '<img src="' . str_replace('admin/', '', base_url(quotes_to_entities($image))) . '" border="0" alt="' . quotes_to_entities($title) . '"';

        if (!empty($title)) {
            $image .= ' title="' . quotes_to_entities($title) . '"';
        }

        if ($width > 0) {
            $image .= ' width="' . (int)$width . '"';
        }

        if ($height > 0) {
            $image .= ' height="' . (int)$height . '"';
        }

        if (!empty($parameters)) {
            $image .= ' ' . $parameters;
        }

        $image .= ' />';

        return $image;
    }
}

/* End of file html_output_helper.php */
/* Location: ./system/helpers/html_output_helper.php */