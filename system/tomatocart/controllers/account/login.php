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
 * Login Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Login extends TOC_Controller
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
        $this->set_page_title(lang('sign_in_heading'));
        
        //breadcrumb
        $this->template->set_breadcrumb(lang('breadcrumb_my_account'), site_url('account'));
        $this->template->set_breadcrumb(lang('breadcrumb_sign_in'), site_url('account/login'));
    }

    /**
     * Default Function
     *
     * @access public
     * @param string
     * @return void
     */
    public function index()
    {
        //if customer is already logged in redirect to account
        if ($this->customer->is_logged_on()) {
            redirect('account');
        }
        
        //setup view
        $this->template->build('account/login');
    }

    /**
     * customer login process
     *
     * @param string
     * @return
     */
    public function process()
    {
        //load model
        $this->load->model('account_model');
         
        $email_address = $this->input->post('email_address');
        $password = $this->input->post('password');

        //check account
        if ($this->account_model->check_account($email_address, $password))
        {
            //check account status
            if ($this->account_model->status_check($email_address))
            {
                //set customer data
                $this->customer->set_data($email_address);

                //update last logon
                $this->account_model->update_last_logon($this->customer->get_id());

                //synchronize shopping cart content with database
                $this->shopping_cart->synchronize_with_database();
                
                //synchronize wishlist with database
                $this->wishlist->synchronize_with_database(); 

                //remove current url from nagivation history
                $this->navigation_history->remove_current_page();

                //if nagivation history has path, then redirect to path else redirect to account
                if ($this->navigation_history->has_path())
                {
                    $this->navigation_history->redirect_to_path();
                }
                else
                {
                    redirect('account');
                }
            }
            else
            {
                $this->message_stack->add('login', lang('error_login_status_disabled'));
            }
        }
        else
        {
            $this->message_stack->add('login', lang('error_login_no_match'));
        }

        $this->template->build('account/login');
    }


    /**
     * customer login process
     *
     * @param string
     * @return
     */
    public function ajax_process()
    {
        //load model
        $this->load->model('account_model');
         
        $email_address = $this->input->post('email_address');
        $password = $this->input->post('password');

        //check account
        if ($this->account_model->check_account($email_address, $password))
        {
            //check account status
            if ($this->account_model->status_check($email_address))
            {
                //set customer data
                $this->customer->set_data($email_address);

                //update last logon
                $this->account_model->update_last_logon($this->customer->get_id());

                //synchronize shopping cart content with database
                $this->shopping_cart->synchronize_with_database();
                
                //synchronize wishlist with database
                $this->wishlist->synchronize_with_database(); 
            }
            else
            {
                $this->message_stack->add('login', lang('error_login_status_disabled'));
            }
        }
        else
        {
            $this->message_stack->add('login', lang('error_login_no_match'));
        }

        // if there is no error
        if ($this->message_stack->size('login') == 0)
        {
            $this->output->set_output(json_encode(array('success' => TRUE)));
        }
        else
        {
            $this->output->set_output(json_encode(array('success' => FALSE, 'errors' => $this->message_stack->output_plain('login'))));
        }
    }
}

/* End of file login.php */
/* Location: ./system/tomatocart/controllers/account/login.php */