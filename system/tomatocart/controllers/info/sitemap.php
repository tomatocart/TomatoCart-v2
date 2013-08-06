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
 * Sitemap Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-info-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Sitemap extends TOC_Controller {
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Default Function
     *
     * @access public
     */
    public function index()
    {
        //set page title
        $this->set_page_title(lang('info_sitemap_heading'));

        //breadcrumb
        $this->template->set_breadcrumb(lang('breadcrumb_sitemap'), site_url('info/sitemap'));

        //load library
        $this->load->library('category_tree');
        $this->category_tree->reset();
        $this->category_tree->set_show_category_product_count(FALSE);

        //setup view data
        $data['category_tree'] = $this->category_tree->build_tree();

        //setup view
        $this->template->build('info/sitemap', $data);
    }
}

/* End of file sitemap.php */
/* Location: ./system/tomatocart/controllers/info/sitemap.php */