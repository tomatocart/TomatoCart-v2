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

class Admin_Order_Status_Updated extends Email_Templates 
{
  public function __construct()
  {
    $this->_template_name = 'admin_order_status_updated';
    
    $this->_keywords = array( '%%order_number%%',
                              '%%invoice_link%%',
                              '%%date_ordered%%',
                              '%%order_comment%%',
                              '%%new_order_status%%',
                              '%%customer_name%%',
                              '%%store_name%%',
                              '%%store_owner_email_address%%');
    
    parent::__construct($this->_template_name);
  }
  
  public function set_data($order_number, $invoice_link, $date_ordered, $append_comment, $order_comment, $new_order_status, $customer_name, $customers_email_address)
  {
    $this->_order_number = $order_number;
    $this->_invoice_link = $invoice_link;
    $this->_date_ordered = $date_ordered;
    $this->_order_comment = $order_comment;
    $this->_new_order_status = $new_order_status;
    $this->_append_comment = $append_comment;
    $this->_customer_name = $customer_name;
    
    $this->add_recipient($customer_name, $customers_email_address);
  }
  
  public function build_message()
  {
    if ( $this->_append_comment === false ) 
    {
      $this->_order_comment = '';
    }
    
    $replaces = array($this->_order_number, $this->_invoice_link, $this->_date_ordered, $this->_order_comment, $this->_new_order_status, $this->_customer_name, STORE_NAME, STORE_OWNER_EMAIL_ADDRESS);
    
    $this->_title = str_replace($this->_keywords, $replaces, $this->_title);
    $this->_email_text = str_replace($this->_keywords, $replaces, $this->_content);
  }
}


/* End of file admin_order_status_updated.php */
/* Location: ./system/modules/email_templates/admin_order_status_updated/controllers/admin_order_status_updated.php */