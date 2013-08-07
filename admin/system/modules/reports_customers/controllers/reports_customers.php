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
 * @filesource system/modules/reports_customers/controllers/reports_customers.php
 */

class Reports_Customers extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->library('currencies');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('best_orders_panel');
    $this->load->view('orders_total_panel');
  }
  
  public function list_best_orders()
  {
    $this->load->model('best_orders_model');
    $this->load->helper('date');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    $start_date = $this->input->get_post('start_date');
    $end_date = $this->input->get_post('end_date');
    
    $orders = $this->best_orders_model->get_orders($start_date, $end_date, $start, $limit);
    
    $records = array();
    if (!empty($orders))
    {
      foreach($orders as $order)
      {
        $records[] = array('orders_id' => $order['orders_id'],
                           'customers_id' => $order['customers_id'],
                           'customers_name' => $order['customers_name'],
                           'date_purchased' => mdate('%Y/%m/%d %H:%i:%s', human_to_unix($order['date_purchased'])),
                           'value' => (float) $order['value']);
      }
    }
    else
    {
      $records[] = array('orders_id' => 0,
                         'customers_id' => 0,
                         'customers_name' => lang('none'),
                         'date_purchased' => '',
                         'value' => 0);
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->best_orders_model->get_total($start_date, $end_date),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function list_orders_total()
  {
    $this->load->model('orders_total_model');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    $start_date = $this->input->get_post('start_date');
    $end_date = $this->input->get_post('end_date');
    
    $orders = $this->orders_total_model->get_orders($start_date, $end_date, $start, $limit);
    
    $records = array();
    if (!empty($orders))
    {
      foreach($orders as $order)
      {
        $records[] = array('orders_id' => $order['orders_id'],
                           'customers_id' => $order['customers_id'],
                           'customers_name' => $order['customers_name'],
                           'value' => (float) $order['value']);
      }
    }
    else
    {
      $records[] = array('orders_id' => 0,
                         'customers_id' => 0,
                         'customers_name' => lang('none'),
                         'value' => 0);
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->orders_total_model->get_total($start_date, $end_date),
                 EXT_JSON_READER_ROOT => $records);
  }
}



/* End of file reports_customers.php */
/* Location: system/modules/reports_customers/controllers/reports_customers.php */