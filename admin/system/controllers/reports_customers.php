<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Reports Customers Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Reports_Customers extends TOC_Controller
{
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
// ------------------------------------------------------------------------
    
    /**
     * List the best orders
     *
     * @access public
     * @return string
     */
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
        if ($orders != NULL)
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
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->best_orders_model->get_total($start_date, $end_date),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * List the orders total
     *
     * @access public
     * @return string
     */
    public function list_orders_total()
    {
        $this->load->model('orders_total_model');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        $start_date = $this->input->get_post('start_date');
        $end_date = $this->input->get_post('end_date');
        
        $orders = $this->orders_total_model->get_orders($start_date, $end_date, $start, $limit);
        
        $records = array();
        if ($orders != NULL)
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
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->orders_total_model->get_total($start_date, $end_date),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
}

/* End of file reports_customers.php */
/* Location: ./system/controllers/reports_customers.php */