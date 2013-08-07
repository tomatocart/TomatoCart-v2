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
class TOC_Payment_paypal_standard extends TOC_Payment_Module
{
    /**
     * payment module code
     *
     * @access protected
     * @var string
     */
    protected $code = 'paypal_standard';

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_STATUS',
              'title' => 'Enable PayPal Website Payments Standard', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'True',
              'description' => 'Do you want to accept PayPal Website Payments Standard payments?',
              'values' => array(array('id' => 'True', 'text' => 'True'),
    array('id' => 'False', 'text' => 'False'))),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_ID',
              'title' => '* PayPal E-Mail Address', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The seller e-mail address to use for accepting PayPal payments.'),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_ZONE',
              'title' => 'Payment Zone', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'If a zone is selected, only enable this payment method for that zone.',
              'action' => 'config/get_shipping_zone'),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_SORT_ORDER',
              'title' => 'Sort order of display.', 
              'type' => 'numberfield',
              'value' => '0',
              'description' => 'Sort order of display. Lowest is displayed first.'),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_ORDER_STATUS_ID',
              'title' => '* Set PayPal Acknowledged Order Status', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'Set the status of orders made with this payment module to this value',
              'action' => 'config/get_order_status'),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_GATEWAY_SERVER',
              'title' => '* Gateway Server', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'Sandbox',
              'description' => 'Use the testing (sandbox) or live gateway server for transactions?',
              'values' => array(array('id' => 'Live', 'text' => 'Live'),
    array('id' => 'Sandbox', 'text' => 'Sandbox'))),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_TRANSFER_CART',
              'title' => 'Transfer Cart Line Items', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => '1',
              'description' => 'Do you want to transfer the details about the items in the cart to paypal?',
              'values' => array(array('id' => '1', 'text' => 'True'),
    array('id' => '-1', 'text' => 'False'))),
    array('name' => 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY',
              'title' => 'Encrypted Web Payments Private Key (Seller)', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The location of your Private Key to use for signing the data. (*.pem)'),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_TRANSACTION_METHOD',
              'title' => 'Transaction Method', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'Sale',
              'description' => 'The processing method to use for each transaction.',
              'values' => array(array('id' => 'Authorization', 'text' => 'Authorization'),
    array('id' => 'Sale', 'text' => 'Sale'))),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_PAGE_STYLE',
              'title' => 'Page Style', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The page style to use for the transaction procedure (defined at your PayPal Profile page)'),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_DEBUG_EMAIL',
              'title' => 'Debug E-Mail Address', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'All parameters of an Invalid IPN notification will be sent to this email address if one is entered.'),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_STATUS',
              'title' => 'Enable Encrypted Web Payments', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'Sale',
              'description' => 'Do you want to enable Encrypted Web Payments?',
              'values' => array(array('id' => '1', 'text' => 'True'),
    array('id' => '-1', 'text' => 'False'))),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PRIVATE_KEY',
              'title' => 'Your Private Key', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The location of your Private Key to use for signing the data. (*.pem)'),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PUBLIC_KEY',
              'title' => 'Your Public Certificate', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The location of your Public Certificate to use for signing the data. (*.pem)'),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PAYPAL_KEY',
              'title' => 'PayPals Public Certificate', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The location of the PayPal Public Certificate for encrypting the data.'),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_CERT_ID',
              'title' => 'Your PayPal Public Certificate ID', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The Certificate ID to use from your PayPal Encrypted Payment Settings Profile.'),
    array('name' => 'MODULE_PAYMENT_PAYPAL_STANDARD_EWP_OPENSSL',
              'title' => 'OpenSSL Location', 
              'type' => 'textfield',
		   	  'value' => '/usr/bin/openssl',
              'description' => 'The location of the openssl binary file.'));

    /**
     * ignore order totals module
     *
     * @access private
     * @var array
     */
    private $ignore_order_totals = array('sub_total', 'tax', 'total');

    /**
     * Transaction Response
     *
     * @access private
     * @var array
     */
    private $transaction_response = NULL;

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();

        $this->title = lang('payment_paypal_standard_title');
        $this->method_title = lang('payment_paypal_standard_method_title');
        $this->status = (isset($this->config['MODULE_PAYMENT_PAYPAL_STANDARD_STATUS']) && ($this->config['MODULE_PAYMENT_PAYPAL_STANDARD_STATUS'] == 'True')) ? TRUE : FALSE;
        $this->sort_order = isset($this->config['MODULE_PAYMENT_PAYPAL_STANDARD_SORT_ORDER']) ? $this->config['MODULE_PAYMENT_PAYPAL_STANDARD_SORT_ORDER'] : NULL;
    }

    /**
     * Initialize the shipping module
     *
     * @access public
     */
    function initialize()
    {
        if ($this->config['MODULE_PAYMENT_PAYPAL_STANDARD_GATEWAY_SERVER'] == 'Live')
        {
            $this->form_action_url = 'https://www.paypal.com/cgi-bin/webscr';
        }
        else
        {
            $this->form_action_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }

        if ($this->status === TRUE)
        {
            $this->order_status = $this->config['MODULE_PAYMENT_PAYPAL_STANDARD_ORDER_STATUS_ID'] > 0 ? (int) $this->config['MODULE_PAYMENT_PAYPAL_STANDARD_ORDER_STATUS_ID'] : (int)config('ORDERS_STATUS_PAID');

            if ((int)$this->config['MODULE_PAYMENT_PAYPAL_STANDARD_ZONE'] > 0)
            {
                $this->ci->load->model('address_model');

                $zones = $this->ci->address_model->get_zone_id_via_geo_zone($this->ci->shopping_cart->get_billing_address('country_id'), $this->config['MODULE_PAYMENT_PAYPAL_STANDARD_ZONE']);

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
        $this->ci->load->library('order');

        $this->order_id = $this->ci->order->create_order(ORDERS_STATUS_PREPARING);
    }

    /**
     * Process button
     *
     * @access public
     * @return string
     */
    function process_button()
    {
        $process_button_string = '';
        $params = array('business' => $this->config['MODULE_PAYMENT_PAYPAL_STANDARD_ID'],
                      'currency_code' => $this->ci->currencies->get_code(),
                      'invoice' => $this->order_id,
                      'custom' => $this->ci->customer->get_id(),
                      'no_note' => '1',
                      'notify_url' =>  site_url('checkout/callback/' . $this->code),
                      'return' => site_url('checkout/process'),
                      'rm' => '2',
                      'cancel_return' => site_url('checkout/checkout'),
                      'bn' => 'Tomatocart_Default_ST',
                      'paymentaction' => (($this->config['MODULE_PAYMENT_PAYPAL_STANDARD_TRANSACTION_METHOD'] == 'Sale') ? 'sale' : 'authorization'));

        if ($this->ci->shopping_cart->has_shipping_address())
        {
            $params['address_override'] = '1';
            $params['first_name'] = $this->ci->shopping_cart->get_shipping_address('firstname');
            $params['last_name'] =  $this->ci->shopping_cart->get_shipping_address('lastname');
            $params['address1'] = $this->ci->shopping_cart->get_shipping_address('street_address');
            $params['city'] = $this->ci->shopping_cart->get_shipping_address('city');
            $params['state'] = $this->ci->shopping_cart->get_shipping_address('zone_code');
            $params['zip'] = $this->ci->shopping_cart->get_shipping_address('postcode');
            $params['country'] = $this->ci->shopping_cart->get_shipping_address('country_iso_code_2');
        }
        else
        {
            $params['no_shipping'] = '1';
            $params['first_name'] = $this->ci->shopping_cart->get_billing_address('firstname');
            $params['last_name'] = $this->ci->shopping_cart->get_billing_address('lastname');
            $params['address1'] = $this->ci->shopping_cart->get_billing_address('street_address');
            $params['city'] = $this->ci->shopping_cart->get_billing_address('city');
            $params['state'] = $this->ci->shopping_cart->get_billing_address('zone_code');
            $params['zip'] = $this->ci->shopping_cart->get_billing_address('postcode');
            $params['country'] = $this->ci->shopping_cart->get_billing_address('country_iso_code_2');
        }

        if ($this->config['MODULE_PAYMENT_PAYPAL_STANDARD_TRANSFER_CART'] == '-1')
        {
            $params['cmd'] = '_xclick';
            $params['item_name'] = config('STORE_NAME');

            $shipping_tax = ($this->ci->shopping_cart->get_shipping_method('cost')) * ($this->ci->tax->get_tax_rate($this->ci->shopping_cart->get_shipping_method('tax_class_id'), $this->ci->shopping_cart->get_taxing_address('country_id'), $this->ci->shopping_cart->get_taxing_address('zone_id')) / 100);

            if (config('DISPLAY_PRICE_WITH_TAX') == '1')
            {
                $shipping = $this->ci->shopping_cart->get_shipping_method('cost');
            }
            else
            {
                $shipping = $this->ci->shopping_cart->get_shipping_method('cost') + $shipping_tax;
            }
            $params['shipping'] = $this->ci->currencies->format_raw($shipping);

            $total_tax = $this->ci->shopping_cart->get_tax() - $shipping_tax;
            $params['tax'] = $this->ci->currencies->format_raw($total_tax);
            $params['amount'] = $this->ci->currencies->format_raw($this->ci->shopping_cart->get_total() - $shipping - $total_tax);
        }
        else
        {
            $params['cmd'] = '_cart';
            $params['upload'] = '1';
            if (config('DISPLAY_PRICE_WITH_TAX') == '-1')
            {
                $params['tax_cart'] = $this->ci->currencies->format_raw($this->ci->shopping_cart->getTax());
            }

            //products
            $products = array();
            if ($this->ci->shopping_cart->has_contents()) {
                $i = 1;

                $products = $this->ci->shopping_cart->get_products();
                foreach($products as $product)
                {
                    $product_name = $product['name'];

                    //gift certificate
                    if ($product['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE)
                    {
                        $product_name .= "\n" . ' - ' . lang('senders_name') . ': ' . $product['gc_data']['senders_name'];

                        if ($product['gc_data']['type'] == GIFT_CERTIFICATE_TYPE_EMAIL)
                        {
                            $product_name .= "\n" . ' - ' . lang('senders_email')  . ': ' . $product['gc_data']['senders_email'];
                        }

                        $product_name .= "\n" . ' - ' . lang('recipients_name') . ': ' . $product['gc_data']['recipients_name'];

                        if ($product['gc_data']['type'] == GIFT_CERTIFICATE_TYPE_EMAIL)
                        {
                            $product_name .= "\n" . ' - ' . lang('recipients_email')  . ': ' . $product['gc_data']['recipients_email'];
                        }

                        $product_name .= "\n" . ' - ' . lang('message')  . ': ' . $product['gc_data']['message'];
                    }

                    if ($this->ci->shopping_cart->has_variants($product['id']))
                    {
                        foreach ($this->ci->shopping_cart->get_variants($product['id']) as $variant)
                        {
                            $product_name .= ' - ' . $variant['groups_name'] . ': ' . $variant['values_name'];
                        }
                    }

                    $product_data = array('item_name_' . $i => $product_name, 'item_number_' . $i => $product['sku'], 'quantity_' . $i  => $product['quantity']);

                    $tax = $this->ci->tax->get_tax_rate($product['tax_class_id'], $this->ci->shopping_cart->get_taxing_address('country_id'), $this->ci->shopping_cart->get_taxing_address('zone_id'));
                    $price = $this->ci->currencies->add_tax_rate_to_price($product['final_price'], $tax);
                    $product_data['amount_' . $i] = $this->ci->currencies->format_raw($price);

                    $params = array_merge($params,$product_data);

                    $i++;
                }
            }

            //order totals
            foreach ($this->ci->shopping_cart->get_order_totals() as $total)
            {
                if ( !in_array($total['code'], $this->ignore_order_totals) )
                {
                    if ( ($total['code'] == 'coupon') || ($total['code'] == 'gift_certificate') )
                    {
                        $params['discount_amount_cart'] += $this->ci->currencies->format_raw(abs($total['value']));
                    }
                    else
                    {
                        $order_total = array('item_name_' . $i => $total['title'], 'quantity_' . $i => 1, 'amount_' . $i => $total['value']);
                        $params = array_merge($params, $order_total);

                        $i++;
                    }
                }
            }
        }

        if ( isset($this->config['MODULE_PAYMENT_PAYPAL_STANDARD_PAGE_STYLE']) ) {
            $params['page_style'] = $this->config['MODULE_PAYMENT_PAYPAL_STANDARD_PAGE_STYLE'];
        }

        if ($this->config['MODULE_PAYMENT_PAYPAL_STANDARD_EWP_STATUS'] == '1') {
            $params['cert_id'] = $this->config['MODULE_PAYMENT_PAYPAL_STANDARD_EWP_CERT_ID'];

            $random_string = osc_create_random_string(5, 'digits') . '-' . $this->ci->customer->get_id() . '-';

            $data = '';
            reset($params);
            foreach ($params as $key => $value) {
                $data .= $key . '=' . $value . "\n";
            }

            $fp = fopen(DIR_FS_WORK . $random_string . 'data.txt', 'w');
            fwrite($fp, $data);
            fclose($fp);

            unset($data);

            if (function_exists('openssl_pkcs7_sign') && function_exists('openssl_pkcs7_encrypt')) {
                openssl_pkcs7_sign(DIR_FS_WORK . $random_string . 'data.txt', DIR_FS_WORK . $random_string . 'signed.txt', file_get_contents(MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PUBLIC_KEY), file_get_contents(MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PRIVATE_KEY), array('From' => MODULE_PAYMENT_PAYPAL_STANDARD_ID), PKCS7_BINARY);

                unlink(DIR_FS_WORK . $random_string . 'data.txt');

                // remove headers from the signature
                $signed = file_get_contents(DIR_FS_WORK . $random_string . 'signed.txt');
                $signed = explode("\n\n", $signed);
                $signed = base64_decode($signed[1]);

                $fp = fopen(DIR_FS_WORK . $random_string . 'signed.txt', 'w');
                fwrite($fp, $signed);
                fclose($fp);

                unset($signed);

                openssl_pkcs7_encrypt(DIR_FS_WORK . $random_string . 'signed.txt', DIR_FS_WORK . $random_string . 'encrypted.txt', file_get_contents(MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PAYPAL_KEY), array('From' => MODULE_PAYMENT_PAYPAL_STANDARD_ID), PKCS7_BINARY);

                unlink(DIR_FS_WORK . $random_string . 'signed.txt');

                // remove headers from the encrypted result
                $data = file_get_contents(DIR_FS_WORK . $random_string . 'encrypted.txt');
                $data = explode("\n\n", $data);
                $data = '-----BEGIN PKCS7-----' . "\n" . $data[1] . "\n" . '-----END PKCS7-----';

                unlink(DIR_FS_WORK . $random_string . 'encrypted.txt');
            } else {
                exec(MODULE_PAYMENT_PAYPAL_STANDARD_EWP_OPENSSL . ' smime -sign -in ' . DIR_FS_WORK . $random_string . 'data.txt -signer ' . MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PUBLIC_KEY . ' -inkey ' . MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PRIVATE_KEY . ' -outform der -nodetach -binary > ' . DIR_FS_WORK . $random_string . 'signed.txt');
                unlink(DIR_FS_WORK . $random_string . 'data.txt');

                exec(MODULE_PAYMENT_PAYPAL_STANDARD_EWP_OPENSSL . ' smime -encrypt -des3 -binary -outform pem ' . MODULE_PAYMENT_PAYPAL_STANDARD_EWP_PAYPAL_KEY . ' < ' . DIR_FS_WORK . $random_string . 'signed.txt > ' . DIR_FS_WORK . $random_string . 'encrypted.txt');
                unlink(DIR_FS_WORK . $random_string . 'signed.txt');

                $fp = fopen(DIR_FS_WORK . $random_string . 'encrypted.txt', 'rb');
                $data = fread($fp, filesize(DIR_FS_WORK . $random_string . 'encrypted.txt'));
                fclose($fp);

                unset($fp);

                unlink(DIR_FS_WORK . $random_string . 'encrypted.txt');
            }

            $process_button_string = osc_draw_hidden_field('cmd', '_s-xclick') .
            osc_draw_hidden_field('encrypted', $data);

            unset($data);
        } else {
            $process_button_string = '';

            foreach ($params as $key => $value) {
                $process_button_string .= form_hidden($key, $value);
            }
        }

        return $process_button_string;
    }

    function process() {
        $pre_order_id = $this->ci->session->userdata('pre_order_id');

        if ($pre_order_id !== NULL)
        {
            $prep = explode('-', $pre_order_id);

            if ($prep[0] == $this->ci->shopping_cart->get_cart_id())
            {
                $this->ci->load->model('order_model');

                $orders_status_history = $this->ci->order_model->get_order_status_history($prep[1]);

                $paid = FALSE;
                if ($orders_status_history !== NULL)
                {
                    foreach ($orders_status_history as $orders_status)
                    {
                        if ($orders_status['orders_status_id'] == $this->order_status)
                        {
                            $paid = TRUE;
                        }
                    }
                }

                if ($paid === FALSE) {
                    if (!empty($this->config['MODULE_PAYMENT_PAYPAL_STANDARD_PROCESSING_ORDER_STATUS_ID']))
                    {
                        $invoice = $this->ci->input->post('invoice');

                        $this->ci->order->process($invoice, $this->config['MODULE_PAYMENT_PAYPAL_STANDARD_PROCESSING_ORDER_STATUS_ID'], 'PayPal Processing Transaction');
                    }
                }
            }

            $this->ci->session->unset_userdata('pre_order_id');
        }
    }

    function callback() {
        $post_string = 'cmd=_notify-validate&';

        foreach ($_POST as $key => $value) {
            $post_string .= $key . '=' . urlencode($value) . '&';
        }

        $post_string = substr($post_string, 0, -1);

        $this->transaction_response = $this->send_transaction_to_gateway($this->form_action_url, $post_string);

        if (strtoupper(trim($this->transaction_response)) == 'VERIFIED')
        {
            $invoice = $this->input->post('invoice');
            if (isset($invoice) && is_numeric($invoice) && ($invoice > 0))
            {
                $this->ci->load->library('order');
                $this->ci->load->model('order_model');

                $order_total = $this->order_model->get_order_total($invoice);

                if ($order_total !== NULL)
                {
                    $payment_status = $this->input->post('payment_status');
                    $payer_status = $this->input->post('payer_status');
                    $mc_gross = $this->input->post('mc_gross');
                    $currency = $this->input->post('mc_currency');
                    $pending_reason = $this->input->post('pending_reason');
                    $reason_code = $this->input->post('reason_code');
                    $order_info = $this->ci->order_model->query($invoice);
                    $comment = $payment_status . ' (' . ucfirst($payer_status) . '; ' . currencies_format($mc_gross, FALSE, $currency) . ')';

                    if ($payment_status == 'Pending')
                    {
                        $comment .= '; ' . $pending_reason;
                    }
                    elseif ($payment_status == 'Reversed' || $payment_status == 'Refunded')
                    {
                        $comment .= '; ' . $reason_code;
                    }

                    if ( $mc_gross != number_format($order_total * $order_info['currency_value'], $this->ci->currencies->get_decimal_places($order_info['currency'])) )
                    {
                        $comment .= '; PayPal transaction value (' . $mc_gross . ') does not match order value (' . number_format($order_total * $order_info['currency_value'], $this->ci->currencies->get_decimal_places($order_info['currency'])) . ')';
                    }

                    $comments = 'PayPal IPN Verified [' . $comment . ']';

                    $this->ci->order->process($invoice, $this->order_status, $comments);
                }
            }
        } else {
            if (isset($this->config['MODULE_PAYMENT_PAYPAL_STANDARD_DEBUG_EMAIL'])) {
                $email_body = 'PAYPAL_STANDARD_DEBUG_POST_DATA:' . "\n\n";

                $posts = $this->input->post();
                foreach($posts as $key => $value)
                {
                    $email_body .= $key . '=' . $value . "\n";
                }

                $email_body .= "\n" . 'PAYPAL_STANDARD_DEBUG_GET_DATA:' . "\n\n";

                $gets = $this->input->post();
                foreach($gets as $key => $value)
                {
                    $email_body .= $key . '=' . $value . "\n";
                }

                //osc_email('', MODULE_PAYMENT_PAYPAL_STANDARD_DEBUG_EMAIL, 'PayPal IPN Invalid Process', $email_body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            }

            $payment_status = $this->input->post('payment_status');
            $payer_status = $this->input->post('payer_status');
            $mc_gross = $this->input->post('mc_gross');
            $currency = $this->input->post('mc_currency');
            $pending_reason = $this->input->post('pending_reason');
            $reason_code = $this->input->post('reason_code');
            $order_info = $this->ci->order_model->query($invoice);

            if (isset($invoice) && is_numeric($invoice) && $invoice > 0) 
            {
                if ($order_info !== NULL) 
                {
                    $comment = $payment_status;

                    if ($payment_status == 'Pending') {
                        $comment .= '; ' . $pending_reason;
                    }elseif ( ($payment_status == 'Reversed') || ($payment_status == 'Refunded') ) {
                        $comment .= '; ' . $reason_code;
                    }
                    $comments = 'PayPal IPN Invalid [' . $comment . ']';

                    $this->ci->order->insert_order_status_history($invoice, $this->order_status, $comments);
                }
            }
        }
    }
}