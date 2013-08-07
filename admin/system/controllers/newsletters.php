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
 * Newsletters Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Newsletters extends TOC_Controller
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
        
        $this->load->model('newsletters_model');
    }
    
// ------------------------------------------------------------------------
    
    /**
     * List the newsletters
     *
     * @access public
     * @return string
     */
    public function list_newsletters()
    {
        $this->load->helper('html_output');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $newsletters = $this->newsletters_model->get_newsletters($start, $limit);
        
        $records = array();
        if ($newsletters != NULL)
        {
            foreach($newsletters as $newsletter)
            {
                if ($newsletter['status'] == 1)
                {
                    $sent = icon('checkbox_ticked.gif');
                    $action_class = 'icon-log-record';
                }
                else
                {
                    $sent = icon('checkbox_crossed.gif');
                    $action_class = 'icon-send-email-record';
                }
                
                $records[] = array('newsletters_id' => $newsletter['newsletters_id'],
                                   'title' =>  $newsletter['title'],
                                   'size' => $newsletter['content_length'],
                                   'module' => $newsletter['module'],
                                   'sent' => $sent,
                                   'action_class' => $action_class
                );         
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->newsletters_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Delete the newsletter
     *
     * @access public
     * @return string
     */
    public function delete_newsletter()
    {
        if (!$this->newsletters_model->delete($this->input->post('newsletters_id')))
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed')); 
        }
        else
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Delete the newsletters
     *
     * @access public
     * @return string
     */
    public function delete_newsletters()
    {
        $newsletters_ids = json_decode($this->input->post('batch'));
        
        $error = FALSE;
        foreach($newsletters_ids as $id)
        {
            if (!$this->newsletters_model->delete($id))
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
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the newsletters types
     *
     * @access public
     * @return string
     */
    public function get_modules()
    {
        $records = array(array('id' => 'email', 'text' => lang('newsletter_email_title')), 
                         array('id' => 'newsletter', 'text' => lang('newsletter_newsletter_title')));
                         
         $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------

    /**
     * Save the newsletters
     *
     * @access public
     * @return string
     */
    public function save_newsletter()
    {
        $data = array('title' => $this->input->post('title'), 
                      'content' => $this->input->post('content'), 
                      'module' => $this->input->post('newsletter_module'));
        
        if ($this->newsletters_model->save($this->input->post('newsletters_id'), $data))
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
     * Load the newsletter
     *
     * @access public
     * @return string
     */
    public function load_newsletter()
    {
        $data = $this->newsletters_model->get_data($this->input->post('newsletters_id'));
        
        if ($data != NULL)
        {
            $data['newsletter_module'] = $data['module'];
            
            $response = array('success' => TRUE, 'data' => $data);
        }
        else
        {
            $response = array('success' => FALSE);
        }
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the customers
     *
     * @access public
     * @return string
     */
    public function get_emails_audience()
    {
        $customers_array = array(array('id' => '***',
                                       'text' => lang('newsletter_email_all_customers')));
         
        $customers = $this->newsletters_model->get_customers();
         
        foreach($customers as $customer)
        {
            $customers_array[] = array('id' => $customer['customers_id'],
                                       'text' => $customer['customers_lastname'] . ', ' . $customer['customers_firstname'] . ' (' . $customer['customers_email_address'] . ')');
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $customers_array)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the emails confirmation string
     *
     * @access public
     * @return string
     */
    public function get_emails_confirmation()
    {
        $confirmation_string = '';
        $audience_size = 0;
        
        $customers_ids = json_decode($this->input->post('batch'));
        
        if (!empty($customers_ids))
        {
            $audience_size = $this->newsletters_model->get_total_customers($this->input->post('newsletters_id'), $customers_ids);
            $email = $this->newsletters_model->get_data($this->input->post('newsletters_id'));
            
            if ($email != NULL)
            {
                $confirmation_string = '<p style="margin: 10px 0;"><font color="#ff0000"><b>' . sprintf(lang('newsletter_email_total_recipients'), $audience_size) . '</b></font></p>' .
                                       '<p style="margin: 10px 0;"><b>' . $email['title'] . '</b></p>' .
                                       '<p style="margin: 10px 0;">' . nl2br($email['content']) . '</p>';
            }
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'confirmation' => $confirmation_string)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the confirmation message for the newsletters
     *
     * @access public
     * @return string
     */
    public function get_newsletters_confirmation()
    {
        $newsletters_id = $this->input->post('newsletters_id');
        $email = $this->newsletters_model->get_data($newsletters_id);
        
        $recipients = $this->newsletters_model->get_newsletters_recipients($newsletters_id);
        
        $confirmation_string = '<p style="margin: 10px;"><font color="#ff0000"><b>' . sprintf(lang('newsletter_newsletter_total_recipients'), $recipients) . '</b></font></p>' .
                               '<p style="margin: 10px;"><b>' . $email['title'] . '</b></p>' .
                               '<p style="margin: 10px;">' . nl2br($email['content']) . '</p>';
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'execute' => ($recipients > 0 ? TRUE : FALSE), 'confirmation' => $confirmation_string)));
  }
  
// ------------------------------------------------------------------------
    
    /**
     * Send out the emails
     *
     * @access public
     * @return string
     */
    public function send_emails()
    {
        $customers_ids = json_decode($this->input->post('batch'));
        $newsletters_id = $this->input->post('newsletters_id');
        $email = $this->newsletters_model->get_data($this->input->post('newsletters_id'));
        
        //error flag
        $error = FALSE;
        $error_msg = '';
        
        //get recipients
        $recipients = array();
        $customers = $this->newsletters_model->get_audiences($newsletters_id, $customers_ids);
        if ($customers != NULL)
        {
            foreach($customers as $customer)
            {
                $recipients[] =  $customer['customers_email_address'];
            }
        }
        
        //send the email to the recipients
        if (count($recipients) > 0)
        {
            if ( ! $this->send($email, $recipients))
            {
                $error = TRUE;
                $error_msg .= $this->email->print_debugger();
            }
            else
            {
                //log the reciepients
                foreach ($recipients as $recipient)
                {
                    $this->newsletters_model->log($newsletters_id, $recipient);
                }
            }
        }
        //there is not valid recipient
        else
        {
            $error = TRUE;
        }
        
        if ($error === FALSE)
        {
            //update email status
            $this->newsletters_model->update($newsletters_id);
            
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed') . '<br />' . $error_msg);
        }
            
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * List the logs
     *
     * @access public
     * @return string
     */
    public function list_log()
    {
        $this->load->helper('date');
        $this->load->helper('html_output');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $logs = $this->newsletters_model->get_logs($start, $limit, $this->input->get_post('newsletters_id'));
        
        $records = array();
        if ($logs != NULL)
        {
            foreach($logs as $log)
            {
                $records[] = array('email_address' => $log['email_address'], 
                                   'sent' => icon(!empty($log['date_sent']) ? 'checkbox_ticked.gif' : 'checkbox_crossed.gif'), 
                                   'date_sent' => mdate('%Y/%m/%d', human_to_unix($log['date_sent'])));
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->newsletters_model->get_total_logs($this->input->get_post('newsletters_id')),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Send the newsletters
     *
     * @access public
     * @return string
     */
    public function send_newsletters()
    {
        define('PAGE_PARSE_START_TIME', microtime());
        
        $newsletters_id = $this->input->post('newsletters_id');
        
        $time_start = explode(' ', PAGE_PARSE_START_TIME);
        $max_execution_time = 0.8 * (int)ini_get('max_execution_time');
        
        $email = $this->newsletters_model->get_data($newsletters_id);
        
        //error flag
        $error = FALSE;
        $error_msg = '';
        
        $customers = $this->newsletters_model->get_recipients($newsletters_id);
        
        $recipients = array();
        if ($customers != NULL)
        {
            foreach($customers as $customer)
            {
               $recipients[] = $customer['customers_email_address'];
            }
            
            //send the newsletter
            if ( ! $this->send($email, $recipients))
            {
                $error = TRUE;
                $error_msg .= $this->email->print_debugger();
            }
            else
            {
                //log the reciepients
                foreach ($recipients as $recipient)
                {
                    $this->newsletters_model->log($newsletters_id, $recipient);
                }
                
            }
            
            $time_end = explode(' ', microtime());
            $time_total = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
            
            if ( $time_total > $max_execution_time )
            {
                $error = TRUE;
            }
        }
        //no recipient
        else
        {
            $error =TRUE;
        }
        
        if ($error === FALSE)
        {
            $this->newsletters_model->update($newsletters_id);
            
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . $error_msg);
        }
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Send the email
     *
     * @access private
     * @param array $email
     * @param array $recipients
     * @return boolean
     */
    protected function send($email, $recipients) 
    {
        $this->load->library('email');
        
        //filter the recipients
        $filted_addresses = array();
        foreach ($recipients as $recipient)
        {
            if ($this->email->valid_email($recipient))
            {
                $filted_addresses[] = $recipient;
            }
        }
        
        //set the email information
        $this->email->subject( $email['title']);
        $this->email->message( $email['content']);
        $this->email->set_alt_message(strip_tags($email['content']));
        
        //set recipients
        $this->email->to($filted_addresses);
        
        return $this->email->send();
    }
}

/* End of file newsletters.php */
/* Location: ./system/controllers/newsletters.php */