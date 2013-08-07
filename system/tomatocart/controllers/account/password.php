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
 * Account_Password Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Password extends TOC_Controller 
{
    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();

        //set page title
        $this->set_page_title(lang('account_password_heading'));
        
        //breadcrumb
        $this->template->set_breadcrumb(lang('breadcrumb_my_account'), site_url('account'));
        $this->template->set_breadcrumb(lang('breadcrumb_edit_password'), site_url('account/password'));
    }


    /**
     * Default Function
     *
     * @access public
     */
    public function index()
    {
        //Setup view
        $this->template->build('account/account_password');
    }

    /**
     * Save the new password
     *
     * @access public
     */
    public function save()
    {
        //load model
        $this->load->model('account_model');

        //get the post data
        $current_password = trim($this->input->post('password_current'));
        $new_password = trim($this->input->post('password_new'));
        $confirmation_password = trim($this->input->post('password_confirmation'));

        //validate the current password and the new password
        if (empty($current_password) || (strlen($current_password) < config('ACCOUNT_PASSWORD')))
        {
            $this->message_stack->add('account_password', sprintf(lang('field_customer_password_current_error'), config('ACCOUNT_PASSWORD')));
        }
        elseif (empty($new_password) || (strlen($new_password) < config('ACCOUNT_PASSWORD')))
        {
            $this->message_stack->add('account_password', sprintf(lang('field_customer_password_new_error'), config('ACCOUNT_PASSWORD')));
        }
        elseif (empty($confirmation_password) || ($new_password != $confirmation_password))
        {
            $this->message_stack->add('account_password', lang('field_customer_password_new_mismatch_with_confirmation_error'));
        }

        //if the validation is successful, update the password
        if ($this->message_stack->size('account_password') === 0)
        {
            if ($this->account_model->check_account($this->customer->get_email_address(), $current_password))
            {
                $data['customers_password'] = encrypt_password($new_password);

                if ($this->account_model->save($data, $this->customer->get_id()))
                {
                    $this->message_stack->add_session('account', lang('success_password_updated'), 'success');

                    redirect(site_url('account'));
                }
                else
                {
                    $this->message_stack->add('account_password', lang('error_database'));
                }
            }
        }

        //Setup view
        $this->template->build('account/account_password');
    }
}

/* End of file password.php */
/* Location: ./system/tomatocart/controllers/account/password.php */