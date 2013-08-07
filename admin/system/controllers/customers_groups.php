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
 * Customers Groups Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Customers_Groups extends TOC_Controller
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
        
        $this->load->model('customers_groups_model');
    }
    
    // ------------------------------------------------------------------------

    /**
     * List the customers groups
     *
     * @access public
     * @return string
     */
    public function list_customers_groups()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $groups = $this->customers_groups_model->get_groups($start, $limit);
        
        $records = array();
        if ($groups !== NULL)
        {
            foreach($groups as $group)
            {
                $group_name = $group['customers_groups_name'];
                
                //verify that it is the default customer group
                if ($group['is_default'])
                {
                    $group_name .= '(' . lang('default_entry') . ')';
                }
                
                $records[] = array('language_id' => $group['language_id'],
                                   'customers_groups_id' => $group['customers_groups_id'],
                                   'customers_groups_name' => $group_name,
                                   'customers_groups_discount' => sprintf("%d%%", $group['customers_groups_discount']));     
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->customers_groups_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Save a customer group
     *
     * @access public
     * @return string
     */
    public function save_customers_groups()
    {
        $data = array('customers_groups_id' => $this->input->post('groups_id'),
                      'customers_groups_discount' => $this->input->post('customers_groups_discount'),
                      'customers_groups_name' => $this->input->post('customers_groups_name'),
                      'is_default' => ((int)$this->input->post('is_default') === 1 ? (int)$this->input->post('is_default') : 0));
      
        if ($this->customers_groups_model->save($this->input->post('groups_id'), $data))
        {
            $response = array('success' => TRUE , 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE , 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Delete a customer group
     *
     * @access public
     * @return string
     */
    public function delete_customers_group()
    {
        $error = FALSE;
        $feedback = array();
        
        $data = $this->customers_groups_model->get_data($this->input->post('customer_groups_id'));
        
        //it is not allowed to delete the default customer group
        if ($data['is_default'] == 1)
        {
            $error = TRUE;
            $feedback[] = lang('delete_error_customer_group_prohibited');
        }
        
        //verify that the customer group is used by some customers
        $check_in_use = $this->customers_groups_model->get_in_use($this->input->post('customer_groups_id'));
        if ($check_in_use > 0)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('delete_error_customer_group_in_use'), $check_in_use);
        }
        
        if ($error === FALSE)
        {
            if ($this->customers_groups_model->delete($this->input->post('customer_groups_id')) === FALSE)
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
            else
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Batch delete customers groups
     *
     * @access public
     * @return string
     */
    public function delete_customers_groups()
    {
        $error = FALSE;
        $feedback = array();
        $check_customers_flag = array();
        
        $customers_groups_ids = json_decode($this->input->post('batch'));
        
        foreach($customers_groups_ids as $id)
        {
            $data = $this->customers_groups_model->get_data($id);
            
            //prohibited to delete the default customer group
            if ($data['is_default'] == 1)
            {
                $error = TRUE;
                $feedback[] = lang('delete_error_customer_group_prohibited');
                break;
            }
            
            //verify that the customer group is used by some customers
            $check_in_use = $this->customers_groups_model->get_in_use($id);
            
            if ($check_in_use > 0)
            {
                $error = TRUE;
                $check_customers_flag[] = $data['customers_groups_name'];
                break;
            }
        }
        
        //delete the customers groups
        if ($error === FALSE)
        {
            foreach($customers_groups_ids as $id)
            {
                if ($this->customers_groups_model->delete($id) === FALSE)
                {
                    $error = TRUE;
                    break;
                }
            }
            
            if ($error === FALSE)
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
            if (count($check_customers_flag) > 0)
            {
                $feedback[] = lang('batch_delete_error_customer_group_in_use') . '<br />' . implode(', ', $check_customers_flag);
            }
            
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Load a customer group
     *
     * @access public
     * @return string
     */
    public function load_customers_groups()
    {
        $infos = $this->customers_groups_model->get_info($this->input->post('groups_id'));
        
        $data = array();
        if ($infos !== NULL)
        {
            foreach($infos as $info)
            {
                if ($info['language_id'] == lang_id())
                {
                    $data['customers_groups_discount'] = $info['customers_groups_discount'];
                    $data['is_default'] = $info['is_default'];
                }
                
                $data['customers_groups_name[' . $info['language_id'] . ']'] = $info['customers_groups_name'];
            }
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file customers_groups.php */
/* Location: ./system/controllers/customers_groups.php */