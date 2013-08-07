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
 * TomatoCart DateTime Helpers
 *
 * @package		TomatoCart
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

// ------------------------------------------------------------------------
/**
 * Get date now
 *
 * @access public
 * @return string
 */
if( ! function_exists('get_date_now'))
{
    function get_date_now()
    {
        return date('Y-m-d H:i:s');
    }
}

/**
 * Get date part of a datetime
 *
 * @access public
 * @param $datetime
 * @return string
 */
if( ! function_exists('get_date'))
{
    function get_date($datetime)
    {
        if ( $datetime == '0000-00-00 00:00:00' ) {
            $date = 'null';
        } else {
            $date = substr($datetime, 0, 10);
        }

        return $date;
    }
}

/**
 * Get short type date format
 *
 * @access public
 * @param $number
 * @param $currency_code
 * @param $currency_value
 * @return string
 */
if( ! function_exists('get_date_short'))
{
    function get_date_short($date = null, $with_time = false)
    {
        $CI =& get_instance();

        if (empty($date))
        {
            $date = get_date_now();
        }

        $year = substr($date, 0, 4);
        $month = (int)substr($date, 5, 2);
        $day = (int)substr($date, 8, 2);
        $hour = (int)substr($date, 11, 2);
        $minute = (int)substr($date, 14, 2);
        $second = (int)substr($date, 17, 2);

        if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year)
        {
            return strftime($CI->lang->get_date_format_short(), mktime($hour, $minute, $second, $month, $day, $year));
        }
        else
        {            
            return preg_replace('/2037/', $year, strftime($CI->lang->get_date_format_short(), mktime($hour, $minute, $second, $month, $day, 2037)));
        }
    }
}

/**
 * Get long type date format
 *
 * @access public
 * @param $number
 * @param $currency_code
 * @param $currency_value
 * @return string
 */
if( ! function_exists('get_date_long'))
{
    function get_date_long($date = null, $with_time = false)
    {
        $CI =& get_instance();

        if (empty($date))
        {
            $date = get_date_now();
        }

        $year = substr($date, 0, 4);
        $month = (int)substr($date, 5, 2);
        $day = (int)substr($date, 8, 2);
        $hour = (int)substr($date, 11, 2);
        $minute = (int)substr($date, 14, 2);
        $second = (int)substr($date, 17, 2);

        if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year)
        {
            return strftime($CI->lang->get_date_format_long(), mktime($hour, $minute, $second, $month, $day, $year));
        }
        else
        {
            return preg_replace('/2037/', $year, strftime($CI->lang->get_date_format_long(), mktime($hour, $minute, $second, $month, $day, 2037)));
        }
    }
}

/* End of file datetime_helper.php */
/* Location: ./system/tomatocart/helpers/datetime_helper.php */