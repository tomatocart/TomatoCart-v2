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
 * Paypal Standard -- Payment Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Payment_paypal_direct extends TOC_Payment_Module
{
    /**
     * payment module code
     *
     * @access protected
     * @var string
     */
    protected $code = 'paypal_direct';

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_STATUS',
              'title' => 'Enable PayPal Direct Module', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'True',
              'description' => 'Do you want to accept PayPal Direct payments?',
              'values' => array(array('id' => 'True', 'text' => 'True'),
                                array('id' => 'False', 'text' => 'False'))),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_API_USERNAME',
              'title' => 'API Username', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The username to use for the PayPal Web Services API.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_API_PASSWORD',
              'title' => 'API Password', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The password to use for the PayPal Web Services API.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_API_SIGNATURE',
              'title' => 'API Signature', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The location of the PayPal Direct Signature for the PayPal Web Services API.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_SERVER',
              'title' => 'Transaction Server', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'Sandbox',
              'description' => 'The server to perform transactions in.',
              'values' => array(array('id' => 'Production', 'text' => 'Production'),
                                array('id' => 'Sandbox', 'text' => 'Sandbox'))),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_METHOD',
              'title' => 'Transaction Method', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'Sandbox',
              'description' => 'The method to perform transactions in.',
              'values' => array(array('id' => 'Athorization', 'text' => 'Athorization'),
                                array('id' => 'Sale', 'text' => 'Sale'))),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_SORT_ORDER',
              'title' => 'Sort order of display.', 
              'type' => 'numberfield',
              'value' => '0',
              'description' => 'Sort order of display. Lowest is displayed first.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_ZONE',
              'title' => 'Payment Zone', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'If a zone is selected, only enable this payment method for that zone.',
              'action' => 'config/get_shipping_zone'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_ORDER_STATUS_ID',
              'title' => 'Set Order Status', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '',
              'description' => 'Set the status of orders made with this payment module to this value',
              'action' => 'config/get_order_status'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_CURL_PROGRAM_LOCATION',
              'title' => 'cURL Program Location', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The location of the cURL Program Location.'));

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();

        $this->title = lang('payment_paypal_direct_title');
        $this->method_title = lang('payment_paypal_direct_method_title');
        $this->status = (isset($this->config['MODULE_PAYMENT_PAYPAL_DIRECT_STATUS']) && ($this->config['MODULE_PAYMENT_PAYPAL_DIRECT_STATUS'] == 'True')) ? TRUE : FALSE;
        $this->sort_order = isset($this->config['MODULE_PAYMENT_PAYPAL_DIRECT_SORT_ORDER']) ? $this->config['MODULE_PAYMENT_PAYPAL_DIRECT_SORT_ORDER'] : NULL;

        $this->cc_types = array('VISA'       => 'Visa',
                                'MASTERCARD' => 'MasterCard',
                                'DISCOVER'   => 'Discover Card',
                                'AMEX'       => 'American Express',
                                'SWITCH'     => 'Maestro',
                                'SOLO'       => 'Solo');
    }

    /**
     * Initialize the shipping module
     *
     * @access public
     */
    function initialize()
    {
        if ($this->config['MODULE_PAYMENT_PAYPAL_DIRECT_SERVER'] == 'Production')
        {
            $this->api_url = 'https://api-3t.paypal.com/nvp';
        }
        else
        {
            $this->api_url = 'https://api-3t.sandbox.paypal.com/nvp';
        }

        if ($this->status === TRUE)
        {
            $this->ci->load->model('address_model');
            
            $this->order_status = $this->config['MODULE_PAYMENT_PAYPAL_DIRECT_ORDER_STATUS_ID'] > 0 ? (int) $this->config['MODULE_PAYMENT_PAYPAL_DIRECT_ORDER_STATUS_ID'] : (int)config('ORDERS_STATUS_PAID');

            if ((int)$this->config['MODULE_PAYMENT_PAYPAL_DIRECT_ZONE'] > 0)
            {
                $zones = $this->ci->address_model->get_zone_id_via_geo_zone($this->ci->shopping_cart->get_billing_address('country_id'), $this->config['MODULE_PAYMENT_PAYPAL_DIRECT_ZONE']);

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
    function confirmation() {
        $types_array = array();
        foreach($this->cc_types as $key => $value) {
            $types_array[$key] = $value;
        }

        $today = getdate();

        $months_array = array();
        for ($i=1; $i<13; $i++) {
            $months_array[sprintf('%02d', $i)] = strftime('%B',mktime(0,0,0,$i,1,2000));
        }

        $year_valid_from_array = array();
        for ($i=$today['year']-10; $i < $today['year']+1; $i++) {
            $year_valid_from_array[strftime('%Y',mktime(0,0,0,1,1,$i))] = strftime('%Y',mktime(0,0,0,1,1,$i));
        }

        $year_expires_array = array();
        for ($i=$today['year']; $i < $today['year']+10; $i++) {
            $year_expires_array[strftime('%Y',mktime(0,0,0,1,1,$i))] = strftime('%Y',mktime(0,0,0,1,1,$i));
        }

        $confirmation = array('fields' => array(array('title' => lang('payment_paypal_direct_card_owner'),
                                                      'field' => form_input('cc_owner', $this->ci->shopping_cart->get_billing_address('firstname') . ' ' . $this->ci->shopping_cart->get_billing_address('lastname'))),
                                                array('title' => lang('payment_paypal_direct_card_type'),
                                                      'field' => form_dropdown('cc_type', $types_array)),
                                                array('title' => lang('payment_paypal_direct_card_number'),
                                                      'field' => form_input('cc_number_nh-dns')),
                                                array('title' => lang('payment_paypal_direct_card_valid_from'),
                                                      'field' => form_dropdown('cc_starts_month', $months_array) . '&nbsp;' . form_dropdown('cc_starts_year', $year_valid_from_array) . ' ' . lang('payment_paypal_direct_card_valid_from_info')),
                                                array('title' => lang('payment_paypal_direct_card_expires'),
                                                      'field' => form_dropdown('cc_expires_month', $months_array) . '&nbsp;' . form_dropdown('cc_expires_year', $year_expires_array)),
                                                array('title' => lang('payment_paypal_direct_card_cvc'),
                                                      'field' => form_input('cc_cvc_nh-dns', '', 'size="5" maxlength="4"')),
                                                array('title' => lang('payment_paypal_direct_card_issue_number'),
                                                      'field' => form_input('cc_issue_nh-dns', '', 'size="3" maxlength="2"') . ' ' . lang('payment_paypal_direct_card_issue_number_info'))));

        return $confirmation;
    }

    /**
     * Process Button
     *
     * @access public
     * @return boolean 
     */
    function process_button() {
        return false;
    }

    /**
     * Payment process
     * 
     * @access public
     * @return void
     */
    function process() {
        $currency = currency_code();
        
        $cc_owner = $this->ci->input->post('cc_owner');
        $cc_type = $this->ci->input->post('cc_type');
        $cc_number_nh_dns = $this->ci->input->post('cc_number_nh-dns');
        $cc_starts_month = $this->ci->input->post('cc_starts_month');
        $cc_starts_year = $this->ci->input->post('cc_starts_year');
        $cc_expires_month = $this->ci->input->post('cc_expires_month');
        $cc_expires_year = $this->ci->input->post('cc_expires_year');
        $cc_cvc_nh_dns = $this->ci->input->post('cc_cvc_nh-dns');
        $cc_issue_nh_dns = $this->ci->input->post('cc_issue_nh-dns');
        
        if (isset($cc_owner) && !empty($cc_owner) && isset($cc_type) && isset($this->cc_types[$cc_type]) && isset($cc_number_nh_dns) && !empty($cc_number_nh_dns)) {
            $params = array('USER' => $this->config['MODULE_PAYMENT_PAYPAL_DIRECT_API_USERNAME'],
                        'PWD' => $this->config['MODULE_PAYMENT_PAYPAL_DIRECT_API_PASSWORD'],
                        'VERSION' => '3.2',
                        'SIGNATURE' => $this->config['MODULE_PAYMENT_PAYPAL_DIRECT_API_SIGNATURE'],
                        'METHOD' => 'DoDirectPayment',
                        'PAYMENTACTION' => (($this->config['MODULE_PAYMENT_PAYPAL_DIRECT_METHOD'] == 'Sale') ? 'Sale' : 'Authorization'),
                        'IPADDRESS' => get_ip_address(),
                        'AMT' => currencies_format_raw($this->ci->shopping_cart->get_total() - $this->ci->shopping_cart->get_shipping_method('cost'), $currency),
                        'CREDITCARDTYPE' => $cc_type,
                        'ACCT' => $cc_number_nh_dns,
                        'STARTDATE' => $cc_starts_month . $cc_starts_year,
                        'EXPDATE' => $cc_expires_month . $cc_expires_year,
                        'CVV2' => $cc_cvc_nh_dns,
                        'FIRSTNAME' => substr($cc_owner, 0, strpos($cc_owner, ' ')),
                        'LASTNAME' => substr($cc_owner, strpos($cc_owner, ' ') + 1),
                        'STREET' => $this->ci->shopping_cart->get_billing_address('street_address'),
                        'CITY' => $this->ci->shopping_cart->get_billing_address('city'),
                        'STATE' => $this->ci->shopping_cart->get_billing_address('state'),
                        'COUNTRYCODE' => $this->ci->shopping_cart->get_billing_address('country_iso_code_2'),
                        'ZIP' => $this->ci->shopping_cart->get_billing_address('postcode'),
                        'EMAIL' => $this->ci->customer->get_email_address(),
                        'PHONENUM' => $this->ci->shopping_cart->get_billing_address('telephone_number'),
                        'CURRENCYCODE' => $currency,
                        'BUTTONSOURCE' => 'tomatcart');

            if ( ($cc_type == 'SWITCH') || ($cc_type == 'SOLO') ) {
                $params['ISSUENUMBER'] = $cc_issue_nh_dns;
            }

            if ($this->ci->shopping_cart->has_shipping_address()) {
                $params['SHIPTONAME'] = $this->ci->shopping_cart->get_shipping_address('firstname') . ' ' . $this->ci->shopping_cart->get_shipping_address('lastname');
                $params['SHIPTOSTREET'] = $this->ci->shopping_cart->get_shipping_address('street_address');
                $params['SHIPTOCITY'] = $this->ci->shopping_cart->get_shipping_address('city');
                $params['SHIPTOSTATE'] = $this->ci->shopping_cart->get_shipping_address('zone_code');
                $params['SHIPTOCOUNTRYCODE'] = $this->ci->shopping_cart->get_shipping_address('country_iso_code_2');
                $params['SHIPTOZIP'] = $this->ci->shopping_cart->get_shipping_address('postcode');
            }

            $post_string = '';
            foreach ($params as $key => $value) {
                $post_string .= $key . '=' . urlencode(trim($value)) . '&';
            }
            $post_string = substr($post_string, 0, -1);
            $response = $this->send_transaction_to_gateway($this->api_url, $post_string);

            $response_array = array();
            parse_str($response, $response_array);

            if (($response_array['ACK'] != 'Success') && ($response_array['ACK'] != 'SuccessWithWarning')) {
                $this->ci->message_stack->add_session('checkout', stripslashes($response_array['L_LONGMESSAGE0']), 'error');
                
                redirect('checkout/index/index/orderConfirmationForm');
            }else {
                $this->ci->load->library('order');
                $orders_id = $this->ci->order->create_order();
                
                //update order order status
                $comments = 'PayPal Website Payments Pro (US) Direct Payments [' . 'ACK: ' . $response_array['ACK'] . '; TransactionID: ' . $response_array['TRANSACTIONID'] . ';' . ']';
                $this->ci->order->process($orders_id, ORDERS_STATUS_PAID, $comments);
            }
        }else {
            $this->ci->message_stack->add_session('checkout', lang('payment_paypal_direct_error_all_fields_required'), 'error');

            redirect('checkout/index/index/orderConfirmationForm');
        }
    }

    function callback() {
        return FALSE;
    }
}