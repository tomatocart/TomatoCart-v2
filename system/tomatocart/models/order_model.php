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
 * Order_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-departments-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Order_Model extends CI_Model
{

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert Order
     *
     * @access public
     * @return boolean
     */
    public function insert_order($order_status = NULL)
    {
        //order data
        $data = array(
            'customers_id' => $this->customer->get_id(),
            'customers_name' => $this->customer->get_name(),
            'customers_company' => '',
            'customers_street_address' => '',
            'customers_suburb' => '',
            'customers_city' => '',
            'customers_postcode' => '',
            'customers_state' => '',
            'customers_state_code' => '',
            'customers_country_iso2' => '',
            'customers_country_iso3' => '',
            'customers_telephone' => '',
            'customers_email_address' => $this->customer->get_email_address(),
            'customers_comment' => $this->session->userdata('payment_comments'),
            'customers_address_format' => '',
            'customers_ip_address' => $this->input->ip_address(),
            'delivery_name' => $this->shopping_cart->get_shipping_address('firstname') . ' ' . $this->shopping_cart->get_shipping_address('lastname'),
            'delivery_company' => $this->shopping_cart->get_shipping_address('company'),
            'delivery_street_address' => $this->shopping_cart->get_shipping_address('street_address'),
            'delivery_suburb' => $this->shopping_cart->get_shipping_address('suburb'),
            'delivery_city' => $this->shopping_cart->get_shipping_address('city'),
            'delivery_postcode' => $this->shopping_cart->get_shipping_address('postcode'),
            'delivery_state' => $this->shopping_cart->get_shipping_address('state'),
            'delivery_zone_id' => $this->shopping_cart->get_shipping_address('zone_id'),
            'delivery_state_code' => $this->shopping_cart->get_shipping_address('zone_code'),
            'delivery_country_id' => $this->shopping_cart->get_shipping_address('country_id'),
            'delivery_country' => $this->shopping_cart->get_shipping_address('country_title'),
            'delivery_country_iso2' => $this->shopping_cart->get_shipping_address('country_iso_code_2'),
            'delivery_country_iso3' => $this->shopping_cart->get_shipping_address('country_iso_code_3'),
            'delivery_address_format' => $this->shopping_cart->get_shipping_address('format'),
            'delivery_telephone' => $this->shopping_cart->get_shipping_address('telephone_number'),
            'billing_name' => $this->shopping_cart->get_billing_address('firstname') . ' ' . $this->shopping_cart->get_billing_address('lastname'),
            'billing_company' => $this->shopping_cart->get_billing_address('company'),
            'billing_street_address' => $this->shopping_cart->get_billing_address('street_address'),
            'billing_suburb' => $this->shopping_cart->get_billing_address('suburb'),
            'billing_city' => $this->shopping_cart->get_billing_address('city'),
            'billing_postcode' => $this->shopping_cart->get_billing_address('postcode'),
            'billing_state' => $this->shopping_cart->get_billing_address('state'),
            'billing_zone_id' => $this->shopping_cart->get_billing_address('zone_id'),
            'billing_state_code' => $this->shopping_cart->get_billing_address('zone_code'),
            'billing_country_id' => $this->shopping_cart->get_billing_address('country_id'),
            'billing_country' => $this->shopping_cart->get_billing_address('country_title'),
            'billing_country_iso2' => $this->shopping_cart->get_billing_address('country_iso_code_2'),
            'billing_country_iso3' => $this->shopping_cart->get_billing_address('country_iso_code_3'),
            'billing_address_format' => $this->shopping_cart->get_billing_address('format'),
            'billing_telephone' => $this->shopping_cart->get_billing_address('telephone_number'),
            'payment_method' => $this->shopping_cart->get_billing_method('title'),
            'payment_module' => $this->shopping_cart->get_billing_method('id'),
            'uses_store_credit' => 0,
            'store_credit_amount' => 0,
            'orders_status' => ($order_status === NULL) ? config('DEFAULT_ORDERS_STATUS_ID') : $order_status,
            'currency' => $this->currencies->get_code(),
            'currency_value' => $this->currencies->value($this->currencies->get_code()),
            'gift_wrapping' => '0',
            'wrapping_message' => '');

        $this->db->insert('orders', $data);

        //get insert id
        $insert_id = $this->db->insert_id();

        //insert order totals
        $order_totals = $this->shopping_cart->get_order_totals();
        foreach ($order_totals as $total) {
            $data = array(
              	'orders_id' => $insert_id, 
              	'title' => $total['title'], 
              	'text' => $total['text'], 
              	'value' => $total['value'], 
              	'class' => $total['code'], 
              	'sort_order' => $total['sort_order']);

            $this->db->insert('orders_total', $data);
        }

        //insert comment
        $data = array('orders_id' => $insert_id, 'orders_status_id' => config('DEFAULT_ORDERS_STATUS_ID'), 'customer_notified' => 0, 'comments' => $this->session->userdata('payment_comments'), 'date_added' => date('Y-m-d H:i:s',now()));
        $this->db->insert('orders_status_history', $data);

        //products
        $products = $this->shopping_cart->get_products();
        foreach($products as $product) {
            $data = array(
                'orders_id' => $insert_id,
                'products_id' => get_product_id($product['id']),
                'products_type' => $product['type'],
                'products_sku' => $product['sku'],
                'products_name' => $product['name'],
                'products_price' => $product['price'],
                'final_price' => $product['final_price'],
                'products_tax' => get_tax_rate($product['tax_class_id'], $this->shopping_cart->get_taxing_address('country_id'), $this->shopping_cart->get_taxing_address('zone_id')),
                'products_quantity' => $product['quantity']);

            $this->db->insert('orders_products', $data);

            //variants
            $order_products_id = $this->db->insert_id();

            if ($this->shopping_cart->has_variants($product['id']))
            {
                foreach ($this->shopping_cart->get_variants($product['id']) as $variants_id => $variants)
                {
                    $result = $this->db->select('pvg.products_variants_groups_name, pvv.products_variants_values_name')
                    ->from('products_variants pv')
                    ->join('products_variants_entries pve', 'pv.products_variants_id = pve.products_variants_id', 'inner')
                    ->join('products_variants_groups pvg', 'pve.products_variants_groups_id = pvg.products_variants_groups_id', 'inner')
                    ->join('products_variants_values pvv', 'pve.products_variants_values_id = pvv.products_variants_values_id', 'inner')
                    ->where('pv.products_id', $product['id'])
                    ->where('pve.products_variants_groups_id', $variants['groups_id'])
                    ->where('pve.products_variants_values_id', $variants['variants_values_id'])
                    ->where('pvg.language_id', lang_id())
                    ->where('pvv.language_id', lang_id())
                    ->get();

                    if ($result->num_rows() > 0)
                    {
                        $row = $result->row_array();

                        $data = array(
                            'orders_id' => $insert_id,
                            'orders_products_id' => $order_products_id,
                            'products_variants_groups_id' => $variants['groups_id'],
                            'products_variants_groups' => $row['products_variants_groups_name'],
                            'products_variants_values_id' => $variants['variants_values_id'],
                            'products_variants_values' => $row['products_variants_values_name']);

                        $this->db->insert('orders_products_variants', $data);
                    }
                }
            }
        }

        return $insert_id;
    }

    /**
     * Update order status
     *
     * @access public
     * @param $orders_id
     * @param $status_id
     * @param $comments
     */
    public function update_order_status($orders_id, $status_id = '', $comments)
    {
        //insert order history
        $data = array(
        	'orders_id' => $orders_id, 
        	'orders_status_id' => $status_id, 
        	'customer_notified' => (config('SEND_EMAILS') == '1') ? '1' : '0', 
        	'comments' => $comments);

        $this->db->insert('orders_status_history', $data);

        //update orders status
        $this->db->where('orders_id', $orders_id);
        $this->db->update('orders', array('orders_status' => $status_id));
    }

    /**
     * Get orders
     *
     * @access public
     * @param $customers_id
     * @return array
     */
    public function get_orders($customers_id)
    {
        $result = $this->db->select('o.orders_id, o.date_purchased, o.delivery_name, o.delivery_country, o.billing_name, o.billing_country, ot.text as order_total, o.orders_status, s.orders_status_name, s.returns_flag')
        ->from('orders o')
        ->join('orders_total ot', 'o.orders_id = ot.orders_id and ot.class = "total"', 'inner')
        ->join('orders_status s', 'o.orders_status = s.orders_status_id', 'inner')
        ->where('o.customers_id', $customers_id)
        ->where('s.language_id', lang_id())
        ->order_by('orders_id')->get();

        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }

    /**
     * Get number of products
     *
     * @access public
     * @param $orders_id
     * @return int
     */
    function number_of_products($orders_id = null)
    {
        $result = $this->db->select('count(*) as total')->from('orders_products')->where('orders_id', $orders_id)->get();

        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return $row['total'];
        }

        return 0;
    }

    /**
     *
     * @param $orders_id
     */
    public function get_order_status_id($orders_id)
    {
        $result = $this->db->select('orders_status')->from('orders')->where('orders_id', $orders_id)->get();

        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return $row['orders_status'];
        }

        return NULL;
    }

    /**
     * Get order status history
     *
     * @access public
     */
    public function get_order_status_history($orders_id)
    {
        $result = $this->db->select('*')->from('orders_status_history')->where('orders_id', $orders_id)->get();

        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }

    /**
     * Remove order
     *
     * @param $orders_id
     */
    public function remove($orders_id)
    {
        $status = $this->get_order_status_id($orders_id);

        if ($status === config('ORDERS_STATUS_PREPARING'))
        {
            $this->db->delete('orders_products_variants', array('orders_id' => $orders_id));

            $this->db->delete('orders_products', array('orders_id' => $orders_id));

            $this->db->delete('orders_status_history', array('orders_id' => $orders_id));

            $this->db->delete('orders_total', array('orders_id' => $orders_id));

            $this->db->delete('orders', array('orders_id' => $orders_id));
        }

        $this->session->unset_userdata('pre_order_id');
    }

    /**
     * Get order data
     *
     * @access public
     * @param $orders_id
     * @return array
     */
    public function query($orders_id)
    {
        $data = array();

        $result = $this->db->select('*')->from('orders')->where('orders_id', $orders_id)->get();
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            //order totals
            $result = $this->db->select('title, text, class')->from('orders_total')->where('orders_id', $orders_id)->order_by('sort_order')->get();
            if ($result->num_rows() > 0)
            {
                $totals = array();
                foreach($result->result_array() as $row)
                {
                    $totals[] = array('title' => $row['title'], 'text' => $row['text']);

                    if ( strpos($row['class'], 'shipping') !== false ) {
                        $shipping_method_string = strip_tags($row['title']);

                        if (substr($shipping_method_string, -1) == ':') {
                            $shipping_method_string = substr($row['title'], 0, -1);
                        }

                        $data['shipping_method_string'] = $shipping_method_string;
                    }

                    if ($row['class'] == 'total') {
                        $data['order_total_string'] = strip_tags($row['text']);
                    }
                }

                $data['totals'] = $totals;
            }

            //status
            $result = $this->db->select('*')->from('orders_status')->where('orders_status_id', $data['orders_status'])->where('language_id', lang_id())->get();
            if ($result->num_rows() > 0)
            {
                $row = $result->row_array();
                $data['orders_status_name'] = $row['orders_status_name'];
            }

            //status history
            $result = $this->db->select('os.orders_status_name, osh.date_added, osh.comments')
            ->from('orders_status os')
            ->join('orders_status_history osh', 'osh.orders_status_id = os.orders_status_id', 'inner')
            ->where('osh.orders_id', $orders_id)
            ->where('os.language_id', lang_id())
            ->where('os.public_flag', 1)
            ->order_by('osh.date_added desc')->get();

            if ($result->num_rows() > 0)
            {
                $data['status_history'] = $result->result_array();
            }

            //info
            $data['info'] =
            array('currency' => $data['currency'],
                      'currency_value' => $data['currency_value'],
                      'payment_method' => $data['payment_method'],
                      'date_purchased' => $data['date_purchased'],
                      'orders_status_id' => $data['orders_status'],
                      'orders_status' => $data['orders_status_name'],
                      'last_modified' => $data['last_modified'],
                      'total' => $data['order_total_string'],
                      'shipping_method' => $data['shipping_method_string'],
                      'tracking_no' => $data['tracking_no'],
                      'wrapping_message' => $data['wrapping_message']);

            //customer
            $data['customer'] =
            array('id' => $data['customers_id'],
                          'name' => $data['customers_name'],
                          'company' => $data['customers_company'],
                          'street_address' => $data['customers_street_address'],
                          'suburb' => $data['customers_suburb'],
                          'city' => $data['customers_city'],
                          'postcode' => $data['customers_postcode'],
                          'state' => $data['customers_state'],
                          'zone_code' => $data['customers_state_code'],
                          'countries_name' => $data['customers_country'],
                          'country_iso2' => $data['customers_country_iso2'],
                          'country_iso3' => $data['customers_country_iso3'],
                          'format' => $data['customers_address_format'],
                          'telephone' => $data['customers_telephone'],
                          'email_address' => $data['customers_email_address']);

            //delivery
            $data['delivery'] =
            array('name' => $data['delivery_name'],
                        'company' => $data['delivery_company'],
                        'street_address' => $data['delivery_street_address'],
                        'suburb' => $data['delivery_suburb'],
                        'city' => $data['delivery_city'],
                        'postcode' => $data['delivery_postcode'],
                        'state' => $data['delivery_state'],
                        'zone_code' => $data['delivery_state_code'],
                        'countries_name' => $data['delivery_country'],
                        'country_iso2' => $data['delivery_country_iso2'],
                        'country_iso3' => $data['delivery_country_iso3'],
                        'format' => $data['delivery_address_format']);

            if (empty($data['delivery']['name']) && empty($data['delivery']['street_address'])) {
                $data['delivery'] = FALSE;
            }

            //billing
            $data['billing'] = array('name' => $data['billing_name'],
                             'company' => $data['billing_company'],
                             'street_address' => $data['billing_street_address'],
                             'suburb' => $data['billing_suburb'],
                             'city' => $data['billing_city'],
                             'postcode' => $data['billing_postcode'],
                             'state' => $data['billing_state'],
                             'zone_code' => $data['billing_state_code'],
                             'countries_name' => $data['billing_country'],
                             'country_iso2' => $data['billing_country_iso2'],
                             'country_iso3' => $data['billing_country_iso3'],
                             'format' => $data['billing_address_format']);

            //products
            $result = $this->db->select('orders_products_id, products_id, products_type, products_name, products_sku, products_price, products_tax, products_quantity, final_price')->from('orders_products')->where('orders_id', $orders_id)->get();
            if ($result->num_rows() > 0)
            {
                $products = array();
                foreach($result->result_array() as $row)
                {
                    $product = array('id' => $row['products_id'],
                                     'orders_products_id' => $row['orders_products_id'],
                                     'type' => $row['products_type'],
                                     'qty' => $row['products_quantity'],
                                     'name' => $row['products_name'],
                                     'sku' => $row['products_sku'],
                                     'tax' => $row['products_tax'],
                                     'price' => $row['products_price'],
                                     'final_price' => $row['final_price']);

                    //variants
                    $result = $this->db->select('products_variants_groups_id as groups_id, products_variants_groups as groups_name, products_variants_values_id as values_id, products_variants_values as values_name')
                    ->from('orders_products_variants')
                    ->where('orders_id', $orders_id)
                    ->where('orders_products_id', $row['orders_products_id'])->get();

                    if ($result->num_rows() > 0)
                    {
                        $product['variants'] = $result->result_array();
                    }

                    $products[] = $product;

                    $data['info']['tax_groups'][$row['products_tax']] = '1';
                }

                $data['products'] = $products;
            }
        }

        return $data;
    }

    /**
     * Get order total
     *
     * @access public
     * @return
     */
    public function get_order_total ($orders_id)
    {
        //status history
        $result = $this->db->select('value')->from('orders_total')->where('orders_id', $orders_id)->where('class', 'total')->limit(1)->get();

        if ($result->num_rows() > 0)
        {
            $row = $this->db->row_array();

            return $row['value'];
        }

        return NULL;
    }
}

/* End of file order_model.php */
/* Location: ./system/tomatocart/models/order_model.php */