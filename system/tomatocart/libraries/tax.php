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
 * Tax Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Tax
{
    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    protected $ci = null;

    /**
     * tax rates
     *
     * @access protected
     * @var array
     */
    protected $tax_rates = array();

    /**
     * Toc Tax Constructor
     */
    function __construct() {
        $this->ci = get_instance();

        $this->ci->load->model('tax_model');
    }

    /**
     * Get tax rate
     *
     * @param $class_id
     * @param $country_id
     * @param $zone_id
     */
    function get_tax_rate($class_id, $country_id = -1, $zone_id = -1)
    {
        //if country and zone is uninitialized
        if ( ($country_id == -1) && ($zone_id == -1) )
        {
            $country_id = $this->ci->shopping_cart->get_taxing_address('country_id');
            $zone_id = $this->ci->shopping_cart->get_taxing_address('zone_id');
        }

        //if the tax rate is not set
        if (!isset($this->tax_rates[$class_id][$country_id][$zone_id]['rate']))
        {
            $tax_rates = $this->ci->tax_model->get_tax_rate($country_id, $zone_id, $class_id);

            if ($tax_rates !== NULL)
            {
                $tax_multiplier = 1.0;
                foreach($tax_rates as $tax_rate)
                {
                    $tax_multiplier *= 1.0 + ($tax_rate['tax_rate'] / 100);
                }

                $tax_rate = ($tax_multiplier - 1.0) * 100;
            }
            else
            {
                $tax_rate = 0;
            }

            $this->tax_rates[$class_id][$country_id][$zone_id]['rate'] = $tax_rate;
        }

        return $this->tax_rates[$class_id][$country_id][$zone_id]['rate'];
    }

    /**
     * Get tax rate description
     *
     * @param $class_id
     * @param $country_id
     * @param $zone_id
     */
    function get_tax_rate_description($class_id, $country_id, $zone_id)
    {
        if (!isset($this->tax_rates[$class_id][$country_id][$zone_id]['description']))
        {
            $descriptons = $this->ci->tax_model->get_tax_rate_description($country_id, $zone_id, $class_id);

            if ($descriptons !== NULL)
            {
                $this->tax_rates[$class_id][$country_id][$zone_id]['description'] = implode(' + ', $descriptons);
            }
            else
            {
                $this->tax_rates[$class_id][$country_id][$zone_id]['description'] = lang('tax_rate_unknown');
            }
        }

        return $this->tax_rates[$class_id][$country_id][$zone_id]['description'];
    }

    /**
     * Calculate tax.
     *
     * @param $price
     * @param $tax_rate
     */
    function calculate($price, $tax_rate)
    {
        return round($price * $tax_rate / 100, $this->ci->currencies->get_decimal_places());
    }

    /**
     * display tax rate value.
     *
     * @param $value
     * @param $padding
     */
    function display_tax_rate_value($value, $padding = NULL)
    {
        if (!is_numeric($padding))
        {
            $padding = config('TAX_DECIMAL_PLACES');
        }

        if (strpos($value, '.') !== FALSE)
        {
            while (true)
            {
                if (substr($value, -1) == '0')
                {
                    $value = substr($value, 0, -1);
                }
                else
                {
                    if (substr($value, -1) == '.') {
                        $value = substr($value, 0, -1);
                    }

                    break;
                }
            }
        }

        if ($padding > 0)
        {
            if (($decimal_pos = strpos($value, '.')) !== FALSE)
            {
                $decimals = strlen(substr($value, ($decimal_pos+1)));

                for ($i = $decimals; $i < $padding; $i++)
                {
                    $value .= '0';
                }
            }
            else
            {
                $value .= '.';

                for ($i = 0; $i < $padding; $i++)
                {
                    $value .= '0';
                }
            }
        }

        return $value . '%';
    }
}
// END Tax Class

/* End of file tax.php */
/* Location: ./system/tomatocart/libraries/tax.php */