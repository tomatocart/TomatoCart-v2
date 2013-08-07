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
 * Contact Us Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-info-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Contact_Us extends TOC_Controller 
{
    /**
     * The view data
     *
     * @var array
     * @access private
     */
    private $_data = array();
    
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        
        //set page title
        $this->template->set_title(lang('info_contact_heading'));
        
        $this->load->model('departments_model');
        
        //setup view data
        $this->_data['departments'] = array();
        $departments = $this->departments_model->get_listing();
        
        if (!empty($departments))
        {
            foreach($departments as $department)
            {
                $this->_data['departments'][$department['departments_email_address']] = $department['departments_title'];
            }
        }
    }

    /**
     * Default Function
     *
     * @access public
     * @return void
     */
    public function index()
    {
        //setup view
        $this->template->build('info/contact_us', $this->_data);
    }
    
    /**
     * Save contact us
     *
     * @access public
     * @return void
     */
    public function save()
    {
        //validate department email
        $department_email = $this->input->post('department_email');
        if (!empty($department_email))
        {
            $department_email = $this->security->xss_clean($department_email);
            
            if (!validate_email_address($department_email))
            {
                $this->message_stack->add('contact', lang('field_departments_email_error'));
            }
        }
        else
        {
            $department_email = config('STORE_OWNER_EMAIL_ADDRESS');
        }
        
        //validate customer name field
        $name = $this->input->post('name');
        if (!empty($name))
        {
            $name = $this->security->xss_clean($name);
        }
        else
        {
            $this->message_stack->add('contact', lang('field_customer_name_error'));
        }
        
        //validate customer email field
        $email = $this->input->post('email');
        if (!empty($email) && validate_email_address($email))
        {
            $email = $this->security->xss_clean($email);
        }
        else
        {
            $this->message_stack->add('contact', lang('field_customer_concat_email_error'));
        }
        
        //validate customer telephone
        $telephone = $this->input->post('telephone');
        if (!empty($telephone))
        {
            $telephone = $this->security->xss_clean($telephone);
        }
        
        //validate enquiry
        $enquiry = $this->input->post('enquiry');
        if (!empty($enquiry))
        {
            $enquiry = $this->security->xss_clean($enquiry);
        }
        else
        {
            $this->message_stack->add('contact', lang('field_enquiry_error'));
        }
        
        if ($this->message_stack->size('contact') === 0)
        {
            //ignore the send email action
            
            //setup view
            $this->template->build('info/contact_success', $this->_data);
        }
        else
        {
            //setup view
            $this->template->build('info/contact_us', $this->_data);
        }
    }
}

/* End of file contact_us.php */
/* Location: ./system/tomatocart/controllers/info/contact_us.php */