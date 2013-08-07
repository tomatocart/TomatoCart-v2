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

require_once 'payment_module.php';

/**
 * Bank Wire -- Payment Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Payment_western_union extends TOC_Payment_Module
{
    /**
     * payment module code
     *
     * @access protected
     * @var string
     */
    var $code = 'western_union';

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
    array('name' => 'MODULE_PAYMENT_WESTERN_UNION_STATUS',
              'title' => 'Enable Western Union Module', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'True',
              'description' => 'Do you want to accept west union payments?',
              'values' => array(
    array('id' => 'True', 'text' => 'True'),
    array('id' => 'False', 'text' => 'False'))),
    array('name' => 'MODULE_PAYMENT_WESTERN_UNION_ZONE',
              'title' => 'Payment Zone', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'If a zone is selected, only enable this payment method for that zone.',
              'action' => 'config/get_shipping_zone'),
    array('name' => 'MODULE_PAYMENT_WESTERN_UNION_SORT_ORDER',
              'title' => 'Sort order of display.', 
              'type' => 'numberfield',
              'value' => '0',
              'description' => 'Sort order of display. Lowest is displayed first.'),
    array('name' => 'MODULE_PAYMENT_WESTERN_UNION_ORDER_STATUS_ID',
              'title' => 'Set Order Status', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'Set the status of orders made with this payment module to this value',
              'action' => 'config/get_order_status'),
    array('name' => 'MODULE_PAYMENT_WESTERN_UNION_STORE_ONWER_NAME',
              'title' => 'Name of store owner', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The name of store onwer'),
    array('name' => 'MODULE_PAYMENT_WESTERN_UNION_STORE_ONWER_COUNTRY',
              'title' => 'Country', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The country of store onwer'),
    array('name' => 'MODULE_PAYMENT_WESTERN_UNION_STORE_ONWER_CITY',
              'title' => 'City', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The city of store onwer'),
    array('name' => 'MODULE_PAYMENT_WESTERN_UNION_STORE_ONWER_TELEPHONE',
              'title' => 'Telephone', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The telephone of store onwer by which the customer can contact to you.'),
    array('name' => 'MODULE_PAYMENT_WESTERN_UNION_STORE_ONWER_EMAIL',
              'title' => 'Email', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The email of store onwer'));

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function __construct() 
    {
        parent::__construct();

        $this->title = lang('payment_western_union_title');
        $this->method_title = lang('payment_western_union_method_title');
        $this->status = (isset($this->config['MODULE_PAYMENT_WESTERN_UNION_STATUS']) && ($this->config['MODULE_PAYMENT_WESTERN_UNION_STATUS'] == 'True')) ? TRUE : FALSE;
        $this->sort_order = isset($this->config['MODULE_PAYMENT_WESTERN_UNION_SORT_ORDER']) ? $this->config['MODULE_PAYMENT_WESTERN_UNION_SORT_ORDER'] : null;
    }

    /**
     * Initialize the shipping module
     *
     * @access public
     */
    function initialize()
    {
        if ($this->status === TRUE)
        {
            if ((int)$this->config['MODULE_PAYMENT_WESTERN_UNION_ORDER_STATUS_ID'] > 0)
            {
                $this->order_status = $this->config['MODULE_PAYMENT_WESTERN_UNION_ORDER_STATUS_ID'];
            }

            if ((int)$this->config['MODULE_PAYMENT_WESTERN_UNION_ZONE'] > 0)
            {
                $this->ci->load->model('address_model');
                
                $zones = $this->ci->address_model->get_zone_id_via_geo_zone($this->ci->shopping_cart->get_billing_address('country_id'), $this->config['MODULE_PAYMENT_WESTERN_UNION_ZONE']);

                $check_flag = FALSE;
                if ($zones !== NULL)
                {
                    foreach($zones as $zone_id)
                    {
                        if ($zone_id < 1)
                        {
                            $check_flag = TRUE;
                            break;
                        }
                        elseif ($zone_id == $this->ci->shopping_cart->get_billing_address('zone_id'))
                        {
                            $check_flag = TRUE;
                            break;
                        }
                    }
                }

                if ($check_flag == FALSE) {
                    $this->status = FALSE;
                }
            }
        }
    }

    /**
     * Get selected payment module
     *
     * @access public
     * @return payment module selection
     */
    function selection()
    {
        return array('id' => $this->code, 'module' => $this->method_title);
    }

    /**
     * Get selected payment module
     *
     * @access public
     * @return payment module selection
     */
    function confirmation()
    {
        $confirmation = array('title' => $this->method_title,
		                      'fields' => array(array('title' => lang('payment_western_union_owner_name'),
		                                              'field' => $this->config['MODULE_PAYMENT_WESTERN_UNION_STORE_ONWER_NAME']),
        array('title' => lang('payment_western_union_owner_country'),
																						      'field' => $this->config['MODULE_PAYMENT_WESTERN_UNION_STORE_ONWER_COUNTRY']),
        array('title' => lang('payment_western_union_owner_city'),
																						      'field' => $this->config['MODULE_PAYMENT_WESTERN_UNION_STORE_ONWER_CITY']),
        array('title' => lang('payment_western_union_owner_telephone'),
																						      'field' => $this->config['MODULE_PAYMENT_WESTERN_UNION_STORE_ONWER_TELEPHONE']),
        array('title' => lang('payment_western_union_owner_email'),
																						      'field' => $this->config['MODULE_PAYMENT_WESTERN_UNION_STORE_ONWER_EMAIL'])
        ));

        return $confirmation;
    }

    /**
     * Process the payment module
     *
     * @access public
     * @return void
     */
    function process() {
        $this->ci->load->library('order');
        
        $orders_id = $this->ci->order->create_order();
        $this->ci->order->process($orders_id, $this->order_status);
    }
}