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
 * Index Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-index-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Index extends TOC_Controller
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
     * @param string
     */
    public function index()
    {
        //page title
        $this->set_page_title(sprintf(lang('index_heading'), config('STORE_NAME')));

        //code
        $code = strtoupper($this->lang->get_code());

        //get the home page meta information
        if ( (config('HOME_META_KEYWORD_' . $code) !== FALSE) && (config('HOME_META_DESCRIPTION_' . $code) !== FALSE) && (config('HOME_PAGE_TITLE_' . $code) !== FALSE) )
        {
            $page_title = config('HOME_PAGE_TITLE_' . $code);
            $meta_keywords = config('HOME_META_KEYWORD_' . $code);
            $meta_description = config('HOME_META_DESCRIPTION_' . $code);
        }

        //set page title
        if (!empty($page_title))
        {
            $this->set_page_title($page_title);
        }

        //set keywords
        if (!empty($meta_keywords))
        {
            $this->template->add_meta_tags('keywords', $meta_keywords);
        }

        //set description
        if (!empty($meta_description))
        {
            $this->template->add_meta_tags('description', $meta_description);
        }

        //setup view data
        $data['is_logged_on'] = $this->customer->is_logged_on();
        $data['customer_firstname'] = $this->customer->get_firstname();

        //setup view
        $this->template->build('index/index', $data);
    }
}

/* End of file index.php */
/* Location: ./system/tomatocart/controllers/index/index.php */