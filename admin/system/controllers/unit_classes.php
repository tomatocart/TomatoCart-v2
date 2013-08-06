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
 * Unit Classes Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Unit_Classes extends TOC_Controller
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
        
        $this->load->model('unit_classes_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the unit classes
     *
     * @access public
     * @return string
     */
    public function list_unit_classes()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $classes = $this->unit_classes_model->get_classes($start, $limit);
        
        $records = array();
        if ($classes !== NULL)
        {
            foreach($classes as $class)
            {
                $unit_class_title = $class['quantity_unit_class_title'];
                
                if ($class['quantity_unit_class_id'] == DEFAULT_UNIT_CLASSES)
                {
                    $unit_class_title  .=  ' (' . lang('default_entry') . ')';
                }
                
                $records[] = array('unit_class_id' => $class['quantity_unit_class_id'],
                                   'unit_class_title' => $unit_class_title,
                                   'languange_id'=> lang_id());
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->unit_classes_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the quantity unit classes
     *
     * @access public
     * @return string
     */
    public function delete_unit_class()
    {
        $error = FALSE;
        $feedback = array();
        
        //deleting the default unit class
        if ($this->input->post('unit_class_id') == DEFAULT_UNIT_CLASSES)
        {
            $error = TRUE;
            $feedback[] = lang('delete_error_unit_class_prohibited');
        }
        else
        {
            
            $check_products = $this->unit_classes_model->get_total_products($this->input->post('unit_class_id'));
            
            //the unit class using by some products
            if ($check_products > 0)
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('delete_error_unit_class_in_use'), $check_products);
            }
        }
        
        if ($error === FALSE)
        {
            if ($this->unit_classes_model->delete($this->input->post('unit_class_id')))
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
            $response = array('success' => FALSE, 'feedback' => implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Batch delete the quantity unit classes
     *
     * @access public
     * @return string
     */
    public function delete_unit_classes()
    {
        $error = FALSE;
        $feedback = array();
        
        $unit_classes_ids = json_decode($this->input->post('batch'));
        
        if (count($unit_classes_ids) > 0)
        {
            foreach($unit_classes_ids as $id)
            {
                //the default unit class is included
                if ($id == DEFAULT_UNIT_CLASSES)
                {
                    $error = TRUE;
                    $feedback[] = lang('batch_delete_error_unit_class_prohibited');
                }
                else
                {
                    $check_products = $this->unit_classes_model->get_total_products($id);
                    
                    //include the unit classes which are using by some products
                    if ($check_products > 0)
                    {
                        $error = TRUE;
                        $feedback[] = lang('batch_delete_error_unit_class_in_use');
                        break;
                    }
                }
            }
        }
        else
        {
            $error = TRUE;
        }
        
        if ($error === FALSE)
        {
            foreach($unit_classes_ids as $id)
            {
                if ($this->unit_classes_model->delete($id) === FALSE)
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
            $response = array('success' => FALSE, 'feedback' => implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the quantity unit classes
     *
     * @access public
     * @return string
     */
    public function save_unit_class()
    {
        $data = array('unit_class_title' => $this->input->post('unit_class_title'));
        
        if ($this->unit_classes_model->save($this->input->post('unit_class_id'), $data, $this->input->post('default') == 'on' ? TRUE : FALSE))
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
     * Load the quantity unit classes
     *
     * @access public
     * @return string
     */
    public function load_unit_class()
    {
        $unit_class_id = $this->input->post('unit_class_id') ? $this->input->post('unit_class_id') : 0;
        
        $data = array();
        if ( $unit_class_id == DEFAULT_UNIT_CLASSES ) 
        {
            $data['is_default'] = 1; 
        }
        
        $classes_infos = $this->unit_classes_model->get_classes_infos($unit_class_id);
        
        if ($classes_infos !== NULL)
        {
            foreach($classes_infos as $classes_info)
            {
                $data['unit_class_title[' . $classes_info['language_id'] . ']'] =  $classes_info['quantity_unit_class_title'];
            }
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file unit_classes.php */
/* Location: ./system/controllers/unit_classes.php */