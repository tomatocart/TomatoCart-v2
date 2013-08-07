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
 * @filesource ./system/modules/access/orders_status.php
 */ 

  class TOC_Access_Orders_Status extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'orders_status';
      $this->_group = 'definitions';
      $this->_icon = 'status.png';
      $this->_sort_order = 800;
      
      $this->_title = lang('access_orders_status_title');
    }
  }
  
/* End of file orders_status.php */
/* Location: ./system/modules/access/orders_status.php */