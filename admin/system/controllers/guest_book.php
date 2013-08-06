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
 * Guest Book Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Guest_Book extends TOC_Controller 
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
        
        $this->load->model('guest_book_model');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * List guest book
     *
     * @access public
     * @return string
     */
    public function list_guest_books()
    {
        $start = $this->input->get_post('start');
        $limit = $this->input->get_post('limit');
        
        $start = empty($start) ? 0 : $start;
        $limit = empty($limit) ? MAX_DISPLAY_SEARCH_RESULTS : $limit;
        
        $guest_books = $this->guest_book_model->get_guest_books($start, $limit);
        
        $records = array();
        if ($guest_books !== NULL)
        {
            foreach($guest_books as $guest_book)
            {
                $records[]=  array('guest_books_id' => $guest_book['guest_books_id'],
                                   'title' => $guest_book['title'],
                                   'email'=> $guest_book['email'],
                                   'guest_books_status' => $guest_book['guest_books_status'],
                                   'languages' => show_image($guest_book['code']),
                                   'content' => $guest_book['content'],
                                   'date_added' => mdate('%Y/%m/%d', human_to_unix($guest_book['date_added'])));
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->guest_book_model->get_totals(), EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Load one guest book
     * 
     * @access public
     * @return string
     */
    public function load_guest_book()
    {
        $data = $this->guest_book_model->get_data($this->input->get_post('guest_books_id'));
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get all the available languages
     * 
     * @access public
     * @return string
     */
    public function get_languages()
    {
        $languages = array();
        
        //languages
        foreach (lang_get_all() as $l)
        {
            $languages[] = array('id' => $l['id'], 'text' => $l['name']);
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $languages)));
    }

    // ------------------------------------------------------------------------
    
    /**
     * Save guest book
     * 
     * @access public
     * @return string
     */
    public function save_guest_book()
    {
        $data = array('guest_books_id' => $this->input->post('guest_books_id'), 
                      'title' => $this->input->post('title'), 
                      'email' => $this->input->post('email'), 
                      'url' => $this->input->post('url'), 
                      'content' => $this->input->post('content'), 
                      'languages_id' => $this->input->post('languages_id'), 
                      'guest_books_status' => $this->input->post('guest_books_status'));
        
        if ($this->guest_book_model->save($data))
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
     * Delete guest book
     *
     * @access public
     * @return string
     */
    public function delete_guest_book()
    {
        $guest_book_id = $this->input->post('guest_books_id');
        
        if ($this->guest_book_model->delete($guest_book_id))
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
     * Delete guestbooks
     * 
     * @access public
     * @return string
     */
    public function delete_guest_books()
    {
        $batch = $this->input->post('batch');
        
        $guest_book_ids = json_decode($batch);
        
        if ($this->guest_book_model->batch_delete($guest_book_ids))
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
     * Set the status of guest book
     * 
     * @access public
     * @return string
     */
    public function set_status()
    {
        $guest_book_id = $this->input->post('guest_books_id');
        $flag = $this->input->post('flag');
        
        if ($this->guest_book_model->set_status($guest_book_id, $flag))
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

/* End of file guest_book.php */
/* Location: ./system/controllers/guest_book.php */