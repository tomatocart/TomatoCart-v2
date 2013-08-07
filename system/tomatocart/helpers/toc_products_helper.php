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
 * TomatoCart Products Helpers
 *
 * @package		TomatoCart
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

// ------------------------------------------------------------------------

/**
 * Get Product Id String
 *
 * Combine the variant values with product id to make a product id string
 *
 * @access public
 * @param $id products id
 * @param $params variants
 * @return string
 */
if( ! function_exists('get_product_id_string'))
{
    function get_product_id_string($id, $params)
    {
        $string = (int)$id;

        if (is_array($params) && !empty($params))
        {
            $variants_check = true;
            $variants_ids = array();

            //lei:sort the variant by the options id
            ksort($params);

            foreach ($params as $group => $value)
            {
                if (is_numeric($group) && is_numeric($value))
                {
                    $variants_ids[] = (int)$group . ':' . (int)$value;
                }
                else
                {
                    $variants_check = false;
                    break;
                }
            }

            if ($variants_check === true) {
                $string .= '::' . implode('-', $variants_ids);
            }
        }

        return $string;
    }
}

/**
 * Generate a numeric product id without product variant combinations
 *
 * @access public
 * @param string $id the product id
 * @return int
 */
if( ! function_exists('get_product_id'))
{
    function get_product_id($id)
    {
        if (is_numeric($id))
        {
            return $id;
        }

        $product = explode('::', $id, 2);

        return (int) $product[0];
    }
}

/**
 * Generate a product variant string from product id string
 *
 * @access public
 * @param string $id the product id
 * @return int
 */
if( ! function_exists('get_product_variants_string'))
{
    function get_product_variants_string($id)
    {
        if (is_numeric($id))
        {
            return NULL;
        }

        $product = explode('::', $id, 2);
        if (isset($product[1])) 
        {
            return $product[1];
        }
    }
}

/**
 * Parse a variants string into variant array
 *
 * @access public
 * @param string $id the product variants string
 * @return array
 */
if( ! function_exists('parse_variants_string'))
{
    function parse_variants_string($variants_string)
    {
        $variants = explode('-',$variants_string);
        $variants_array = array();

        foreach($variants as $variant)
        {
            $tmp = explode(':', $variant);

            if (is_numeric($tmp[0]) && is_numeric($tmp[1]))
            {
                $variants_array[$tmp[0]] = $tmp[1];
            }
        }

        return $variants_array;
    }
}

/**
 * Parse variants part of a products id string into variant array
 *
 * @access public
 * @param string $id the product variants string
 * @return array
 */
if( ! function_exists('parse_variants_from_id_string'))
{
    function parse_variants_from_id_string($products_id_string)
    {
        $variants = NULL;

        if (!is_numeric($products_id_string))
        {
            $tmp = explode('::', $products_id_string);
            if(isset($tmp[1]))
            $variants = parse_variants_string($tmp[1]);
        }

        return $variants;
    }
}

/* End of file products_helper.php */
/* Location: ./system/tomatocart/helpers/products_helper.php */