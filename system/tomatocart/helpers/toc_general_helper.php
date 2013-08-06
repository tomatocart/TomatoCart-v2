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
 * TomatoCart General Helpers
 *
 * @package		TomatoCart
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

/**
 * Gets a configuration option value
 *
 * @access public
 * @param $key The configuration name
 * @return string
 */
if( ! function_exists('config'))
{
    function config($key)
    {
        $CI =& get_instance();
        $line = $CI->configuration->line($key);

        return $line;
    }
}

/**
 * Encrypt password
 *
 * @access public
 * @param $plain the password
 * @return string encrypted password
 */
if( ! function_exists('encrypt_password'))
{
    function encrypt_password($plain)
    {
        $password = '';

        for ($i=0; $i<10; $i++)
        {
            $password .= mt_rand();
        }

        $salt = substr(md5($password), 0, 2);

        $password = md5($salt . $plain) . ':' . $salt;

        return $password;
    }
}

/**
 * Parse and output a user submited value
 *
 * @access public
 * @param string $string The string to parse and output
 * @param array $translate An array containing the characters to parse
 * @return string
 */
if( ! function_exists('output_string'))
{
    function output_string($string, $translate = NULL)
    {
        if (empty($translate))
        {
            $translate = array('"' => '&quot;');
        }

        return strtr(trim($string), $translate);
    }
}


/**
 * Strictly parse and output a user submited value
 *
 * @param string $string The string to strictly parse and output
 * @access public
 */
if( ! function_exists('output_string_protected'))
{
    function output_string_protected($string)
    {
        return htmlspecialchars(trim($string));
    }
}


/**
 * Display Image
 *
 * @access public
 * @param string $image the image to show
 * @return string
 */
if( ! function_exists('image_url'))
{
    function image_url($image)
    {
        return base_url() . 'images/' . $image;
    }
}


/**
 * Display Product Image
 *
 * @access public
 * @param string $image the image to show
 * @param string $group image group
 * @return string
 */
if( ! function_exists('product_image_url'))
{
    function product_image_url($image, $group = 'thumbnails')
    {
        return base_url() . 'images/products/' . $group . '/' . $image;
    }
}


/**
 * Validates the format of an email address
 *
 * @param string $email_address The email address to validate
 * @access public
 */
if( ! function_exists('address_format'))
{
    function address_format($address, $new_line = "\n")
    {
        //get instance
        $ci = &get_instance();

        //load address book model
        $ci->load->model('address_book_model');
        $ci->load->model('address_model');

        //get address
        if (is_numeric($address))
        {
            $address = $ci->address_book_model->get_address(NULL, $address);
        }

        $firstname = $lastname = '';

        if (isset($address['firstname']) && !empty($address['firstname']))
        {
            $firstname = $address['firstname'];
            $lastname = $address['lastname'];
        }
        elseif (isset($address['name']) && !empty($address['name']))
        {
            $firstname = $address['name'];
        }

        $state = $address['state'];
        $state_code = $address['zone_code'];

        if (isset($address['zone_id']) && is_numeric($address['zone_id']) && ($address['zone_id'] > 0))
        {
            $state = $ci->address_model->get_zone_name($address['zone_id']);
            $state_code = $ci->address_model->get_zone_code($address['zone_id']);
        }

        $country = $address['countries_name'];

        if (empty($country) && isset($address['country_id']) && is_numeric($address['country_id']) && ($address['country_id'] > 0))
        {
            $country = $ci->address_model->get_country_name($address['country_id']);
        }

        if (isset($address['format'])) 
        {
            $address_format = $address['format'];
        } 
        elseif (isset($address['country_id']) && is_numeric($address['country_id']) && ($address['country_id'] > 0)) 
        {
            $address_format = $ci->address_model->get_format($address['country_id']);
        }

        if (empty($address['address_format']))
        {
            $address['address_format'] = ":name\n:street_address\n:postcode :city\n:country";
        }

        $find_array = array('/\:name\b/',
                            '/\:street_address\b/',
                            '/\:suburb\b/',
                            '/\:city\b/',
                            '/\:postcode\b/',
                            '/\:state\b/',
                            '/\:state_code\b/',
                            '/\:country\b/');


        $replace_array = array($firstname . ' ' . $lastname,
                               empty($address['street_address']) ? ' ' : $address['street_address'],
                               empty($address['suburb']) ? ' ' : $address['suburb'],
                               empty($address['city']) ? ' ' : $address['city'],
                               empty($address['postcode']) ? ' ' : $address['postcode'],
                               $state,
                               $state_code,
                               $country);
                               
        $formated = preg_replace($find_array, $replace_array, $address['address_format']);

        if ( (config('ACCOUNT_COMPANY') > -1) && !empty($address['company']) )
        {
            $company = $address['company'];

            $formated = $company . $new_line . $formated;
        }

        if ($new_line != "\n")
        {
            $formated = str_replace("\n", $new_line, $formated);
        }

        return $formated;
    }
}


