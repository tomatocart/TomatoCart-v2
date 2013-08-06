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

class TOC_Order_Total_tax extends TOC_Order_Total_Module
{
    /**
     * order total module code
     *
     * @access protected
     * @var string
     */
    var $code = 'tax';
    
    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
        array('name' => 'MODULE_ORDER_TOTAL_TAX_STATUS',
                        'title' => 'Display Tax', 
                        'type' => 'combobox',
                        'mode' => 'local',
                        'value' => 'true',
                        'description' => 'Do you want to display the order tax value?',
                        'values' => array(
                            array('id' => 'true', 'text' => 'True'),
                            array('id' => 'false', 'text' => 'False'))),
        array('name' => 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER',
                        'title' => 'Sort Order', 
                        'type' => 'numberfield',
                        'value' => '50',
                        'description' => 'Sort order of display.'));

    /**
     * Constructor
     *
     * @access public
     */
    function __construct() {
        parent::__construct();

        $this->title = lang('order_total_tax_title');
        $this->description = lang('order_total_tax_description');
        $this->status = (isset($this->config['MODULE_ORDER_TOTAL_TAX_STATUS']) && ($this->config['MODULE_ORDER_TOTAL_TAX_STATUS'] == 'true') ? TRUE : FALSE);
        $this->sort_order = (isset($this->config['MODULE_ORDER_TOTAL_TAX_SORT_ORDER']) ? $this->config['MODULE_ORDER_TOTAL_TAX_SORT_ORDER'] : NULL);

    }

    /**
     * Process the total
     */
    function process()
    {
        $this->output = array();

        foreach ($this->ci->shopping_cart->get_tax_groups() as $key => $value)
        {
            if ($value > 0)
            {
                $this->output[] = array('title' => $key . ':',
                                        'text' => $this->ci->currencies->format($value),
                                        'value' => $value);
            }
        }
    }
}