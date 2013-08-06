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
 * Shopping Cart Class
 *
 * The shopping cart class is copied from TomatoCart v1.0. It keep some basic features and following features are
 * removed:
 *   gift wrapping
 *   gift wrapping message
 *   coupon
 *   gift certificate
 *   customer credit
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Shopping_Cart
{

    /**
     * ci instance
     *
     * @access protected
     * @var string
     */
    protected $ci = NULL;

    /**
     * shopping cart content
     *
     * @access protected
     * @var array
     */
    protected $contents = array();

    /**
     * products subtotal
     *
     * @access protected
     * @var float
     */
    protected $subtotal = 0;

    /**
     * shopping cart total
     *
     * @access protected
     * @var float
     */
    protected $total = 0;

    /**
     * products total weight
     *
     * @access protected
     * @var float
     */
    protected $weight = 0;

    /**
     * products tax total
     *
     * @access protected
     * @var float
     */
    protected $tax = 0;

    /**
     * products tax groups
     *
     * @access protected
     * @var array
     */
    protected $tax_groups = array();

    /**
     * shopping cart content type
     *
     * @access protected
     * @var string
     */
    protected $content_type;

    /**
     * shopping cart shipping address
     *
     * @access protected
     * @var array
     */
    protected $shipping_address = NULL;

    /**
     * shopping cart billing address
     *
     * @access protected
     * @var array
     */
    protected $billing_address = NULL;

    /**
     * products tax total
     *
     * @access protected
     * @var boolean
     */
    protected $products_in_stock = TRUE;

    /**
     * Shopping Class Constructor
     *
     * The constructor loads the Session class, used to store the shopping cart contents.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        // Set the super object to a local variable for use later
        $this->ci->load->model('shopping_cart_model');

        // Grab the shopping cart array from the session table, if it exists
        $cart_contents = $this->ci->session->userdata('cart_contents');
        if ($cart_contents !== NULL)
        {
            $this->contents = $cart_contents['contents'];
            $this->subtotal = $cart_contents['subtotal'];
            $this->total = $cart_contents['total'];
            $this->weight = $cart_contents['weight'];
            $this->tax = $cart_contents['tax'];
            $this->tax_groups = $cart_contents['tax_groups'];
            $this->shipping_boxes_weight =$cart_contents['shipping_boxes_weight'];
            $this->shipping_boxes =$cart_contents['shipping_boxes'];
            $this->shipping_address = $cart_contents['shipping_address'];
            $this->shipping_method =$cart_contents['shipping_method'];
            $this->billing_address = $cart_contents['billing_address'];
            $this->billing_method = $cart_contents['billing_method'];
            $this->shipping_quotes = $cart_contents['shipping_quotes'];
            $this->order_totals = $cart_contents['order_totals'];
        }
        else
        {
            $this->contents = array();
            $this->subtotal = 0;
            $this->total = 0;
            $this->weight = 0;
            $this->tax = 0;
            $this->tax_groups = array();
            $this->content_type = NULL;

            $this->shipping_boxes_weight = 0;
            $this->shipping_boxes = 1;
            $this->shipping_address = array('zone_id' => config('STORE_ZONE'), 'country_id' => config('STORE_COUNTRY'));
            $this->shipping_method = array();
            $this->billing_address = array('zone_id' => config('STORE_ZONE'), 'country_id' => config('STORE_COUNTRY'));
            $this->billing_method = array();
            $this->shipping_quotes = array();
            $this->order_totals = array();
        }

        log_message('debug', "TOC Shopping Cart Class Initialized");
    }

    /**
     * save session to data
     *
     * @return void
     */
    function save_session()
    {
        $cart_contents = array(
            'contents' => $this->contents,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'weight' => $this->weight,
            'tax' => $this->tax,
            'tax_groups' => $this->tax_groups,
            'shipping_boxes_weight' => $this->shipping_boxes_weight,
            'shipping_boxes' => $this->shipping_boxes,
            'shipping_address' => $this->shipping_address,
            'shipping_method' => $this->shipping_method,
            'billing_address' => $this->billing_address,
            'billing_method' => $this->billing_method,
            'shipping_quotes' => $this->shipping_quotes,
            'order_totals' => $this->order_totals);

        $this->ci->session->set_userdata('cart_contents', $cart_contents);
    }

    /**
     * Update Shopping Cart
     *
     * @return void
     */
    function update()
    {
        $cart_id = $this->ci->session->userdata('cart_id');
        if ($cart_id === NULL) {
            $this->calculate();
        }
    }

    /**
     * Return whether the shopping cart has contents
     *
     * @return boolean
     */
    function has_contents()
    {
        return !empty($this->contents);
    }

    /**
     * Synchronize shopping cart contents with database
     *
     * @access public
     * @return boolean
     */
    function synchronize_with_database() {
        //if customer is not logged on
        if (!$this->ci->customer->is_logged_on()) {
            return FALSE;
        }

        // insert current cart contents in database
        if ($this->has_contents()) {
            foreach ($this->contents as $products_id_string => $data) {
                $basket = $this->ci->shopping_cart_model->get_content($this->ci->customer->get_id(), $products_id_string);

                if ($basket !== NULL) {
                    $this->ci->shopping_cart_model->update_content($this->ci->customer->get_id(), $products_id_string, $data['quantity'] + $basket['customers_basket_quantity']);
                } else {
                    $this->ci->shopping_cart_model->insert_content($this->ci->customer->get_id(), $products_id_string, $data['quantity'], $data['final_price']);
                }
            }
        }

        // reset per-session cart contents, but not the database contents
        $this->reset();

        // synchronize content
        $products = $this->ci->shopping_cart_model->get_contents($this->ci->customer->get_id());
        if ($products != NULL) 
        {
            foreach ($products as $data) 
            {
                $products_id = $data['products_id'];
                $variants = parse_variants_from_id_string($products_id);
                $quantity = $data['customers_basket_quantity'];

                $product = load_product_library($products_id);
                if ($product->is_valid()) 
                {
                    $this->contents[$products_id] = array('id' => $products_id,
                                                          'name' => $product->get_title(),
                                                          'type' => $product->get_product_type(),
                                                          'keyword' => $product->get_keyword(),
                                                          'sku' => $product->get_sku($variants),
                                                          'image' => $product->get_image(),
                                                          'price' => $product->get_price($variants, $quantity),
                                                          'final_price' => $product->get_price($variants, $quantity),
                                                          'quantity' => ($quantity > $product->get_quantity($products_id)) ? $product->get_quantity($products_id) : $quantity,
                                                          'weight' => $product->get_weight($variants),
                                                          'tax_class_id' => $product->get_tax_class_id(),
                                                          'date_added' => get_date_short($data['customers_basket_date_added']),
                                                          'weight_class_id' => $product->get_weight_class());

                    //set in stock status
                    $this->contents[$products_id]['in_stock'] = $this->is_in_stock($products_id);

                    if ($variants !== NULL) {
                        foreach ($variants as $group_id => $value_id) {
                            $data = $this->ci->shopping_cart_model->get_variants_data(get_product_id($products_id), $group_id, $value_id);

                            if ($data !== NULL) {
                                $this->contents[$products_id]['variants'][$group_id] = array('groups_id' => $group_id,
                                                                                             'variants_values_id' => $value_id,
                                                                                             'groups_name' => $data['products_variants_groups_name'],
                                                                                             'values_name' => $data['products_variants_values_name']);
                            }
                        }
                    }
                }
            }
        }

        $this->clean_up();
        $this->calculate();
    }

    /**
     * Reset shopping cart
     *
     * @param boolean
     * @return void
     */
    public function reset($reset_database = FALSE)
    {
        if (($reset_database === TRUE) && $this->ci->customer->is_logged_on())
        {
            $this->ci->shopping_cart_model->delete($this->ci->customer->get_id());
        }

        $this->contents = array();
        $this->subtotal = 0;
        $this->total = 0;
        $this->weight = 0;
        $this->tax = 0;
        $this->tax_groups = array();
        $this->content_type = NULL;

        $this->shipping_boxes_weight = 0;
        $this->shipping_boxes = 1;
        $this->shipping_address = array('zone_id' => config('STORE_ZONE'), 'country_id' => config('STORE_COUNTRY'));
        $this->shipping_method = array();
        $this->billing_address = array('zone_id' => config('STORE_ZONE'), 'country_id' => config('STORE_COUNTRY'));
        $this->billing_method = array();
        $this->shipping_quotes = array();
        $this->order_totals = array();

        $this->reset_shipping_address();
        $this->reset_shipping_method();
        $this->reset_billing_address();
        $this->reset_billing_method();

        $this->save_session();
        
        //unset cart id
        $this->ci->session->unset_userdata('cart_id');
    }

    /**
     * Add product to shopping cart
     *
     * @param string $products_id_string
     * @param array $variants
     * @param int $quantity
     * @param stirng $action
     */
    function add($products_id_string, $variants = NULL, $quantity = NULL, $action = 'add')
    {
        //load product object
        $product = load_product_library($products_id_string);

        if ($product->is_valid())
        {
            //if product has variants and variants is not given
            if ($product->has_variants() && ($variants == NULL)) {
                $variant = $product->get_default_variant();
                $variants = parse_variants_from_id_string($variant['product_id_string']);
            }

            //get products id string
            $products_id_string = get_product_id_string($products_id_string, $variants);

            //if product already exists in shopping cart
            if ($this->exists($products_id_string))
            {
                $old_quantity = $this->get_quantity($products_id_string);

                //if quantity is not specified
                if (!is_numeric($quantity))
                {
                    $quantity = $this->get_quantity($products_id_string) + 1;
                }
                //if quantity is zero
                else if (is_numeric($quantity) && ($quantity == 0))
                {
                    $this->remove($products_id_string);

                    return;
                }
                else
                {
                    if ($action == 'add')
                    {
                        $quantity = $this->get_quantity($products_id_string) + $quantity;
                    }
                }

                //check minimum order quantity
                $products_moq = $product->get_moq();
                if ($quantity < $products_moq)
                {
                    $quantity = $products_moq;
                    $error = sprintf(lang('error_minimum_order_quantity'), $product->get_title(), $products_moq);
                }

                //check maximum order quantity
                $products_max_order_quantity = $product->get_max_order_quantity();
                if ( $products_max_order_quantity > 0 )
                {
                    if ( $quantity > $products_max_order_quantity )
                    {
                        $quantity = $products_max_order_quantity;
                        $error = sprintf(lang('error_maximum_order_quantity'), $product->get_title(), $products_max_order_quantity);
                    }
                }

                //check order increment
                $increment = $product->get_order_increment();
                if ((($quantity - $products_moq) % $increment) != 0)
                {
                    $quantity = $products_moq + (floor(($quantity - $products_moq) / $increment) + 1) * $increment;
                    $error = sprintf(lang('error_order_increment'), $product->get_title(), $increment);
                }

                //set error to session
                if (isset($error) && !empty($error))
                {
                    $this->contents[$products_id_string]['error'] = $error;
                }

                $price = $product->get_price($variants, $quantity);
                
                //specials

                $this->contents[$products_id_string]['quantity'] = $quantity;
                $this->contents[$products_id_string]['price'] = $price;
                $this->contents[$products_id_string]['final_price'] = $price;

                // update database
                if ($this->ci->customer->is_logged_on())
                {
                    $this->ci->shopping_cart_model->update_content($this->ci->customer->get_id(), $products_id_string, $quantity);
                }
            }
            // add product
            else
            {
                if (!is_numeric($quantity)) {
                    $quantity = 1;
                }

                //check minimum order quantity
                $products_moq = $product->get_moq();
                if ($quantity < $products_moq) {
                    $quantity = $products_moq;
                    $error = sprintf(lang('error_minimum_order_quantity'), $product->get_title(), $products_moq);
                }

                //check order increment
                $increment = $product->get_order_increment();
                if ((($quantity - $products_moq) % $increment) != 0) {
                    $quantity = $products_moq + (floor(($quantity - $products_moq) / $increment) + 1) * $increment;
                    $error = sprintf(lang('error_order_increment'), $product->get_title(), $increment);
                }

                $price = $product->get_price($variants, $quantity);
                $this->contents[$products_id_string] = array('id' => $products_id_string,
                                                             'name' => $product->get_title(),
                                                             'type' => $product->get_product_type(),
                                                             'keyword' => $product->get_keyword(),
                                                             'sku' => $product->get_sku($variants),
                                                             'image' => $product->get_image(),
                                                             'price' => $price,
                                                             'final_price' => $price,
                                                             'quantity' => $quantity,
                                                             'weight' => $product->get_weight($variants),
                                                             'tax_class_id' => $product->get_tax_class_id(),
                                                             'date_added' => get_date_short(get_date_now()),
                                                             'weight_class_id' => $product->get_weight_class());

                //set in stock status
                $this->contents[$products_id_string]['in_stock'] = $this->is_in_stock($products_id_string);

                //set error to session
                if (isset($error) && !empty($error)) {
                    $this->contents[$products_id_string]['error'] = $error;
                }

                // insert into database
                if ($this->ci->customer->is_logged_on())
                {
                    $this->ci->shopping_cart_model->insert_content($this->ci->customer->get_id(), $products_id_string, $quantity, $price);
                }

                if (is_array($variants) && !empty($variants)) {
                    $variants_array = $product->get_variants();
                    $products_variants_id_string = get_product_id_string($products_id_string, $variants);
                    $products_variants_id = $variants_array[$products_variants_id_string]['variants_id'];

                    $this->contents[$products_id_string]['products_variants_id'] = $products_variants_id;
                    if (isset($variants_array[$products_variants_id_string]['filename']) && !empty($variants_array[$products_variants_id_string]['filename'])) {
                        $this->contents[$products_id_string]['variant_filename'] = $variants_array[$products_variants_id_string]['filename'];
                        $this->contents[$products_id_string]['variant_cache_filename'] = $variants_array[$products_variants_id_string]['cache_filename'];
                    }

                    foreach ($variants as $group_id => $value_id) {
                        $names = $this->ci->products_model->get_product_variant_group_and_value_name($product->get_id(), $group_id, $value_id);

                        $this->contents[$products_id_string]['variants'][$group_id] = array('groups_id' => $group_id,
                                                                                   'variants_values_id' => $value_id,
                                                                                   'groups_name' => $names['products_variants_groups_name'],
                                                                                   'values_name' => $names['products_variants_values_name']);
                    }
                }
            }

            $this->clean_up();
            $this->calculate();
        }
    }

    /**
     * Get number of physical items
     *
     * @access public
     * @return int
     */
    public function number_of_physical_items()
    {
        $total = 0;

        if ($this->has_contents())
        {
            foreach ($this->contents as $product)
            {
                if($product['type'] == PRODUCT_TYPE_SIMPLE) {
                    $total += $product['quantity'];
                }
            }
        }

        return $total;
    }

    /**
     * Get number of items
     *
     * @access public
     * @return void
     */
    public function number_of_items()
    {
        $total = 0;

        if ($this->has_contents())
        {
            foreach (array_keys($this->contents) as $products_id)
            {
                $total += $this->get_quantity($products_id);
            }
        }

        return $total;
    }

    /**
     * Get shopping cart product quantity
     *
     * @access public
     * @param $products_id
     * @return int
     */
    public function get_quantity($products_id)
    {
        if (isset($this->contents[$products_id]))
        {
            return $this->contents[$products_id]['quantity'];
        }

        return 0;
    }

    /**
     * Check whether the product exists
     *
     * @access public
     * @param $products_id
     * @return boolean
     */
    public function exists($products_id)
    {
        return isset($this->contents[$products_id]);
    }

    /**
     * Remove shopping cart content
     *
     * @access public
     * @param $products_id
     * @return void
     */
    public function remove($products_id)
    {
        unset($this->contents[$products_id]);

        // remove from database
        if ($this->ci->customer->is_logged_on())
        {
            $this->ci->shopping_cart_model->delete_content($this->ci->customer->get_id(), $products_id);
        }

        $this->calculate();
    }

    /**
     * Get shopping cart contents
     *
     * @access public
     * @return array
     */
    public function get_products()
    {
        uasort($this->contents, array('TOC_Shopping_Cart', '_uasort_products_by_date_added'));

        return $this->contents;
    }

    /**
     * Get shopping cart sub total
     *
     * @access public
     * @return float
     */
    function get_sub_total()
    {
        return $this->subtotal;
    }

    /**
     * Get shopping cart total
     *
     * @access public
     * @return float
     */
    function get_total()
    {
        return $this->total;
    }

    /**
     * Check whether the cart total is zero
     *
     * @access public
     * @return boolean
     */
    function is_total_zero()
    {
        return ($this->total == 0);
    }

    /**
     * Get total weight
     *
     * @access public
     * @return float
     */
    function get_weight()
    {
        return $this->weight;
    }

    /**
     * Generate Cart ID
     * 
     * @access public
     * @param $length
     * @return string
     */
    function generate_cart_id($length = 5)
    {
        return create_random_string($length, 'digits');
    }

    /**
     * Get Cart ID
     * 
     * @access public
     * @return mixed
     */
    function get_cart_id()
    {
        return $this->ci->session->userdata('cart_id');
    }

    /**
     * Get shopping cart content type(physical, virtual, mixed)
     *
     * @access public
     * @return string
     */
    function get_content_type()
    {
        if ($this->has_contents())
        {
            $products = array_values($this->contents);

            foreach ($products as $product)
            {
                if ($product['type'] == PRODUCT_TYPE_SIMPLE)
                {
                    switch ($this->content_type)
                    {
                        case 'virtual':
                            $this->content_type = 'mixed';

                            return $this->content_type;
                            break;
                        default:
                            $this->content_type = 'physical';
                            break;
                    }
                }
                else
                {
                    switch ($this->content_type)
                    {
                        case 'physical':
                            $this->content_type = 'mixed';

                            return $this->content_type;
                            break;
                        default:
                            $this->content_type = 'virtual';
                            break;
                    }
                }
            }
        }

        return $this->content_type;
    }

    /**
     * Check whether the content is virtual
     *
     * @access public
     * @return boolean
     */
    function is_virtual_cart()
    {
        return ($this->get_content_type() == 'virtual');
    }

    /**
     * Check whether a product has variants
     *
     * @access public
     * @return boolean
     */
    function has_variants($products_id)
    {
        return isset($this->contents[$products_id]['variants']) && !empty($this->contents[$products_id]['variants']);
    }

    /**
     * Get product variants
     *
     * @access public
     * @param $products_id
     * @return array
     */
    function get_variants($products_id)
    {
        if (isset($this->contents[$products_id]['variants']) && !empty($this->contents[$products_id]['variants'])) {
            return $this->contents[$products_id]['variants'];
        }

        return NULL;
    }

    /**
     * Check if product is in stock
     *
     * @access public
     * @param $products_id_string
     * @return boolean
     */
    function is_in_stock($products_id_string)
    {
        $product = load_product_library($products_id_string);

        if (($product->get_quantity($products_id_string) - $this->contents[$products_id_string]['quantity']) >= 0)
        {
            return TRUE;
        }
        elseif ($this->products_in_stock === TRUE)
        {
            $this->products_in_stock = FALSE;
        }

        return FALSE;
    }

    /**
     * Check whether the products is in stock
     *
     * @access public
     * @return boolean
     */
    function has_stock()
    {
        return $this->products_in_stock;
    }

    /**
     * Check whether the shopping cart has shipping address
     *
     * @access public
     * @return boolean
     */
    function has_shipping_address()
    {
        return isset($this->shipping_address) && isset($this->shipping_address['id']);
    }

    /**
     * Set shipping address
     *
     * @access public
     * @param $address_id
     * @return void
     */
    function set_shipping_address($address_id)
    {
        $address_changed = FALSE;

        if ( isset($this->shipping_address['id']) && ($this->shipping_address['id'] != $address_id) )
        {
            $address_changed = TRUE;
        }

        $this->ci->load->model('address_book_model');
        $address_book = $this->ci->address_book_model->get_address($this->ci->customer->get_id(), $address_id);

        $this->shipping_address = array('id' => $address_id,
                                        'gender' => $address_book['gender'],
                                        'firstname' => $address_book['firstname'],
                                        'lastname' => $address_book['lastname'],
                                        'company' => $address_book['company'],
                                        'street_address' => $address_book['street_address'],
                                        'suburb' => $address_book['suburb'],
                                        'city' => $address_book['city'],
                                        'postcode' => $address_book['postcode'],
                                        'state' => (!empty($address_book['state'])) ? $address_book['state'] : $address_book['zone_name'],
                                        'zone_id' => $address_book['zone_id'],
                                        'zone_code' => $address_book['zone_code'],
                                        'country_id' => $address_book['country_id'],
                                        'country_title' => $address_book['countries_name'],
                                        'country_iso_code_2' => $address_book['countries_iso_code_2'],
                                        'country_iso_code_3' => $address_book['countries_iso_code_3'],
                                        'format' => $address_book['address_format'],
                                        'telephone_number' => $address_book['telephone'],
                                        'fax' => $address_book['fax']);

        if ($address_changed === FALSE)
        {
            $this->calculate();
        }
        else
        {
            $this->save_session();
        }
    }

    /**
     * Set raw shipping address
     *
     * @access public
     * @param $data
     * @return void
     */
    function set_raw_shipping_address($data)
    {
        $this->ci->load->model('address_book_model');

        $country = $this->ci->address_book_model->get_country_data($data['country_id']);
        $state = isset($data['state']) ? $data['state'] : '';
        $zone_code = '';
        $zone_id = isset($data['zone_id']) ? $data['zone_id'] : 0;

        if (!empty($zone_id)) {
            $zone = $this->ci->address_book_model->get_zone_data($zone_id);

            $state = (!empty($state)) ? $state : $zone['zone_name'];
            $zone_code = $zone['zone_code'];
            $zone_name = $zone['zone_name'];
        }

        $this->shipping_address = array('id' => -1,
                                      'gender' => $data['gender'],
                                      'firstname' => $data['firstname'],
                                      'lastname' => $data['lastname'],
                                      'company' => $data['company'],
                                      'street_address' => $data['street_address'],
                                      'suburb' => $data['suburb'],
                                      'city' => $data['city'],
                                      'postcode' => $data['postcode'],
                                      'state' => $state,
                                      'zone_id' => $zone_id,
                                      'zone_code' => $zone_code,
                                      'country_id' => $data['country_id'],
                                      'country_title' => $country['countries_name'],
                                      'country_iso_code_2' => $country['countries_iso_code_2'],
                                      'country_iso_code_3' => $country['countries_iso_code_3'],
                                      'format' => $country['address_format'],
                                      'telephone_number' => $data['telephone'],
                                      'fax' => $data['fax'],
                                      'create_shipping_address' => 1); //$data['create_shipping_address']);

        $this->calculate();
    }

    /**
     * Get shipping address
     * 
     * @access public
     * @param $key
     * @return mixed
     */
    function get_shipping_address($key = '')
    {
        if (empty($key))
        {
            return $this->shipping_address;
        }
        
        return $this->shipping_address[$key];
    }

    /**
     * Reset shipping address
     * 
     * @access public
     * @return void
     */
    function reset_shipping_address()
    {
        $this->shipping_address = array('zone_id' => config('STORE_ZONE'), 'country_id' => config('STORE_COUNTRY'));

        if ($this->ci->customer->is_logged_on() && $this->ci->customer->has_default_address())
        {
            $this->set_shipping_address($this->ci->customer->get_default_address_id());
        }
    }

    /**
     * Set shipping method
     * 
     * @access public
     * @param $shipping_array
     * @param $calculate_total
     * @return void
     */
    function set_shipping_method($shipping_array, $calculate_total = TRUE)
    {
        $this->shipping_method = $shipping_array;

        if ($calculate_total === TRUE)
        {
            $this->calculate(FALSE);
        }
        else 
        {
            $this->save_session();
        }
    }

    /**
     * Get shipping method
     * 
     * @access public
     * @param $key
     * @return mixed
     */
    function get_shipping_method($key = '')
    {
        if (empty($key))
        {
            return $this->shipping_method;
        }
        else if (isset($this->shipping_method[$key]))
        {
            return $this->shipping_method[$key];
        }

        return NULL;
    }

    /**
     * Reset shipping method
     * 
     * @access public
     * @return void
     */
    function reset_shipping_method()
    {
        $this->shipping_method = array();

        $this->calculate();
    }

    /**
     * Has shipping method
     * 
     * @access public
     * @return boolean
     */
    function has_shipping_method()
    {
        return !empty($this->shipping_method);
    }

    /**
     * Has billing address
     * 
     * @access public
     * @return boolean
     */
    function has_billing_address()
    {
        return isset($this->billing_address) && isset($this->billing_address['id']);
    }

    /**
     * Set billing address
     * 
     * @access public
     * @return boolean
     */
    function set_billing_address($address_id)
    {
        $address_changed = FALSE;

        if ( isset($this->billing_address['id']) && ($this->billing_address['id'] != $address_id) )
        {
            $address_changed = TRUE;
        }

        $address_book = $this->ci->address_book_model->get_address($this->ci->customer->get_id(), $address_id);

        $this->billing_address = array('id' => $address_id,
                                       'gender' => $address_book['gender'],
                                       'firstname' => $address_book['firstname'],
                                       'lastname' => $address_book['lastname'],
                                       'company' => $address_book['company'],
                                       'street_address' => $address_book['street_address'],
                                       'suburb' => $address_book['suburb'],
                                       'city' => $address_book['city'],
                                       'postcode' => $address_book['postcode'],
                                       'state' => (!empty($address_book['state'])) ? $address_book['state'] : $address_book['zone_name'],
                                       'zone_id' => $address_book['zone_id'],
                                       'zone_code' => $address_book['zone_code'],
                                       'country_id' => $address_book['country_id'],
                                       'country_title' => $address_book['countries_name'],
                                       'country_iso_code_2' => $address_book['countries_iso_code_2'],
                                       'country_iso_code_3' => $address_book['countries_iso_code_3'],
                                       'format' => $address_book['address_format'],
                                       'telephone_number' => $address_book['telephone'],
                                       'fax' => $address_book['fax']);

        if ($address_changed === FALSE)
        {
            $this->calculate();
        }
        else
        {
            $this->save_session();
        }
    }

    /**
     * Set raw billing address
     * 
     * @access public
     * @return void
     */
    function set_raw_billing_address($data)
    {
        $this->ci->load->model('address_book_model');

        $country = $this->ci->address_book_model->get_country_data($data['country_id']);
        $state = isset($data['state']) ? $data['state'] : '';
        $zone_code = '';
        $zone_id = isset($data['zone_id']) ? $data['zone_id'] : 0;

        if (!empty($zone_id)) {
            $zone = $this->ci->address_book_model->get_zone_data($zone_id);

            $state = (!empty($state)) ? $state : $zone['zone_name'];
            $zone_code = $zone['zone_code'];
            $zone_name = $zone['zone_name'];
        }

        $this->billing_address = array( 'id' => -1,
                                        'email_address' => $data['email_address'],
                                        'checkout_method' => $data['checkout_method'],
        								'password' => $data['password'],
                                        'gender' => $data['gender'],
                                        'firstname' => $data['firstname'],
                                        'lastname' => $data['lastname'],
                                        'company' => $data['company'],
                                        'street_address' => $data['street_address'],
                                        'suburb' => $data['suburb'],
                                        'city' => $data['city'],
                                        'postcode' => $data['postcode'],
                                        'state' => $state,
                                        'zone_id' => $zone_id,
                                        'zone_code' => $zone_code,
                                        'country_id' => $data['country_id'],
                                        'country_title' => $country['countries_name'],
                                        'country_iso_code_2' => $country['countries_iso_code_2'],
                                        'country_iso_code_3' => $country['countries_iso_code_3'],
                                        'format' => $country['address_format'],
                                        'telephone_number' => $data['telephone'],
                                        'fax' => $data['fax'],
                                        'ship_to_this_address' => $data['ship_to_this_address'],
                                        'create_billing_address' => $data['create_billing_address']);

        $this->calculate();
    }

    /**
     * Get billing address
     * 
     * @access public
     * @return void
     */
    function get_billing_address($key = '')
    {
        if (empty($key)) {
            return $this->billing_address;
        }

        return $this->billing_address[$key];
    }

    /**
     * Reset billing address
     * 
     * @access public
     * @return void
     */
    function reset_billing_address()
    {
        $this->billing_address = array('zone_id' => config('STORE_ZONE'), 'country_id' => config('STORE_COUNTRY'));

        if ($this->ci->customer->is_logged_on() && $this->ci->customer->has_default_address())
        {
            $this->set_billing_address($this->ci->customer->get_default_address_id());
        }
    }

    /**
     * Set billing method
     * 
     * @access public
     * @return void
     */
    function set_billing_method($billing_array)
    {
        $this->billing_method = $billing_array;

        $this->calculate();
    }

    /**
     * Get billing method
     * 
     * @access public
     * @return void
     */
    function get_billing_method($key = '')
    {
        if (empty($key))
        {
            return $this->billing_method;
        }

        if (isset($this->billing_method[$key]))
        {
            return $this->billing_method[$key];
        }
    
        return NULL;
    }

    /**
     * Reset billing method
     * 
     * @access public
     * @return void
     */
    function reset_billing_method($calculate = TRUE)
    {
        $this->billing_method = array();

        if ($calculate == TRUE)
        {
            $this->calculate();
        }
    }

    /**
     * Has billing method
     * 
     * @access public
     * @return boolean
     */
    function has_billing_method()
    {
        return is_array($this->billing_method) && !empty($this->billing_method);
    }

    /**
     * Get taxing address
     * 
     * @access public
     * @return boolean
     */
    function get_taxing_address($id = '')
    {
        if ($this->get_content_type() == 'virtual')
        {
            return $this->get_billing_address($id);
        }

        return $this->get_shipping_address($id);
    }

    /**
     * Add tax amount
     * 
     * @access public
     * @param $amount
     * @return void
     */
    function add_tax_amount($amount)
    {
        $this->tax += $amount;
    }

    /**
     * Get shopping cart tax
     * 
     * @access public
     * @return float
     */
    function get_tax()
    {
        return $this->tax;
    }

    /**
     * Get number of tax groups
     * 
     * @access public
     * @return void
     */
    function number_of_tax_groups()
    {
        return count($this->tax_groups);
    }

    /**
     * Add tax group
     * 
     * @access public
     * @param $group
     * @param $amount
     * @return void
     */
    function add_tax_group($group, $amount)
    {
        if (isset($this->tax_groups[$group]))
        {
            $this->tax_groups[$group] += $amount;
        } else {
            $this->tax_groups[$group] = $amount;
        }
    }

    /**
     * Add to total 
     * 
     * @access public
     * @param $amount
     * @return void
     */
    function add_to_total($amount) {
        $this->total += $amount;
    }

    /**
     * Get order total modules
     * 
     * @access public
     * @param $code
     * @return void
     */
    function get_order_totals($code = NULL) {
        if ($code != NULL) {
            if (is_array($this->order_totals)) {
                foreach ($this->order_totals as $total) {
                    if ($total['code'] == $code) {
                        return $total;
                    }
                }
            }

            //if specific order total is not there then return NULL
            return NULL;
        }

        return $this->order_totals;
    }

    /**
     * Clean up shopping cart
     */
    function clean_up()
    {
        foreach ($this->contents as $product_id_string => $data)
        {
            if ($data['quantity'] < 1)
            {
                unset($this->contents[$product_id_string]);

                // remove from database
                if ($this->ci->customer->is_logged_on())
                {
                    $this->ci->shopping_cart_model->delete_content($this->ci->customer->get_id(), $product_id_string);
                }
            }
        }
    }

    /**
     * Caculate Shopping Cart
     *
     * @access public
     * @param $set_shipping
     * @return void
     */
    function calculate($set_shipping = TRUE)
    {
        $this->sub_total = 0;
        $this->total = 0;
        $this->weight = 0;
        $this->tax = 0;
        $this->tax_groups = array();
        $this->shipping_boxes_weight = 0;
        $this->shipping_boxes = 0;
        $this->shipping_quotes = array();
        $this->order_totals = array();

        $this->ci->load->library('weight');
        $this->ci->load->library('tax');
        
        //generate temp cart id
        $cart_id = $this->generate_cart_id();
        $this->ci->session->set_userdata('cart_id', $cart_id);

        if ($this->has_contents())
        {
            foreach ($this->contents as $data)
            {
                if($data['type'] == PRODUCT_TYPE_SIMPLE)
                {
                    $products_weight = $this->ci->weight->convert($data['weight'], $data['weight_class_id'], config('SHIPPING_WEIGHT_UNIT'));
                    $this->weight += $products_weight * $data['quantity'];
                }

                $tax = $this->ci->tax->get_tax_rate($data['tax_class_id'], $this->get_taxing_address('country_id'), $this->get_taxing_address('zone_id'));
                $tax_description = $this->ci->tax->get_tax_rate_description($data['tax_class_id'], $this->get_taxing_address('country_id'), $this->get_taxing_address('zone_id'));

                $shown_price = $this->ci->currencies->add_tax_rate_to_price($data['final_price'], $tax, $data['quantity']);

                $this->sub_total += $shown_price;
                $this->total += $shown_price;

                if (config('DISPLAY_PRICE_WITH_TAX') == '1')
                {
                    $tax_amount = $shown_price - ($shown_price / (($tax < 10) ? '1.0' . str_replace('.', '', $tax) : '1.' . str_replace('.', '', $tax)));
                }
                else
                {
                    $tax_amount = ($tax / 100) * $shown_price;

                    $this->total += $tax_amount;
                }

                $this->tax += $tax_amount;

                if (isset($this->tax_groups[$tax_description]))
                {
                    $this->tax_groups[$tax_description] += $tax_amount;
                } 
                else 
                {
                    $this->tax_groups[$tax_description] = $tax_amount;
                }
            }

            $this->shipping_boxes_weight = $this->weight;
            $this->shipping_boxes = 1;

            if (config('SHIPPING_BOX_WEIGHT') >= ($this->shipping_boxes_weight * config('SHIPPING_BOX_PADDING') / 100))
            {
                $this->shipping_boxes_weight = $this->shipping_boxes_weight + config('SHIPPING_BOX_WEIGHT');
            }
            else
            {
                $this->shipping_boxes_weight = $this->shipping_boxes_weight + ($this->shipping_boxes_weight * config('SHIPPING_BOX_PADDING')/100);
            }

            if ($this->shipping_boxes_weight > config('SHIPPING_MAX_WEIGHT'))
            { // Split into many boxes
                $this->shipping_boxes = ceil($this->shipping_boxes_weight / config('SHIPPING_MAX_WEIGHT'));
                $this->shipping_boxes_weight = $this->shipping_boxes_weight / $this->shipping_boxes;
            }

            if ($set_shipping === TRUE)
            {
                if (!$this->is_virtual_cart())
                {
                    $this->ci->load->library('shipping');
                    if (!$this->has_shipping_method() || ($this->get_shipping_method('is_cheapest') === TRUE))
                    {
                        $this->set_shipping_method($this->ci->shipping->get_cheapest_quote(), FALSE);
                    }
                    else
                    {
                        $this->set_shipping_method($this->ci->shipping->get_quote($this->get_shipping_method('id')), FALSE);
                    }
                }
                else
                {
                    //reset shipping address and shipping method
                    $this->shipping_address = array();
                    $this->shipping_method = array();
                }
            }

            $this->ci->load->library('order_total');
            $this->order_totals = $this->ci->order_total->get_result();
        }

        $this->save_session();
    }
    
    /**
     * Get contents
     * 
     * @access public
     * @return array
     */
    function get_contents()
    {
        return $this->contents;
    }
    
    /**
     * Get tax groups
     * 
     * @access public
     * @return array
     */
    function get_tax_groups()
    {
        return $this->tax_groups;
    }

    /**
     * Sort products by date added
     * 
     * @access public
     * @param $a
     * @param $b
     * @return boolean
     */
    function _uasort_products_by_date_added($a, $b) 
    {
        if ($a['date_added'] == $b['date_added']) {
            return strnatcasecmp($a['name'], $b['name']);
        }

        return ($a['date_added'] > $b['date_added']) ? -1 : 1;
    }
}

/* End of file shopping_cart.php */
/* Location: ./system/tomatocart/libraries/shopping_cart.php */