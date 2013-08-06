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
 * @filesource system/modules/access/reports_products.php
 */

  class TOC_Access_Reports_Products extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'reports_products';
      $this->_group = 'reports';
      $this->_icon = 'products.png';
      $this->_sort_order = 100;
      
      $this->_title = lang('access_reports_products_title');
      
      $this->_subgroups = array(array('iconCls' => 'icon-reports-products-purchased-win',
                                      'shortcutIconCls' => 'icon-reports-products-purchased-shortcut',
                                      'title' => lang('access_products_purchased_title'),
                                      'identifier' => 'reports_products-purchased-win',
                                      'params' => array('report' => 'products-purchased')),
                                array('iconCls' => 'icon-reports-products-viewed-win',
                                      'shortcutIconCls' => 'icon-reports-products-viewed-shortcut',
                                      'title' => lang('access_products_viewed_title'),
                                      'identifier' => 'reports_products-viewed-win',
                                      'params' => array('report' => 'products-viewed')),
                                array('iconCls' => 'icon-reports-products-categories-win',
                                      'shortcutIconCls' => 'icon-reports-products-categories-shortcut',
                                      'title' => lang('access_categories_purchased_title'),
                                      'identifier' => 'reports_products-categories-purchased-win',
                                      'params' => array('report' => 'categories-purchased')),
                                array('iconCls' => 'icon-reports-products-low-stock-win',
                                      'shortcutIconCls' => 'icon-reports-products-low-stock-shortcut',
                                      'title' => lang('access_low_stock_title'),
                                      'identifier' => 'reports_products-low-stock-win',
                                      'params' => array('report' => 'low-stock')));
    }
  }

/* End of file reports_products.php */
/* Location: system/modules/access/reports_products.php */