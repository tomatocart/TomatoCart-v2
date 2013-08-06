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
 * Faqs Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Faqs extends TOC_Controller 
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
        
        $this->load->model('faqs_model');
    }
    
    // ------------------------------------------------------------------------

    /**
     * List the faqs
     * 
     * @access public
     * @return string
     */
    public function list_faqs()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        $search = $this->input->get_post('search');
        
        $faqs = $this->faqs_model->get_faqs($start, $limit, $search);
        
        $records = array();
        if ($faqs !== NULL)
        {
            $records = $faqs;
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->faqs_model->get_total($search),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the faq
     * 
     * @access public
     * @return string
     */
    public function save_faq()
    {
        $this->load->helper('html_output');
        
        //search engine friendly urls
        $formatted_urls = array();
        $urls = $this->input->post('faqs_url');
        $faqs_question = $this->input->post('faqs_question');
    
        if (is_array($urls) && count($urls) > 0)
        {
            foreach($urls as $languages_id => $url)
            {
                $url = format_friendly_url($url);
                
                if (empty($url))
                {
                    $url = $faqs_question[$languages_id];
                }
        
                $formatted_urls[$languages_id] = $url;
            }
        }
        
        $data = array('faqs_question' => $this->input->post('faqs_question'),
                      'faqs_url' => $formatted_urls,
                      'faqs_answer' => $this->input->post('faqs_answer'),
                      'faqs_order' => $this->input->post('faqs_order'),
                      'faqs_status' => $this->input->post('faqs_status'));
        
        if ($this->faqs_model->save($this->input->post('faqs_id'), $data))
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
     * Load the faq
     * 
     * @access public
     * @return string
     */
    public function load_faq()
    {
        $data = $this->faqs_model->get_data($this->input->post('faqs_id'));
        
        if ($data !== NULL)
        {
            $faq_descriptions = $this->faqs_model->get_description($this->input->post('faqs_id'));
            
            if ($faq_descriptions !== NULL)
            {
                foreach($faq_descriptions as $description)
                {
                    $data['faqs_question[' . $description['language_id'] . ']'] = $description['faqs_question'];
                    $data['faqs_url[' . $description['language_id'] . ']'] = $description['faqs_url'];
                    $data['faqs_answer[' . $description['language_id'] . ']'] = $description['faqs_answer'];
                }
            }
            
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------

    /**
     * Delete the faq
     * 
     * @access public
     * @return stirng
     */
    public function delete_faq()
    {
        if ($this->faqs_model->delete($this->input->post('faqs_id')))
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
     * Batch delete the faqs
     * 
     * @access public
     * @return stirng
     */
    public function delete_faqs()
    {
        $error = FALSE;
        
        $faqs_ids = json_decode($this->input->post('batch'));
        
        if (count($faqs_ids) > 0)
        {
            foreach($faqs_ids as $faqs_id)
            {
                if ($this->faqs_model->delete($faqs_id) === FALSE)
                {
                    $error = TRUE;
                    break;
                }
            }
        }
        else
        {
            $error = TRUE;
        }
        
        if ($error === FALSE)
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
     * Set the status of the faq
     * 
     * @access public
     * @return stirng
     */
    public function set_status()
    {
        if ($this->faqs_model->set_status($this->input->post('faqs_id'), $this->input->post('flag')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
}

/* End of file faqs.php */
/* Location: ./system/controllers/faqs.php */