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
 * @filesource ./system/modules/access/unit_classes.php
 */ 

  class TOC_Access_Unit_Classes extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'unit_classes';
      $this->_group = 'definitions';
      $this->_icon = 'unit.png';
      $this->_sort_order = 600;
      
      $this->_title = lang('access_unit_classes_title');
    }
  }
  
/* End of file unit_classes.php */
/* Location: ./system/modules/access/unit_classes.php */