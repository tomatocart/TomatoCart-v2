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
 * Module Article Categories Controller
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */

class Mod_Article_Categories extends TOC_Module 
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    protected $code = 'article_categories';

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
        array('name' => 'MODULE_ARTICLES_CATEGORIES_MAX_LIST',
              'title' => 'Maximum List Size', 
              'type' => 'numberfield',
              'value' => '10',
              'description' => 'Maximum amount of article categories to show in the listing'));

    /**
     * Article Categories Module Constructor
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
        
        $this->title = lang('box_articles_categories_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of article categories module
     */
    public function index()
    {
        //load model
        $this->ci->load->model('info_model');

        $categories = $this->ci->info_model->get_articles_categories($this->config['MODULE_ARTICLES_CATEGORIES_MAX_LIST']);
        if ($categories != NULL)
        {
            return $this->load_view('index.php', array('categories' => $categories));
        }

        return NULL;
    }
}

/* End of file article_categories.php */
/* Location: ./system/tomatocart/modules/article_categories/article_categories.php */