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
 * TomatoCart Currencies Helpers
 *
 * @package		TomatoCart
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

/**
 * Get default currency code
 *
 * @access public
 * @return string
 */
if( ! function_exists('currency_code'))
{
    function currency_code()
    {
        $CI =& get_instance();
        
        return $CI->currencies->get_code();
    }
}

/**
 * Get default currency title
 *
 * @access public
 * @return string
 */
if( ! function_exists('currency_title'))
{
    function currency_title()
    {
        $CI =& get_instance();
        
        return $CI->currencies->get_title();
    }
}

/**
 * Get default currency left symbol
 *
 * @access public
 * @return string
 */
if( ! function_exists('currency_symbol_left'))
{
    function currency_symbol_left()
    {
        $CI =& get_instance();
        return $CI->currencies->get_symbol_left();
    }
}

/**
 * Get all currencies
 *
 * @access public
 * @return array
 */
if( ! function_exists('get_currencies'))
{
    function get_currencies()
    {
        $CI =& get_instance();
        
        return $CI->currencies->get_currencies();
    }
}

/**
 * Format currency value with currency symbol
 *
 * @access public
 * @param $number
 * @param $currency_code
 * @param $currency_value
 * @return string
 */
if( ! function_exists('currencies_format'))
{
    function currencies_format($number, $currency_code = '', $currency_value = '')
    {
        $CI =& get_instance();
        
        return $CI->currencies->format($number, $currency_code, $currency_value);
    }
}

/**
 * Format currency raw value without symbol
 *
 * @access public
 * @param $number
 * @param $currency_code
 * @param $currency_value
 * @return string
 */
if( ! function_exists('currencies_format_raw'))
{
    function currencies_format_raw($number, $currency_code = '', $currency_value = '')
    {
        $CI =& get_instance();
        
        return $CI->currencies->format_raw($number, $currency_code, $currency_value);
    }
}

/**
 * Add tax value to product price
 *
 * @access public
 * @param $price
 * @param $tax_rate
 * @param $quantity
 * @return string
 */
if( ! function_exists('currencies_add_tax_rate_to_price'))
{
    function currencies_add_tax_rate_to_price($price, $tax_rate, $quantity = 1)
    {
        $CI =& get_instance();
        
        return $CI->currencies->add_tax_rate_to_price($price, $tax_rate, $quantity);
    }
}

/**
 * Display total price for certain amount of products with currency symbol
 *
 * @access public
 * @param $price
 * @param $tax_rate
 * @param $quantity
 * @return string
 */
if( ! function_exists('currencies_display_price'))
{
    function currencies_display_price($price, $tax_class_id, $quantity = 1, $currency_code = null, $currency_value = null)
    {
        $CI =& get_instance();
        
        return $CI->currencies->display_price($price, $tax_class_id, $quantity, $currency_code, $currency_value);
    }
}

/**
 * Display total price with tax value for certain amount of products with currency symbol
 *
 * @access public
 * @param $price
 * @param $tax_rate
 * @param $quantity
 * @param $currency_code
 * @param $currency_value
 * @return string
 */
if( ! function_exists('currencies_display_price_with_tax_rate'))
{
    function currencies_display_price_with_tax_rate($price, $tax_rate, $quantity = 1, $currency_code = '', $currency_value = '')
    {
        $CI =& get_instance();
        
        return $CI->currencies->display_price_with_tax_rate($price, $tax_rate, $quantity, $currency_code, $currency_value);
    }
}


/**
 * Display raw price (without tax value) with symbol
 *
 * @access public
 * @param $number
 * @param $currency_code
 * @return string
 */
if( ! function_exists('currencies_display_raw_price'))
{
    function currencies_display_raw_price($number, $currency_code = '')
    {
        $CI =& get_instance();
        
        return $CI->currencies->display_raw_price($number, $currency_code);
    }
}

/* End of file toc_currencies_helper.php */
/* Location: ./system/tomatocart/helpers/toc_currencies_helper.php */