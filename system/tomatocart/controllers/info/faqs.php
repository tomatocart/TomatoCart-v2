<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Faqs Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-info-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Faqs extends TOC_Controller
{
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        
        //load model
        $this->load->model('info_model');
    }

    /**
     * Default Function
     *
     * @access public
     */
    public function index($id = NULL)
    {
        //set page title
        $this->set_page_title(lang('info_faqs_heading'));

        //breadcrumb
        $this->template->set_breadcrumb(lang('info_faqs_heading'), site_url('info/faqs'));

        //setup view data
        $data['faqs'] = $this->info_model->get_faqs();

        //Which faq should be actived
        if (is_numeric($id))
        {
            $data['active'] = $id;
        }

        //setup view
        $this->template->build('info/faqs', $data);
    }
}

/* End of file faqs.php */
/* Location: ./system/tomatocart/controllers/info/faqs.php */