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
 * Checkout Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-checkout-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Checkout extends TOC_Controller {

    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Load checkout method form
     */
    public function load_checkout_method_form() {
        $this->lang->db_load('account');

        $result = array('success' => TRUE,
    	                'form' => $this->template->_find_view('checkout/checkout_method_form.php', array()));

        $this->output->set_output(json_encode($result));
    }

    /**
     * load billing form
     */
    public function load_billing_form() {
        $this->load->model('address_model');
        $this->load->model('address_book_model');

        //checkout method: 'register' or 'guest'
        $data['checkout_method'] = $this->input->post('checkout_method');
        $data['is_logged_on'] = $this->customer->is_logged_on();

        $data['address_books'] = array();
        if ($this->customer->is_logged_on())
        {
            $data['address_books'] = $this->address_book_model->get_addresses($this->customer->get_id());
        }

        //get billing address
        $billing_address = $this->shopping_cart->get_billing_address();

        $data['billing_email_address'] = isset($billing_address['email_address']) ? $billing_address['email_address'] : null;
        $data['billing_gender'] = isset($billing_address['gender']) ? $billing_address['gender'] : 'm';
        $data['billing_firstname'] = isset($billing_address['firstname']) ? $billing_address['firstname'] : null;
        $data['billing_lastname'] = isset($billing_address['lastname']) ? $billing_address['lastname'] : null;
        $data['billing_company'] = isset($billing_address['company']) ? $billing_address['company'] : null;
        $data['billing_street_address'] = isset($billing_address['street_address']) ? $billing_address['street_address'] : null;
        $data['billing_suburb'] = isset($billing_address['suburb']) ? $billing_address['suburb'] : null;
        $data['billing_postcode'] = isset($billing_address['postcode']) ? $billing_address['postcode'] : null;
        $data['billing_city'] = isset($billing_address['city']) ? $billing_address['city'] : null;
        $data['zone_code'] = isset($billing_address['zone_code']) ? $billing_address['zone_code'] : null;
        $data['billing_state'] = isset($billing_address['state']) ? $billing_address['state'] : null;
        $data['billing_country_id'] = isset($billing_address['country_id']) ? $billing_address['country_id'] : config('STORE_COUNTRY');
        $data['billing_telephone'] = isset($billing_address['telephone_number']) ? $billing_address['telephone_number'] : null;
        $data['billing_fax'] = isset($billing_address['fax']) ? $billing_address['fax'] : null;
        $data['ship_to_this_address'] = (isset($billing_address['ship_to_this_address']) && $billing_address['ship_to_this_address'] == 'on') ? TRUE : FALSE;

        $data['create_billing_address'] = FALSE;
        if (!$this->customer->is_logged_on())
        {
            $data['create_billing_address'] = TRUE;
        }
        else if(isset($billing_address['id']) && ($billing_address['id'] == '-1'))
        {
            $data['create_billing_address'] = TRUE;
        }
        else if ($this->customer->is_logged_on() && (count($data['address_books']) == 0))
        {
            $data['create_billing_address'] = TRUE;
        }
        //END: get billing address

        //get countries
        $countries = $this->address_model->get_countries();

        $countries_array[''] = lang('pull_down_default');
        foreach ($countries as $country) {
            $countries_array[$country['id']] = $country['name'];
        }

        $data['countries'] = $countries_array;
        //END: get countries

        //get states
        $states = $this->address_model->get_states($data['billing_country_id']);
        foreach ($states as $state) {
            $states_array[$state['id']] = $state['text'];
        }

        $data['states'] = $states_array;
        //END: get states

        $data['is_virtual_cart'] = $this->shopping_cart->is_virtual_cart();

        $result = array('success' => TRUE,
    					'form' => $this->template->_find_view('checkout/billing_address_form.php', $data));

        $this->output->set_output(json_encode($result));
    }

    /**
     * Save billing form
     */
    public function save_billing_form()
    {
        $data = array();
        $errors = array();
        
        $this->load->model('account_model');

        //checkout method: 'register' or 'guest'
        $checkout_method = $this->input->post('checkout_method');

        //if the customer is not logged on
        //check email
        if (!$this->customer->is_logged_on())
        {
            $billing_email_address = $this->input->post('billing_email_address');
            if ( ($billing_email_address === NULL) || (strlen(trim($billing_email_address)) < config('ACCOUNT_EMAIL_ADDRESS')) )
            {
                $errors[] = sprintf(lang('field_customer_email_address_error'), config('ACCOUNT_EMAIL_ADDRESS'));
            }
            else
            {
                //validate email address
                if ( !validate_email_address($billing_email_address) )
                {
                    $errors[] = lang('field_customer_email_address_check_error');
                }
                else
                {
                    //check whether email exists
                    $data = $this->account_model->get_data($billing_email_address);
                    if ($data !== NULL)
                    {
                        $errors[] = lang('field_customer_email_address_exists_error');
                    }
                    else
                    {
                        $data['email_address'] = $billing_email_address;
                    }
                }
            }

            //if checkout method is 'register' then check the password
            $data['password'] = NULL;
            if ($checkout_method == 'register')
            {
                $billing_password = $this->input->post('billing_password');
                $confirmation = $this->input->post('confirmation');

                if ( ($billing_password === NULL) || (($billing_password !== NULL) && (strlen(trim($billing_password)) < config('ACCOUNT_PASSWORD'))) )
                {
                    $errors[] = sprintf(lang('field_customer_password_error'), config('ACCOUNT_PASSWORD'));
                }
                elseif ( ($confirmation === NULL) || (($confirmation !== NULL) && (trim($billing_password) != trim($confirmation))) )
                {
                    $errors[] = lang('field_customer_password_mismatch_with_confirmation');
                }
                else
                {
                    $data['password'] = $billing_password;
                }
            }
        }

        //if the create_billing_address equals 1 then get the data
        $data['create_billing_address'] = $this->input->post('create_billing_address');
        if ($data['create_billing_address'] == 'on')
        {
            //gender
            $billing_gender = $this->input->post('billing_gender');
            if (config('ACCOUNT_GENDER') == '1')
            {
                if (($billing_gender == 'm') || ($billing_gender == 'f'))
                {
                    $data['gender'] = $billing_gender;
                }
                else
                {
                    $errors[] = lang('field_customer_gender_error');
                }
            }
            else
            {
                $data['gender'] = ($billing_gender !== NULL) ? $billing_gender : 'm';
            }

            //firstname
            $billing_firstname = $this->input->post('billing_firstname');
            if (($billing_firstname !== NULL) && (strlen(trim($billing_firstname)) >= config('ACCOUNT_FIRST_NAME')))
            {
                $data['firstname'] = $billing_firstname;
            }
            else
            {
                $errors[] = sprintf(lang('field_customer_first_name_error'), config('ACCOUNT_FIRST_NAME'));
            }

            //lastname
            $billing_lastname = $this->input->post('billing_lastname');
            if (($billing_lastname !== NULL) && (strlen(trim($billing_lastname)) >= config('ACCOUNT_LAST_NAME')))
            {
                $data['lastname'] = $billing_lastname;
            }
            else
            {
                $errors[] = sprintf(lang('field_customer_last_name_error'), config('ACCOUNT_LAST_NAME'));
            }

            //company
            if (config('ACCOUNT_COMPANY') > -1)
            {
                $billing_company = $this->input->post('billing_company');
                if (($billing_company !== NULL) && (strlen(trim($billing_company)) >= config('ACCOUNT_COMPANY')))
                {
                    $data['company'] = $billing_company;
                }
                else
                {
                    $errors[] = sprintf(lang('field_customer_company_error'), config('ACCOUNT_COMPANY'));
                }
            }

            //street address
            $billing_street_address = $this->input->post('billing_street_address');
            if (($billing_street_address !== NULL) && (strlen(trim($billing_street_address)) >= config('ACCOUNT_STREET_ADDRESS')))
            {
                $data['street_address'] = $billing_street_address;
            } else {
                $errors[] = sprintf(lang('field_customer_street_address_error'), config('ACCOUNT_STREET_ADDRESS'));
            }

            //suburb
            if (config('ACCOUNT_SUBURB') >= 0)
            {
                $billing_suburb = $this->input->post('billing_suburb');
                if (($billing_suburb !== NULL) && (strlen(trim($billing_suburb)) >= config('ACCOUNT_SUBURB')))
                {
                    $data['suburb'] = $billing_suburb;
                }
                else
                {
                    $errors[] = sprintf(lang('field_customer_suburb_error'), config('ACCOUNT_SUBURB'));
                }
            }

            //postcode
            if (config('ACCOUNT_POST_CODE') > -1) {
                $billing_postcode = $this->input->post('billing_postcode');
                if (($billing_postcode !== NULL) && (strlen(trim($billing_postcode)) >= config('ACCOUNT_POST_CODE')))
                {
                    $data['postcode'] = $billing_postcode;
                }
                else
                {
                    $errors[] = sprintf(lang('field_customer_post_code_error'), config('ACCOUNT_POST_CODE'));
                }
            }

            //city
            $billing_city = $this->input->post('billing_city');
            if (($billing_city !== NULL) && (strlen(trim($billing_city)) >= config('ACCOUNT_CITY')))
            {
                $data['city'] = $billing_city;
            }
            else
            {
                $errors[] = sprintf(lang('field_customer_city_error'), config('ACCOUNT_CITY'));
            }

            //country
            $billing_country = $this->input->post('billing_country');
            if (($billing_country !== NULL) && is_numeric($billing_country) && ($billing_country >= 1))
            {
                $data['country_id'] = $billing_country;
            }
            else
            {
                $errors[] = lang('field_customer_country_error');
            }

            //states
            if (config('ACCOUNT_STATE') >= 0)
            {
                $this->load->model('address_model');

                $billing_state = $this->input->post('billing_state');
                if ($this->address_model->has_zones($billing_country))
                {
                    $zone_id = $this->address_model->get_zone_id($billing_country, $billing_state);

                    if ($zone_id !== NULL)
                    {
                        $data['zone_id'] = $zone_id;
                    }
                    else
                    {
                        $errors[] = lang('field_customer_state_select_pull_down_error');
                    }
                }
                else
                {
                    if (strlen(trim($billing_state)) >= config('ACCOUNT_STATE'))
                    {
                        $data['state'] = $billing_state;
                    }
                    else
                    {
                        $errors[] = sprintf(lang('field_customer_state_error'), config('ACCOUNT_STATE'));
                    }
                }
            }
            else
            {
                if (strlen(trim($billing_state)) >= config('ACCOUNT_STATE'))
                {
                    $data['state'] = $billing_state;
                }
                else
                {
                    $errors[] = sprintf(lang('field_customer_state_error'), config('ACCOUNT_STATE'));
                }
            }

            //telephone
            if (config('ACCOUNT_TELEPHONE') >= 0)
            {
                $billing_telephone = $this->input->post('billing_telephone');
                if (($billing_telephone !== NULL) && (strlen(trim($billing_telephone)) >= config('ACCOUNT_TELEPHONE')))
                {
                    $data['telephone'] = $billing_telephone;
                }
                else
                {
                    $errors[] = sprintf(lang('field_customer_telephone_number_error'), config('ACCOUNT_TELEPHONE'));
                }
            }

            //fax
            if (config('ACCOUNT_FAX') >= 0)
            {
                $billing_fax = $this->input->post('billing_fax');
                if (($billing_fax !== NULL) && (strlen(trim($billing_fax)) >= config('ACCOUNT_FAX')))
                {
                    $data['fax'] = $billing_fax;
                }
                else
                {
                    $errors[] = sprintf(lang('field_customer_fax_number_error'), config('ACCOUNT_FAX'));
                }
            }
        }

        if (sizeof($errors) > 0)
        {
            $response = array('success' => FALSE, 'errors' => $errors);
        }
        else
        {
            $response = array('success' => TRUE);

            //set checkout method
            $data['checkout_method'] = $checkout_method;

            //if ship_to_this_address is on
            $data['ship_to_this_address'] = $this->input->post('ship_to_this_address');

            if ($this->customer->is_logged_on())
            {
                $data['email_address'] = $this->customer->get_email_address();
                $data['password'] = '';

                if(($data['create_billing_address'] !== NULL) && ($data['create_billing_address'] == 'on'))
                {
                    $this->shopping_cart->set_raw_billing_address($data);

                    if($data['ship_to_this_address'] == 'on')
                    {
                        $this->shopping_cart->set_raw_shipping_address($data);
                    }
                } else {
                    $billing_address_id = $this->input->post('sel_billing_address');
                    $this->shopping_cart->set_billing_address($billing_address_id);

                    if($data['ship_to_this_address'] == 'on')
                    {
                        $this->shopping_cart->set_shipping_address($billing_address_id);
                    }
                }
            } else {
                $this->shopping_cart->set_raw_billing_address($data);

                if($data['ship_to_this_address'] == 'on')
                {
                    $this->shopping_cart->set_raw_shipping_address($data);
                }
            }
        }

        $this->output->set_output(json_encode($response));
    }

    /**
     * Load shipping form
     */
    public function load_shipping_form() {
        $this->load->model('address_model');

        $data['is_logged_on'] = $this->customer->is_logged_on();

        $data['address_books'] = array();
        if ($this->customer->is_logged_on()) {
            $data['address_books'] = $this->address_book_model->get_addresses($this->customer->get_id());
        }

        //get shipping address
        $shipping_address = $this->shopping_cart->get_shipping_address();

        $data['shipping_email_address'] = isset($shipping_address['email_address']) ? $shipping_address['email_address'] : null;
        $data['shipping_gender'] = isset($shipping_address['gender']) ? $shipping_address['gender'] : null;
        $data['shipping_firstname'] = isset($shipping_address['firstname']) ? $shipping_address['firstname'] : null;
        $data['shipping_lastname'] = isset($shipping_address['lastname']) ? $shipping_address['lastname'] : null;
        $data['shipping_company'] = isset($shipping_address['company']) ? $shipping_address['company'] : null;
        $data['shipping_street_address'] = isset($shipping_address['street_address']) ? $shipping_address['street_address'] : null;
        $data['shipping_suburb'] = isset($shipping_address['suburb']) ? $shipping_address['suburb'] : null;
        $data['shipping_postcode'] = isset($shipping_address['postcode']) ? $shipping_address['postcode'] : null;
        $data['shipping_city'] = isset($shipping_address['city']) ? $shipping_address['city'] : null;
        $data['zone_code'] = isset($shipping_address['zone_code']) ? $shipping_address['zone_code'] : null;
        $data['shipping_state'] = isset($shipping_address['state']) ? $shipping_address['state'] : null;
        $data['shipping_country_id'] = isset($shipping_address['country_id']) ? $shipping_address['country_id'] : config('STORE_COUNTRY');
        $data['shipping_telephone'] = isset($shipping_address['telephone_number']) ? $shipping_address['telephone_number'] : null;
        $data['shipping_fax'] = isset($shipping_address['fax']) ? $shipping_address['fax'] : null;

        $data['create_shipping_address'] = FALSE;
        if (!$this->customer->is_logged_on())
        {
            $data['create_shipping_address'] = TRUE;
        }
        else if (isset($shipping_address['id']) && ($shipping_address['id'] == '-1'))
        {
            $data['create_shipping_address'] = TRUE;
        }
        else if ($this->customer->is_logged_on() && (count($data['address_books']) == 0))
        {
            $data['create_shipping_address'] = TRUE;
        }
        //END: get shipping address

        //get countries
        $countries = $this->address_model->get_countries();

        $countries_array[''] = lang('pull_down_default');
        foreach ($countries as $country) {
            $countries_array[$country['id']] = $country['name'];
        }

        $data['countries'] = $countries_array;

        //get states
        $states = $this->address_model->get_states($data['shipping_country_id']);

        foreach ($states as $state) {
            $states_array[$state['id']] = $state['text'];
        }

        $data['states'] = $states_array;

        $result = array(
        	'success' => TRUE, 
        	'form' => $this->template->_find_view('checkout/shipping_address_form.php', $data));

        $this->output->set_output(json_encode($result));
    }


    /**
     * Save shipping form
     */
    public function save_shipping_form() {
        $data = array();
        $errors = array();

        $this->load->model('account_model');

        $data['create_shipping_address'] = $this->input->post('create_shipping_address');
        if ((!$this->customer->is_logged_on()) || ($this->customer->is_logged_on() && isset($data['create_shipping_address']) && ($data['create_shipping_address'] == 'on'))) {
            //gender
            if (config('ACCOUNT_GENDER') == '1') {
                $shipping_gender = $this->input->post('shipping_gender');
                if (isset($shipping_gender) && (($shipping_gender == 'm') || ($shipping_gender == 'f'))) {
                    $data['gender'] = $shipping_gender;
                } else {
                    $errors[] = lang('field_customer_gender_error');
                }
            } else {
                $data['gender'] = isset($shipping_gender) ? $shipping_gender : 'm';
            }

            //firstname
            $shipping_firstname = $this->input->post('shipping_firstname');
            if (isset($shipping_firstname) && (strlen(trim($shipping_firstname)) >= config('ACCOUNT_FIRST_NAME'))) {
                $data['firstname'] = $shipping_firstname;
            } else {
                $errors[] = sprintf(lang('field_customer_first_name_error'), config('ACCOUNT_FIRST_NAME'));
            }

            //lastname
            $shipping_lastname = $this->input->post('shipping_lastname');
            if (isset($shipping_lastname) && (strlen(trim($shipping_lastname)) >= config('ACCOUNT_LAST_NAME'))) {
                $data['lastname'] = $shipping_lastname;
            } else {
                $errors[] = sprintf(lang('field_customer_last_name_error'), config('ACCOUNT_LAST_NAME'));
            }

            //company
            if (config('ACCOUNT_COMPANY') > -1) {
                $shipping_company = $this->input->post('shipping_company');
                if (isset($shipping_company) && (strlen(trim($shipping_company)) >= config('ACCOUNT_COMPANY'))) {
                    $data['company'] = $shipping_company;
                } else {
                    $errors[] = sprintf(lang('field_customer_company_error'), config('ACCOUNT_COMPANY'));
                }
            }

            //street address
            $shipping_street_address = $this->input->post('shipping_street_address');
            if (isset($shipping_street_address) && (strlen(trim($shipping_street_address)) >= config('ACCOUNT_STREET_ADDRESS'))) {
                $data['street_address'] = $shipping_street_address;
            } else {
                $errors[] = sprintf(lang('field_customer_street_address_error'), config('ACCOUNT_STREET_ADDRESS'));
            }

            //suburb
            if (config('ACCOUNT_SUBURB') >= 0) {
                $shipping_suburb = $this->input->post('shipping_suburb');
                if (isset($shipping_suburb) && (strlen(trim($shipping_suburb)) >= config('ACCOUNT_SUBURB'))) {
                    $data['suburb'] = $shipping_suburb;
                } else {
                    $errors[] = sprintf(lang('field_customer_suburb_error'), config('ACCOUNT_SUBURB'));
                }
            }

            //postcode
            if (config('ACCOUNT_POST_CODE') > -1) {
                $shipping_postcode = $this->input->post('shipping_postcode');
                if (isset($shipping_postcode) && (strlen(trim($shipping_postcode)) >= config('ACCOUNT_POST_CODE'))) {
                    $data['postcode'] = $shipping_postcode;
                } else {
                    $errors[] = sprintf(lang('field_customer_post_code_error'), config('ACCOUNT_POST_CODE'));
                }
            }

            //city
            $shipping_city = $this->input->post('shipping_city');
            if (isset($shipping_city) && (strlen(trim($shipping_city)) >= config('ACCOUNT_CITY'))) {
                $data['city'] = $shipping_city;
            } else {
                $errors[] = sprintf(lang('field_customer_city_error'), config('ACCOUNT_CITY'));
            }

            //country
            $shipping_country = $this->input->post('shipping_country');
            if (isset($shipping_country) && is_numeric($shipping_country) && ($shipping_country >= 1)) {
                $data['country_id'] = $shipping_country;
            } else {
                $errors[] = lang('field_customer_country_error');
            }

            //states
            if (config('ACCOUNT_STATE') >= 0) {
                $this->load->model('address_model');

                $shipping_state = $this->input->post('shipping_state');
                if ($this->address_model->has_zones($shipping_country)) {
                    $zone_id = $this->address_model->get_zone_id($shipping_country, $shipping_state);

                    if ($zone_id !== NULL) {
                        $data['zone_id'] = $zone_id;
                    } else {
                        $errors[] = lang('field_customer_state_select_pull_down_error');
                    }
                } else {
                    if (strlen(trim($shipping_state)) >= config('ACCOUNT_STATE')) {
                        $data['state'] = $shipping_state;
                    } else {
                        $errors[] = sprintf(lang('field_customer_state_error'), config('ACCOUNT_STATE'));
                    }
                }
            } else {
                if (strlen(trim($shipping_state)) >= config('ACCOUNT_STATE')) {
                    $data['state'] = $shipping_state;
                } else {
                    $errors[] = sprintf(lang('field_customer_state_error'), config('ACCOUNT_STATE'));
                }
            }

            //telephone
            if (config('ACCOUNT_TELEPHONE') >= 0) {
                $shipping_telephone = $this->input->post('shipping_telephone');
                if (isset($shipping_telephone) && (strlen(trim($shipping_telephone)) >= config('ACCOUNT_TELEPHONE'))) {
                    $data['telephone'] = $shipping_telephone;
                } else {
                    $errors[] = sprintf(lang('field_customer_telephone_number_error'), config('ACCOUNT_TELEPHONE'));
                }
            }

            //fax
            if (config('ACCOUNT_FAX') >= 0) {
                $shipping_fax = $this->input->post('shipping_fax');
                if (isset($shipping_fax) && (strlen(trim($shipping_fax)) >= config('ACCOUNT_FAX'))) {
                    $data['fax'] = $shipping_fax;
                } else {
                    $errors[] = sprintf(lang('field_customer_fax_number_error'), config('ACCOUNT_FAX'));
                }
            }
        }

        if (sizeof($errors) > 0)
        {
            $response = array('success' => FALSE, 'errors' => $errors);
        }
        else
        {
            if ($this->customer->is_logged_on())
            {
                if(isset($data['create_shipping_address']) && ($data['create_shipping_address'] == 'on'))
                {
                    $this->shopping_cart->set_raw_shipping_address($data);
                }
                else
                {
                    $this->shopping_cart->set_shipping_address($this->input->post('sel_shipping_address'));
                }
            } else {
                $this->shopping_cart->set_raw_shipping_address($data);
            }

            $response = array('success' => TRUE);
        }

        $this->output->set_output(json_encode($response));
    }

    /**
     * load shipping method
     */
    public function load_shipping_method_form() {
        $this->load->library('shipping');

        $data = array('has_quotes' => $this->shipping->has_quotes(),
                      'quotes' => $this->shipping->get_quotes(),
                      'selected_shipping_method_id' => $this->shopping_cart->get_shipping_method('id'));

        $result = array('success' => TRUE,
    					'form' => $this->template->_find_view('checkout/shipping_method_form.php', $data));

        $this->output->set_output(json_encode($result));
    }

    /**
     * load payment info
     */
    public function load_payment_information_form() {
        $this->load->library('payment');

        $data = array(
        	'selection' => $this->payment->selection(),
          	'has_billing_method' => $this->shopping_cart->has_billing_method(),
          	'selected_billing_method_id' => $this->shopping_cart->get_billing_method('id'),
          	'order_conditions' => $this->session->userdata('order_conditions'),
        	'payment_comments' => $this->session->userdata('payment_comments'));

        $result = array(
        	'success' => TRUE, 
        	'form' => $this->template->_find_view('checkout/payment_information_form.php', $data));

        $this->output->set_output(json_encode($result));
    }

    /**
     * load order confirmation
     */
    public function load_order_confirmation_form() {
        $this->lang->db_load('order');

        $this->load->library('payment', $this->shopping_cart->get_billing_method('id'));

        $shipping_address = $this->shopping_cart->get_shipping_address();
        $shipping_address['countries_name'] = $shipping_address['country_title'];

        $billing_address = $this->shopping_cart->get_billing_address();
        $billing_address['countries_name'] = $billing_address['country_title'];

        $data = array(
            'has_shipping_address' => $this->shopping_cart->has_shipping_address(),
            'shipping_address' => $shipping_address,
            'has_shipping_method' => $this->shopping_cart->has_shipping_method(),
            'shipping_method' => $this->shopping_cart->get_shipping_method('title'),
            'billing_address' => $billing_address,
            'billing_method' => $this->shopping_cart->get_billing_method('title'),
            'number_of_tax_groups' => $this->shopping_cart->number_of_tax_groups(),
            'products' => $this->shopping_cart->get_products(),
            'order_totals' => $this->shopping_cart->get_order_totals(),
            'payment_comments' => $this->session->userdata('payment_comments'),
            'has_action_url' => $this->payment->has_action_url(),
            'form_action_url' => $this->payment->has_action_url() ? $this->payment->get_action_url() : site_url('checkout/checkout/process'),
            'has_active_payment' => $this->payment->has_active(),
            'confirmation' => $this->payment->confirmation(),
            'process_button' => $this->payment->process_button());

        $result = array(
        	'success' => TRUE, 
        	'form' => $this->template->_find_view('checkout/order_confirmation_form.php', $data));

        $this->output->set_output(json_encode($result));
    }

    /**
     * save shipping method
     */
    public function save_shipping_method () {
        $errors = array();

        //load shipping library
        $this->load->library('shipping');

        // if no shipping method has been selected, automatically select the cheapest method.
        if ($this->shopping_cart->has_shipping_method() === FALSE)
        {
            $this->shopping_cart->set_shipping_method($this->shipping->get_cheapest_quote());
        }

        //TRUE to do xss clean
        $shipping_comments = $this->input->post('shipping_comments', TRUE);
        if ($shipping_comments !== FALSE)
        {
            $this->session->set_userdata(array('comments' => $shipping_comments));
        }

        if ($this->shipping->has_quotes())
        {
            $shipping_mod_sel = $this->input->post('shipping_mod_sel');
            if ($shipping_mod_sel !== FALSE)
            {
                list($module, $method) = explode('-', $shipping_mod_sel);
                $module_class = strtolower('shipping_' . $module);
                $this->load->library('shipping/' . $module_class);

                if (is_object($this->$module_class) && $this->$module_class->is_enabled())
                {
                    $quote = $this->shipping->get_quote($shipping_mod_sel);

                    if (isset($quote['error']))
                    {
                        $this->shopping_cart->reset_shipping_method();

                        $errors[] = $quote['error'];
                    }
                    else
                    {
                        $this->shopping_cart->set_shipping_method($quote);
                    }
                }
                else
                {
                    $this->shopping_cart->reset_shipping_method();
                }
            }
        }
        else
        {
            $this->shopping_cart->reset_shipping_method();
        }

        if (sizeof($errors) > 0)
        {
            $response = array('success' => false, 'errors' => $errors);
        }
        else
        {
            $response = array('success' => true);
        }

        $this->output->set_output(json_encode($response));
    }

    /**
     * save payment method
     */
    public function save_payment_method () {
        $errors = array();

        $payment_comments = $this->input->post('payment_comments', TRUE);
        $this->session->set_userdata(array('payment_comments' => $payment_comments));

        if (config('DISPLAY_CONDITIONS_ON_CHECKOUT') == '1') {
            $conditions = $this->input->post('conditions');
            if (($conditions === FALSE) || ($conditions != '1')) {
                $errors[] = lang('error_conditions_not_accepted');
            }
        }

        $payment_method = $this->input->post('payment_method');

        //load payment library
        $this->load->library('payment', (($payment_method !== FALSE) ? $payment_method : $this->shopping_cart->get_billing_method('id')));


        $module_class = strtolower('payment_' . $payment_method);
        if ($payment_method !== FALSE) {
            $this->shopping_cart->set_billing_method(array('id' => $payment_method, 'title' => $this->$module_class->get_title()));
        }

        if ( $this->payment->has_active() && ((isset($this->$module_class) === FALSE) || (isset($this->$module_class) && is_object($this->$module_class) && ($this->$module_class->is_enabled() === FALSE))) ) 
        {
            $errors[] = lang('error_no_payment_module_selected');
        }

        if ($this->payment->has_active()) {
            $this->payment->pre_confirmation_check();
        }

        if ($this->message_stack->size('checkout_payment') > 0) {
            $errors =  array_merge($errors, $messageStack->getMessages('checkout_payment'));
        }

        if (sizeof($errors) > 0) {
            $response = array('success' => FALSE, 'errors' => $errors);
        } else {
            $response = array('success' => TRUE);
        }

        $this->output->set_output(json_encode($response));
    }

    /**
     * checkout success
     */
    public function process() {
        //if shopping cart does not has content and redirec to home
        if (!$this->shopping_cart->has_contents())
        {
            redirect(site_url());
        }

        if ($this->shopping_cart->has_billing_method()) {
            // load selected payment module
            $payment_method = $this->shopping_cart->get_billing_method('id');

            //load payment library
            $this->load->library('payment', (($payment_method !== FALSE) ? $payment_method : $this->shopping_cart->get_billing_method('id')));

            $this->{'payment_' . $payment_method}->process();
        } 
        else 
        {
            $this->load->library('order');
            
            $orders_id = $this->order->create_order();
            $this->order->process($orders_id, ORDERS_STATUS_PAID);
        }

        $this->shopping_cart->reset();

        redirect('checkout/success');
    }

    /**
     * get country states
     */
    public function get_country_states()
    {
        $this->load->model('address_model');

        $countries_id = $this->input->get_post('countries_id');
        $type = $this->input->get_post('type');
        $type = ($type === NULL) ? 'billing' : 'shipping';

        //states
        $states = $this->address_model->get_states($countries_id);

        $options = '';
        if (($states !== NULL) && sizeof($states) > 0)
        {
            foreach ($states as $state) {
                $states_array[$state['id']] = $state['text'];
            }

            $options = form_dropdown($type . 'state', $states_array, NULL, 'id="' . $type . '_state"');
        }
        else
        {
            $options = '<input type="text" id="' . $type . '_state" name="' . $type . '_state" />';
        }

        $result = array('success' => TRUE, 'options' => $options);

        $this->output->set_output(json_encode($result));
    }
}

/* End of file checkout.php */
/* Location: ./system/tomatocart/controllers/checkout/checkout.php */