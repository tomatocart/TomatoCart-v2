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
 */ 

  class TOC_Access_Faqs extends TOC_Access {
    public function __construct()
    {
        parent::__construct();
        
        $this->_module = 'faqs';
        $this->_group = 'articles';
        $this->_icon = 'page.png';
        $this->_sort_order = 300;
        
        $this->_title = lang('access_faqs_title');
    }
  }
  
/* End of file faqs.php */
/* Location: ./system/modules/access/faqs.php */