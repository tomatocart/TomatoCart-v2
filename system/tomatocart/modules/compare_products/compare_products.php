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
 * Module Compare Products Controller
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
class Mod_Compare_Products extends TOC_Module
{
    /**
     * Template Module Code
     *
     * @access protected
     * @var string
     */
    protected $code = 'compare_products';

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

        $this->title = lang('box_compare_products_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of categories module
     */
    public function index()
    {
        $ids = $this->ci->compare_products->get_products();

        if (is_array($ids) && !empty($ids))
        {
            $products = array();
            foreach ($ids as $products_id)
            {
                $product = load_product_library($products_id);

                $products[] = array(
                    'products_id' => $products_id,
                    'products_name' => $product->get_title());
            }
            
            return $this->load_view('index.php', array('products' => $products));
        }
    }
}

/* End of file categories.php */
/* Location: ./system/tomatocart/modules/compare_products/compare_products.php */