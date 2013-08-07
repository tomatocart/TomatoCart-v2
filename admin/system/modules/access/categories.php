<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource access/categories.php
 */ 

  class TOC_Access_Categories extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'categories';
      $this->_group = 'content';
      $this->_icon = 'folder_red.png';
      $this->_sort_order = 100;
      
      $this->_title = lang('access_categories_title');
    }
  }
  
/* End of file categories.php */
/* Location: ./system/modules/access/categories.php */
