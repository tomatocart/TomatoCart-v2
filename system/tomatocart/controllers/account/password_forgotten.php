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
 * Password_Forgotten Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Password_Forgotten extends TOC_Controller 
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
        $this->set_page_title(lang('password_forgotten_heading'));

        //breadcrumb
        $this->template->set_breadcrumb(lang('breadcrumb_my_account'), site_url('account'));
        $this->template->set_breadcrumb(lang('breadcrumb_password_forgotten'), site_url('account/password_forgotten'));
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
        $this->template->build('account/password_forgotten');
    }

    /**
     * Process password forgotten
     * 
     * @access public
     * @return void
     */
    public function process()
    {
        $email_address = $this->input->post('email_address');
        if (validate_email_address($email_address))
        {
            //load model
            $this->load->model('account_model');
             
            $data = $this->account_model->get_data($email_address);
            if ($data !== NULL) {
                $password = create_random_string(config('ACCOUNT_PASSWORD'));

                if ($this->account_model->save_password($data['customers_id'], $password)) {
                    $this->load->library('email_template');

                    $email = $this->email_template->get_email_template('password_forgotten');
                    $email->set_data($data['customers_firstname'], $data['customers_lastname'], getenv('REMOTE_ADDR'), $password, $data['customers_gender'], $data['customers_email_address']);
                    $email->build_message();
                    $email->send_email();

                    $this->message_stack->add_session('login', lang('success_password_forgotten_sent'), 'success');
                    
                    redirect('account/login');
                }
            } else {
                $this->message_stack->add('password_forgotten', lang('error_password_forgotten_no_email_address_found'));
            }
        } else
        {
            $this->message_stack->add('password_forgotten', lang('error_password_forgotten_no_email_address_found'));
        }
         
        $this->template->build('account/password_forgotten');
    }
}

/* End of file password_forgotten.php */
/* Location: ./system/tomatocart/controllers/account/password_forgotten.php */