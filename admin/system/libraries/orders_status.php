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

class TOC_Orders_Status
{
  private $_ci;
  
  public function __construct()
  {
    $this->_ci = & get_instance();
    $this->_ci->load->model('orders_status_model');
  }
  
  public function get_data($id)
  {
    $data = $this->_ci->orders_status_model->get_data($id);
    
    return $data;
  }
}

/* End of file orders_status.php */
/* Location: ./system/library/orders_status.php */