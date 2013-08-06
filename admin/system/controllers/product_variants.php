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
 * Product Variants Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Product_Variants extends TOC_Controller
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
        
        $this->load->model('product_variants_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List Product Variants
     *
     * @access public
     * @return string
     */
    public function list_product_variants()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $groups = $this->product_variants_model->get_variants_groups($start, $limit);
        
        $records = array();
        if ($groups !== NULL)
        {
            foreach($groups as $group)
            {
                $entries = $this->product_variants_model->get_total_entries($group['products_variants_groups_id']);
                
                $records[] = array( 'products_variants_groups_id' => $group['products_variants_groups_id'],
                                    'products_variants_groups_name' => $group['products_variants_groups_name'],
                                    'total_entries' => $entries);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->product_variants_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List Product Variants values
     *
     * @access public
     * @return string
     */
    public function list_product_variants_entries()
    {
        $records = $this->product_variants_model->get_variants_entries($this->input->get_post('products_variants_groups_id'));
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the products variants value from a variants group
     *
     * @access public
     * @return string
     */
    public function delete_product_variants_entry()
    {
        $error = FALSE;
        $feedback = array();
        
        //verify whether the variants value is in use by some products
        $entry_data = $this->product_variants_model->get_entry_data($this->input->post('products_variants_values_id'));
        if (($entry_data !== NULL) && ($entry_data['total_products'] > 0))
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('delete_error_group_entry_in_use'), $entry_data['total_products']);
        }
        
        //delete the variants value
        if ($error === FALSE)
        {
            if ($this->product_variants_model->delete_entry($this->input->post('products_variants_values_id'), $this->input->post('products_variants_groups_id')))
            {
                $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed')); 
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
     * Batch delete the products variants values from a variants group
     *
     * @access public
     * @return string
     */
    public function delete_product_variants_entries()
    {
        $values_ids = json_decode($this->input->post('batch'));
        
        $error = FALSE;
        $feedback = array();
        $check_products_array = array();
      
        //verify whether some variants values are in use by some products
        if (count($values_ids) > 0)
        {
            foreach($values_ids as $id)
            {
                $entry_data = $this->product_variants_model->get_entry_data($id);
                
                if ($entry_data['total_products'] > 0)
                {
                    $check_products_array[] = $entry_data['products_variants_values_name'];
                }
            }
        }
      
        if (count($check_products_array) > 0) 
        {
            $error = TRUE;
            $feedback[] = lang('batch_delete_error_group_entries_in_use') . '<p>' . implode(', ', $check_products_array) . '</p>';
        }
        
        //delete the variants values
        if ($error === FALSE)
        {
            foreach($values_ids as $id)
            {
                if ( ! $this->product_variants_model->delete_entry($id, $this->input->post('products_variants_groups_id')))
                {
                    $error = TRUE;
                    break;
                }
            }
            
            if ($error === FALSE)
            {
                $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE ,'feedback' => lang('ms_error_action_not_performed'));    
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
     * Save the products variants value
     *
     * @access public
     * @return string
     */
    public function save_product_variants_entry()
    {
        $data = array('name' => $this->input->post('products_variants_values_name'), 
                      'products_variants_groups_id' => $this->input->post('products_variants_groups_id'));
        
        if ($this->product_variants_model->save_entry($this->input->post('products_variants_values_id'), $data))
        {
            $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));   
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Load the products variants value
     *
     * @access public
     * @return string
     */
    public function load_product_variants_entry()
    {
        $entries_data = $this->product_variants_model->get_entries_data($this->input->post('products_variants_values_id'));
        
        $data = array();
        if ($entries_data !== NULL)
        {
            foreach($entries_data as $entry)
            {
                if ($entry['language_id'] == lang_id())
                {
                    $data['products_variants_values_id'] = $entry['products_variants_values_id'];
                }
                
                $data['products_variants_values_name[' . $entry['language_id'] . ']'] = $entry['products_variants_values_name'];
            }
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the product variants group
     *
     * @access public
     * @return string
     */
    public function save_product_variant()
    {
        $data = array('name' => $this->input->post('products_variants_groups_name'));
        
        if ($this->product_variants_model->save($this->input->post('products_variants_groups_id'), $data))
        {
            $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Load the product variants group
     *
     * @access public
     * @return string
     */
    public function load_product_variant()
    {
        $groups_data = $this->product_variants_model->get_groups_data($this->input->post('products_variants_groups_id'));
        
        $data = array();
        if ($groups_data !== NULL)
        {
            foreach($groups_data as $group)
            {
                if ($group['language_id'] == lang_id())
                {
                    $data['products_variants_groups_id'] = $group['products_variants_groups_id'];
                }
                
                $data['products_variants_groups_name[' . $group['language_id'] . ']'] = $group['products_variants_groups_name'];
            }
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the product variants group
     *
     * @access public
     * @return string
     */
    public function delete_product_variant()
    {
        $error = FALSE;
        $feedback = array();
        
        $total_products = $this->product_variants_model->get_group_products($this->input->post('products_variants_groups_id'));
        
        //verify whether the variants group is in use by some products
        if ($total_products > 0)
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('delete_error_variant_group_in_use'), $total_products);
        }
        
        //delete the variants group
        if ($error === FALSE)
        {
            if ($this->product_variants_model->delete($this->input->post('products_variants_groups_id')))
            {
                $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));    
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
}

/* End of file product_variants.php */
/* Location: ./system/controllers/product_variants.php */