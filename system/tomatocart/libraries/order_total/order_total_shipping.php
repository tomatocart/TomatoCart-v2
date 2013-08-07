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

require_once 'order_total_module.php';

/**
 * Free Shipping -- Shipping Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Order_Total_shipping extends TOC_Order_Total_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'shipping';
    
    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
        array('name' => 'MODULE_ORDER_TOTAL_SHIPPING_STATUS',
                        'title' => 'Display Shipping', 
                        'type' => 'combobox',
                        'mode' => 'local',
                        'value' => 'true',
                        'description' => 'Do you want to display the order shipping cost?',
                        'values' => array(
                            array('id' => 'true', 'text' => 'True'),
                            array('id' => 'false', 'text' => 'False'))),
        array('name' => 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER',
                        'title' => 'Sort Order', 
                        'type' => 'numberfield',
                        'value' => '20',
                        'description' => 'Sort order of display.'));

    /**
     * Constructor
     *
     * @access public
     */
    function __construct() {
        parent::__construct();

        $this->code = 'shipping';
        $this->title = lang('order_total_shipping_title');
        $this->description = lang('order_total_shipping_description');
        $this->status = (isset($this->config['MODULE_ORDER_TOTAL_SHIPPING_STATUS']) && ($this->config['MODULE_ORDER_TOTAL_SHIPPING_STATUS'] == 'true') ? TRUE : FALSE);
        $this->sort_order = (isset($this->config['MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER']) ? $this->config['MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER'] : NULL);

    }

    /**
     * Process the total
     */
    function process()
    {
        $this->output = array();

        if ($this->ci->shopping_cart->get_content_type() == 'virtual')
        {
            $this->output[] = NULL;
        }
        else
        {
            if ($this->ci->shopping_cart->has_shipping_method())
            {
                //append the shipping method id to the end of code for order editor usage
                $this->code = $this->code . '-' . $this->ci->shopping_cart->get_shipping_method('id');

                //print_r($this->ci->shopping_cart->get_shipping_method());exit;
                $this->ci->shopping_cart->add_to_total($this->ci->shopping_cart->get_shipping_method('cost'));

                if ($this->ci->shopping_cart->get_shipping_method('tax_class_id') > 0)
                {
                    $tax = $this->ci->tax->get_tax_rate($this->ci->shopping_cart->get_shipping_method('tax_class_id'), $this->ci->shopping_cart->get_shipping_address('country_id'), $this->ci->shopping_cart->get_shipping_address('zone_id'));
                    $tax_description = $this->ci->tax->get_tax_rate_description($this->ci->shopping_cart->get_shipping_method('tax_class_id'), $this->ci->shopping_cart->get_shipping_address('country_id'), $this->ci->shopping_cart->get_shipping_address('zone_id'));

                    $this->ci->shopping_cart->add_tax_amount($this->ci->tax->calculate($this->ci->shopping_cart->get_shipping_method('cost'), $tax));
                    $this->ci->shopping_cart->add_tax_group($tax_description, $this->ci->tax->calculate($this->ci->shopping_cart->get_shipping_method('cost'), $tax));

                    //osc3 bug
                    $this->ci->shopping_cart->add_to_total($this->ci->tax->calculate($this->ci->shopping_cart->get_shipping_method('cost'), $tax));

                    if (config('DISPLAY_PRICE_WITH_TAX') == '1') {
                        $this->ci->shopping_cart->shipping_method['cost'] += $this->ci->tax->calculate($this->ci->shopping_cart->get_shipping_method('cost'), $tax);
                        //osc3 bug, no matter tax is displayed or not, all tax has to be add to total
                        //$this->ci->shopping_cart->add_to_total($this->ci->tax->calculate($this->ci->shopping_cart->get_shipping_method('cost'), $tax));
                    }
                }

                $this->output[] = array('title' => $this->ci->shopping_cart->get_shipping_method('title') . ':',
                                        'text' => $this->ci->currencies->format($this->ci->shopping_cart->get_shipping_method('cost')),
                                        'value' => $this->ci->shopping_cart->get_shipping_method('cost'));
            }
        }
    }
}

/* End of file order_total_shipping.php */
/* Location: ./system/tomatocart/libraries/order_total/order_total_shipping.php */