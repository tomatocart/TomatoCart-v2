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
 * Compare Products Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Compare_Products
{
    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    private $ci = null;

    /**
     * compare products content
     *
     * @access protected
     * @var array
     */
    private $contents = array();

    /**
     * Default Constructor
     *
     * @param $id
     */
    function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        $contents = $this->ci->session->userdata('compare_products_contents');
        if ($contents === NULL) {
            $this->ci->session->set_userdata('compare_products_contents', array());
        }

        $this->contents = $contents;
    }

    /**
     * Check whether the product exist in the content
     *
     * @access public
     * @param $products_id
     * @return boolean
     */
    function exists($products_id)
    {
        return isset($this->contents[$products_id]);
    }

    /**
     * Has contents
     *
     * @access public
     * @return boolean
     */
    function has_contents()
    {
        return !empty($this->contents);
    }

    /**
     * Reset
     *
     * @access public
     * @return void
     */
    function reset()
    {
        $this->contents = array();
        
        $this->save_session();
    }

    /**
     * add a product to wishlist
     *
     * @param int $products_id
     * @param string $comments
     * @return boolean
     */
    function add_product($products_id)
    {
        if (!$this->exists($products_id))
        {
            $product = load_product_library($products_id);

            if ($product->is_valid())
            {
                $this->contents[$products_id] = $products_id;

                $this->save_session();
            }
        }
    }

    /**
     * Delete wishlist procuct
     *
     * @param int $products_id
     * @return boolean
     */
    function delete_product($products_id)
    {
        if (isset($this->contents[$products_id]))
        {
            unset($this->contents[$products_id]);

            $this->save_session();
        }
    }

    /**
     * Get wishlist products
     *
     * @access public
     * @return array
     */
    function get_products()
    {
        if (is_array($this->contents) && !empty($this->contents))
        {
            $products = array_keys($this->contents);
             
            return $products;
        }

        return NULL;
    }

    /**
     * Save data to session
     *
     * @access private
     * @return void
     */
    private function save_session()
    {
        $this->ci->session->set_userdata('compare_products_contents', $this->contents);
    }


    /**
     * Get compare data
     *
     * @access public
     * @return array
     */
    function get_compare_data()
    {
        $data = array();
        $products = array();

        if ($this->has_contents())
        {
            foreach ($this->contents as $products_id)
            {
                $products[] = load_product_library($products_id);
            }
        }
    }

    /**
     * Output compare products table
     * 
     * @access public
     * @return string
     */
    function output_compare_products_table()
    {
        $content = '';

        $products_images = array();
        $products_titles = array();
        $products_price = array();
        $products_weight = array();
        $products_sku = array();
        $products_manufacturers = array();
        $products_desciptions = array();
        $products_attributes = array();
        $products_variants = array();
         
        if ($this->has_contents())
        {
            $cols = array('<col width="20%">');
            $col_width = round(80 / count($this->get_products()));

            foreach ($this->get_products() as $products_id)
            {
                $cols[] = '<col width="' . $col_width . '%">';

                $product = load_product_library($products_id);

                $images = $product->get_images();
                $product_title = $product->get_title();
                $product_price = $product->get_price_formated(true);
                $product_weight = $product->get_weight();
                $product_sku = $product->get_sku();

                $image = (is_array($images) ? $images[0]['image'] : $images);

                $products_titles[] = $product_title;

                if (!empty($product_price)) {
                    $products_price[] = $product_price;
                }

                if (!empty($product_weight)) {
                    $products_weight[] = ''; //$osC_Weight->display($product_weight, $product->get_weightClass());
                }

                if (!empty($product_sku)) {
                    $products_sku[] = $product_sku;
                }

                $manufacturer = $product->get_manufacturer();
                if (!empty($manufacturer))  {
                    $products_manufacturers[] = $manufacturer;
                }

                $description = $product->get_description();
                if (!empty($description))  {
                    $products_desciptions[] = $description;
                }

                $products_id = str_replace('#', '_', $products_id);
                $products_images[] = '<div class="image">
                						<a href="' . site_url('product/' . $products_id) . '" title="' . $product->get_title() . '">
                							<img alt="' . $product->get_title() . '" src="' . product_image_url($image) . '" />
                						</a>
                					  </div>' .
                               	      '<a class="btn btn-mini" href="' . site_url('cart_add/' . $products_id) . '">' . lang('button_add_to_cart') . '</a>';
            }

            $content .= '<table id="compareProducts" cellspacing="0" cellpadding="2" border="0" class="table-striped table-bordered">';

            //add col groups
            $content .= '<colgroup>';
            foreach($cols as $col) {
                $content .= $col;
            }
            $content .= '</colgroup>';

            //add product header
            $content .= '<tbody>';
            $content .= '<tr class="first">';
            $content .= '<th>&nbsp;</th>';

            if (!empty($products_images)) {
                foreach($products_images as $k => $product_image) {
                    $content .= '<td' . ($k == (count($products_images) - 1) ? ' class="last"' : '') . '>' . $product_image . '</td>';
                }
            }
            $content .= '</tr>';
            $content .= '</tbody>';

            //add compare details
            $content .= '<tbody>';

            $row_class='even';

            //add product name
            if (!empty($products_titles)) {
                $content .= '<tr class="' . $row_class . '">' .
                        '<th valign="top">' . lang('field_products_name') . '</th>';

                foreach($products_titles as $k => $product_title) {
                    $content .= '<td' . ($k == (count($products_titles) - 1) ? ' class="last"' : '') . ' valign="top">' . $product_title . '</td>';
                }

                $content .= '</tr>';

                $row_class = ($row_class == 'even' ? 'odd' : 'even');
            }

            //add product price
            if (!empty($products_price)) {
                $content .= '<tr class="' . $row_class . '">' .
                        '<th>' . lang('field_products_price') . '</th>';

                foreach($products_price as $k => $product_price) {
                    $content .= '<td' . ($k == (count($products_price) - 1) ? ' class="last"' : '') . '>' . $product_price . '</td>';
                }

                $content .= '</tr>';

                $row_class = ($row_class == 'even' ? 'odd' : 'even');
            }

            //add product weight
            if (!empty($products_weight)) {
                $content .= '<tr class="' . $row_class . '">' .
                        '<th>' . lang('field_products_weight') . '</th>';

                foreach($products_weight as $k => $product_weight) {
                    $content .= '<td' . ($k == (count($products_weight) - 1) ? ' class="last"' : '') . '>' . $product_weight . '</td>';
                }

                $content .= '</tr>';

                $row_class = ($row_class == 'even' ? 'odd' : 'even');
            }

            //add product sku
            if (!empty($products_sku)) {
                $content .= '<tr class="' . $row_class . '">' .
                        '<th>' . lang('field_products_sku') . '</th>';

                foreach($products_sku as $k => $product_sku) {
                    $content .= '<td' . ($k == (count($products_sku) - 1) ? ' class="last"' : '') . '>' . $product_sku . '</td>';
                }

                $content .= '</tr>';

                $row_class = ($row_class == 'even' ? 'odd' : 'even');
            }

            //add product manufacturers
            if (!empty($products_manufacturers)) {
                $content .= '<tr class="' . $row_class . '">' .
                        '<th>' . lang('field_products_manufacturer') . '</th>';

                foreach($products_manufacturers as $k => $product_manufacturer) {
                    $content .= '<td' . ($k == (count($products_manufacturers) - 1) ? ' class="last"' : '') . '>' . $product_manufacturer . '</td>';
                }

                $content .= '</tr>';

                $row_class = ($row_class == 'even' ? 'odd' : 'even');
            }

            //add product variants
            //add product description
            if (!empty($products_desciptions)) {
                $content .= '<tr class="' . $row_class . ' last">' .
                        '<th valign="top">' . lang('field_products_description') . '</th>';

                foreach($products_desciptions as $k => $product_description) {
                    $content .= '<td' . ($k == (count($products_desciptions) - 1) ? ' class="last"' : '') . ' valign="top">' . $product_description . '</td>';
                }

                $content .= '</tr>';

                $row_class = ($row_class == 'even' ? 'odd' : 'even');
            }

            $content .= '</tbody>';
            $content .= '</table>';
        }

        return $content;
    }
}

/* End of file compare_products.php */
/* Location: ./system/tomatocart/libraries/compare_products.php */