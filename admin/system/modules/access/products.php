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

  class TOC_Access_Products extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'products';
      $this->_group = 'content';
      $this->_icon = 'products.png';
      $this->_sort_order = 200;
      
      $this->_title = lang('access_products_title');
      
      $this->_subgroups = array(array('iconCls' => 'icon-products-win',
                                      'shortcutIconCls' => 'icon-products-shortcut',
                                      'title' => lang('access_products_title'),
                                      'identifier' => 'products-win'));
    
    }
  }
  
/* End of file products.php */
/* Location: ./system/modules/access/products.php */
