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
 * Category Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Category
{
    /**
     * Cached data
     *
     * @access protected
     * @var object
     */
    private $ci = null;

    /**
     * category data
     *
     * @access protected
     * @var array
     */
    private $data = array();

    /**
     * Default Constructor
     *
     * @param $id
     */
    public function __construct($id)
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        if ($this->ci->category_tree->exists($id))
        {
            $this->data = $this->ci->category_tree->get_data($id);
        }
    }

    /**
     * Get category id
     *
     * @access public
     * @return int
     */
    function get_id()
    {
        return $this->data['id'];
    }

    /**
     * Get category mode
     *
     * @access public
     * @return string
     */
    function get_mode()
    {
        return $this->data['mode'];
    }

    /**
     * Get category title
     *
     * @access public
     * @return string
     */
    function get_title()
    {
        return $this->data['name'];
    }

    /**
     * Get category image
     *
     * @access public
     * @return string
     */
    function get_image()
    {
        return $this->data['image'];
    }

    /**
     * Get page title
     *
     * @access public
     * @return string
     */
    function get_page_title()
    {
        return $this->data['page_title'];
    }

    /**
     * Get meta keywords
     *
     * @access public
     * @return string
     */
    function get_meta_keywords()
    {
        return $this->data['meta_keywords'];
    }

    /**
     * Get meta description
     *
     * @access public
     * @return string
     */
    function get_meta_description()
    {
        return $this->data['meta_description'];
    }

    /**
     * Has parent category
     *
     * @access public
     * @return boolean
     */
    function has_parent()
    {
        if ($this->data['parent_id'] > 0) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Get parent category
     *
     * @access public
     * @return int
     */
    function get_parent()
    {
        return $this->data['parent_id'];
    }
}
// END Category Class

/* End of file category.php */
/* Location: ./system/tomatocart/library/category.php */