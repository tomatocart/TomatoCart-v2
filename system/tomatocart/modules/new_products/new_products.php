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
 * Module New Products Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Mod_New_Products extends TOC_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'new_products';

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
     * Template Module Version
     *
     * @access private
     * @var string
     */
    var $params = array(
        //MODULE_CONTENT_NEW_PRODUCTS_MAX_DISPLAY
        array('name' => 'MODULE_CONTENT_NEW_PRODUCTS_MAX_DISPLAY',
              'title' => 'Maximum Entries To Display', 
              'type' => 'numberfield',
              'value' => '9',
              'description' => 'Maximum number of feature products to display'),

        //MODULE_NEW_PRODUCTS_CACHE
        array('name' => 'MODULE_NEW_PRODUCTS_CACHE',
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
    public function __construct($config)
    {
        parent::__construct();

        if (!empty($config) && is_string($config))
        {
            $this->config = json_decode($config, true);
        }
        $this->title = lang('box_new_products_heading');
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
        $this->load_model('new_products');

        $data['title'] = $this->title;

        //load products
        $products = $this->new_products->get_new_products($this->config['MODULE_CONTENT_NEW_PRODUCTS_MAX_DISPLAY']);
        if ($products != NULL)
        {
            foreach($products as $product)
            {
                $data['products'][] = array(
                    'products_id' => $product['products_id'],
                    'product_name' => $product['products_name'],
                    'product_price' => $product['products_price'],
                    'specials_price' => $product['specials_price'],
                    'tax_class_id' => $product['products_tax_class_id'],
                    'is_specials' => ($product['specials_price'] === NULL) ? FALSE : TRUE,
                    'is_featured' => ($product['featured_products_id'] === NULL) ? FALSE : TRUE,
                    'product_image' => $product['image'],
                    'short_description' => $product['products_short_description']);
            }

            //load view
            return $this->load_view('index.php', $data);
        }

        return NULL;
    }
}

/* End of file new_products.php */
/* Location: ./system/tomatocart/modules/new_products_content/new_products.php */