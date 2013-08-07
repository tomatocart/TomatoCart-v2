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
 * Specials Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-products-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Specials extends TOC_Controller {

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
    public function index() 
    {
        //page title
        $this->set_page_title(lang('specials'));
        
        //breadcrumb
        $this->template->set_breadcrumb(lang('specials'), site_url('specials'));

        $this->load->model('products_model');

        //get page
        $page = $this->uri->segment(2);
        $filter = array(
            'page' => (isset($page) && is_numeric($page)) ? ($page - 1) : 0,
            'per_page' => config('MAX_DISPLAY_SEARCH_RESULTS'));

        $specials = $this->products_model->get_special_products($filter);

        $products = array();
        foreach ($specials as $product) {
            $products[] = array(
                        'products_id' => $product['products_id'],
                        'product_name' => $product['products_name'],
                        'product_price' => $product['products_price'],
                        'specials_price' => $product['specials_price'],
                    	'tax_class_id' => $product['products_tax_class_id'],
                        'is_specials' => ($product['specials_price'] === NULL) ? FALSE : TRUE,
                        'is_featured' => ($product['featured_products_id'] === NULL) ? FALSE : TRUE,
                        'product_image' => $product['image'],
                        'short_description' => $product['products_short_description']);
        }
        //load pagination library
        $this->load->library('pagination');
        
        //initialize pagination parameters
        $pagination['base_url'] = site_url('specials');
        $pagination['total_rows'] = $this->products_model->count_special_products($filter);
        $pagination['per_page'] = config('MAX_DISPLAY_SEARCH_RESULTS');
        $pagination['use_page_numbers'] = TRUE;
        $pagination['uri_segment'] = 2;

        $pagination['full_tag_open'] = '<ul>';
        $pagination['full_tag_close'] = '</ul>';
        
        $pagination['first_tag_open'] = '<li>';
        $pagination['first_tag_close'] = '</li>';
        
        $pagination['last_tag_open'] = '<li>';
        $pagination['last_tag_close'] = '</li>';
        
        $pagination['cur_tag_open'] = '<li class="current"><a href="javascript:void(0);">';
        $pagination['cur_tag_close'] = '</a></li>';
        
        $pagination['next_tag_open'] = '<li>';
        $pagination['next_tag_close'] = '</li>';
        
        $pagination['prev_tag_open'] = '<li>';
        $pagination['prev_tag_close'] = '</li>';
        
        $pagination['num_tag_open'] = '<li>';
        $pagination['num_tag_close'] = '</li>';

        $this->pagination->initialize($pagination);
        $data['links'] = $this->pagination->create_links();

        $data['products'] = $products;
        $this->template->build('products/specials', $data);
    }
}

/* End of file specials.php */
/* Location: ./system/tomatocart/controllers/products/specials.php */