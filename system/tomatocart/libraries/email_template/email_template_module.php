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
 * TOC_Email_Template_Module
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Email_Template_Module
{

    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    protected $ci = NULL;

    /**
     * Keywords
     *
     * @access protected
     * @var array
     */
    protected $keywords = array();

    /**
     * Template name
     *
     * @access protected
     * @var string
     */
    protected $template_name = '';

    /**
     * Status
     *
     * @access protected
     * @var
     */
    protected $status;

    /**
     * Title
     *
     * @access protected
     * @var string
     */
    protected $title;

    /**
     * Content
     *
     * @access protected
     * @var string
     */
    protected $content;

    /**
     * Email Text
     *
     * @access protected
     * @var string
     */
    protected $email_text;

    /**
     * Attachments
     *
     * @access protected
     * @var array
     */
    protected $attachments = array();

    /**
     * Recipients
     *
     * @access protected
     * @var array
     */
    protected $recipients = array();

    // class constructor
    /**
     * Constructor 
     * 
     * @access public
     * @param $template_name
     * @return void
     */
    function __construct($template_name)
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        //load email template model
        $this->ci->load->model('email_template_model');

        //get email template data
        $data = $this->ci->email_template_model->get_data($template_name);

        //initialize data
        $this->status = $data['email_templates_status'];
        $this->title = $data['email_title'];
        $this->content = $data['email_content'];
    }

    /**
     * Get keywords
     *
     * @access public
     * @return mixed
     */
    function get_keywords()
    {
        return $this->keywords;
    }

    /**
     * Add recipient
     *
     * @access public
     * @param $name
     * @param $email_address
     * @return void
     */
    function add_recipient($name, $email_address)
    {
        $this->recipients[] = array('name' => $name, 'email' => $email_address);
    }

    /**
     * Add attachment
     *
     * @access public
     * @param $file
     * @param $is_uploaded
     * @return void
     */
    function add_attachment($file, $is_uploaded = false)
    {
        $this->attachments[] = array($file, $is_uploaded);
    }

    /**
     * Reset recipients
     *
     * @access public
     * @return void
     */
    function reset_recipients()
    {
        $this->recipients = array();
    }

    /**
     * Has attachment
     *
     * @access public
     * @return boolean
     */
    function has_attachment()
    {
        if (count($this->attachments) != 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Send Email
     *
     * @access public
     * @return boolean
     */
    function send_email()
    {
        if($this->status == '1')
        {
            foreach($this->recipients as $recipient)
            {
                if (config('SEND_EMAILS') == '-1')
                {
                    return FALSE;
                }
                
                $this->ci->load->library('email');
                
                $this->ci->email->from(config('STORE_OWNER_EMAIL_ADDRESS'), config('STORE_OWNER'));
                $this->ci->email->to($recipient['email']);
                
                $this->ci->email->subject($this->title);
                $this->ci->email->message($this->content);
                
                if ($this->has_attachment()) {
                    foreach ($this->attachments as $attachment) {
                        $this->email->attach($attachment[0]);
                    }
                }
                
                return $this->ci->email->send();
            }
        }

        return FALSE;
    }
}

/* End of file email_template_module.php */
/* Location: ./system/tomatocart/libraries/email_template/email_template_module.php */