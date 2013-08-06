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
 * @filesource ./system/modules/access/feature_products_manager.php
 */ 

  class TOC_Access_Feature_Products_Manager extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'feature_products_manager';
      $this->_group = 'content';
      $this->_icon = 'home.png';
      $this->_sort_order = 1200;
      
      $this->_title = lang('access_feature_product_manager_title');
    }
  }
  
/* End of file feature_products_manager.php */
/* Location: ./system/modules/access/feature_products_manager.php */