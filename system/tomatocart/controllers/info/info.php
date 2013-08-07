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
 * Articles Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-info-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Info extends TOC_Controller {
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
        if ($id !== NULL)
        {
            //get the article
            $information = $this->info_model->get_article($id);

            //if information is not NULL
            if ($information != NULL)
            {
                //set page title
                $this->set_page_title($information['articles_name']);

                //breadcrumb
                $this->template->set_breadcrumb($information['articles_categories_name'], site_url('articles_categories/' . $information['articles_categories_id']));
                $this->template->set_breadcrumb($information['articles_name'], site_url('info/' . $id));

                //add the meta title
                if (!empty($information['page_title']))
                {
                    $this->template->add_meta_tags('title', $information['page_title']);
                }

                //add the meta keywords
                if (!empty($information['meta_keywords']))
                {
                    $this->template->add_meta_tags('keywords', $information['meta_keywords']);
                }

                //add the meta description
                if (!empty($information['meta_description']))
                {
                    $this->template->add_meta_tags('description', $information['meta_description']);
                }

                //setup view data
                $data['information'] = $information;

                //setup view
                $this->template->build('info/info', $data);
            }
            else
            {
                //set page title
                $this->template->set_title(lang('info_not_found_heading'));

                //setup view
                $this->template->build('info/info_not_found');
            }
        }
        else
        {
            //set page title
            $this->template->set_title(lang('info_not_found_heading'));

            //setup view
            $this->template->build('info/info_not_found');
        }
    }
}

/* End of file articles.php */
/* Location: ./system/tomatocart/controllers/info/articles.php */