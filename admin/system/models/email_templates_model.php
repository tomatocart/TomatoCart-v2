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
 * Email Templates Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-model
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com
*/

class Email_Templates_Model extends CI_Model
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
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get the email templates
	 * 
	 * @access public
	 * @return mixed
	 */
	public function get_email_templates()
	{
		$result = $this->db
		->select('*')
		->from('email_templates e')
		->join('email_templates_description ed', 'e.email_templates_id = ed.email_templates_id', 'inner')
		->where('ed.language_id', lang_id())
		->order_by('e.email_templates_name')
		->get();
		
		return $result->result_array();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Set the status of email template
	 *
	 * @access public
	 * @param int
	 * @param int
	 * @return boolean
	 */
	public function set_status($id, $status)
	{
		return $this->db->update('email_templates', array('email_templates_status' => $status), array('email_templates_id' => $id));
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get the data of email template
	 *
	 * @access public
	 * @param int
	 * @return mixed
	 */
	public function get_email_data($id)
	{
		$result = $this->db
		->select('*')
		->from('email_templates e')
		->join('email_templates_description ed', 'e.email_templates_id = ed.email_templates_id', 'inner')
		->where('e.email_templates_id', $id)
		->get();
		
		if ($result->num_rows() > 0)
		{
			$data = array();
			foreach ($result->result_array() as $email_template)
			{
				//email template data
				if ( ! isset($data['email_templates_id']))
				{
					$data['email_templates_id'] = $email_template['email_templates_id'];
				}
				
				if ( ! isset($data['email_templates_name']))
				{
					$data['email_templates_name'] = $email_template['email_templates_name'];
				}
				
				if ( ! isset($data['email_templates_status']))
				{
					$data['email_templates_status'] = $email_template['email_templates_status'];
				}
				
				//email template description
				$data['email_title[' . $email_template['language_id'] . ']'] = $email_template['email_title'];
				$data['email_content[' . $email_template['language_id'] . ']'] = $email_template['email_content'];
			}
			
			return $data;
		}
		
		return NULL;
	}
	
	/**
	 * Get email template data
	 *
	 * @access public
	 * @return mixed
	 */
	public function get_data($template_name)
	{
		$result = $this->db->select('et.email_templates_status, etd.email_title, etd.email_content')
		->from('email_templates as et')
		->join('email_templates_description as etd', 'et.email_templates_id = etd.email_templates_id', 'inner')
		->where('et.email_templates_name', $template_name)
		->where('etd.language_id', lang_id())
		->get();
	
		if ($result->num_rows() > 0)
		{
			return $result->row_array();
		}
	
		return NULL;
	}
	
	/**
	 * Save the email template
	 *
	 * @access public
	 * @param int
	 * @param array
	 * @return mixed
	 */
	public function save($id, $data)
	{
		$error = FALSE;
		
		//start transaction
		$this->db->trans_begin();
		
		//update email template table
		$this->db->update('email_templates', array('email_templates_status' => $data['email_templates_status']), array('email_templates_id' => $id));
		
		//update error
		if ($this->db->trans_status() === FALSE)
		{
			$error = TRUE;
		}
		//update email template description
		else
		{
			foreach (lang_get_all() as $l)
			{
				$this->db->update('email_templates_description', 
					array(
						'email_title' => $data['email_title'][$l['id']], 
						'email_content' => $data['email_content'][$l['id']]
					),
					array(
						'email_templates_id' => $id,
						'language_id' => $l['id']				
					)
				);
				
				//error happened
				if ($this->db->trans_status() === FALSE)
				{
					$error = TRUE;
					break;
				}
			}
		}
		
		//commit transaction
		if ($error === FALSE)
		{
			$this->db->trans_commit();
			
			return TRUE;
		}
		
		//rollbak transaction
		$this->db->trans_rollback();
		
		return FALSE;
	}
}

/* End of file email_templates_model.php */
/* Location: ./system/modules/email_templates/models/email_templates_model.php */