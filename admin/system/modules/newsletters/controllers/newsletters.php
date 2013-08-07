<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource ./system/modules/newsletters/controllers/newsletters.php
 */

class Newsletters extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('newsletters_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('newsletters_grid');
    $this->load->view('newsletters_dialog');
    $this->load->view('send_emails_dialog');
    $this->load->view('log_dialog');
    $this->load->view('send_newsletters_dialog');
  }
  
  public function list_newsletters()
  {
    $this->load->helper('html_output');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $newsletters = $this->newsletters_model->get_newsletters($start, $limit);
    
    $records = array();
    if (!empty($newsletters))
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
        
        $records[] = array(
          'newsletters_id' => $newsletter['newsletters_id'],
          'title' =>  $newsletter['title'],
          'size' => $newsletter['content_length'],
          'module' => $newsletter['module'],
          'sent' => $sent,
          'action_class' => $action_class
        );         
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->newsletters_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
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
    
    return $response;
  }
  
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
    
    return $response;
  }
  
  public function get_modules()
  {
    $records = array(array('id' => 'email', 'text' => lang('newsletter_email_title')), 
                     array('id' => 'newsletter', 'text' => lang('newsletter_newsletter_title')));
                     
   return array(EXT_JSON_READER_ROOT => $records);
  }
  
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
    
    return $response;
  }
  
  public function load_newsletter()
  {
    $data = $this->newsletters_model->get_data($this->input->post('newsletters_id'));
    $data['newsletter_module'] = $data['module'];
    
    return array('success' => TRUE, 'data' => $data);
  }
  
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
    
    return array(EXT_JSON_READER_ROOT => $customers_array);
  }
  
  public function get_emails_confirmation()
  {
    $confirmation_string = '';
    $audience_size = 0;
    
    $customers_ids = json_decode($this->input->post('batch'));
    
    if (!empty($customers_ids))
    {
      $audience_size = $this->newsletters_model->get_total_customers($this->input->post('newsletters_id'), $customers_ids);
      $email = $this->newsletters_model->get_data($this->input->post('newsletters_id'));
      
      $confirmation_string = '<p style="margin: 10px 0;"><font color="#ff0000"><b>' . sprintf(lang('newsletter_email_total_recipients'), $audience_size) . '</b></font></p>' .
                             '<p style="margin: 10px 0;"><b>' . $email['title'] . '</b></p>' .
                             '<p style="margin: 10px 0;">' . nl2br($email['content']) . '</p>';
    }
    
    return array('success' => TRUE, 'confirmation' => $confirmation_string);
  }
  
  public function get_newsletters_confirmation()
  {
    $newsletters_id = $this->input->post('newsletters_id');
    $email = $this->newsletters_model->get_data($newsletters_id);
    
    $recipients = $this->newsletters_model->get_newsletters_recipients($newsletters_id);
    
    $confirmation_string = '<p style="margin: 10px;"><font color="#ff0000"><b>' . sprintf(lang('newsletter_newsletter_total_recipients'), $recipients) . '</b></font></p>' .
                           '<p style="margin: 10px;"><b>' . $email['title'] . '</b></p>' .
                           '<p style="margin: 10px;">' . nl2br($email['content']) . '</p>';
    
    return array('success' => TRUE, 'execute' => ($recipients > 0 ? TRUE : FALSE), 'confirmation' => $confirmation_string);
}
  
  public function send_emails()
  {
    $customers_ids = json_decode($this->input->post('batch'));
    $newsletters_id = $this->input->post('newsletters_id');
    $email = $this->newsletters_model->get_data($this->input->post('newsletters_id'));
    
    $audience = array();
    $customers = array();
    
    $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    
    if ($newsletters_id > 0)
    {
      $customers = $this->newsletters_model->get_audiences($newsletters_id, $customers_ids);
      
      if (!empty($customers))
      {
        foreach($customers as $customer)
        {
          if (!isset($audience[$customer['customers_id']]))
          {
            $audience[$customer['customers_id']] = array('firstname' => $customer['customers_firstname'], 
                                                         'lastname' => $customer['customers_lastname'], 
                                                         'email_address' => $customer['customers_email_address']);
          }
        }
      }
      
      if (count($audience) > 0)
      {
        $this->init_email();
        
        $this->email->subject($email['title']);
        $this->email->from(EMAIL_FROM, STORE_OWNER);
        $this->email->message($email['content']);
  
        foreach($audience as $key => $value)
        {
          $this->email->to($value['email_address']); 
          $this->email->send();
          
          $this->newsletters_model->log($newsletters_id, $value['email_address']);
        }
      }
      
      $this->newsletters_model->update($newsletters_id);
      
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    
    return $response;
  }
  
  public function list_log()
  {
    $this->load->helper('date');
    $this->load->helper('html_output');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $logs = $this->newsletters_model->get_logs($start, $limit, $this->input->get_post('newsletters_id'));
    
    $records = array();
    if (!empty($logs))
    {
      foreach($logs as $log)
      {
        $records[] = array('email_address' => $log['email_address'], 
                           'sent' => icon(!empty($log['date_sent']) ? 'checkbox_ticked.gif' : 'checkbox_crossed.gif'), 
                           'date_sent' => mdate('%Y/%m/%d', human_to_unix($log['date_sent'])));
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->newsletters_model->get_total_logs($this->input->get_post('newsletters_id')),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function send_newsletters()
  {
    define('PAGE_PARSE_START_TIME', microtime());
    
    $newsletters_id = $this->input->post('newsletters_id');
    
    $time_start = explode(' ', PAGE_PARSE_START_TIME);
    $max_execution_time = 0.8 * (int)ini_get('max_execution_time');
    
    $email = $this->newsletters_model->get_data($newsletters_id);
    
    $error = FALSE;
    
    $recipients = $this->newsletters_model->get_recipients($newsletters_id);
    
    if (!empty($recipients))
    {
      $send_type = $this->init_email();
      
      $this->email->subject($email['title']);
      
      if ($send_type == 'smtp')
      {
        $this->email->from(SMTP_USERNAME, STORE_OWNER);
      }
      else
      {
        $this->email->from(EMAIL_FROM, STORE_OWNER);
      }
      
      $this->email->message($email['content']);
      
      foreach($recipients as $recipient)
      {
        $this->email->to($recipient['customers_email_address']); 
        if (!$this->email->send())
        {
          $error = TRUE;
        }
        
        if ($error === FALSE)
        {
          $this->newsletters_model->log($newsletters_id, $recipient['customers_email_address']);
        }
        
        $time_end = explode(' ', microtime());
        $time_total = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
        
        if ( $time_total > $max_execution_time ) 
        {
          $error === TRUE;
        }
      }
    }
    
    if ($error === FALSE)
    {
      $this->newsletters_model->update($newsletters_id);
      
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function get_products()
  {
    $products = $this->newsletters_model->get_products();
    
    $result = array();
    if (!empty($products))
    {
      foreach($products as $product)
      {
        $result[] = array('id' => $product['products_id'], 
                          'text' => $product['products_name']);
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $result);
  }
  
  private function init_email()
  {
    $this->load->library('email');
    
    $config = array();
    
    $smtp_host = SMTP_HOST;
    $smtp_user = SMTP_USERNAME;
    $smtp_pass = SMTP_PASSWORD;
    $smtp_port = SMTP_PORT;
    
    if (!empty($smtp_host) && !empty($smtp_user) && !empty($smtp_pass) && !empty($smtp_port))
    {
      $config['protocol'] = 'smtp';
      $config['smtp_host'] = $smtp_host;
      $config['smtp_user'] = $smtp_user;
      $config['smtp_pass'] = $smtp_pass;
      $config['smtp_port'] = $smtp_port;
      $config['smtp_timeout'] = '20';
      $config['smtp_crypto'] = 'ssl';
    }
    
    if (!isset($config['protocol']) && substr(PHP_OS, 0, 3) == 'WIN')
    {
      $config['protocol'] = 'mail';
      $config['newline'] = "\r\n";
      $config['crlf'] = "\r\n";
    }
    
    if (!isset($config['protocol']))
    {
      $config['protocol'] = 'sendmail';
    }
    
    $this->email->initialize($config);
    
    return $config['protocol'];
  }
}

/* End of file newsletters.php */
/* Location: ./system/modules/newsletters/controllers/newsletters.php */