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
 * Homepage Info Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Homepage_Info extends TOC_Controller
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
        
        $this->load->model('homepage_info_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the home information
     *
     * @access public
     * @return string
     */
    public function save_info()
    {
        $data = array('page_title' => $this->input->post('HOME_PAGE_TITLE'), 
                      'keywords' => $this->input->post('HOME_META_KEYWORD'), 
                      'descriptions' => $this->input->post('HOME_META_DESCRIPTION'), 
                      'index_text' => $this->input->post('index_text'));
        
        if ($this->homepage_info_model->save_data($data))
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
     * Load the home page infomation
     *
     * @access public
     * @return string
     */
    public function load_info()
    {
      
        $data = $this->homepage_info_model->get_data();
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file homepage_info.php */
/* Location: ./system/controllers/homepage_info.php */