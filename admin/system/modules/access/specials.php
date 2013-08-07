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
 * @filesource ./system/modules/access/specials.php
 */ 

  class TOC_Access_Specials extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'specials';
      $this->_group = 'content';
      $this->_icon = 'specials.png';
      $this->_sort_order = 700;
      
      $this->_title = lang('access_specials_title');
    }
  }
  
/* End of file articles.php */
/* Location: ./system/modules/access/articles.php */