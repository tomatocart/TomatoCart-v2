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
 * Wishlist Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Wishlist extends TOC_Controller 
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
     * Default Function
     *
     * @access public
     * @param string
     * @return void
     */
    public function index()
    {
        //set page title
        $this->set_page_title(lang('wishlist_heading'));

        //breadcrumb
        $this->template->set_breadcrumb(lang('breadcrumb_my_account'), site_url('account'));
        $this->template->set_breadcrumb(lang('breadcrumb_wishlist'), site_url('account/wishlist'));

        //assign
        $data['products'] = $this->wishlist->get_products();
        $data['customers_name'] = $this->customer->get_name();
        $data['customers_email'] = $this->customer->get_email_address();

        $this->template->build('account/wishlist', $data);
    }

    /**
     * Add product to wishlist
     *
     * @access public
     * @param $products_id
     * @return void
     */
    public function add($products_id)
    {
        if (is_numeric($products_id))
        {
            $this->wishlist->add_product($products_id);
        }

        redirect('wishlist');
    }

    /**
     * Delete product from wishlist
     *
     * @access public
     * @param $products_id
     * @return void
     */
    public function delete($products_id)
    {
        if (is_numeric($products_id))
        {
            if ($this->wishlist->delete_product($products_id))
            {

                $this->message_stack->add_session('wishlist', lang('success_wishlist_entry_deleted'), 'success');
            }
        }

        redirect('wishlist');
    }

    /**
     * Update wishlist
     *
     * @access public
     * @return void
     */
    public function update()
    {
        $comments = $this->input->post('comments');

        if (is_array($comments) && !empty($comments))
        {
            if ($this->wishlist->update_wishlist($comments))
            {
                $this->message_stack->add_session('wishlist', lang('success_wishlist_entry_updated'), 'success');
            }
        }

        redirect('wishlist');
    }

    /**
     * Display wishlist
     *
     * @access public
     * @return void
     */
    public function display($token = NULL)
    {
        if ($token !== NULL)
        {
            $contents = $this->wishlist->get_products_by_token($token);

            $products = array();
            if ($contents != NULL)
            {
                foreach ($contents as $content)
                {
                    $product = load_product_library($content['products_id']);

                    if($product->is_valid())
                    {
                        $products[] = array('products_id' => $content['products_id'],
                                            'name' => $product->get_title(),
                                            'image' => $product->get_image(),
                                            'price' => $product->get_price(), 
      									    'date_added' => get_date_short($content['date_added']),
                                            'variants' => array(), //variants is not support 
                                            'comments' => $content['comments']);
                    }
                }


            }
        }

        $this->template->build('account/display_wishlist', array('products' => $products));
    }

    /**
     * Share wishlist
     * @access public
     * @return void
     */
    public function share()
    {
        $data = array();

        $customer = $this->input->post('wishlist_customer');
        if (isset($customer) && !empty($customer))
        {
            $data['wishlist_customer'] = $customer;
        }
        else
        {
            $this->message_stack->add('wishlist', lang('field_share_wishlist_customer_name_error'), 'error');
        }

        $email_from = $this->input->post('wishlist_from_email');
        if (isset($email_from) && !empty($email_from))
        {
            $data['wishlist_from_email'] = $email_from;
        }
        else
        {
            $this->message_stack->add('wishlist', lang('field_share_wishlist_customer_email_error'), 'error');
        }

        $email_to = $this->input->post('wishlist_emails');
        if (isset($email_to) && !empty($email_to))
        {
            $data['wishlist_emails'] = $email_to;
        }
        else
        {
            $this->message_stack->add('wishlist', lang('field_share_wishlist_emails_error'), 'error');
        }

        $message = $this->input->post('wishlist_message');
        if (isset($message) && !empty($message))
        {
            $data['wishlist_message'] = $message;
        }
        else
        {
            $this->message_stack->add('wishlist', lang('field_share_wishlist_message_error'), 'error');
        }

        if ($this->message_stack->size('wishlist') === 0)
        {
            $wishlist_url = site_url('account/wishlist/display/' . $this->wishlist->get_token());

            //send email
            $this->load->library('email_template');
            $email = $this->email_template->get_email_template('share_wishlist');
            $email->set_data($data['wishlist_customer'], $data['wishlist_from_email'], $data['wishlist_emails'], $data['wishlist_message'], $wishlist_url);
            $email->build_message();
            $email->send_email();

            redirect('wishlist');
        }
        else
        {
            //load wishlist page
            $this->index();
        }
    }
}

/* End of file wishlist.php */
/* Location: ./system/tomatocart/controllers/account/wishlist.php */