<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * Currencies Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Currencies
{

    /**
     * ci instance
     *
     * @access protected
     * @var string
     */
    protected $ci = null;

    /**
     * all currencies
     *
     * @access protected
     * @var array
     */
    protected $currencies = array();

    /**
     * Toc Currencies Constructor
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci = get_instance();

        //Load currencies data from database
        $this->ci->load->model('currencies_model');
        $this->currencies = $this->ci->currencies_model->get_all();

        //initialize the currencies system
        $session_currency = $this->ci->session->userdata('currency');
        $post_currency = $this->ci->input->post('currency');
        if ($post_currency == NULL) {
            $post_currency = $this->ci->input->get('currency');
        }
        if (($session_currency === NULL) || ($post_currency !== NULL) || ( (config('USE_DEFAULT_LANGUAGE_CURRENCY') == '1') && ($this->get_code($this->ci->lang->get_currency_id()) != $session_currency) ) )
        {
            if (isset($post_currency) && $this->exists($post_currency))
            {
                $session_currency = $post_currency;
            }
            else
            {
                $session_currency = (config('USE_DEFAULT_LANGUAGE_CURRENCY') == '1') ? $this->get_code($this->ci->lang->get_currency_id()) : config('DEFAULT_CURRENCY');
            }

            $this->ci->session->set_userdata('currency', $session_currency);
        }
    }
    
    /**
     * Format currency value with currency symbol
     *
     * @param $number
     * @param $currency_code
     * @param $currency_value
     */
    public function format($number, $currency_code = '', $currency_value = '')
    {
        //if currency code is empty
        if (empty($currency_code) || ($this->exists($currency_code) == FALSE))
        {
            $currency_code = ($this->ci->session->userdata('currency') !== FALSE) ? $this->ci->session->userdata('currency') : config('DEFAULT_CURRENCY');
        }

        //if currency value is empty
        if (empty($currency_value) || (is_numeric($currency_value) == FALSE))
        {
            $currency_value = $this->currencies[$currency_code]['value'];
        }

        return $this->currencies[$currency_code]['symbol_left'] . number_format(round($number * $currency_value, $this->currencies[$currency_code]['decimal_places']), $this->currencies[$currency_code]['decimal_places'], $this->ci->lang->get_numeric_decimal_separator(), $this->ci->lang->get_numeric_thousands_separator()) . $this->currencies[$currency_code]['symbol_right'];
    }

    /**
     * Format currency raw value without symbol
     *
     * @param $number
     * @param $currency_code
     * @param $currency_value
     */
    function format_raw($number, $currency_code = '', $currency_value = '')
    {
        //if currency code is empty
        if (empty($currency_code) || ($this->exists($currency_code) == FALSE))
        {
            $currency_code = ($this->ci->session->userdata('currency') !== FALSE) ? $this->ci->session->userdata('currency') : config('DEFAULT_CURRENCY');
        }

        //if currency value is empty
        if (empty($currency_value) || (is_numeric($currency_value) == FALSE))
        {
            $currency_value = $this->currencies[$currency_code]['value'];
        }

        return number_format(round($number * $currency_value, $this->currencies[$currency_code]['decimal_places']), $this->currencies[$currency_code]['decimal_places'], '.', '');
    }

    /**
     * Add tax value to product price
     *
     * @param $price
     * @param $tax_rate
     * @param $quantity
     */
    function add_tax_rate_to_price($price, $tax_rate, $quantity = 1)
    {
        $price = round($price, $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);

        if ( (config('DISPLAY_PRICE_WITH_TAX') == '1') && ($tax_rate > 0) )
        {
            $price += round($price * ($tax_rate / 100), $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);
        }

        return round($price * $quantity, $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);
    }

    /**
     * Display total price for certain amount of products with currency symbol
     *
     * @param $price
     * @param $tax_rate
     * @param $quantity
     */
    function display_price($price, $tax_class_id, $quantity = 1, $currency_code = null, $currency_value = null)
    {
        $price = round($price, $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);

        if ( (config('DISPLAY_PRICE_WITH_TAX') == '1') && ($tax_class_id > 0) )
        {
            $price += round($price * ($this->ci->tax->get_tax_rate($tax_class_id) / 100), $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);
        }

        return $this->format($price * $quantity, $currency_code, $currency_value);
    }

    /**
     * Display total price with tax value for certain amount of products with currency symbol
     *
     * @param $price
     * @param $tax_rate
     * @param $quantity
     * @param $currency_code
     * @param $currency_value
     */
    function display_price_with_tax_rate($price, $tax_rate, $quantity = 1, $currency_code = '', $currency_value = '')
    {
        $price = round($price, $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);

        if ( (config('DISPLAY_PRICE_WITH_TAX') == '1') && ($tax_rate > 0) )
        {
            $price += round($price * ($tax_rate / 100), $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);
        }

        return $this->format($price * $quantity, $currency_code, $currency_value);
    }

    /**
     * Display raw price (without tax value) with symbol
     *
     * @param $number
     * @param $currency_code
     */
    function display_raw_price($number, $currency_code = '')
    {
        if (empty($currency_code) || ($this->exists($currency_code) == FALSE))
        {
            $currency_code = ($this->ci->session->userdata('currency') !== FALSE) ? $this->ci->session->userdata('currency') : config('DEFAULT_CURRENCY');
        }

        return $this->currencies[$currency_code]['symbol_left'] . number_format(round($number, $this->currencies[$currency_code]['decimal_places']), $this->currencies[$currency_code]['decimal_places'], $this->ci->lang->get_numeric_decimal_separator(), $this->ci->lang->get_numeric_thousands_separator()) . $this->currencies[$currency_code]['symbol_right'];
    }

    /**
     * Check whether the currencies code exist.
     *
     * @param $code
     */
    function exists($code)
    {
        if (isset($this->currencies[$code]))
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Get decimal places of specified currency code.
     *
     * @param $code
     */
    function decimal_places($code)
    {
        if ($this->exists($code))
        {
            return $this->currencies[$code]['decimal_places'];
        }

        return FALSE;
    }

    /**
     * Get currency value of specified currency
     *
     * @param $code
     */
    function value($code)
    {
        if ($this->exists($code))
        {
            return $this->currencies[$code]['value'];
        }

        return FALSE;
    }

    /**
     * Get all currencies data
     */
    function get_data()
    {
        return $this->currencies;
    }


    /**
     * Get all currencies
     */
    function get_currencies()
    {
        return $this->currencies;
    }

    /**
     * Get currencies title by id
     *
     * @param $id
     */
    function get_title($id = '')
    {
        if (is_numeric($id))
        {
            foreach ($this->currencies as $key => $value)
            {
                if ($value['id'] == $id)
                {
                    return $value['title'];
                }
            }
        }
        else
        {
            $code = $this->ci->session->userdata('currency');

            foreach ($this->currencies as $key => $value)
            {
                if ($key == $code)
                {
                    return $value['title'];
                }
            }
        }
        
        return NULL;
    }

    /**
     * Get currencies code by currency id
     *
     * @param $id
     */
    function get_code($id = '')
    {
        if (is_numeric($id))
        {
            foreach ($this->currencies as $key => $value)
            {
                if ($value['id'] == $id)
                {
                    return $key;
                }
            }
        }
        else
        {
            return $this->ci->session->userdata('currency');
        }
    }

    /**
     * Get symbol left by currency id
     *
     * @param $id
     */
    function get_symbol_left($id = '')
    {
        if (is_numeric($id))
        {
            foreach ($this->currencies as $key => $value)
            {
                if ($value['id'] == $id)
                {
                    return $value['symbol_left'];
                }
            }
        }
        else
        {
            return $this->currencies[config('DEFAULT_CURRENCY')]['symbol_left'];
        }
    }

    /**
     * Get symbol right by currency id
     *
     * @param $id
     */
    function get_symbol_right($id = '')
    {
        if (is_numeric($id))
        {
            foreach ($this->currencies as $key => $value)
            {
                if ($value['id'] == $id)
                {
                    return $value['symbol_right'];
                }
            }
        }
        else
        {
            return $this->currencies[config('DEFAULT_CURRENCY')]['symbol_right'];
        }
    }

    /**
     * Get decimal places by currency id
     *
     * @param $id
     */
    function get_decimal_places($id = '')
    {
        if (is_numeric($id))
        {
            foreach ($this->currencies as $key => $value)
            {
                if ($value['id'] == $id)
                {
                    return $value['decimal_places'];
                }
            }
        }
        else
        {
            return $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places'];
        }
    }

    /**
     * Get id by code
     *
     * @param $code
     */
    function get_id($code = '')
    {
        if (empty($code))
        {
            $code = $this->ci->session->userdata('currency');
        }

        return $this->currencies[$code]['id'];
    }
}
// END Currencies Class

/* End of file currencies.php */
/* Location: ./system/tomatocart/libraries/currencies.php */