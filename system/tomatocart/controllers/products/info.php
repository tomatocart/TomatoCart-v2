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
 * Info Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-products-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Info extends TOC_Controller {
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
     */
    public function index($id, $manufacturers_id = FALSE, $tab = FALSE)
    {
        $id = $this->products_model->parse_products_id($id);
        
        if ($id != NULL)
        {
            $this->load->library('product', $id, 'product_' . $id);
            $product = $this->{'product_' . $id};

            //if product is found
            if ($product->is_valid())
            {
                $this->products_model->increment_counter($id);

                //get data
                $data = $product->get_data();

                //set page title & page meta information
                $this->set_page_title($data['name']);
                $this->template->add_meta_tags('keywords', $data['name']);
                $this->template->add_meta_tags('keywords', $data['sku']);

                if ($product->has_page_title())
                {
                    $this->template->set_title($product->get_page_title());
                }

                if ($product->has_tags())
                {
                    $this->template->add_meta_tags('keywords', $product->has_tags());
                }

                if ($product->has_meta_keywords())
                {
                    $this->template->add_meta_tags('keywords', $product->get_meta_keywords());
                }

                if ($product->has_meta_description())
                {
                    $this->template->add_meta_tags('description', $product->get_meta_description());
                }

                //manufactureres_id
                if (($manufacturers_id !== FALSE) && is_numeric($manufacturers_id))
                {
                    $this->load->model('manufacturer_model');
                    $manufacturer_data = $this->manufacturer_model->get_data();

                    $this->template->set_breadcrumb($manufacturer_data['title'], site_url('manufacturer/' . $manufacturer_data['id']));
                    $this->template->set_breadcrumb($data['name'], site_url('product/' . $manufacturer_data['id']));
                }
                else
                {
                    //breadcrumb
                    $categories = $this->category_tree->get_full_cpath_info($product->get_category_id());
                    foreach ($categories as $categories_id => $categories_name)
                    {
                        $this->template->set_breadcrumb($categories_name, site_url('category/' . $categories_id));
                    }
                }

                //get variants list
                $data['combobox_array'] = $product->get_variants_combobox_array();

                //reviews
                $data['tab'] = (in_array($tab, array('description', 'reviews', 'accessories')) ? $tab : 'description');
                $data['reviews'] = $this->products_model->get_review_listing($id);
                $data['review_count'] = sizeof($data['reviews']);
                $data['ratings'] = $this->products_model->get_category_ratings($product->get_category_id());
                $data['ratings'] = $this->products_model->get_category_ratings($product->get_category_id());
                
                $data['is_specials'] = ($data['specials_price'] === NULL) ? FALSE : TRUE;
                $data['is_featured'] = ($data['featured_products_id'] === NULL) ? FALSE : TRUE;

                $this->template->build('products/info', $data);
            }
            else
            {
                $this->set_page_title(lang('product_not_found_heading'));
                $this->template->build('products/info_not_found.php');
            }
        }
        else
        {
            $this->set_page_title(lang('product_not_found_heading'));
            $this->template->build('products/info_not_found.php');
        }
    }

    /**
     * Save review
     *
     * @access public
     * @param $products_id
     * @return
     */
    public function save_review($products_id)
    {
        if (!$this->customer->is_logged_on())
        {
            redirect('login');
        }

        $data = array('products_id' => $products_id);

        $data['customer_id'] = $this->customer->get_id();
        $data['customer_name'] = $this->customer->get_name();

        $data['review'] = $this->input->post('review');
        if (($data['review'] === FALSE) || strlen(trim($data['review'])) < config('REVIEW_TEXT_MIN_LENGTH'))
        {
            $this->message_stack->add('reviews', sprintf(lang('js_review_text'), config('REVIEW_TEXT_MIN_LENGTH')), 'error');
        }

        //check rating count
        $rating_count = $this->input->post('radio_count');

        //no rating items
        if ($rating_count === null)
        {
            $data['rating'] = $this->input->post('rating');
            if (is_numeric($data['rating']))
            {
                if ( ($data['rating'] < 1) || ($data['rating'] > 5) )
                {
                    $this->message_stack->add('reviews', lang('js_review_rating'), 'error');
                    break;
                }
            }
            else
            {
                $this->message_stack->add('reviews', lang('js_review_rating'), 'error');
            }
        }
        //rating items
        else
        {
            $ratings = array();
            $posts = $this->input->post();

            foreach ($posts as $key => $value)
            {
                if (substr($key, 0, 7) == 'rating_')
                {
                    $ratings_id = substr($key, 7);
                    $ratings[$ratings_id] = $value;
                }
            }

            $data['rating'] = $ratings;

            if (count($data['rating']) != $rating_count)
            {
                $this->message_stack->add('reviews', lang('js_review_rating'));
            }
        }

        //save review entry
        if ($this->message_stack->size('reviews') === 0)
        {
            $this->load->model('reviews_model');
            $this->reviews_model->save_review($data);
            
            $this->message_stack->add_session('reviews', lang('success_review_new'), 'success');

            redirect('product/' . $products_id . '/null/reviews');
        }

        $this->index($products_id, FALSE, 'reviews');
    }
}

/* End of file info.php */
/* Location: ./system/tomatocart/controllers/products/info.php */