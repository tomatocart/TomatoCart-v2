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
 * @filesource
 */ 

  class TOC_Access_Slide_Images extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'slide_images';
      $this->_group = 'articles';
      $this->_icon = 'image.png';
      $this->_sort_order = 400;
      
      $this->_title = lang('access_slide_images_title');
    }
  }
  
/* End of file slide_images.php */
/* Location: ./system/modules/access/slide_images.php */