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
 * @filesource ./system/modules/access/zone_groups.php
 */ 

  class TOC_Access_Zone_Groups extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'zone_groups';
      $this->_group = 'definitions';
      $this->_icon = 'relationships.png';
      $this->_sort_order = 400;
      
      $this->_title = lang('access_zone_groups_title');
    }
  }
  
/* End of file zone_groups.php */
/* Location: ./system/modules/access/zone_groups.php */
