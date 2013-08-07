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
 * Category Tree Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Category_Tree
{
    /**
     * Cached data
     *
     * @access private
     * @var object
     */
    private $ci = null;

    /**
     * Root category id
     *
     * @access private
     * @var int
     */
    private $root_category_id = 0;

    /**
     * Category max level
     *
     * @access private
     * @var int
     */
    private $max_level = 0;

    /**
     * Category data
     *
     * @access private
     * @var array
     */
    private $data = array();

    /**
     * Root start string
     *
     * @access private
     * @var string
     */
    private $root_start_string = '';

    /**
     * Root end string
     *
     * @access private
     * @var string
     */
    private $root_end_string = '';

    /**
     * Parent start string
     *
     * @access private
     * @var string
     */
    private $parent_start_string;

    /**
     * Parent end string
     *
     * @access private
     * @var string
     */
    private $parent_end_string = '';

    /**
     * Parent group start string
     *
     * @access private
     * @var string
     */
    private $parent_group_start_string = '<ul>';

    /**
     * Parent group end string
     *
     * @access private
     * @var string
     */
    private $parent_group_end_string = '</ul>';

    /**
     * Child start string
     *
     * @access private
     * @var string
     */
    private $child_start_string = '<li>';

    /**
     * Child end string
     *
     * @access private
     * @var string
     */
    private $child_end_string = '</li>';

    /**
     * Breadcrumb separator
     *
     * @access private
     * @var string
     */
    private $breadcrumb_separator = '_';

    /**
     * Breadcrumb usage
     *
     * @access private
     * @var boolean
     */
    private $breadcrumb_usage = TRUE;

    /**
     * Spacer string
     *
     * @access private
     * @var string
     */
    private $spacer_string = '';

    /**
     * Spacer multiplier
     *
     * @access private
     * @var string
     */
    private $spacer_multiplier = 1;

    /**
     * Follow cpath
     *
     * @access private
     * @var boolean
     */
    private $follow_cpath = FALSE;

    /**
     * Cpath array
     *
     * @access private
     * @var array
     */
    private $cpath_array = array();

    /**
     * Cpath start string
     *
     * @access private
     * @var string
     */
    private $cpath_start_string = '';

    /**
     * Cpath end string
     *
     * @access private
     * @var string
     */
    private $cpath_end_string = '';

    /**
     * show category product count
     *
     * @access private
     * @var boolean
     */
    private $show_category_product_count = FALSE;

    /**
     * category product count start string
     *
     * @access private
     * @var string
     */
    private $category_product_count_start_string = '&nbsp;(';

    /**
     * category product count end string
     *
     * @access private
     * @var string
     */
    private $category_product_count_end_string = ')';

    /**
     * Constructor
     *
     * @param $load_from_database
     * @param $load_all_categories
     * @param $load_from_cache
     */
    public function __construct($load_from_database = TRUE, $load_all_categories = FALSE, $load_from_cache = TRUE)
    {
        //initialize the ci instance
        $this->ci = get_instance();

        if (config('SERVICES_CATEGORY_PATH_CALCULATE_PRODUCT_COUNT') == '1')
        {
            $this->show_category_product_count = TRUE;
        }

        //if load from database
        if ($load_from_database === TRUE)
        {
            $is_cache_loaded = FALSE;

            //if load from cache
            if ($load_from_cache === TRUE)
            {
                $this->data = FALSE; //$this->ci->cache->get('category_tree-' . $this->ci->lang->get_code());
                if ($this->data != FALSE) {
                    $is_cache_loaded = TRUE;
                }
            }

            //if cache is not found
            if ($is_cache_loaded === FALSE)
            {
                $this->ci->load->model('categories_model');
                $rows = $this->ci->categories_model->get_all($load_all_categories);

                $this->data = array();
                foreach ($rows as $row)
                {
                    $this->data[$row['parent_id']][$row['categories_id']] = array(
                        'name' => $row['categories_name'], 
                        'url' => $row['categories_url'], 
                        'page_title' => $row['categories_page_title'], 
                        'meta_keywords' => $row['categories_meta_keywords'], 
                        'meta_description' => $row['categories_meta_description'], 
                        'image' => $row['categories_image'], 
                        'count' => 0);
                }

                if ($this->show_category_product_count === TRUE)
                {
                    $this->calculate_category_product_count();
                }

                if ($load_from_cache === TRUE)
                {
                    $this->ci->cache->save('category_tree-' . $this->ci->lang->get_code(), $this->data, CACHE_MAX_TIME);
                }
            }
        }
    }

    public function set_data(&$data_array) {
        if (is_array($data_array)) {
            $this->data = array();

            for ($i=0, $n=sizeof($data_array); $i<$n; $i++) {
                $this->data[$data_array[$i]['parent_id']][$data_array[$i]['categories_id']] = array(
          'name' => $data_array[$i]['categories_name'], 
          'count' => $data_array[$i]['categories_count']);
            }
        }
    }

    /**
     * Reset category tree
     *
     * @access public
     * @return void
     */
    public function reset()
    {
        $this->root_category_id = 0;
        $this->max_level = 0;
        $this->root_start_string = '';
        $this->root_end_string = '';
        $this->parent_start_string = '';
        $this->parent_end_string = '';
        $this->parent_group_start_string = '<ul>';
        $this->parent_group_end_string = '</ul>';
        $this->child_start_string = '<li>';
        $this->child_end_string = '</li>';
        $this->breadcrumb_separator = '_';
        $this->breadcrumb_usage = TRUE;
        $this->spacer_string = '';
        $this->spacer_multiplier = 1;
        $this->follow_cpath = FALSE;
        $this->cpath_array = array();
        $this->cpath_start_string = '';
        $this->cpath_end_string = '';
        $this->show_category_product_count = (config('SERVICES_CATEGORY_PATH_CALCULATE_PRODUCT_COUNT') == '1') ? TRUE : FALSE;
        $this->category_product_count_start_string = '&nbsp;(';
        $this->category_product_count_end_string = ')';
    }

    /**
     * Build branch
     *
     * @deprecated
     * @param $parent_id
     * @param $level
     * @return array
     */
    public function build_branch($parent_id, $level = 0)
    {
        $result = $this->parent_group_start_string;

        if (isset($this->data[$parent_id]))
        {
            foreach ($this->data[$parent_id] as $category_id => $category) {
                if ($this->breadcrumb_usage == TRUE) {
                    $category_link = $this->build_breadcrumb($category_id);
                } else {
                    $category_link = $category_id;
                }

                $result .= $this->child_start_string;

                if (isset($this->data[$category_id])) {
                    $result .= $this->parent_start_string;
                }

                if ($level == 0) {
                    $result .= $this->root_start_string;
                }
                 
                if ( ($this->follow_cpath === TRUE) && in_array($category_id, $this->cpath_array) ) {
                    $link_title = $this->cpath_start_string . $category['name'] . $this->cpath_end_string;
                } else {
                    $link_title = $category['name'];
                }

                $result .= str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . anchor(site_url('cpath/' . $category_link), $link_title);

                if ($this->show_category_product_count === TRUE) {
                    $result .= $this->category_product_count_start_string . $category['count'] . $this->category_product_count_end_string;
                }

                if ($level == 0) {
                    $result .= $this->root_end_string;
                }

                if (isset($this->data[$category_id])) {
                    $result .= $this->parent_end_string;
                }

                $result .= $this->child_end_string;

                if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
                    if ($this->follow_cpath === TRUE) {
                        if (in_array($category_id, $this->cpath_array)) {
                            $result .= $this->build_branch($category_id, $level+1);
                        }
                    } else {
                        $result .= $this->build_branch($category_id, $level+1);
                    }
                }
            }
        }

        $result .= $this->parent_group_end_string;

        return $result;
    }

    /**
     * Build branch as array
     *
     * @param $parent_id
     * @param $level
     * @param $result
     * @return void
     */
    function build_branch_array($parent_id, $level = 0, $result = NULL)
    {
        if (empty($result))
        {
            $result = array();
        }

        if (isset($this->data[$parent_id]))
        {
            foreach ($this->data[$parent_id] as $category_id => $category)
            {
                if ($this->breadcrumb_usage == TRUE)
                {
                    $category_link = $this->build_breadcrumb($category_id);
                }
                else
                {
                    $category_link = $category_id;
                }

                //current category
                if ( ($this->follow_cpath === TRUE) && in_array($category_id, $this->cpath_array) )
                {
                    $link_title = $this->cpath_start_string . $category['name'] . $this->cpath_end_string;
                }
                else
                {
                    $link_title = $category['name'];
                }

                $link_title = str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . $link_title;

                $result[] = array('id' => $category_link,
          						  'image' => $category['image'],
        						  'name' => $category['name'],
        						  'url' => $category['url'],
        						  'page_title' => $category['page_title'],
        						  'meta_keywords' => $category['meta_keywords'],
        						  'meta_description' => $category['meta_description'],
        						  'title' => $link_title);

                if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1)))
                {
                    if ($this->follow_cpath === TRUE)
                    {
                        if (in_array($category_id, $this->cpath_array))
                        {
                            $result = $this->build_branch_array($category_id, $level+1, $result);
                        }
                    }
                    else
                    {
                        $result = $this->build_branch_array($category_id, $level+1, $result);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Build category breadcrumb
     *
     * @param $category_id
     * @param $level
     * @return string
     */
    function build_breadcrumb($category_id, $level = 0) {
        $breadcrumb = '';

        foreach ($this->data as $parent => $categories) {
            foreach ($categories as $id => $info) {
                if ($id == $category_id) {
                    if ($level < 1) {
                        $breadcrumb = $id;
                    } else {
                        $breadcrumb = $id . $this->breadcrumb_separator . $breadcrumb;
                    }

                    if ($parent != $this->root_category_id) {
                        $breadcrumb = $this->build_breadcrumb($parent, $level+1) . $breadcrumb;
                    }
                }
            }
        }

        return $breadcrumb;
    }

    /**
     * Build category tree
     *
     * @deprecated
     * @access public
     * @return string
     */
    public function build_tree()
    {
        return $this->build_branch($this->root_category_id);
    }

    /**
     * Get category tree
     *
     * @access public
     * @param $parent_id
     * @return array
     */
    public function get_tree($parent_id = '')
    {
        return $this->build_branch_array((empty($parent_id) ? $this->root_category_id : $parent_id));
    }

    /**
     * Check whether category id exists
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function exists($id)
    {
        foreach ($this->data as $parent => $categories)
        {
            foreach ($categories as $category_id => $info)
            {
                if ($id == $category_id)
                {
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    /**
     * Get children
     *
     * @access public
     * @param $category_id
     * @param $array
     * @return array
     */
    public function get_children($category_id, &$array)
    {
        foreach ($this->data as $parent => $categories)
        {
            if ($parent == $category_id)
            {
                foreach ($categories as $id => $info)
                {
                    $array[] = array('id' => $this->get_full_cpath($id), 'info' => $info);
                    $this->get_children($id, $array);
                }
            }
        }

        return $array;
    }

    /**
     * Get category data
     *
     * @access public
     * @param $id
     * @return array
     */
    public function get_data($id = NULL)
    {
        if ($id == NULL)
        {
            return $this->data;
        }
        else
        {
            foreach ($this->data as $parent => $categories)
            {
                foreach ($categories as $category_id => $info)
                {
                    if ($id == $category_id)
                    {
                        return array('id' => $id,
                                     'name' => $info['name'],
                                     'page_title' => $info['page_title'],
                                     'meta_keywords' => $info['meta_keywords'],
                                     'meta_description' => $info['meta_description'],
                                     'parent_id' => $parent,
                                     'image' => $info['image'],
                                     'count' => $info['count']
                        );
                    }
                }
            }
        }

        return FALSE;
    }

    /**
     * Calculate category product count
     *
     * @access public
     * @return void
     */
    public function calculate_category_product_count()
    {
        $totals = $this->ci->categories_model->get_categories_products_count();

        foreach ($this->data as $parent => $categories)
        {
            foreach ($categories as $id => $info)
            {
                if (isset($totals[$id]) && ($totals[$id] > 0))
                {
                    $this->data[$parent][$id]['count'] = $totals[$id];

                    $parent_category = $parent;
                    while ($parent_category != $this->root_category_id)
                    {
                        foreach ($this->data as $parent_parent => $parent_categories)
                        {
                            foreach ($parent_categories as $parent_category_id => $parent_category_info)
                            {
                                if ($parent_category_id == $parent_category)
                                {
                                    $this->data[$parent_parent][$parent_category_id]['count'] += $this->data[$parent][$id]['count'];

                                    $parent_category = $parent_parent;
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }
        }

        unset($totals);
    }

    /**
     * Get number of products
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_number_of_products($id)
    {
        foreach ($this->data as $parent => $categories)
        {
            foreach ($categories as $category_id => $info)
            {
                if ($id == $category_id)
                {
                    return $info['count'];
                }
            }
        }

        return FALSE;
    }

    /**
     * set root category id
     *
     * @access public
     * @param $root_category_id
     * @return void
     */
    function set_root_category_id($root_category_id)
    {
        $this->root_category_id = $root_category_id;
    }

    /**
     * set maximum level
     *
     * @access public
     * @param $max_level
     * @return void
     */
    function set_maximum_level($max_level)
    {
        $this->max_level = $max_level;
    }

    /**
     * set root string
     *
     * @access public
     * @param $root_start_string
     * @param $root_end_string
     * @return void
     */
    function set_root_string($root_start_string, $root_end_string)
    {
        $this->root_start_string = $root_start_string;
        $this->root_end_string = $root_end_string;
    }

    /**
     * set parent string
     *
     * @access public
     * @param $parent_start_string
     * @param $parent_end_string
     * @return void
     */
    function set_parent_string($parent_start_string, $parent_end_string)
    {
        $this->parent_start_string = $parent_start_string;
        $this->parent_end_string = $parent_end_string;
    }

    /**
     * set group string
     *
     * @access public
     * @param $parent_group_start_string
     * @param $parent_group_end_string
     * @return void
     */
    function set_parent_group_string($parent_group_start_string, $parent_group_end_string)
    {
        $this->parent_group_start_string = $parent_group_start_string;
        $this->parent_group_end_string = $parent_group_end_string;
    }

    /**
     * set child string
     *
     * @access public
     * @param $child_start_string
     * @param $child_end_string
     * @return void
     */
    function set_child_string($child_start_string, $child_end_string)
    {
        $this->child_start_string = $child_start_string;
        $this->child_end_string = $child_end_string;
    }

    /**
     * set breadcrumb separator
     *
     * @access public
     * @param $breadcrumb_separator
     * @return void
     */
    function set_breadcrumb_separator($breadcrumb_separator)
    {
        $this->breadcrumb_separator = $breadcrumb_separator;
    }

    /**
     * set breadcrumb usage
     *
     * @access public
     * @param $breadcrumb_usage
     * @return void
     */
    function set_breadcrumb_usage($breadcrumb_usage)
    {
        if ($breadcrumb_usage === TRUE) {
            $this->breadcrumb_usage = TRUE;
        } else {
            $this->breadcrumb_usage = FALSE;
        }
    }

    /**
     * set spacer string
     *
     * @access public
     * @param $spacer_string
     * @param $spacer_multiplier
     * @return void
     */
    function set_spacer_string($spacer_string, $spacer_multiplier = 2)
    {
        $this->spacer_string = $spacer_string;
        $this->spacer_multiplier = $spacer_multiplier;
    }

    /**
     * set category path
     *
     * @access public
     * @param $cpath
     * @param $cpath_start_string
     * @param $cpath_end_string
     * @return void
     */
    function set_category_path($cpath, $cpath_start_string = '', $cpath_end_string = '')
    {
        $this->follow_cpath = TRUE;
        $this->cpath_array = explode($this->breadcrumb_separator, $cpath);
        $this->cpath_start_string = $cpath_start_string;
        $this->cpath_end_string = $cpath_end_string;
    }

    /**
     * set follow category path
     *
     * @access public
     * @param $follow_cpath
     * @return void
     */
    function set_follow_category_path($follow_cpath)
    {
        if ($follow_cpath === TRUE)
        {
            $this->follow_cpath = TRUE;
        }
        else
        {
            $this->follow_cpath = FALSE;
        }
    }

    /**
     * set category path string
     *
     * @access public
     * @param $cpath_start_string
     * @param $cpath_end_string
     * @return void
     */
    function set_category_path_string($cpath_start_string, $cpath_end_string)
    {
        $this->cpath_start_string = $cpath_start_string;
        $this->cpath_end_string = $cpath_end_string;
    }

    /**
     * set show category product count
     *
     * @access public
     * @param $show_category_product_count
     * @return void
     */
    function set_show_category_product_count($show_category_product_count)
    {
        if ($show_category_product_count === TRUE)
        {
            $this->show_category_product_count = TRUE;
        }
        else
        {
            $this->show_category_product_count = FALSE;
        }
    }

    /**
     * set category product count string
     *
     * @access public
     * @param $category_product_count_start_string
     * @param $category_product_count_end_string
     * @return void
     */
    function set_category_product_count_string($category_product_count_start_string, $category_product_count_end_string)
    {
        $this->category_product_count_start_string = $category_product_count_start_string;
        $this->category_product_count_end_string = $category_product_count_end_string;
    }

    /**
     * get category product count string
     *
     * @access public
     * @param $category_id
     * @param $categories
     * @return array
     */
    function get_parent_categories($category_id, &$categories)
    {
        foreach ($this->data as $parent => $sub_categories)
        {
            foreach ($sub_categories as $id => $info)
            {
                if ( ($id == $category_id) && ($parent != $this->root_category_id) )
                {
                    $categories[] = $parent;
                    $this->get_parent_categories($parent, $categories);
                }
            }
        }
    }

    /**
     * get full cpath
     *
     * @access public
     * @param $category_id
     * @return array
     */
    function get_full_cpath($categories_id)
    {
        if ( preg_match('/_/', $categories_id) )
        {
            return $categories_id;
        }
        else
        {
            $categories = array();
            $this->get_parent_categories($categories_id, $categories);

            $categories = array_reverse($categories);
            $categories[] = $categories_id;
            $cpath = implode('_', $categories);

            return $cpath;
        }
    }

    /**
     * get full cpath info
     *
     * @access public
     * @param $cpath
     * @return array
     */
    function get_full_cpath_info($cpath)
    {
        $string = $this->get_full_cpath($cpath);
        $categories = explode('_', $string);

        $data = array();
        foreach ($categories as $category)
        {
            $data[$category] = $this->get_category_name($category);
        }

        return $data;
    }

    /**
     * get category url
     *
     * @access public
     * @param $cpath
     * @return array
     */
    function get_category_url($cpath)
    {
        $cpath = $this->get_full_cpath($cpath);
        $categories = @explode('_', $cpath);

        if(sizeof($categories) > 1)
        {
            $category_id = end($categories);
            $parent_id = $categories[sizeof($categories)-2];
        }
        else
        {
            $category_id = $cpath;
            $parent_id = $this->root_category_id;
        }

        $category_url = $this->data[$parent_id][$category_id]['url'];

        return $category_url;
    }

    /**
     * Build search enginee friendly url
     *
     * @access public
     * @param $cpath
     * @return string
     */
    function get_sef_url($cpath)
    {
        $categories = @explode('_', $cpath);

        if(sizeof($categories) >= 1)
        {
            $data = array();

            foreach ($categories as $category)
            {
                $data[] = $this->get_category_url($category);
            }

            return implode('/', $data);
        }

        return NULL;
    }

    /**
     * Get category id from friendly url
     *
     * @param $category_id
     * @param $parent
     * @return mixed
     */
    public function get_cid_from_sef_url($url, $parent = 0)
    {
        if (isset($this->data[$parent]))
        {
            foreach ($this->data[$parent] as $id => $info)
            {
                //if url found in currency category id
                if ($info['url'] == $url)
                {
                    return $id;
                }
                //else try to find it in sub category
                else
                {
                    $cid = $this->get_cid_from_sef_url($url, $id);

                    //found
                    if (is_numeric($cid))
                    {
                        return $cid;
                    }
                }
            }
        }

        return NULL;
    }

    /**
     * Compile search enginee friendly url
     *
     * @access public
     * @param $cpath
     * @return string
     */
    public function parse_cpath($cpath)
    {
        //if it is already cpath style and return the original path
        if ( preg_match("/([0-9_]+)/", $cpath, $matches) > 0 )
        {
            return $cpath;
        }
        //parse friendly url
        else
        {
            $paths = explode('/', $cpath);

            if (sizeof($paths) == 1)
            {
                return $this->get_cid_from_sef_url($paths[0]);
            }
            else
            {
                $cpath = array();
                foreach ($paths as $path)
                {
                    $cpath[] = $this->get_cid_from_sef_url($path);
                }

                return implode('_', $cpath);
            }
        }

        return NULL;
    }

    /**
     * get category name
     *
     * @access public
     * @param $cpath
     * @return string
     */
    function get_category_name($cpath)
    {
        $cpath = $this->get_full_cpath($cpath);
        $categories = @explode('_', $cpath);

        if(sizeof($categories) > 1)
        {
            $category_id = end($categories);
            $parent_id = $categories[sizeof($categories)-2];
        }
        else
        {
            $category_id = $cpath;
            $parent_id = $this->root_category_id;
        }

        $category_name = $this->data[$parent_id][$category_id]['name'];
        return $category_name;
    }
}
// END Category Tree Class

/* End of file category_tree.php */
/* Location: ./system/tomatocart/libraries/category_tree.php */