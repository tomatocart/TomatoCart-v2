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
 * TomatoCart Tax Helpers
 *
 * @package		TomatoCart
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

// ------------------------------------------------------------------------

/**
 * Get tax rate
 *
 * @param $class_id
 * @param $country_id
 * @param $zone_id
 * @return float
 */
if( ! function_exists('get_tax_rate'))
{
    function get_tax_rate($class_id, $country_id = -1, $zone_id = -1)
    {
        $CI =& get_instance();
        return $CI->tax->get_tax_rate($class_id, $country_id, $zone_id);
    }
}

/**
 * Get tax rate description
 *
 * @param $class_id
 * @param $country_id
 * @param $zone_id
 * @return
 */
if( ! function_exists('get_tax_rate_description'))
{
    function get_tax_rate_description($class_id, $country_id, $zone_id)
    {
        $CI =& get_instance();
        return $CI->tax->get_tax_rate_description($class_id, $country_id, $zone_id);
    }
}

/**
 * display tax rate value.
 *
 * @param $value
 * @param $padding
 * @return
 */
if( ! function_exists('display_tax_rate_value'))
{
    function display_tax_rate_value($value, $padding = null)
    {
        $CI =& get_instance();
        return $CI->tax->display_tax_rate_value($value, $padding);
    }
}

/* End of file tax_helper.php */
/* Location: ./system/tomatocart/helpers/tax_helper.php */