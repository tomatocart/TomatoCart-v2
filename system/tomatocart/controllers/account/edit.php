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
 * Account_Edit Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Edit extends TOC_Controller 
{
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        //load model
        $this->load->model('account_model');

        //set page title
        $this->set_page_title(lang('account_edit_heading'));
        
        //breadcrumb
        $this->template->set_breadcrumb(lang('breadcrumb_my_account'), site_url('account'));
        $this->template->set_breadcrumb(lang('breadcrumb_edit_account'), site_url('account/edit'));
    }

    /**
     * Default Function
     *
     * @access public
     */
    public function index()
    {
        //setup view data
        $data = $this->account_model->get_data($this->customer->get_email_address());
        
        //setup view
        $this->template->build('account/account_edit', $data);
    }

    /**
     * Save the edited account
     *
     * @access public
     */
    public function save()
    {
        $data = array();

        //validate gender
        if (config('ACCOUNT_GENDER') == '1')
        {
            $gender = $this->input->post('gender');
            if (($gender == 'm') || ($gender == 'f'))
            {
                $data['customers_gender'] = $gender;
            }
            else
            {
                $this->message_stack->add('account_edit', lang('field_customer_gender_error'));
            }
        }
        else
        {
            $data['customers_gender'] = !empty($gender) ? $gender : '';
        }

        //validate firstname
        $firstname = $this->input->post('firstname');
        if (!empty($firstname) || (strlen(trim($firstname)) >= config('ACCOUNT_FIRST_NAME')))
        {
            $data['customers_firstname'] = $this->security->xss_clean($firstname);
        }
        else
        {
            $this->message_stack->add('account_edit', sprintf(lang('field_customer_first_name_error'), config('ACCOUNT_FIRST_NAME')));
        }

        //validate lastname
        $lastname = $this->input->post('lastname');
        if (!empty($lastname) || (strlen(trim($lastname)) >= config('ACCOUNT_LAST_NAME')))
        {
            $data['customers_lastname'] = $this->security->xss_clean($lastname);
        }
        else
        {
            $this->message_stack->add('account_edit', sprintf(lang('field_customer_last_name_error'), config('ACCOUNT_LAST_NAME')));
        }

        //validate dob days
        if (config('ACCOUNT_DATE_OF_BIRTH') == '1')
        {
            $dob_days = $this->input->post('dob_days');
            if (!empty($dob_days))
            {
                $data['customers_dob'] = $dob_days;
            }
            else
            {
                $this->message_stack->add('account_edit', lang('field_customer_date_of_birth_error'));
            }
        }

        //email address
        $email_address = $this->input->post('email_address');
        if ((!empty($email_address)) && (strlen(trim($email_address)) >= config('ACCOUNT_EMAIL_ADDRESS')))
        {
            if (validate_email_address($email_address))
            {
                if ($this->account_model->check_duplicate_entry($email_address, $this->customer->get_id()) === FALSE)
                {
                    $data['customers_email_address'] = $email_address;
                }
                else
                {
                    $this->message_stack->add('account_edit', lang('field_customer_email_address_exists_error'));
                }
            }
            else
            {
                $this->message_stack->add('account_edit', lang('field_customer_email_address_check_error'));
            }
        }
        else
        {
            $this->message_stack->add('account_edit', sprintf(lang('field_customer_email_address_error'), config('ACCOUNT_EMAIL_ADDRESS')));
        }

        //newsletter
        if (config('ACCOUNT_NEWSLETTER') == '1')
        {
            $data['customers_newsletter'] = ($this->input->post('newsletter') == 1) ? '1' : '0';
        }

        if ($this->message_stack->size('account_edit') === 0)
        {
            if ($this->account_model->save($data, $this->customer->get_id()))
            {
                $this->customer->set_data($data['customers_email_address']);

                $this->message_stack->add_session('account', lang('success_account_updated'), 'success');

                redirect(site_url('account'));
            }
            else
            {
                $this->message_stack->add('account_edit', lang('error_database'));
            }
        }

        //setup view
        $this->template->build('account/account_edit');
    }
}

/* End of file edit.php */
/* Location: ./system/tomatocart/controllers/account/edit.php */