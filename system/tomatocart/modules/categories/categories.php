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
 * Module Categories Controller
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
class Mod_Categories extends TOC_Module
{
    /**
     * Template Module Code
     *
     * @access protected
     * @var string
     */
    protected $code = 'categories';

    /**
     * Template Module Author Name
     *
     * @access protected
     * @var string
     */
    protected $author_name = 'TomatoCart';

    /**
     * Template Module Author Url
     *
     * @access protected
     * @var string
     */
    protected $author_url = 'http://www.tomatocart.com';

    /**
     * Template Module Version
     *
     * @access protected
     * @var string
     */
    protected $version = '1.0';

    /**
     * Template Module Parameter
     *
     * @access protected
     * @var string
     */
    protected $params = array(
        array('name' => 'MODULE_CATEGORIES_SHOW_PRODUCT_COUNT',
              'title' => 'Show Product Count', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => '1',
              'description' => 'Show the amount of products each category has.',
              'values' => array(
                  array('id' => '1', 'text' => 'True'),
                  array('id' => '-1', 'text' => 'False'))));

    /**
     * Categories Module Constructor
     *
     * @access public
     * @param string
     */
    public function __construct($config)
    {
        parent::__construct();

        if (!empty($config) && is_string($config))
        {
            $this->config = json_decode($config, TRUE);
        }

        $this->title = lang('box_categories_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of categories module
     */
    public function index()
    {
        $data['title'] = $this->title;

        $cpath = $this->ci->registry->get('cpath');

        $this->ci->category_tree->reset();
        $this->ci->category_tree->set_show_category_product_count(($this->config['MODULE_CATEGORIES_SHOW_PRODUCT_COUNT'] == '1') ? TRUE : FALSE);
        $this->ci->category_tree->set_category_path($cpath, '<b>', '</b>');
        $this->ci->category_tree->set_parent_group_string('', '');
        $this->ci->category_tree->set_parent_string('', '&raquo;');
        $this->ci->category_tree->set_child_string('<li>', '</li>');
        $this->ci->category_tree->set_spacer_string('&nbsp;', 4);

        $data['categories'] = $this->ci->category_tree->get_tree();

        return $this->load_view('index.php', $data);
    }
}

/* End of file categories.php */
/* Location: ./system/tomatocart/modules/categories/categories.php */