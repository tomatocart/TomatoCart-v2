<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * Google Sitemap Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
*/
class Google_Sitemap extends TOC_Controller
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
		$this->load->library('google_sitemap');
	
	}
	
	public function create_google_sitemap() {
		//global $toC_Json, $osC_Language;
		
		$config = array(
				'_products_frequency'=>$this->input->get_post('products_frequency'),
				'_products_priority'=>$this->input->get_post('products_priority'),
				'_categories_frequency'=>$this->input->get_post('categories_frequency'),
				'_categories_priority'=>$this->input->get_post('categories_priority'),
				'_articles_frequency'=>$this->input->get_post('articles_frequency'),
				'_articles_frequency'=>$this->input->get_post('articles_priority')
				);
		
		$this->google_sitemap->initialize($config);
		if ($this->google_sitemap->generateSitemap()) {
			$response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
		} else {
			$response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
		}
		 
		//echo $toC_Json->encode($response);
		$this->output->set_header("Content-Type: text/html")->set_output(json_encode($response));
	}
}

/* End of file google_sitemap.php */
/* Location: ./system/controllers/google_sitemap.php */