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
 * Orders Status Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Orders_Status extends TOC_Controller
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
        
        $this->load->model('orders_status_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the orders status
     *
     * @access public
     * @return string
     */
    public function list_orders_status()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $statuses = $this->orders_status_model->get_orders_status($start, $limit);
        
        $records = array();
        if ($statuses !== NULL)
        {
            foreach($statuses as $status)
            {
                $orders_status_name = $status['orders_status_name'];
                
                if ($status['orders_status_id'] == DEFAULT_ORDERS_STATUS_ID)
                {
                    $orders_status_name .= ' (' . lang('default_entry') . ')';
                }
                
                $records[] = array('orders_status_id' => $status['orders_status_id'],
                                   'language_id' => lang_id(),
                                   'orders_status_name' => $orders_status_name,
                                   'public_flag' => $status['public_flag']);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->orders_status_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Set the status of the order status
     *
     * @access public
     * @return string
     */
    public function set_status()
    {
        if ($this->orders_status_model->set_status($this->input->post('orders_status_id'), $this->input->post('flag')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the orders status
     *
     * @access public
     * @return string
     */
    public function save_orders_status()
    {
        $data = array('name' => $this->input->post('name'), 
                      'public_flag' => $this->input->post('public_flag') == 'on' ? 1 : 0);
        
        if ($this->orders_status_model->save($this->input->post('orders_status_id'), $data, $this->input->post('default') == 'on' ? TRUE : FALSE))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Load the orders status
     *
     * @access public
     * @return string
     */
    public function load_orders_status()
    {
        $statuses = $this->orders_status_model->load_order_status($this->input->post('orders_status_id'));
        
        $data = array();
        if ($statuses !== NULL)
        {
            if (DEFAULT_ORDERS_STATUS_ID == $this->input->post('orders_status_id'))
            {
                $data['default'] = '1';
            }
        
            foreach($statuses as $status)
            {
                $data['name[' . $status['language_id'] . ']'] = $status['orders_status_name'];
                $data['public_flag'] = $status['public_flag'];
            }
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the orders status
     *
     * @access public
     * @return string
     */
    public function delete_orders_status()
    {
        $result = array('error' => FALSE, 'feedback' => array());
         
        //whether the order status is allowed to be deleted
        $result = $this->check_orders_status($this->input->post('orders_status_id'), $result);
        
        if ($result['error'] === FALSE)
        {
            if ($this->orders_status_model->delete($this->input->post('orders_status_id')))
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $result['feedback']));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Batch delete the orders status
     *
     * @access public
     * @return string
     */
    public function batch_delete_orders_status()
    {
        $result = array('error' => FALSE, 'feedback' => array());
        
        $orders_status_ids = json_decode($this->input->post('batch'));
        
        if (count($orders_status_ids) > 0)
        {
            foreach($orders_status_ids as $id)
            {
                //whether the order status is allowed to be deleted
                $result = $this->check_orders_status($id, $result);
                
                if ($result['error'] === TRUE)
                {
                    break;
                }
            }
        }
        else
        {
            $result['error'] = TRUE;
        }
        
        if ($result['error'] === FALSE)
        {
            foreach($orders_status_ids as $id)
            {
                if ($this->orders_status_model->delete($id) === FALSE)
                {
                    $result['error'] = TRUE;
                    break;
                }
            }
            
            if ($result['error'] === FALSE) 
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            } 
            else 
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $result['feedback']));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Check the orders status
     *
     * @access private
     * @param $id
     * @return array
     */
    private function check_orders_status($id, $result)
    {
        //deleting the default order status
        if ($id == DEFAULT_ORDERS_STATUS_ID)
        {
            $result['error'] = TRUE;
            $result['feedback'][] = lang('delete_error_order_status_prohibited');
        }
        else
        {
            $check_orders = $this->orders_status_model->check_orders($id);
            
            //the order status using by some orders
            if ($check_orders > 0)
            {
                $result['error'] = TRUE;
                $result['feedback'][] = sprintf(lang('delete_error_order_status_in_use'), $check_orders);
            }
            
            $check_history = $this->orders_status_model->check_history($id);
            
            //the order status using by some orders history
            if ($check_history > 0)
            {
                $result['error'] = TRUE;
                $result['feedback'][] = sprintf(lang('delete_error_order_status_used'), $check_history);
            }
        }
        
        return $result;
    }
}

/* End of file orders_status.php */
/* Location: ./system/controllers/orders_status.php */