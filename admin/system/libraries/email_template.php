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

class TOC_Email_Template {
  private $_ci;
  
  private $_template_name = '';
  private $_status;
  private $_title;
  private $_content;
  private $_email_text;
  private $_attachments = array();
  private $_recipients = array();
  private $_keywords = array();
  
  public function __construct($template_name)
  {
    $this->_ci = & get_instance();
    $this->_ci->load->model('email_template_model');
    
    $template = $this->_ci->email_template_model->get_email_template($template_name);
    
    if (!empty($template))
    {
      $this->_status = $template['email_templates_status'];
      $this->_title = $template['email_title'];
      $this->_content = $template['email_content'];
    }
  }
  
  public static function get_email_template($template_name)
  {
    $ci = & get_instance();
    
    $file_path = APPPATH . 'modules/email_templates/' . $template_name . '/' . $template_name . '.php';
    
    if (file_exists($file_path))
    {
      $ci->load->module('email_templates/' . $template_name);
    }
  }

}

/* End of file email_template.php */
/* Location: ./system/library/email_template.php */