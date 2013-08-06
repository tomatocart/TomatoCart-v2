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
 * Create Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Create extends TOC_Controller
{

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        //set page title
        $this->set_page_title(lang('sign_in_heading'));

        //breadcrumb
        $this->template->set_breadcrumb(lang('breadcrumb_my_account'), site_url('account'));
        $this->template->set_breadcrumb(lang('create_account_heading'), site_url('account/create'));

        //data
        $this->data = array();

        if (config('DISPLAY_PRIVACY_CONDITIONS') == '1')
        {
            //load model
            $this->load->model('info_model');

            //get the privacy
            $privacy = $this->info_model->get_article(INFORMATION_PRIVACY_NOTICE, lang_id());
            $this->data['privacy'] = array('title' => $privacy['articles_name'], 'content' => $privacy['articles_description']);
        }
    }

    /**
     * Default Function
     *
     * @access public
     */
    public function index()
    {
        //setup view
        $this->template->build('account/create', $this->data);
    }

    /**
     * save customer
     *
     * @access public
     */
    public function save()
    {
        //load model
        $this->load->model('account_model');
         
        $data = array();

        //validate privacy conditions
        if (config('DISPLAY_PRIVACY_CONDITIONS') == '1')
        {
            if ($this->input->post('privacy_conditions') != '1')
            {
                $this->message_stack->add('create', lang('error_privacy_statement_not_accepted'));
            }
        }

        //validate gender
        if (config('ACCOUNT_GENDER') == '1')
        {
            if (($this->input->post('gender') == 'm') || ($this->input->post('gender') == 'f'))
            {
                $data['customers_gender'] = $this->input->post('gender');
            }
            else
            {
                $this->message_stack->add('create', lang('field_customer_gender_error'));
            }
        }
        else
        {
            $gender = $this->input->post('gender');
            $data['customers_gender'] = ($this->input->post('gender') == NULL) ? '' : $this->input->post('gender');
        }

        //validate firstname
        if (( $this->input->post('firstname') !== NULL) && (strlen(trim( $this->input->post('firstname'))) >= config('ACCOUNT_FIRST_NAME')))
        {
            $data['customers_firstname'] = $this->input->post('firstname');
        }
        else
        {
            $this->message_stack->add('create', sprintf(lang('field_customer_first_name_error'), config('ACCOUNT_FIRST_NAME')));
        }

        //validate lastname
        if (($this->input->post('lastname') !== NULL) && (strlen(trim($this->input->post('lastname'))) >= config('ACCOUNT_LAST_NAME')))
        {
            $data['customers_lastname'] = $this->input->post('lastname');
        }
        else
        {
            $this->message_stack->add('create', sprintf(lang('field_customer_last_name_error'), config('ACCOUNT_LAST_NAME')));
        }

        //newsletter
        $data['customers_newsletter'] = ($this->input->post('newsletter') == 1) ? '1' : '0';

        //validate dob days
        if (config('ACCOUNT_DATE_OF_BIRTH') == '1')
        {
            $dob_days = $this->input->post('dob_days');
            if (!empty($dob_days))
            {
                $data['customers_dob'] = $this->input->post('dob_days');
            }
            else
            {
                $this->message_stack->add('create', lang('field_customer_date_of_birth_error'));
            }
        }

        //email address
        if (($this->input->post('email_address') !== NULL) && (strlen(trim($this->input->post('email_address'))) >= config('ACCOUNT_EMAIL_ADDRESS')))
        {
            if (validate_email_address($this->input->post('email_address')))
            {
                if ($this->account_model->check_duplicate_entry($this->input->post('email_address')) === FALSE)
                {
                    $data['customers_email_address'] = $this->input->post('email_address');
                }
                else
                {
                    $this->message_stack->add('create', lang('field_customer_email_address_exists_error'));
                }
            }
            else
            {
                $this->message_stack->add('create', lang('field_customer_email_address_check_error'));
            }
        }
        else
        {
            $this->message_stack->add('create', sprintf(lang('field_customer_email_address_error'), config('ACCOUNT_EMAIL_ADDRESS')));
        }

        //validate password
        if (($this->input->post('password') === NULL) || (($this->input->post('password') !== NULL) && (strlen(trim($this->input->post('password'))) < config('ACCOUNT_PASSWORD'))) )
        {
            $this->message_stack->add('create', sprintf(lang('field_customer_password_error'), config('ACCOUNT_PASSWORD')));
        }
        elseif ( ($this->input->post('confirmation') === NULL) || (($this->input->post('confirmation') !== NULL) && (trim($this->input->post('password')) != trim($this->input->post('confirmation')))) )
        {
            $this->message_stack->add('create', lang('field_customer_password_mismatch_with_confirmation'));
        }
        else
        {
            $data['customers_password'] = encrypt_password($this->input->post('password'));
        }

        if ($this->message_stack->size('create') === 0)
        {
            $data['customers_status'] = 1;

            //if create account success send email
            if ($this->account_model->insert($data))
            {
                //set data to session
                $this->customer->set_data($data['customers_email_address']);

                //synchronize shopping cart content with database
                $this->shopping_cart->synchronize_with_database();

                //synchronize wishlist with database
                $this->wishlist->synchronize_with_database();

                //send email
                $this->load->library('email_template');
                $email = $this->email_template->get_email_template('create_account_email');
                $email->set_data($data['customers_password']);
                $email->build_message();
                $email->send_email();
            }

            //set page title
            $this->set_page_title(lang('create_account_success_heading'));

            //setup view
            $this->template->build('account/create_success');
        }
        else
        {
            //setup view
            $this->template->build('account/create', $this->data);
        }
    }
}

/* End of file create.php */
/* Location: ./system/tomatocart/controllers/account/create.php */