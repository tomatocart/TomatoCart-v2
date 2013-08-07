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
 * Order Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Order
{
    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    private $ci = NULL;

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();
        
        $this->ci->load->model('order_model');
    }

    /**
     * Create order
     *
     * @access public
     * @return boolean
     */
    public function create_order($order_status = NULL)
    {
        $pre_order_id = $this->ci->session->userdata('pre_order_id');
        if ($pre_order_id !== NULL) 
        {
            $prep = explode('-', $pre_order_id);

            if ($prep[0] == $this->ci->shopping_cart->get_cart_id()) 
            {
                return $prep[1]; // order_id
            } 
            else 
            {
                if ($this->ci->order_model->get_order_status_id($prep[1]) === ORDERS_STATUS_PREPARING) 
                {
                    $this->ci->order_model->remove($prep[1]);
                }
            }
        }
        
        //create account
        if (!$this->ci->customer->is_logged_on())
        {
            //get billing address
            $billing_address = $this->ci->shopping_cart->get_billing_address();

            $data['customers_gender'] = $billing_address['gender'];
            $data['customers_firstname'] = $billing_address['firstname'];
            $data['customers_lastname'] = $billing_address['lastname'];
            $data['customers_newsletter'] = 0;
            $data['customers_dob'] = NULL;
            $data['customers_email_address'] = $billing_address['email_address'];
            $data['customers_password'] = encrypt_password($billing_address['password']);
            $data['customers_status'] = 1;

            //load model
            $this->ci->load->model('account_model');
            $this->ci->load->model('address_book_model');

            if ($this->ci->account_model->insert($data))
            {
                //set data to session
                $this->ci->customer->set_data($data['customers_email_address']);
                
                $this->ci->address_book_model->save($billing_address, $this->ci->customer->get_id(), NULL, TRUE);
                
                //insert shipping address
                if (isset($address['ship_to_this_address']) && $address['ship_to_this_address'] == 'on') 
                {
                    $shipping_address = $this->ci->shopping_cart->get_shipping_address();
                  
                    $this->ci->address_book_model->save($shipping_address, $this->ci->customer->get_id());
                }
            }
        }
        else
        {
            //get billing address
            $billing_address = $this->ci->shopping_cart->get_billing_address();

            //if create billing address
            if (isset($billing_address['create_billing_address']) && ($billing_address['create_billing_address'] == 'on'))
            {
                $data['entry_gender'] = $billing_address['gender'];
                $data['entry_firstname'] = $billing_address['firstname'];
                $data['entry_lastname'] = $billing_address['lastname'];
                $data['entry_company'] = $billing_address['company'];
                $data['entry_street_address'] = $billing_address['street_address'];
                $data['entry_suburb'] = $billing_address['suburb'];
                $data['entry_postcode'] = $billing_address['postcode'];
                $data['entry_city'] = $billing_address['city'];
                $data['entry_country_id'] = $billing_address['country_id'];
                $data['entry_zone_id'] = $billing_address['zone_id'];
                $data['entry_telephone'] = $billing_address['telephone_number'];
                $data['entry_fax'] = $billing_address['fax'];
                $primary = $this->ci->customer->has_default_address() ? FALSE : TRUE;

                //load model
                $this->ci->load->model('address_book_model');

                //save billing address
                $this->ci->address_book_model->save($data, $this->ci->customer->get_id(), NULL, $primary);
            }

            $shipping_address = $this->ci->shopping_cart->get_shipping_address();

            //create shipping address
            if (isset($shipping_address['create_shipping_address']) && ($shipping_address['create_shipping_address'] == '1'))
            {
                $data['entry_gender'] = $shipping_address['gender'];
                $data['entry_firstname'] = $shipping_address['firstname'];
                $data['entry_lastname'] = $shipping_address['lastname'];
                $data['entry_company'] = $shipping_address['company'];
                $data['entry_street_address'] = $shipping_address['street_address'];
                $data['entry_suburb'] = $shipping_address['suburb'];
                $data['entry_postcode'] = $shipping_address['postcode'];
                $data['entry_city'] = $shipping_address['city'];
                $data['entry_country_id'] = $shipping_address['country_id'];
                $data['entry_zone_id'] = $shipping_address['zone_id'];
                $data['entry_telephone'] = $shipping_address['telephone_number'];
                $data['entry_fax'] = $shipping_address['fax'];

                //load model
                $this->ci->load->model('address_book_model');

                //save billing address
                $this->ci->address_book_model->save($data, $this->ci->customer->get_id());
            }
        }

        $this->ci->load->model('order_model');
        
        $orders_id = $this->ci->order_model->insert_order($order_status);
        $pre_order_id = $this->ci->shopping_cart->get_cart_id() . '-' . $orders_id;

        $this->ci->session->set_userdata('pre_order_id', $pre_order_id);
        
        return $orders_id;
    }

    /**
     * Process order
     *
     * @access public
     * @param $order_id
     * @param $status_id
     * @param $comments
     * @return
     */
    public function process($order_id, $status_id = '', $comments = '')
    {
        if (empty($status_id) || (is_numeric($status_id) === false)) {
            $status_id = config('DEFAULT_ORDERS_STATUS_ID');
        }

        $this->ci->order_model->update_order_status($order_id, $status_id, $comments);

        /*
        $Qproducts = $osC_Database->query('select orders_products_id, products_id, products_quantity from :table_orders_products where orders_id = :orders_id');
        $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
        $Qproducts->bindInt(':orders_id', $order_id);
        $Qproducts->execute();

        while ($Qproducts->next()) {
            osC_Product::updateStock($order_id, $Qproducts->valueInt('orders_products_id'), $Qproducts->valueInt('products_id'), $Qproducts->valueInt('products_quantity'));
        }
*/
        //osC_Order::sendEmail($order_id);

        //unset the pre order id, finish the order process
        $this->ci->session->unset_userdata('pre_order_id');
    }
}
// END Order

/* End of file order.php */
/* Location: ./system/tomatocart/libraries/order.php */