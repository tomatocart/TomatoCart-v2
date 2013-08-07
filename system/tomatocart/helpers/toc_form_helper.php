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
 * TomatoCart Form Helpers
 *
 * @package		TomatoCart
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

// ------------------------------------------------------------------------

/**
 * Outputs a label for form field elements
 *
 * @access public
 * @param $text The text to use as the form field label
 * @param $for The ID of the form field element to assign the label to
 * @param  $access_key The access key to use for the form field element
 * @param $required A flag to show if the form field element requires input or not
 * @return string label content
 */
if( ! function_exists('draw_label'))
{
    function draw_label($text, $for, $access_key = null, $required = false, $parameters = null)
    {
        if (!is_bool($required))
        {
            $required = false;
        }

        return '<label' . (!empty($for) ? ' for="' . output_string($for) . '"' : '') . (!empty($access_key) ? ' accesskey="' . output_string($access_key) . '"' : '') . (!empty($parameters) ? ' ' . $parameters : '') . '>' . output_string($text) . ($required === true ? '<em>*</em>' : '') . '</label>';
    }
}


/**
 * Validates the format of an email address
 *
 * @access public
 * @param string $email_address
 * @return boolean
 */
if( ! function_exists('validate_email_address'))
{
    function validate_email_address($email_address)
    {
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email_address)) ? FALSE : TRUE;
    }
}

/**
 * Output validation errors in html format
 *
 * @access public
 * @param string $field
 * @return string
 */
if( ! function_exists('toc_validation_errors'))
{
    function toc_validation_errors($field)
    {
        $CI =& get_instance();
        return $CI->message_stack->output($field);
    }
}

/**
 * Output validation errors in plain text format
 *
 * @access public
 * @param string $field
 * @return string
 */
if( ! function_exists('toc_validation_errors_plain'))
{
    function toc_validation_errors_plain($field)
    {
        $CI =& get_instance();
        return $CI->message_stack->output_plain($field);
    }
}

/* End of file form_helper.php */
/* Location: ./system/tomatocart/helpers/form_helper.php */