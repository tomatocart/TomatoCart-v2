<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Shopping Cart Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Shopping Cart
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/cart.html
 */
class TOC_Shopping_Cart {

  private $ci = null;
  private $contents = array();
  private $subtotal = 0;
  private $total = 0;
  private $weight = 0;
  private $tax = 0;
  private $tax_groups = array();
  private $is_gift_wrapping = false;
  private $gift_wrapping_message = '';
  private $coupon_code = null;
  private $coupon_amount = 0;
  private $gift_certificate_codes = array();
  private $gift_certificate_redeem_amount = array();
  private $content_type;
  private $customer_credit = 0;
  private $use_customer_credit = false;
  private $products_in_stock = true;

  /**
   * Shopping Class Constructor
   *
   * The constructor loads the Session class, used to store the shopping cart contents.
   */
  public function __construct()
  {
    // Set the super object to a local variable for use later
    $this->_ci =& get_instance();

    // Grab the shopping cart array from the session table, if it exists
    if ($this->_ci->session->userdata('cart_contents') !== FALSE)
    {
      $this->contents = $this->_ci->session->userdata('cart_contents');
    }
    else
    {
      // No cart exists so we'll set some base values
      $this->contents['cart_total'] = 0;
      $this->contents['total_items'] = 0;
    }

    log_message('debug', "TOC Shopping Cart Class Initialized");
  }
  
  public function reset() 
  {
    
  }

  // --------------------------------------------------------------------

  /**
   * Insert items into the cart and save it to the session table
   *
   * @access  public
   * @param array
   * @return  bool
   */
  function add($products_id_string)
  {
    
    $this->_ci->load->model('products_model');
    $data = $this->_ci->products_model->get_product_data($products_id_string);
    
    $this->contents[] = array(
      'products_id' => $products_id_string,
      'products_name' => $data['products_name'],
      'products_model' => $data['products_model'],
      'image'  => $data['image']);

    // If we made it this far it means that our cart has data.
    // Let's pass it to the Session class so it can be stored
    $this->_ci->session->set_userdata(array('cart_contents' => $this->contents));

    return TRUE;
  }
  
  function get_contents()
  {
    return $this->contents;
  }
}
// END Cart Class

/* End of file Cart.php */
/* Location: ./system/libraries/Cart.php */