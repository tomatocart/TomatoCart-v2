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
 * Module Special Products Controller
 *
 * @package        TomatoCart
 * @subpackage    tomatocart
 * @category    template-module-controller
 * @author        TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
class Mod_Special_Products extends TOC_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'special_products';

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
     * @access private
     * @var string
     */
    var $params = array(
        //MODULE_SPECIAL_MAX_DISPLAY
        array('name' => 'MODULE_SPECIAL_MAX_DISPLAY',
              'title' => 'Maximum Entries To Display', 
              'type' => 'numberfield',
              'value' => '9',
              'description' => 'Maximum number of special products to display'),
        
        //MODULE_SPECIAL_CACHE
        array('name' => 'MODULE_SPECIAL_CACHE',
              'title' => 'Cache Contents', 
              'type' => 'numberfield',
              'value' => '60',
              'description' => 'Number of minutes to keep the contents cached (0 = no cache)'));

    /**
     * Special Products Module Constructor
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
        
        $this->title = lang('box_specials_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of special products content module
     */
    public function index()
    {
        //load model
        $this->load_model('special_products');

        //load products
        $products = $this->special_products->get_specials($this->config['MODULE_SPECIAL_MAX_DISPLAY']);
        if ($products != NULL)
        {
            foreach($products as $product)
            {
                $data['products'][] = array(
                    'products_id' => $product['products_id'],
                    'products_name' => $product['products_name'],
                    'products_image' => $product['image'],
                    'products_price' => $product['products_price'],
                    'tax_class_id' => $product['products_tax_class_id'],
                    'short_description' => $product['short_description'],
                    'special_price' => $product['special_price']);
            }
            
            //load view
            return $this->load_view('index.php', $data);
        }
         
        return NULL;
    }
}

/* End of file special_products.php */
/* Location: ./system/tomatocart/modules/special_products/special_products.php */