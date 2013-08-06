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
 * Customers Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com
 */
class Customers extends TOC_Controller
{
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('customers_model');
    }

    // --------------------------------------------------------------------

    /**
     * List customers
     *
     * @access public
     * @return string
     */
    public function list_customers()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;

        $search = $this->input->get_post('search');
        
        $records = array();

        $customers = $this->customers_model->get_customers($start, $limit, $search);
        if ($customers !== NULL)
        {
            foreach($customers as $customer)
            {
                //build the extra information of the customers
                $customers_info = array('field_email_address' => array('label' => lang('field_gender'), 'value' => $customer['customers_email_address']), 
                                        'field_customers_group' => array('label' => lang('field_customers_group'), 'value' => $customer['customers_groups_name']), 
                                        'field_number_of_logons' => array('label' => lang('field_number_of_logons'), 'value' => $customer['number_of_logons']), 
                                        'field_date_last_logon' => array('label' => lang('field_date_last_logon'), 'value' => get_date_short($customer['date_last_logon'])), 
                                        'field_gender' => array('label' => lang('field_gender'), 'value' => $customer['customers_gender'] == 'm' ? lang('gender_male') : lang('gender_female')));
                
                $records[] = array(
                    'customers_id' => $customer['customers_id'],
                    'customers_lastname' => $customer['customers_lastname'],
                    'customers_firstname' => $customer['customers_firstname'],
                    'customers_credits' => $this->currencies->format($customer['customers_credits']),
                    'date_account_created' => $customer['date_account_created'],  
                    'customers_status' => $customer['customers_status'],
                    'customers_info' => $customers_info);           
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->customers_model->get_totals($search), EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------

    /**
     * Delete customer
     *
     * @access public
     * @param $customers_id
     * @return string
     */
    public function delete_customer($customers_id)
    {
        $customers_id = $this->input->post('customers_id');

        if ($this->customers_model->delete($customers_id))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Set status
     *
     * @access public
     * @return string
     */
    public function set_status()
    {
        $flag = $this->input->post('flag');
        $customers_id = $this->input->post('customers_id');

        if ($this->customers_model->set_status($customers_id, $flag))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Get customers groups
     *
     * @access public
     * @return string
     */
    public function get_customers_groups()
    {
        $groups = $this->customers_model->get_customers_groups();

        $records = array(array('id' => '', 'text' => lang('none')));
        if ($groups !== NULL)
        {
            foreach($groups as $group)
            {
                $records[] = array('id' => $group['customers_groups_id'],'text' => $group['customers_groups_name']);
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------

    /**
     * Save customer data
     *
     * @access public
     * @return string
     */
    public function save_customer()
    {
        $this->load->helper('email');

        $customers_dob = explode('-', $this->input->post('customers_dob'));
        $dob_year = $customers_dob[0];
        $dob_month = $customers_dob[1];
        $dob_date= $customers_dob[2];

        $customers_gender = $this->input->post('customers_gender');
        $customers_newsletter = $this->input->post('customers_newsletter');
        $customers_status = $this->input->post('customers_status');
        $customers_groups_id = $this->input->post('customers_groups_id');
        $customers_id = $this->input->post('customers_id');
        $confirm_password = $this->input->post('confirm_password');

        $data = array('gender' => ( ! empty($customers_gender) ? $customers_gender : ''),
                      'firstname' => $this->input->post('customers_firstname'),
                      'lastname' => $this->input->post('customers_lastname'),
                      'dob' => $this->input->post('customers_dob'),
                      'email_address' => $this->input->post('customers_email_address'),
                      'customers_password' => $this->input->post('customers_password'),
                      'newsletter' => ( ! empty($customers_newsletter) && ($customers_newsletter == 'on') ? '1' : '0' ),           
                      'status' => ( ! empty($customers_status) && ($customers_status == 'on') ? '1' : '0'),
                      'customers_groups_id' => ( ! empty($customers_groups_id) ? $customers_groups_id : '') );

        $error = FALSE;
        $feedback = array();

        //customer gender
        if (ACCOUNT_GENDER > 0)
        {
            if ( ($data['gender'] != 'm') && ($data['gender'] != 'f') )
            {
                $error = TRUE;
                $feedback[] = lang('ms_error_gender');
            }
        }

        //customer firstname
        if (strlen(trim($data['firstname'])) < ACCOUNT_FIRST_NAME)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_first_name'), ACCOUNT_FIRST_NAME);
        }

        //customer lastname
        if (strlen(trim($data['lastname'])) < ACCOUNT_LAST_NAME)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_last_name'), ACCOUNT_LAST_NAME);
        }

        //customer email address
        if (strlen(trim($data['email_address'])) < ACCOUNT_EMAIL_ADDRESS)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_email_address'), ACCOUNT_EMAIL_ADDRESS);
        }
        elseif ( ! valid_email($data['email_address']))
        {
            $error = TRUE;
            $feedback[] = lang('ms_error_email_address_invalid');
        }
        else
        {
            //verify that the email address is uesed by some customers
            $check = $this->customers_model->check($data['email_address'], $customers_id);

            if ($check > 0)
            {
                $error = TRUE;
                $feedback[] = lang('ms_error_email_address_exists');
            }
        }

        //password
        if ((empty($customers_id) || ! empty($data['customers_password'])) && (strlen(trim($data['customers_password'])) < ACCOUNT_PASSWORD))
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_password'), ACCOUNT_PASSWORD);
        }
        else if ( ! empty($confirm_password) && ( (trim($data['customers_password']) != trim($confirm_password)) || ( strlen(trim($data['customers_password'])) != strlen(trim($confirm_password)))))
        {
            $error = TRUE;
            $feedback[] = lang('ms_error_password_confirmation_invalid');
        }

        //save customer data
        if ($error === FALSE)
        {
            if ($this->customers_model->save((is_numeric($customers_id) ? $customers_id : NULL), $data))
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Load customer
     *
     * @access public
     * @return string
     */
    public function load_customer()
    {
        $customers_id = $this->input->post('customers_id');

        $data = $this->customers_model->get_data($customers_id);
        
        if ($data !== NULL) 
        {
            $data['customers_dob'] = mdate('%Y-%m-%d', human_to_unix($data['customers_dob']));
            $data['customers_password'] = '';
            $data['confirm_password'] = '';
        }

        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }

    // --------------------------------------------------------------------

    /**
     * List address books
     *
     * @access public
     * @return string
     */
    public function list_address_books()
    {
        //load address library
        $this->load->library('address');

        $customers_id = $this->input->get_post('customers_id');
        $addresses = $this->customers_model->get_addressbook_data($customers_id);
        
        //get the default address book id of current customers id
        $customers_info = $this->customers_model->get_data($customers_id);
        $default_address_id = $customers_info['customers_default_address_id'];
        
        $records = array();
        if ($addresses !== NULL)
        {
            foreach($addresses as $address)
            {
                $address_html= $this->address->format($address, '<br />');
                
                //the address book is the default address book
                if ($default_address_id == $address['address_book_id'])
                {
                    $address_html .= '<strong><i>&nbsp;(' . lang('primary_address') . ')</i></strong>';
                }

                $records[] = array(
                    'address_book_id' => $address['address_book_id'],
                    'address_html' => $address_html
                );
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------

    /**
     * Delete address book
     *
     * @access public
     * @return string
     */
    public function delete_address_book()
    {
        $address_book_id = $this->input->post('address_book_id');
        $customers_id = $this->input->post('customers_id');
        
        //flag to check the error
        $error = FALSE;
        $feedback = array();
        
        //get the current customer infomation
        $customer_info = $this->customers_model->get_data($customers_id);
        
        //forbidden to delete the default address book
        if ($customer_info['customers_default_address_id'] == $address_book_id)
        {
            $error = TRUE;
            $feedback[] = lang('delete_warning_primary_address_book_entry');
        }
        
        if ($error === FALSE)
        {
            //delete the address book
            if ($this->customers_model->delete_address($address_book_id))
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Get countries
     *
     * @access public
     * @return string
     */
    public function get_countries()
    {
        $this->load->library('address');

        //get countries
        $countries = $this->address->get_countries();
        
        $records = array();
        if ($countries !== NULL) 
        {
            foreach ($countries as $country)
            {
                $records[] = array('country_id' => $country['id'], 'country_title' => $country['name']);
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------

    /**
     * Get zones
     *
     * @access public
     * @return string
     */
    public function get_zones()
    {
        $this->load->library('address');

        $country_id = $this->input->get_post('country_id');
        $zones = $this->address->get_zones($country_id);

        $records = array();
        if ($zones !== NULL) 
        {
            foreach ($zones as $zone) {
                $records[] = array(
                    'zone_code' => $zone['code'],
                    'zone_name' => $zone['name']);
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------

    /**
     * Save address book
     *
     * @access public
     * @return string
     */
    public function save_address_book()
    {
        $gender = $this->input->post('gender');
        $company = $this->input->post('company');
        $suburb = $this->input->post('suburb');
        $postcode = $this->input->post('postcode');
        $z_code = $this->input->post('z_code');
        $telephone_number = $this->input->post('telephone_number');
        $fax_number = $this->input->post('fax_number');
        $primary= $this->input->post('primary');

        $data = array(
            'customer_id' => $this->input->post('customers_id'),
            'gender' => (!empty($gender) ? $gender : ''),
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'company' => (!empty($company) ? $company : ''),
            'street_address' => $this->input->post('street_address'),
            'suburb' => (!empty($suburb) ? $suburb : ''),
            'postcode' => (!empty($postcode) ? $postcode : ''),
            'city' => $this->input->post('city'),
            'state' => (!empty($z_code) ? $z_code : ''),
            'zone_id' => '0', //set blow
            'country_id' => $this->input->post('country_id'),
            'telephone' => (!empty($telephone_number) ? $telephone_number : ''),
            'fax' => (!empty($fax_number) ? $fax_number : ''),
            'primary' => (!empty($primary) && ($primary == 'on') ? TRUE : FALSE));

        $error = FALSE;
        $feedback = array();

        //verify account gender
        if (ACCOUNT_GENDER > 0)
        {
            if ( ($data['gender'] != 'm') && ($data['gender'] != 'f') )
            {
                $error = TRUE;
                $feedback[] = lang('ms_error_gender');
            }
        }

        //verify account first name
        if (strlen(trim($data['firstname'])) < ACCOUNT_FIRST_NAME)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_first_name'), ACCOUNT_FIRST_NAME);
        }

        //verify account last name
        if (strlen(trim($data['lastname'])) < ACCOUNT_LAST_NAME)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_last_name'), ACCOUNT_LAST_NAME);
        }


        //verify account company
        if (ACCOUNT_COMPANY > 0)
        {
            if (strlen(trim($data['company'])) < ACCOUNT_COMPANY)
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('ms_error_company'), ACCOUNT_COMPANY);
            }
        }

        //verify account street address
        if (strlen(trim($data['street_address'])) < ACCOUNT_STREET_ADDRESS)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_street_address'), ACCOUNT_STREET_ADDRESS);
        }

        //verify account suburb
        if (ACCOUNT_SUBURB > 0)
        {
            if (strlen(trim($data['suburb'])) < ACCOUNT_SUBURB)
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('ms_error_suburb'), ACCOUNT_SUBURB);
            }
        }

        //verify account post code
        if (ACCOUNT_POST_CODE > 0)
        {
            if (strlen(trim($data['postcode'])) < ACCOUNT_POST_CODE)
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('entry_post_code'), ACCOUNT_POST_CODE);
            }
        }

        //verify account city
        if (strlen(trim($data['city'])) < ACCOUNT_CITY)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_city'), ACCOUNT_CITY);
        }

        //verify account state
        if (ACCOUNT_STATE > 0)
        {
            $zones_nums = $this->customers_model->get_state_zones($data['country_id']);

            //set the zone id
            if ($zones_nums > 0)
            {
                $zones = $this->customers_model->get_zones($data['country_id'], strtoupper($data['state']));

                if (count($zones) === 1)
                {
                    $data['zone_id'] = $zones[0]['zone_id'];
                }
                else
                {
                    $zone_likes = $this->customers_model->get_zone_likes($data['country_id'], $data['state']);

                    if (count($zone_likes) === 1)
                    {
                        $data['zone_id'] = $zone_likes[0]['zone_id'];
                    }
                    else
                    {
                        $error = TRUE;
                        $feedback[] = lang('ms_warning_state_select_from_list');
                    }
                }
            }
            else if (strlen(trim($data['state'])) < ACCOUNT_STATE)
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('ms_error_state'), ACCOUNT_STATE);
            }
        }

        //verify account country
        if ( ! is_numeric($data['country_id']) || ($data['country_id'] < 1)) 
        {
            $error = TRUE;
            $feedback[] = lang('ms_error_country');
        }

        //verify account telephone
        if (ACCOUNT_TELEPHONE > 0) 
        {
            if (strlen(trim($data['telephone'])) < ACCOUNT_TELEPHONE) 
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('ms_error_telephone_number'), ACCOUNT_TELEPHONE);
            }
        }

        //verify account fax
        if (ACCOUNT_FAX > 0) 
        {
            if (strlen(trim($data['fax'])) < ACCOUNT_FAX) 
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('ms_error_fax_number'), ACCOUNT_FAX);
            }
        }

        if ($error === FALSE ) 
        {
            $address_book_id = $this->input->post('address_book_id');
            $address_book_id = is_numeric($address_book_id) ? $address_book_id : NULL;

            if ($this->customers_model->save_address($address_book_id, $data)) 
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            } 
            else 
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        } 
        else 
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Delete address books
     *
     * @access public
     * @return string
     */
    public function delete_address_books()
    {
        //flag to check error
        $error = FALSE;
        $feedback = array();

        $batch = json_decode($this->input->post('batch'));
        
        //forbidden to delete the default address book
        $customer_info = $this->customers_model->get_data($this->input->post('customers_id'));
        if (in_array($customer_info['customers_default_address_id'], $batch))
        {
            $error = TRUE;
            $feedback[] = lang('delete_warning_primary_address_book_entry');
        }
        
        //delete the address books
        if ($error === FALSE)
        {
            if (is_array($batch) && count($batch) > 0)
            {
                foreach($batch as $id)
                {
                    if ($this->customers_model->delete_address($id) === FALSE)
                    {
                        $error = TRUE;
                        break;
                    }
                }
            }
            
        }
        
        if ($error === FALSE)
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Load address book
     *
     * @access public
     * @return string
     */
    public function load_address_book()
    {
        $customers_id = $this->input->post('customers_id');
        $address_book_id = $this->input->post('address_book_id');
        
        $data = $this->customers_model->get_addressbook_data($customers_id, $address_book_id);

        $data['primary'] = FALSE;
        $customer = $this->customers_model->get_data($customers_id);
        if ($customer['customers_default_address_id'] == $address_book_id) 
        {
            $data['primary'] = TRUE;
        }

        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file customers.php */
/* Location: ./system/controllers/customers.php */