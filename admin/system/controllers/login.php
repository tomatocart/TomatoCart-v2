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
 * @category	template-module-controller
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
    }

    /**
     * Default Function
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->template->set_title(lang('administration_title'));

        //add css
        $this->template->add_stylesheet_file(base_url() . 'templates/base/' . $this->agent->get_medium() . '/css/login.css"');

        //set page title & selected language
        $data = array('title' => lang('administration_title'), 'language' => $this->lang->get_code());

        //set layout
        $this->template->build('login', $data);
    }

    /**
     * Login Process Function
     *
     * @access public
     * @return void
     */
    public function process()
    {
        $user_name = $this->input->post('user_name');
        $user_password = $this->input->post('user_password');

        if (!empty($user_name) && !empty($user_password))
        {
            if ($this->admin_model->check_account($user_name, $user_password) == FALSE)
            {
                $response = array('success' => FALSE, 'error' => lang('ms_error_login_invalid'));
            }
            else
            {
                $customer = $this->admin->login($this->input->post('user_name'));

                $response = array('success' => TRUE);
            }
        }
        else
        {
            $response = array('success' => FALSE, 'error' => lang('ms_error_login_invalid'));
        }

        $this->set_output($response);
    }

    public function get_password()
    {
        $this->load->helper('email');
        $this->load->model('administrators_model');

        $error = FALSE;
        $email = $this->input->post('email_address');

        if (!valid_email($email))
        {
            $error = TRUE;
            $feedback = lang('ms_error_wrong_email_address');
        }
        else if (!$this->administrators_model->check_email($email))
        {
            $error = TRUE;
            $feedback = lang('ms_error_email_not_exist');
        }

        if ($error === FALSE)
        {
            $password = encrypt_password('admin');

            if ($this->administrators_model->update_password($email, $password))
            {
                $error = FALSE;
            }
        }

        if ($error === FALSE)
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => $feedback);
        }

        $this->set_output($response);
    }
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */