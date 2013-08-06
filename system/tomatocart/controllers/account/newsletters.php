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
 * Newsletters Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Newsletters extends TOC_Controller
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
        $this->set_page_title(lang('password_forgotten_heading'));

        //breadcrumb
        $this->template->set_breadcrumb(lang('breadcrumb_my_account'), site_url('account'));
        $this->template->set_breadcrumb(lang('breadcrumb_newsletters'), site_url('account/newsletters'));
    }

    /**
     * Default Function
     *
     * @access public
     */
    public function index()
    {
        //setup view
        $this->template->build('account/account_newsletters');
    }
     
    /**
     * Save the newsletter subscriptions
     *
     * @access public
     */
    public function save()
    {
        $general = $this->input->post('newsletter_general');

        if ($this->input->post('newsletter_general') == '0' || $this->input->post('newsletter_general') == '1')
        {
            //load model
            $this->load->model('account_model');

            if ($this->account_model->update_customers_newsletter($newsletter, $this->customer->get_id()))
            {
                $this->message_stack->add_session('account', lang('success_newsletter_updated'));

                redirect(site_url('account'));
            }
            else
            {
                $this->message_stack->add('newsletters', lang('error_database'));

                //setup view
                $this->template->build('account/account_newsletters');
            }
        }
    }
}

/* End of file newsletters.php */
/* Location: ./system/tomatocart/controllers/account/newsletters.php */