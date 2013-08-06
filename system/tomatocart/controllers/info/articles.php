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

class Articles extends TOC_Controller {
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
    public function index($articles_id = NULL)
    {
        if ($articles_id !== NULL)
        {
            //get the article
            $article = $this->info_model->get_article($articles_id);

            if ($article != NULL)
            {
                //set page title
                $this->set_page_title($article['articles_name']);
                
                //breadcrumb
                $this->template->set_breadcrumb($article['articles_categories_name'], site_url('articles_categories/' . $article['articles_categories_id']));
                
                //breadcrumb
                $this->template->set_breadcrumb($article['articles_name'], site_url('articles/' . $articles_id));

                //add the meta title
                if (!empty($article['page_title']))
                {
                    $this->template->add_meta_tags('title', $article['page_title']);
                }

                //add the meta keywords
                if (!empty($article['meta_keywords']))
                {
                    $this->template->add_meta_tags('keywords', $article['meta_keywords']);
                }

                //add the meta description
                if (!empty($article['meta_description']))
                {
                    $this->template->add_meta_tags('description', $article['meta_description']);
                }

                //setup view data
                $data['article'] = $article;

                //setup view
                $this->template->build('info/article', $data);
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