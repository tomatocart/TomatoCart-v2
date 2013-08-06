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
 * @filesource ./system/modules/access/newsletters.php
 */ 

  class TOC_Access_Newsletters extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'newsletters';
      $this->_group = 'tools';
      $this->_icon = 'email_send.png';
      $this->_sort_order = 1100;
      
      $this->_title = lang('access_newsletters_title');
    }
  }
  
/* End of file newsletters.php */
/* Location: ./system/modules/access/newsletters.php */