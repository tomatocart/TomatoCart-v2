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
 * @filesource ./system/modules/access/languages.php
 */ 

  class TOC_Access_Languages extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'languages';
      $this->_group = 'definitions';
      $this->_icon = 'locale.png';
      $this->_sort_order = 100;
      
      $this->_title = lang('access_languages_title');
    }
  }
  
/* End of file languages.php */
/* Location: ./system/modules/access/languages.php */