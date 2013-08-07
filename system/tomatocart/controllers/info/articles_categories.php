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
 * Articles_Categories Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-info-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Articles_Categories extends TOC_Controller {
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
    public function index($articles_categories_id = NULL)
    {
        if ($articles_categories_id !== NULL)
        {
            //get the article category
            $article_category = $this->info_model->get_article_category($articles_categories_id);

            //article category
            if ($article_category != NULL)
            {
                //set page title
                $this->set_page_title($article_category['articles_categories_name']);

                //breadcrumb
                $this->template->set_breadcrumb($article_category['articles_categories_name'], site_url('articles_categories/' . $articles_categories_id));

                //add the meta title
                if (!empty($article_category['articles_categories_page_title']))
                {
                    $this->template->add_meta_tags('title', $article_category['articles_categories_page_title']);
                }

                //add the meta keywords
                if (!empty($article_category['articles_categories_meta_keywords']))
                {
                    $this->template->add_meta_tags('keywords', $article_category['articles_categories_meta_keywords']);
                }

                //add the meta description
                if (!empty($article_category['articles_categories_meta_description']))
                {
                    $this->template->add_meta_tags('description', $article_category['articles_categories_meta_description']);
                }

                //setup view data
                $data['articles_categories_name'] = $article_category['articles_categories_name'];

                //articles
                $data['articles'] = $this->info_model->get_articles($article_category['articles_categories_id']);

                //setup view
                $this->template->build('info/articles_categories', $data);
            }
            else
            {
                //set page title
                $this->set_page_title(lang('info_not_found_heading'));

                //setup view
                $this->template->build('info/info_not_found');
            }
        }
        else
        {
            //set page title
            $this->set_page_title(lang('info_not_found_heading'));

            //setup view
            $this->template->build('info/info_not_found');
        }
    }
}

/* End of file articles_categories.php */
/* Location: ./system/tomatocart/controllers/info/articles_categories.php */