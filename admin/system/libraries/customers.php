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

class TOC_Customers 
{
  private $ci;
  
  public function __construct() {
    $this->ci =& get_instance();
    
    $this->ci->load->model('customers_model');
  }
  
  public function get_addressbook_data($customers_id)
  {
    $address_data = $this->ci->customers_model->get_addressbook_data($customers_id);
    
    return $address_data;
  }

}


/* End of file customers.php */
/* Location: ./system/library/customers.php */