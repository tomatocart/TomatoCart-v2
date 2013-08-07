<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package      TomatoCart
 * @author       TomatoCart Dev Team
 * @copyright    Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html
 * @link         http://tomatocart.com
 * @since        Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Module Information Controller
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
class Mod_Information extends TOC_Module 
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    protected $code = 'information';

    /**
     * Template Module Author Name
     *
     * @access private
     * @var string
     */
    protected $author_name = 'TomatoCart';

    /**
     * Template Module Author Url
     *
     * @access private
     * @var string
     */
    protected $author_url = 'http://www.tomatocart.com';

    /**
     * Template Module Version
     *
     * @access private
     * @var string
     */
    protected $version = '1.0';
    
    /**
     * Categories Information Constructor
     *
     * @access public
     * @param string
     */
    public function __construct($config)
    {
        parent::__construct();
        
        $this->title = lang('box_information_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of categories module
     */
    public function index()
    {
        //load model
        $this->ci->load->model('info_model');

        //articles
        $articles = $this->ci->info_model->get_articles(1);

        $information = array();
        if ($articles != NULL)
        {
            foreach($articles as $article) 
            {
                $information[] = array(
                	'link' => site_url('info/' . $article['articles_id']),
                    'title' => $article['articles_name']);
            }
        }
        
        //contact us
        $information[] = array(
                	'link' => site_url('contact_us'),
                    'title' => lang('box_information_contact'));
        
        //sitemap
        $information[] = array(
                	'link' => site_url('sitemap'),
                    'title' => lang('box_information_sitemap'));
        
        return $this->load_view('index.php', array('information' => $information));
    }
}

/* End of file information.php */
/* Location: ./system/tomatocart/modules/information/information.php */