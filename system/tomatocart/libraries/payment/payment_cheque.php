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
class TOC_Payment_cheque extends TOC_Payment_Module
{
    /**
     * payment module code
     *
     * @access protected
     * @var string
     */
    var $code = 'cheque';

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
    array('name' => 'MODULE_PAYMENT_CHEQUE_STATUS',
              'title' => 'Enable Cheque Module', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'True',
              'description' => 'Do you want to accept cheque payments?',
              'values' => array(
    array('id' => 'True', 'text' => 'True'),
    array('id' => 'False', 'text' => 'False'))),
    array('name' => 'MODULE_PAYMENT_CHEQUE_ZONE',
              'title' => 'Payment Zone', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'If a zone is selected, only enable this payment method for that zone.',
              'action' => 'config/get_shipping_zone'),
    array('name' => 'MODULE_PAYMENT_CHEQUE_SORT_ORDER',
              'title' => 'Sort order of display.', 
              'type' => 'numberfield',
              'value' => '0',
              'description' => 'Sort order of display. Lowest is displayed first.'),
    array('name' => 'MODULE_PAYMENT_CHEQUE_ORDER_STATUS_ID',
              'title' => 'Set Order Status', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'Set the status of orders made with this payment module to this value',
              'action' => 'config/get_order_status'),
    array('name' => 'MODULE_PAYMENT_CHEQUE_ACCOUNT_OWNER',
              'title' => 'To the order of', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The name to who customers must send their cheque.'),
    array('name' => 'MODULE_PAYMENT_CHEQUE_ADDRESS',
              'title' => 'Address', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The address to which customers must send their cheque.'));

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function __construct() 
    {
        parent::__construct();

        $this->title = lang('payment_cheque_title');
        $this->method_title = lang('payment_cheque_method_title');
        $this->status = (isset($this->config['MODULE_PAYMENT_CHEQUE_STATUS']) && ($this->config['MODULE_PAYMENT_CHEQUE_STATUS'] == 'True')) ? TRUE : FALSE;
        $this->sort_order = isset($this->config['MODULE_PAYMENT_CHEQUE_SORT_ORDER']) ? $this->config['MODULE_PAYMENT_CHEQUE_SORT_ORDER'] : null;
    }

    /**
     * Initialize the shipping module
     *
     * @access public
     */
    function initialize()
    {
        if ($this->status === true)
        {
            if ((int)$this->config['MODULE_PAYMENT_CHEQUE_ORDER_STATUS_ID'] > 0)
            {
                $this->order_status = $this->config['MODULE_PAYMENT_CHEQUE_ORDER_STATUS_ID'];
            }

            if ((int)$this->config['MODULE_PAYMENT_CHEQUE_ZONE'] > 0)
            {
                $this->ci->load->model('address_model');
                
                $zones = $this->ci->address_model->get_zone_id_via_geo_zone($this->ci->shopping_cart->get_billing_address('country_id'), $this->config['MODULE_PAYMENT_CHEQUE_ZONE']);

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

                if ($check_flag == FALSE)
                {
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
    function confirmation() {
        global $osC_Language;

        $confirmation = array('title' => $this->method_title,
                          'fields' => array(array('title' => lang('payment_cheque_account_owner'),
                                                  'field' => $this->config['MODULE_PAYMENT_CHEQUE_ACCOUNT_OWNER']),
        array('title' => lang('payment_cheque_address'),
                                                  'field' => $this->config['MODULE_PAYMENT_CHEQUE_ADDRESS'])
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