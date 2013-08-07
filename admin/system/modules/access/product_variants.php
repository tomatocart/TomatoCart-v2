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
 * @filesource ./system/modules/access/products_variants.php
 */ 

  class TOC_Access_Product_Variants extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'product_variants';
      $this->_group = 'content';
      $this->_icon = 'run.png';
      $this->_sort_order = 300;
      
      $this->_title = lang('access_product_variants_title');
    }
  }
  
/* End of file products_variants.php */
/* Location: ./system/modules/access/products_variants.php */
