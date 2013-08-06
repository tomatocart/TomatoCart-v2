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
 * Module Articles Controller
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */

class Mod_Articles extends TOC_Module 
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    protected $code = 'articles';

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
     * Template Module Params
     *
     * @access private
     * @var array
     */
    protected $params = array(
        //MODULE_SLIDESHOW_IMAGE_GROUPS
        array('name' => 'MODULE_ARTICLES_ARTICLE_CATEGORY',
              'title' => 'Article category', 
              'type' => 'combobox',
              'mode' => 'remote',
              'value' => '',
              'description' => 'The article category',
              'url' => 'articles/get_articles_categories'),
        
        //MODULE_ARTICLES_MAX_LIST
        array('name' => 'MODULE_ARTICLES_MAX_LIST',
              'title' => 'Maximum List Size', 
              'type' => 'numberfield',
              'value' => '5',
              'description' => 'Maximum amount of articles to show in the listing'));

    /**
     * Articles Module Constructor
     *
     * @access public
     */
    public function __construct($config)
    {
        parent::__construct();

        if (!empty($config) && is_string($config))
        {
            $this->config = json_decode($config, TRUE);
        }
        
        $this->title = lang('box_articles_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of articles module
     */
    public function index()
    {
        //load model
        $this->load_model('articles');
        
        $title = $this->articles->get_articles_category_name($this->config['MODULE_ARTICLES_ARTICLE_CATEGORY']);
        $articles = $this->articles->get_articles($this->config['MODULE_ARTICLES_ARTICLE_CATEGORY'], $this->config['MODULE_ARTICLES_MAX_LIST']);
        if ($articles != NULL)
        {
            return $this->load_view('index.php', array('title' => $title, 'articles' => $articles));
        }

        return NULL;
    }
}

/* End of file articles.php */
/* Location: ./system/tomatocart/modules/article_categories/articles.php */