/**
 * Execute service module
 *
 * @access public
 * @param $module
 * @return void
 */
if( ! function_exists('run_service'))
{
    function run_service($module)
    {
        //get instance
        $ci = get_instance();

        //run service
        $ci->service->run($module);
    }
}


/**
 * Create a random string
 *
 * @param int $length The length of the random string to create
 * @param string $type The type of random string to create (mixed, chars, digits)
 * @access public
 * @return string
 */
if( ! function_exists('create_random_string'))
{
    function create_random_string($length, $type = 'mixed')
    {
        if (!in_array($type, array('mixed', 'chars', 'digits')))
        {
            return false;
        }

        $chars_pattern = 'abcdefghijklmnopqrstuvwxyz';
        $mixed_pattern = '1234567890' . $chars_pattern;

        $rand_value = '';

        while (strlen($rand_value) < $length)
        {
            if ($type == 'digits')
            {
                $rand_value .= rand(0,9);
            }
            elseif ($type == 'chars')
            {
                $rand_value .= substr($chars_pattern, rand(0, 25), 1);
            }
            else
            {
                $rand_value .= substr($mixed_pattern, rand(0, 35), 1);
            }
        }

        return $rand_value;
    }
}


/**
 * Short function to load product library and return the object
 *
 * @param int $products_id
 * @access public
 * @return object
 */
if( ! function_exists('load_product_library'))
{
    function load_product_library($products_id)
    {
        $id = get_product_id($products_id); //get products id part and omit the variants part

        //get ci instance
        $CI =& get_instance();

        //load library
        $CI->load->library('product', $products_id, 'product_' . $products_id);

        //return the object
        return $CI->{'product_' . $products_id};
    }
}


/**
 * Short function to check whether the customer is logged on
 *
 * @access public
 * @return object
 */
if( ! function_exists('is_logged_on'))
{
    function is_logged_on()
    {
        //get ci instance
        $CI =& get_instance();

        return $CI->customer->is_logged_on();
    }
}


/**
 * Short function to get number of items in the shopping cart
 *
 * @access public
 * @return object
 */
if( ! function_exists('cart_item_count'))
{
    function cart_item_count()
    {
        //get ci instance
        $CI =& get_instance();

        return $CI->shopping_cart->number_of_items();
    }
}


/**
 * Return shopping cart object
 *
 * @access public
 * @return object
 */
if( ! function_exists('get_shopping_cart'))
{
    function get_shopping_cart()
    {
        //get ci instance
        $CI =& get_instance();

        return $CI->shopping_cart;
    }
}

/**
 * Get the IP address of the client
 *
 * @access public
 */
if (!function_exists('get_ip_address'))
{
    function get_ip_address()
    {
        if (isset($_SERVER))
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            elseif (isset($_SERVER['HTTP_CLIENT_IP']))
            {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
            else
            {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }
        else
        {
            if (getenv('HTTP_X_FORWARDED_FOR'))
            {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            }
            elseif (getenv('HTTP_CLIENT_IP'))
            {
                $ip = getenv('HTTP_CLIENT_IP');
            }
            else
            {
                $ip = getenv('REMOTE_ADDR');
            }
        }

        return $ip;
    }
}

/**
 * Truncate Html
 *
 * @access public
 * @return object
 */
if( ! function_exists('truncate_html'))
{
    function truncate_html($text, $length = 100, $ending = '...', $exact = false, $consider_html = true, $ending_count = false) {
        if ($consider_html) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            if ($ending_count) {
                $total_length = 0;
            } else {
                $total_length = strlen($ending);
            }

            $open_tags = array();
            $truncate = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag
                    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length> $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if($total_length>= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if($consider_html) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }
        return $truncate;
    }
}

/* End of file general_helper.php */
/* Location: ./system/tomatocart/helpers/general_helper.php */