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
 * Low Order Fee -- Shipping Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Order_Total_low_order_fee extends TOC_Order_Total_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'low_order_fee';

    /**
     * Template Module Author Name
     *
     * @access private
     * @var string
     */
    var $author_name = 'TomatoCart';

    /**
     * Template Module Author Url
     *
     * @access private
     * @var string
     */
    var $author_url = 'http://www.tomatocart.com';

    /**
     * Template Module Version
     *
     * @access private
     * @var string
     */
    var $version = '1.0';

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
        array('name' => 'MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS',
              'title' => 'Display Low Order Fee', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'true',
              'description' => 'Do you want to display the low order fee?',
              'values' => array(
                  array('id' => 'true', 'text' => 'True'),
                  array('id' => 'false', 'text' => 'False'))),
        array('name' => 'MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER',
              'title' => 'Sort Order', 
              'type' => 'numberfield',
              'value' => '30',
              'description' => 'Sort order of display.'),
        array('name' => 'MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE',
              'title' => 'Allow Low Order Fee', 
              'type' => 'combobox',
              'mode' => 'local',
		  	  'value' => 'false',
              'description' => 'Do you want to allow low order fees?',
              'values' => array(
                  array('id' => 'true', 'text' => 'True'),
                  array('id' => 'false', 'text' => 'False'))),
        array('name' => 'MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER',
              'title' => 'Order Fee For Orders Under', 
              'type' => 'numberfield',
              'value' => '50',
              'description' => 'Add the low order fee to orders under this amount.'),
        array('name' => 'MODULE_ORDER_TOTAL_LOWORDERFEE_FEE',
              'title' => 'Order Fee', 
              'type' => 'numberfield',
              'value' => '5',
              'description' => 'Low order fee.'),
        array('name' => 'MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION',
              'title' => 'Attach Low Order Fee On Orders Made', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'both',
              'description' => 'Attach low order fee for orders sent to the set destination.',
              'values' => array(
                  array('id' => 'national', 'text' => 'National'),
                  array('id' => 'international', 'text' => 'International'),
                  array('id' => 'both', 'text' => 'Both'))),
        array('name' => 'MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS',
              'title' => 'Tax Class', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'Use the following tax class on the low order fee.',
              'action' => 'config/get_tax_class'));

    /**
     * Constructor
     *
     * @access public
     */
    function __construct() {
        parent::__construct();

        $this->code = 'low_order_fee';
        $this->title = lang('order_total_loworderfee_title');
        $this->description = lang('order_total_loworderfee_description');
        $this->status = (isset($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS']) && ($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS'] == 'true') ? TRUE : FALSE);
        $this->sort_order = (isset($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER']) ? $this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER'] : 0);
    }

    /**
     * Process the total
     */
    function process() {
        $this->output = array();

        if ($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE'] == 'true')
        {
            switch ($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION'])
            {
                case 'national':
                    if ($this->ci->shopping_cart->get_shipping_address('country_id') == $this->config['STORE_COUNTRY'])
                    {
                        $pass = true;
                    }
                    break;

                case 'international':
                    if ($this->ci->shopping_cart->get_shipping_address('country_id') != $this->config['STORE_COUNTRY'])
                    {
                        $pass = true;
                    }
                    break;

                case 'both':
                    $pass = true;
                    break;

                default:
                    $pass = false;
            }

            if ( ($pass == true) && ($this->ci->shopping_cart->get_sub_total() < $this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER']) )
            {
                $tax = $this->ci->tax->get_tax_rate($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS'], $this->ci->shopping_cart->get_taxing_address('country_id'), $this->ci->shopping_cart->get_taxing_address('zone_id'));
                $tax_description = $this->ci->tax->get_tax_rate_description($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS'], $this->ci->shopping_cart->get_taxing_address('country_id'), $this->ci->shopping_cart->get_taxing_address('zone_id'));

                $this->ci->shopping_cart->add_tax_amount($this->ci->tax->calculate($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_FEE'], $tax));
                $this->ci->shopping_cart->add_tax_group($tax_description, $this->ci->tax->calculate($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_FEE'], $tax));
                $this->ci->shopping_cart->add_to_total($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_FEE'] + $this->ci->tax->calculate($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_FEE'], $tax));

                $this->output[] = array('title' => $this->title . ':',
                                        'text' => $this->ci->currencies->display_price_with_tax_rate($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_FEE'], $tax),
                                        'value' => $this->ci->currencies->add_tax_rate_to_price($this->config['MODULE_ORDER_TOTAL_LOWORDERFEE_FEE'], $tax));
            }
        }
    }
}

/* End of file order_total_low_order_fee.php */
/* Location: ./system/tomatocart/libraries/order_total/order_total_low_order_fee.php */