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
 * @filesource ./system/modules/access/homepage_info.php
 */ 

  class TOC_Access_Homepage_Info extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'homepage_info';
      $this->_group = 'configuration';
      $this->_icon = 'articles.png';
      
      $this->_sort_order = 200;
      
      $this->_title = lang('access_homepage_info_title');
    }
  }
  
/* End of file homepage_info.php */
/* Location: ./system/modules/access/homepage_info.php */