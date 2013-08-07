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
 * Module New Products Content Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Mod_Popular_Search_Terms extends TOC_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'popular_search_terms';

    /**
     * Template Module Author Name
     *
     * @access private
     * @var string
     */
    var $author_name = 'TomatoCart';

    /**
     * Template Module Author Url
     *
     * @access private
     * @var string
     */
    var $author_url = 'http://www.tomatocart.com';

    /**
     * Template Module Version
     *
     * @access private
     * @var string
     */
    var $version = '1.0';

    /**
     * Template Module Parameter
     *
     * @access protected
     * @var string
     */
    var $params = array(
        array('name' => 'MODULE_POPULAR_SEARCH_TERM_CACHE',
              'title' => 'Cache Contents', 
              'type' => 'numberfield',
         	  'value' => '60',
              'description' => 'Number of minutes to keep the contents cached (0 = no cache)'));

    /**
     * New Products Content Module Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();

        if (!empty($config) && is_string($config))
        {
            $this->config = json_decode($config, true);
        }

        $this->title = lang('box_popular_search_terms_tag_cloud_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of new products content module
     */
    public function index()
    {
        //load model
        $this->load_model('popular_search_terms');

        //get keywords
        $keywords = $this->popular_search_terms->get_popular_search_terms();

        if ($keywords != NULL)
        {
            $search_terms = array();
            foreach($keywords as $keyword)
            {
                $search_terms[] = array(
                  'tag' => $keyword['text'], 
                  'url' => site_url('search?keywords=' . $keyword['text']), 
                  'count' => $keyword['search_count']);
            }

            //load library
            $this->ci->load->library('tag_cloud', $search_terms);

            //keywords
            $data['keywords'] = $this->ci->tag_cloud->generate_tag_cloud_array();

            //load view
            return $this->load_view('index.php', $data);
        }

        return NULL;
    }
}

/* End of file popular_search_terms.php */
/* Location: ./system/tomatocart/modules/popular_search_terms/popular_search_terms.php */