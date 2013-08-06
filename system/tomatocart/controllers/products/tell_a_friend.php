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
 * Tell A Friend Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-products-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Tell_A_Friend extends TOC_Controller {
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
	public function index() {
		$groups_modules = array(
        'header' => array('header'),
        'before' => array(),
        'after' => array('new_products_content', 'special_products_content', 'feature_products'),
        'left' => array('categories', 'manufacturers', 'information', 'new_products', 'search'),
        'right' => array('shop_by_price', 'special_products', 'article_categories', 'currencies', 'popular_search_terms'),
        'footer' =>array('footer'),
		);
		$this->template->add_modules($groups_modules);

		$data['title'] = 'SONY DSC-T700(g) DIGITAL VIDEO CAMERA';
		$data['product_name'] = 'SONY DSC-T700(g) DIGITAL VIDEO CAMERA';
		$data['product_link'] ='index.php/product/info';
		$data['product_img_src'] ='images/products/mini/dsc-t700-38324.jpg';
		$data['action'] ='products.php?tell_a_friend&amp;19&amp;action=process';
		$data['required_information_text'] ='* Required information';
		$data['your_details_text'] ='Your Details';
		$data['from_name_text'] ='Your Name:';
		$data['from_email_text'] ='Your E-Mail Address:';
		$data['to_name_text'] ='Your Friends Name:';
		$data['to_email_text'] ='Your Friends E-Mail Address:';

		$data['from_name'] ='zheng lei';
		$data['from_email'] ='zheng.lei@live.com';

		$data['continue_text'] ='Continue';
		$data['back_text'] ='Back';

		$data['message_text'] ='Your Message';





		$data['products'] = array(
		array('product_link' => 'index.php/products/info/3', 'product_name' => 'Apple iPhone 4G', 'product_image' => 'images/products/thumbnails-iphone-03923923.jpg', 'product_price' => '$1,299.00', 'special_price' => '$1,000.00'),
		array('product_link' => 'index.php/products/info/4', 'product_name' => 'Apple iPod touch 8 GB', 'product_image' => 'images/products/thumbnails-ipod-touch-392033.jpg', 'product_price' => '$1,299.00', 'special_price' => '$1,000.00'),
		array('product_link' => 'index.php/products/info/1', 'product_name' => 'SONY DSC-T700(g) DIGITAL VIDEO CAMERA', 'product_image' => 'images/products/thumbnails-dsc-t700-38324.jpg', 'product_price' => '$1,299.00', 'special_price' => '$1,000.00'),
		array('product_link' => 'index.php/products/info/2', 'product_name' => 'APPLE 23&quot; HD CINEMA COLOR DISPLAY', 'product_image' => 'images/products/thumbnails-cinema-3923823.jpg', 'product_price' => '$1,299.00', 'special_price' => '$1,000.00'),
		array('product_link' => 'index.php/products/info/2', 'product_name' => 'APPLE 23&quot; HD CINEMA COLOR DISPLAY', 'product_image' => 'images/products/thumbnails-cinema-3923823.jpg', 'product_price' => '$1,299.00', 'special_price' => '$1,000.00'),
		array('product_link' => 'index.php/products/info/3', 'product_name' => 'Apple iPhone 3G', 'product_image' => 'images/products/thumbnails-iphone-03923923.jpg', 'product_price' => '$1,299.00', 'special_price' => '$1,000.00'),
		array('product_link' => 'index.php/products/info/4', 'product_name' => 'Apple iPod touch 8 GB', 'product_image' => 'images/products/thumbnails-ipod-touch-392033.jpg', 'product_price' => '$1,299.00', 'special_price' => '$1,000.00'),
		array('product_link' => 'index.php/products/info/5', 'product_name' => 'APPLE IPOD NANO 4GB SILVER 3RD GEN', 'product_image' => 'images/products/thumbnails-ipod-nano3-392339239.jpg', 'product_price' => '$1,299.00', 'special_price' => '$1,000.00'),
		array('product_link' => 'index.php/products/info/6', 'product_name' => 'Toshiba Satellite A355-S6921', 'product_image' => 'images/products/thumbnails-l305d-s5904-39293323.jpg', 'product_price' => '$1,299.00', 'special_price' => '$1,000.00'));

		$this->template->build('products/tell_a_friend', $data);
	}
}

/* End of file tell_a_friend.php */
/* Location: ./system/tomatocart/controllers/products/tell_a_friend.php */