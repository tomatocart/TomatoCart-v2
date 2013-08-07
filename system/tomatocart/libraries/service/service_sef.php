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

require_once 'service_module.php';

/**
 * Free Shipping -- Shipping Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Service_Sef extends TOC_Service_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'sef';

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
    array('name' => 'SERVICES_KEYWORD_RICH_URLS',
              'title' => 'Create keyword-rich URLs', 
              'type' => 'combobox',
              'mode' => 'local',
		  	  'value' => '1',
              'description' => 'Create keyword-rich URLs for categories, products, articles and faqs.',
              'values' => array(
    array('id' => '1', 'text' => 'True'),
    array('id' => '-1', 'text' => 'False'))));
     
    /**
     * Constructor
     *
     * @access public
     */
    function __construct()
    {
        parent::__construct();

        $this->title = lang('services_sef_title');
        $this->description = lang('services_sef_description');
    }

    /**
     * Generate user friendly URL
     *
     * @access public
     * @param $friendly_url
     * @return string
     */
    function site_url($uri = '')
    {
        //get ci instance
        $ci = get_instance();

        //load models
        $ci->load->model('info_model');

        //catalog
        if ( preg_match("/cpath\/([0-9_]+)/", $uri, $matches) > 0 )
        {
            $friendly_url = $ci->category_tree->get_sef_url($matches[1]);

            if (!empty($friendly_url))
            {
                return $ci->config->slash_item('base_url').$ci->config->item('index_page').'/'.'cpath/'.$friendly_url;
            }
        }
        //product
        else if ( preg_match("/product\/([0-9]+)/", $uri, $matches) > 0 )
        {
            $friendly_url = $ci->products_model->get_product_friendly_url($matches[1]);

            if (!empty($friendly_url))
            {
                return $ci->config->slash_item('base_url').$ci->config->item('index_page').'/product/'.$friendly_url;
            }
        }
        //information
        else if ( preg_match("/info\/([0-9]+)/", $uri, $matches) > 0 )
        {
            $friendly_url = $ci->info_model->get_article_friendly_url($matches[1]);

            if (!empty($friendly_url))
            {
                return $ci->config->slash_item('base_url').$ci->config->item('index_page').'/info/'.$friendly_url;
            }
        }
        //article
        else if ( preg_match("/articles\/([0-9]+)/", $uri, $matches) > 0 )
        {
            $friendly_url = $ci->info_model->get_article_friendly_url($matches[1]);

            if (!empty($friendly_url))
            {
                return $ci->config->slash_item('base_url').$ci->config->item('index_page').'/articles/'.$friendly_url;
            }
        }
        //article categories
        else if ( preg_match("/articles_categories\/([0-9]+)/", $uri, $matches) > 0 )
        {
            $friendly_url = $ci->info_model->get_articles_categories_friendly_url($matches[1]);

            if (!empty($friendly_url))
            {
                return $ci->config->slash_item('base_url').$ci->config->item('index_page').'/articles_categories/'.$friendly_url;
            }
        }
        
        //if no regex is matched then we use the normal site url
        $site_url = $this->ci->config->ci_site_url($uri);
        
        return str_replace('&', '&amp;', $site_url); 
    }
}