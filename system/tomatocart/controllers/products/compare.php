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
 * Compare Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-products-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Compare extends TOC_Controller
{

    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Default action
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $result = $this->compare_products->output_compare_products_table();

        $this->output->set_output($result);
    }

    /**
     * Add product
     *
     * @access public
     * @param $products_id
     * @return void
     */
    public function add($products_id)
    {
        if (is_numeric($products_id))
        {
            $this->compare_products->add_product($products_id);
        }

        //if nagivation history has path, then redirect to path else redirect to home
        if ($this->navigation_history->has_path())
        {
            $this->navigation_history->redirect_to_path();
        }

        redirect();
    }

    /**
     * Delete product
     *
     * @access public
     * @param $products_id
     * @return void
     */
    public function delete($products_id)
    {
        if (is_numeric($products_id))
        {
            $this->compare_products->delete_product($products_id);
        }

        //if nagivation history has path, then redirect to path else redirect to home
        if ($this->navigation_history->has_path())
        {
            $this->navigation_history->redirect_to_path();
        }

        redirect();
    }
    
    /**
     * Reset compare products
     * 
     * @access public
     * @return void
     */
    public function clear()
    {
        $this->compare_products->reset();

        //if nagivation history has path, then redirect to path else redirect to home
        if ($this->navigation_history->has_path())
        {
            $this->navigation_history->redirect_to_path();
        }

        redirect();
    }
}

/* End of file compare.php */
/* Location: ./system/tomatocart/controllers/products/compare.php */