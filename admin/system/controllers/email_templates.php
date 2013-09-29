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
 * Email Templates Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com
 */
class Email_Templates extends TOC_Controller 
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
		
		$this->load->model('email_templates_model');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * List email tempaltes
	 * 
	 * @access public
	 * @return string
	 */
	public function list_email_templates()
	{
		//get the email tempaltes via the model
		$email_templates = $this->email_templates_model->get_email_templates();
		
		$response = array(EXT_JSON_READER_TOTAL => count($email_templates), EXT_JSON_READER_ROOT => $email_templates);
		
		$this->output->set_output(json_encode($response));
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Set the status of email template
	 *
	 * @access public
	 * @return string
	 */
	public function set_status()
	{
		if ($this->email_templates_model->set_status($this->input->post('email_templates_id'), $this->input->post('flag')))
		{
			$response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed') );
		}
		else
		{
			$response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
		}
		
		$this->output->set_output(json_encode($response));
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Load the email template
	 *
	 * @access public
	 * @return string
	 */
	public function load_email_template()
	{
		$email_template_data = $this->email_templates_model->get_email_data($this->input->post('email_templates_id'));
		
		$response = array('success' => TRUE, 'data' => $email_template_data);
		
		$this->output->set_output(json_encode($response));
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Load the variables of email template
	 *
	 * @access public
	 * @return string
	 */
	public function get_variables()
	{
		//load email template instance from the library of the store front
		$email_template = 'email_template_' . $this->input->get('email_templates_name');
		$this->load->library('email_template/' . $email_template);
		
		//get the keywords
		$keywords = $this->$email_template->get_keywords();
		
		$records = array();
		foreach ($keywords as $key => $value)
		{
			$records[] = array('id' => $key, 'value' => $value);
		}
		
		$response = array(EXT_JSON_READER_ROOT => $records);
		
		$this->output->set_output(json_encode($response));
	}
}

/* End of file email_templates.php */
/* Location: ./system/controllers/email_templates.php */