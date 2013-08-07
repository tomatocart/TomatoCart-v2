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
 * @filesource
 */

class Email_Templates extends TOC_Controller 
{
  protected $_template_name = '';
  protected $_status;
  protected $_title;
  protected $_content;
  protected $_email_text;
  protected $_keywords = array();
  protected $_attachments = array();
  protected $_recipients = array();
  
  public function __construct($template_name)
  {
    parent::__construct();
    
    $this->load->model('email_templates_model');
    
    if (!empty($template_name))
    {
      $template = $this->email_templates_model->get_template_info($template_name);
      
      if (!empty($template))
      {
        $this->_status = $template['email_templates_status'];
        $this->_title = $template['email_title'];
        $this->_content = $template['email_content'];
      }
    }
  }
  
  public static function get_email_template($template_name)
  {
    $ci = & get_instance();
    
    $file_path = APPPATH . 'modules/' . $template_name . '/controllers/' . $template_name . '.php';
    
    if (file_exists($file_path))
    {
      $ci->load->module($template_name);
    }
  }
  
  public function add_recipient($name, $email_address)
  {
    $this->_recipients[] = array('name' => $name, 'email' => $email_address);
  }
  
  public function send_email()
  {
    $this->load->library('email');
    
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = SMTP_HOST;
    $config['smtp_user'] = SMTP_USERNAME;
    $config['smtp_pass'] = SMTP_PASSWORD;
    $config['smtp_port'] = SMTP_PORT;
    $config['smtp_timeout'] = '5';
    
    if (substr(PHP_OS, 0, 3) == 'WIN')
    {
      $config['newline'] = "\r\n";
      $config['crlf'] = "\r\n";
    }
   
    $this->email->initialize($config);
    
    if ($this->_status == '1')
    {
      foreach($this->_recipients as $recipient)
      {
        if (SEND_EMAILS == '-1') 
        {
          return FALSE;
        }
        
        $this->email->set_mailtype('html');
        $this->email->from(STORE_OWNER_EMAIL_ADDRESS, STORE_OWNER);
        $this->email->to($recipient['email']);
        $this->email->subject($this->_title);
        $this->email->message($this->_email_text);
        
//        if ($this->has_attachment())
//        {
//          foreach ($this->_attachments as $attachment) 
//          {
//            $this->mail->attach($attachment[0] , $attachment[1]);  
//          }
//        }
        
        if ($this->email->send())
        {
          return TRUE;
        }
      }
    }
    
    return FALSE;
  }
  
  public function has_attachment()
  {
    if (count($this->_attachments) != 0) 
    {
      return TRUE;
    }
    
    return FALSE;
  }
}

/* End of file email_templates.php */
/* Location: ./system/modules/email_templates/controllers/email_templates.php */