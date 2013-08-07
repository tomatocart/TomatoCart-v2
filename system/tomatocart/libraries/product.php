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
 * Product Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Product
{
    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    protected $ci = null;

    /**
     * data
     *
     * @access protected
     * @var array
     */
    protected $data = array();

    /**
     * customers_id
     *
     * @access protected
     * @var int
     */
    protected $customers_id = null;

    /**
     * customers groups id
     *
     * @access protected
     * @var int
     */
    protected $customers_groups_id = null;

    /**
     * customer group discount
     *
     * @access protected
     * @var object
     */
    protected $customer_group_discount = null;

    /**
     * Shopping Class Constructor
     *
     * The constructor loads the Session class, used to store the shopping cart contents.
     */
    public function __construct($id)
    {
        //initialize the ci instance
        $this->ci = get_instance();

        if (!empty($id)) {
            $this->data = $this->ci->products_model->get_data($id);

            //format the variants display price
            if ( isset($this->data['variants']) && is_array($this->data['variants']) )
            {
                $products_variants = $this->data['variants'];

                foreach ($products_variants as $products_id_string => $data)
                {
                    $this->data['variants'][$products_id_string]['display_price'] = $this->ci->currencies->display_price($data['price'], $this->data['tax_class_id']);
                }
            }

            //get quantity discount group
            if ($this->data['quantity_discount_groups_id'] > 0)
            {
                $this->data['quantity_discount'] = $this->get_discount_group();
            }
        }
    }

    /**
     * Get average reviews rating
     *
     * @access public
     * @return int
     */
    public function get_average_reviews_rating()
    {
        if ($this->_reviews_average_rating == NULL)
        {
            $this->_reviews_average_rating = round($this->products_model->get_average_reviews_rating($this->data['id']));
        }

        return $this->_reviews_average_rating;
    }

    /**
     * Get product variants id
     *
     * @param $variants
     * @return array
     */
    public function get_product_variants_id($variants)
    {
        $product_id_string = osc_get_product_id_string($this->get_id(), $variants);

        if(isset($this->data['variants']) && isset($this->data['variants'][$product_id_string]))
        {
            return $this->data['variants'][$product_id_string]['variants_id'];
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Get default variant
     *
     * @access public
     * @return
     */
    function get_default_variant()
    {
        if ($this->has_variants())
        {
            return $this->data['default_variant'];
        }

        return false;
    }

    /**
     * Is valid
     *
     * @access public
     * @return boolean
     */
    function is_valid()
    {
        if (empty($this->data))
        {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Get data
     *
     * @access public
     * @param $key
     * @return array
     */
    function get_data($key = NULL)
    {
        if ($key === NULL)
        {
            return $this->data;
        }
        else
        {
            if (isset($this->data[$key]))
            {
                return $this->data[$key];
            }
        }

        return FALSE;
    }

    /**
     * Get product id
     *
     * @access public
     * @return int
     */
    public function get_id()
    {
        return $this->data['products_id'];
    }

    /**
     * Get product name
     *
     * @access public
     * @return string
     */
    public function get_title()
    {
        return $this->data['name'];
    }

    /**
     * Get product type
     *
     * @access public
     * @return int
     */
    public function get_product_type()
    {
        return $this->data['type'];
    }

    /**
     * Is simple product
     *
     * @access public
     * @return boolean
     */
    public function is_simple()
    {
        return (isset($this->data['type']) && ($this->data['type'] == PRODUCT_TYPE_SIMPLE));
    }

    /**
     * Is virtual product
     *
     * @access public
     * @return boolean
     */
    public function is_virtual()
    {
        return (isset($this->data['type']) && ($this->data['type'] == PRODUCT_TYPE_VIRTUAL));
    }

    /**
     * Is downloadable product
     *
     * @access public
     * @return boolean
     */
    public function is_downloadable()
    {
        return (isset($this->data['type']) && ($this->data['type'] == PRODUCT_TYPE_DOWNLOADABLE));
    }

    /**
     * Has sample file
     *
     * @access public
     * @return boolean
     */
    public function has_sample_file()
    {
        return (isset($this->data['sample_filename']) && !empty($this->data['sample_filename']));
    }

    /**
     * Get sample filename
     *
     * @access public
     * @return string
     */
    public function get_sample_file()
    {
        return $this->data['sample_filename'];
    }

    /**
     * Get short description
     *
     * @access public
     * @return string
     */
    public function get_short_description()
    {
        return $this->data['short_description'];
    }

    /**
     * Get description
     *
     * @access public
     * @return string
     */
    public function get_description()
    {
        return $this->data['description'];
    }

    /**
     * Has product model
     *
     * @access public
     * @return boolean
     */
    public function has_model()
    {
        return (isset($this->data['model']) && !empty($this->data['model']));
    }

    /**
     * Get product model
     *
     * @access public
     * @param $variants
     * @return string
     */
    public function get_model($variants = null)
    {
        if ($variants == null || empty($variants))
        {
            return $this->data['model'];
        }
        else
        {
            $product_id_string = get_product_id_string($this->get_id(), $variants);

            return $this->data['variants'][$product_id_string]['model'];
        }
    }

    /**
     * Has sku
     *
     * @access public
     * @return boolean
     */
    public function has_sku()
    {
        return (isset($this->data['sku']) && !empty($this->data['sku']));
    }

    /**
     * Get product sku
     *
     * @access public
     * @param $variants
     * @return string
     */
    public function get_sku($variants = null)
    {
        if ($variants == null || empty($variants))
        {
            $sku = $this->data['sku'];

            if (isset($this->data['default_variant']) && is_array($this->data['default_variant']) && !empty($this->data['default_variant']))
            {
                $sku = $this->data['default_variant']['sku'];
            }

            return $sku;
        }
        else
        {
            $product_id_string = get_product_id_string($this->get_id(), $variants);

            return $this->data['variants'][$product_id_string]['sku'];
        }
    }

    /**
     * Has keyword
     *
     * @access public
     * @return boolean
     */
    public function has_keyword()
    {
        return (isset($this->data['keyword']) && !empty($this->data['keyword']));
    }

    /**
     * Get keyword
     *
     * @access public
     * @return string
     */
    public function get_keyword()
    {
        return $this->data['keyword'];
    }

    /**
     * Has page title
     *
     * @access public
     * @return boolean
     */
    public function has_page_title()
    {
        return (isset($this->data['page_title']) && !empty($this->data['page_title']));
    }

    /**
     * Get page title
     *
     * @access public
     * @return string
     */
    public function get_page_title()
    {
        return $this->data['page_title'];
    }

    /**
     * Has meta keywords
     *
     * @access public
     * @return boolean
     */
    public function has_meta_keywords()
    {
        return (isset($this->data['meta_keywords']) && !empty($this->data['meta_keywords']));
    }

    /**
     * Get meta keywords
     *
     * @access public
     * @return string
     */
    public function get_meta_keywords()
    {
        return $this->data['meta_keywords'];
    }

    /**
     * Has meta description
     *
     * @access public
     * @return boolean
     */
    public function has_meta_description()
    {
        return (isset($this->data['meta_description']) && !empty($this->data['meta_description']));
    }

    /**
     * Get meta description
     *
     * @access public
     * @return string
     */
    public function get_meta_description()
    {
        return $this->data['meta_description'];
    }

    /**
     * Has tags
     *
     * @access public
     * @return boolean
     */
    public function has_tags()
    {
        return (isset($this->data['tags']) && !empty($this->data['tags']));
    }

    /**
     * Get tags
     *
     * @access public
     * @return string
     */
    public function get_tags()
    {
        return $this->data['tags'];
    }

    /**
     * Get minimum order quantity
     *
     * @access public
     * @return int
     */
    public function get_moq()
    {
        return $this->data['moq'];
    }

    /**
     * Get max order quantity
     *
     * @access public
     * @return int
     */
    public function get_max_order_quantity()
    {
        return $this->data['max_order_quantity'];
    }

    /**
     * Get order increment
     *
     * @access public
     * @return int
     */
    public function get_order_increment()
    {
        return $this->data['order_increment'];
    }

    /**
     * Get unit class
     *
     * @access public
     * @return int
     */
    public function get_unit_class()
    {
        return $this->data['unit_class'];
    }

    /**
     * Is featured product
     *
     * @access public
     * @return boolean
     */
    public function is_featured()
    {
        return ($this->data['featured_products_id'] === NULL) ? FALSE : TRUE;
    }

    /**
     * Is specials product
     *
     * @access public
     * @return boolean
     */
    public function is_specials()
    {
        return ($this->data['specials_price'] === NULL) ? FALSE : TRUE;
    }

    /**
     * Get specials price
     *
     * @access public
     * @return float
     */
    public function get_specials_price()
    {
        if ($this->data['specials_price'] !== NULL)
        {
            return $this->data['specials_price'];
        }

        return 0;
    }

    /**
     * Has accessories
     *
     * @access public
     * @return boolean
     */
    function has_accessories()
    {
        return (isset($this->data['accessories']) && !empty($this->data['accessories']));
    }

    /**
     * Get accessories
     *
     * @access public
     * @return array
     */
    function get_accessories()
    {
        return $this->data['accessories'];
    }

    /**
     * Get product price
     *
     * @access public
     * @param $variants
     * @param $quantity
     * @return float
     */
    public function get_price($variants = null, $quantity = 1) {
        //get product price
        $product_price = $this->data['price'];

        //get variant price
        if (is_array($variants) && !empty($variants))
        {
            $product_id_string = get_product_id_string($this->get_id(), $variants);
            if (isset($this->data['variants'][$product_id_string]))
            $product_price = $this->data['variants'][$product_id_string]['price'];
        }
        //if has variant then get default variant price
        else
        {
            if ($this->has_variants())
            {
                if (is_array($this->data['default_variant']) && !empty($this->data['default_variant']))
                {
                    $product_price = $this->data['default_variant']['price'];
                }
            }
        }

        return $product_price;
    }

    /**
     * Get formated product price with currency symbol
     *
     * @access public
     * @param $with_special
     * @return string
     */
    public function get_price_formated($with_special = false)
    {
        return currencies_display_price($this->get_price(), $this->get_tax_class_id());
    }

    /**
     * Get category id
     *
     * @access public
     * @return int
     */
    public function get_category_id()
    {
        return $this->data['categories_id'];
    }

    /**
     * Get product images
     *
     * @access public
     * @return array
     */
    public function get_images()
    {
        return $this->data['images'];
    }

    /**
     * Has special price
     *
     * @access public
     * @return boolean
     */
    public function has_special()
    {
        $special = $this->ci->specials->get_price($this->data['id']);

        if ( is_numeric($special) && ($special > 0) )
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Has product image
     *
     * @access public
     * @return boolean
     */
    public function has_image()
    {
        foreach ($this->data['images'] as $image)
        {
            if ($image['default_flag'] == '1')
            {
                return TRUE;
            }
        }
    }

    /**
     * Get product image
     *
     * @access public
     * @return boolean
     */
    public function get_image()
    {
        $default_image = NULL;
        $default_variant_image = NULL;

        if (isset($this->data['images']) && count($this->data['images']) > 0) {
            foreach ($this->data['images'] as $image)
            {
                //get variant default image
                if ($this->has_variants())
                {
                    if (is_array($this->data['default_variant']) && !empty($this->data['default_variant']))
                    {
                        if (!empty($this->data['default_variant']['image']) && ($image['id'] == $this->data['default_variant']['image']))
                        {
                            $default_variant_image = $image['image'];
                        }
                    }
                }

                //get default image
                if ($image['default_flag'] == '1')
                {
                    $default_image = $image['image'];
                }
            }
        }

        if ($default_variant_image != NULL)
        {
            return $default_variant_image;
        }

        return $default_image;
    }

    /**
     * Has url
     *
     * @access public
     * @return boolean
     */
    public function has_url()
    {
        return (isset($this->data['url']) && !empty($this->data['url']));
    }

    /**
     * Get url
     *
     * @access public
     * @return string
     */
    public function get_url()
    {
        return $this->data['url'];
    }

    /**
     * Get date available
     *
     * @access public
     * @return string
     */
    public function get_date_available()
    {
        return $this->data['date_available'];
    }

    /**
     * Get data added
     *
     * @access public
     * @return string
     */
    public function get_date_added() {
        return $this->data['date_added'];
    }

    /**
     * Get weight
     *
     * @access public
     * @param $variants
     * @return float
     */
    public function get_weight($variants = null)
    {
        if ($variants == null || empty($variants))
        {
            return $this->data['products_weight'];
        }
        else
        {
            $product_id_string = get_product_id_string($this->get_id(), $variants);

            return $this->data['variants'][$product_id_string]['weight'];
        }
    }

    /**
     * Get tax class id
     *
     * @access public
     * @param
     * @return int
     */
    public function get_tax_class_id()
    {
        return $this->data['tax_class_id'];
    }

    /**
     * Get weight class
     *
     * @access public
     * @param
     * @return
     */
    public function get_weight_class()
    {
        return $this->data['products_weight_class'];
    }

    /**
     * Get manufacturers
     *
     * @access public
     * @return string
     */
    public function get_manufacturer() {
        return $this->data['manufacturers_name'];
    }

    /**
     * Get quantity from database
     *
     * @access public
     * @return int
     */
    public function get_quantity_from_db() {
        global $osCdatabase;

        if (!isset($this->data['quantity']))
        {
            $this->data['quantity'] = $this->ci->products_model->get_product_quantity($this->get_id());
        }

        if ( $this->has_variants() && !isset($this->data['default_variant']['quantity']) )
        {
            foreach ($this->data['variants'] as $id => $variant)
            {
                $this->data['variants'][$id]['quantity'] = $this->ci->products_model->get_product_variant_quantity($variant);

                if ($variant['is_default'] == '1')
                {
                    $this->data['default_variant']['quantity'] = $this->data['variants'][$id]['quantity'];
                }
            }
        }
    }

    /**
     * Get Product Quantity
     *
     * @param string $products_id_string
     * @return int product quantity
     */
    public function get_quantity($products_id_string = '')
    {
        $this->get_quantity_from_db();

        if (is_numeric(strpos($products_id_string,'#')))
        {
            if (isset($this->data['variants'][$products_id_string]))
            {
                return $this->data['variants'][$products_id_string]['quantity'];
            }
        }
        else
        {
            $quantity = $this->data['quantity'];

            if (isset($this->data['default_variant']) && is_array($this->data['default_variant']) && !empty($this->data['default_variant']))
            {
                $quantity = $this->data['default_variant']['quantity'];
            }

            return $quantity;
        }

        exit;
    }

    /**
     * Check whether this product has variants
     *
     * @return boolean has variants or not
     */
    public function has_variants()
    {
        return (isset($this->data['variants']) && !empty($this->data['variants']));
    }

    /**
     * Get product variants
     *
     * @return array variants array
     */
    public function get_variants()
    {
        $this->get_quantity_from_db();

        return $this->data['variants'];
    }

    /**
     * Increase product view counter
     *
     * @access public
     * @return void
     */
    public function increment_counter()
    {
        $this->ci->products_model->increment_counter();
    }

    /**
     * Get number of images
     *
     * @access public
     * @return int
     */
    public function number_of_images()
    {
        return sizeof($this->data['images']);
    }

    /**
     * Get variants combobox array
     *
     * @access public
     * @return array
     */
    function get_variants_combobox_array()
    {
        if ($this->has_variants())
        {
            $combobox_array = array();

            foreach ($this->data['variants_groups'] as $groups_id => $groups_name)
            {
                $values = array();
                foreach($this->data['variants_groups_values'][$groups_id] as $values_id)
                {
                    $values[$values_id] = $this->data['variants_values'][$values_id];
                }

                $combobox_array[$groups_name] = form_dropdown(
            		'variants[' . $groups_id . ']', 
                $values,
                $this->data['default_variant']['groups_id'][$groups_id]);
            }
            return $combobox_array;
        }

        return FALSE;
    }
}

/* End of file product.php */
/* Location: ./system/tomatocart/libraries/product.php */