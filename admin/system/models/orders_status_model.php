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
 * Orders Status Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Orders_Status_Model extends CI_Model
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
     * Get the orders_status
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_orders_status($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('orders_status_id, orders_status_name, public_flag')
            ->from('orders_status')
            ->where('language_id', lang_id());
            
        if ($start !== NULL && $limit !== NULL)
        {
            $this->db->limit($limit, $start);
        }
        
        $result = $this->db->get();

        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get data of the order status with the id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
            ->select('*')
            ->from('orders_status')
            ->where(array('orders_status_id' => $id, 'language_id' => lang_id()))
            ->get();

        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }

        return NULL;
    }

    // ------------------------------------------------------------------------

    /**
     * Set the status of the order status
     *
     * @access public
     * @param $orders_status_id
     * @param $flag
     * @return boolean
     */
    public function set_status($orders_status_id, $flag)
    {
        $this->db->update('orders_status',
            array('public_flag' => $flag),
            array('orders_status_id' => $orders_status_id));
         
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }

        return FALSE;
    }

    // ------------------------------------------------------------------------

    /**
     * Save the orders_status
     *
     * @access public
     * @param $id
     * @param $data
     * @param $default
     * @return boolean
     */
    public function save($id = NULL, $data, $default = FALSE)
    {
        $error = FALSE;

        //start transaction
        $this->db->trans_begin();

        //editing or adding the order status
        if (is_numeric($id))
        {
            $orders_status_id = $id;
        }
        else
        {
            $result = $this->db->
                select_max('orders_status_id')
                ->from('orders_status')
                ->get();

            $status = $result->row_array();
            $orders_status_id = $status['orders_status_id'] + 1;

            $result->free_result();
        }

        //languages
        foreach(lang_get_all() as $l)
        {
            //editing or adding the order status
            if (is_numeric($id))
            {
                $this->db->update('orders_status',
                    array('orders_status_name' => $data['name'][$l['id']],
                          'public_flag' => $data['public_flag']), 
                    array('orders_status_id' => $orders_status_id,
                          'language_id' => $l['id']));
            }
            else
            {
                $this->db->insert('orders_status',
                    array('orders_status_id' => $orders_status_id,
                          'language_id' => $l['id'], 
                          'orders_status_name' => $data['name'][$l['id']], 
                          'public_flag' => $data['public_flag']));
            }

            //check transaction status
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
                break;
            }
        }

        if ($error === FALSE)
        {
            if ($default === TRUE)
            {
                $this->db->update('configuration',
                    array('configuration_value' => $orders_status_id),
                    array('configuration_key' => 'DEFAULT_ORDERS_STATUS_ID'));

                //check transaction status
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
                }
            }
        }

        if ($error === FALSE)
        {
            //commit
            $this->db->trans_commit();

            return TRUE;
        }

        //rollback
        $this->db->trans_rollback();

        return FALSE;
    }

    // ------------------------------------------------------------------------

    /**
     * Load the orders_status
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function load_order_status($id)
    {
        $result = $this->db
            ->select('language_id, orders_status_name, public_flag')
            ->from('orders_status')
            ->where('orders_status_id', $id)
            ->get();

        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }

    // ------------------------------------------------------------------------

    /**
     * Whether the order status is using in orders
     *
     * @access public
     * @param $id
     * @return int
     */
    public function check_orders($id)
    {
        return $this->db->where('orders_status', $id)->from('orders')->count_all_results();
    }

    // ------------------------------------------------------------------------

    /**
     * Whether the order status is using in order status history
     *
     * @access public
     * @param $id
     * @return int
     */
    public function check_history($id)
    {
        return $this->db->where('orders_status_id', $id)->from('orders_status_history')->group_by('orders_id')->count_all_results();
    }

    // ------------------------------------------------------------------------

    /**
     * Delete the order status
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->delete('orders_status', array('orders_status_id' => $id));

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }

        return FALSE;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get total number of the orders status
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->where('language_id', lang_id())->from('orders_status')->count_all_results();
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the all of orders_status
     *
     * @access public
     * @return mixed
     */
    public function get_order_status()
    {
        $result = $this->db
            ->select('orders_status_id, orders_status_name, public_flag')
            ->from('orders_status')
            ->where('language_id', lang_id())
            ->get();

        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }
}

/* End of file orders_status_model.php */
/* Location: ./system/models/orders_status_model.php */