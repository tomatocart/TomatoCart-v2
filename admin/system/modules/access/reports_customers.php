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
 * @filesource system/modules/access/reports_customers.php
 */

  class TOC_Access_Reports_Customers extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'reports_customers';
      $this->_group = 'reports';
      $this->_icon = 'money.png';
      $this->_sort_order = 200;
      
      $this->_title = lang('access_reports_customers_title');
      
      $this->_subgroups = array(array('iconCls' => 'icon-reports-customers-purchased-win',
                                      'shortcutIconCls' => 'icon-reports-customers-purchased-shortcut',
                                      'title' => lang('access_best_orders_title'),
                                      'identifier' => 'reports_customers-purchased-win',
                                      'params' => array('report' => 'customers-purchased')),
                                array('iconCls' => 'icon-reports-customers-orders-total-win',
                                      'shortcutIconCls' => 'icon-reports-customers-orders-total-shortcut',
                                      'title' => lang('access_orders_total_title'),
                                      'identifier' => 'reports_customers-orders-total-win',
                                      'params' => array('report' => 'orders-total')));
    }
  }

/* End of file reports_customers.php */
/* Location: system/modules/access/reports_customers.php */