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
 * Guestbooks Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-info-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Guestbooks extends TOC_Controller {
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
         
        //load model
        $this->load->model('guestbooks_model');
        
        //set page title
        $this->template->set_title(lang('guestbook_heading'));

        //breadcrumb
        $this->template->set_breadcrumb(lang('guestbook_heading'), site_url('info/guestbooks'));
    }

    /**
     * Default Function
     *
     * @access public
     * @return void
     */
    public function index()
    {
        //load helper
        $this->load->helper('date');

        //setup view data
        $data['guestbooks'] = $this->guestbooks_model->get_listing();

        //setup view
        $this->template->build('info/guestbooks', $data);
    }

    /**
     * Add the guest book
     *
     * @access public
     * @return void
     */
    public function add()
    {
        //setup view
        $this->template->build('info/guestbook_add');
    }

    /**
     * Save the guest book
     *
     * @access public
     * @return void
     */
    public function save()
    {
        //validate title
        $title = $this->input->post('title');
        if (!empty($title))
        {
            $data['title'] = $this->security->xss_clean($title);
        }
        else
        {
            $this->message_stack->add('guestbook', lang('field_guestbook_title_error'));
        }

        //validate email
        $email = $this->input->post('email');
        if (!empty($email) && validate_email_address($email))
        {
            $data['email'] = $this->security->xss_clean($email);
        }
        else
        {
            $this->message_stack->add('guestbook', lang('field_guestbook_email_error'));
        }

        //validate content
        $content = $this->input->post('content');
        if (!empty($content))
        {
            $data['content'] = $this->security->xss_clean($content);
        }
        else
        {
            $this->message_stack->add('guestbook', lang('field_guestbook_content_error'));
        }
        
        //url
        $url = $this->input->post('url');
        $data['url'] = $this->security->xss_clean($url);

        if ($this->message_stack->size('guestbook') === 0)
        {
            if ($this->guestbooks_model->save($data))
            {
                $this->message_stack->add_session('guestbook', lang('success_guestbook_saved'), 'success');

                redirect(site_url('info/guestbooks'));
            }
        }
        else
        {
            $this->template->build('info/guestbook_add');
        }
    }
}

/* End of file guestbooks.php */
/* Location: ./system/tomatocart/controllers/info/guestbooks.php */