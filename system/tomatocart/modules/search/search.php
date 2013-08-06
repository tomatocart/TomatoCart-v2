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
 * Module Search Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Mod_Search extends TOC_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    protected $code = 'search';

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
     * New Products Content Module Constructor
     *
     * @access public
     * @param string
     */
    public function __construct() 
    {
        parent::__construct();
        
        $this->title = lang('box_search_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of new products content module
     */
    public function index() 
    {
        $data = array();
        
        return $this->load_view('index.php', $data);
    }
}

/* End of search.php */
/* Location: ./system/tomatocart/modules/search/search.php */