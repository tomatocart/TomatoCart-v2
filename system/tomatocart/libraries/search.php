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
 * Search Class
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-search-library
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class TOC_Search
{
    /**
     * Cached data
     *
     * @access private
     * @var array
     */
    protected $price_from = NULL;

    /**
     * Cached data
     *
     * @access private
     * @var array
     */
    protected $price_to = NULL;
    
    /**
     * Cached data
     *
     * @access private
     * @var array
     */
    protected $keywords = NULL;
    
    /**
     * Cached data
     *
     * @access private
     * @var array
     */
    protected $category = NULL;
    
    /**
     * Cached data
     *
     * @access private
     * @var array
     */
    protected $manufacturer = NULL;
    
    /**
     * Cached data
     *
     * @access private
     * @var array
     */
    protected $recursive = TRUE;

    /**
     * Constructor
     */
    public function __construct()
    {
        //initialize the ci instance
        $this->ci = get_instance();
    }

    /**
     * Get the property
     *
     * @access public
     * @return int
     */
    public function __get($key)
    {
        return $this->$key;
    }

    /**
     * Set the property
     *
     * @access public
     * @param string the property name
     * @param mixed the value of the property
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * Set the search keywords
     *
     * @access public
     * @param string the keywords
     */
    public function set_keywords($keywords)
    {
        $terms = explode(' ', trim($keywords));
        $keywords = array();
        $counter = 0;

        foreach($terms as $word)
        {
            $counter++;

            if ($counter > 5)
            {
                break;
            }
            elseif (!empty($word) && !in_array($word, $keywords))
            {
                $keywords[] = $word;
            }
        }

        $this->keywords = implode(' ', $keywords);
    }

    /**
     * Get the search results
     *
     * @access public
     * @return array
     */
    public function get_search_results()
    {
        //load model
        $this->ci->load->model('search_model');

        //orgnize params
        $params = array('price_from' => $this->price_from,
                        'price_to' => $this->price_to, 
                        'keywords' => $this->keywords, 
                        'category' => $this->category, 
                        'manufacturer' => $this->manufacturer,
                        'recursive' => $this->recursive, 
                        'with_tax' => config('DISPLAY_PRICE_WITH_TAX'),
                        'currency' => $this->ci->currencies->value($this->ci->currencies->get_code()), 
                        'max_results' => config('MAX_DISPLAY_SEARCH_RESULTS'),
                        'language_id' => lang_id());

        if (!empty($this->category) && ($this->recursive === TRUE))
        {
            $subcategories = array();

            //load category tree library
            $this->ci->load->library('category_tree');

            //add the subcategories
            $this->ci->category_tree->get_children($this->category, $subcategories);

            if (!empty($subcategories))
            {
                $params['subcategories'] = array($this->category);

                foreach($subcategories as $subcategory)
                {
                    if (strpos($subcategory['id'], '_') !== FALSE)
                    {
                        $cPath_array = array_unique(array_filter(explode('_', $subcategory['id']), 'is_numeric'));
                        $category_id = end($cPath_array);

                        $params['subcategories'][] = $category_id;
                    }
                    else
                    {
                        $params['subcategories'][] = $subcategory['id'];
                    }
                }
            }
        }

        $search_result = $this->ci->search_model->get_result($params);

        return $search_result;
    }


}
