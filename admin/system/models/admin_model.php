<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize, creative CMS
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.9.0
 */

// ------------------------------------------------------------------------

/**
 * Ionize, creative CMS Settings Model
 *
 * @package		Ionize
 * @subpackage	Models
 * @category	Admin settings
 * @author		Ionize Dev Team
 */

class Admin_Model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  function get_data($username)
  {
    $result = $this->db->select('id, user_name, user_password, user_settings, email_address')
    ->from('administrators')
    ->where('user_name', $username)
    ->get();
    
    $data = FALSE;
    if ($result->num_rows() > 0)
    {
      $data = $result->row_array();
    }
    
    return $data;
  }

  function check_account($username, $password)
  {
    $data = $this->get_data($username);

    if ($data !== FALSE)
    {
      $stack = explode(':', $data['user_password']);

      if (sizeof($stack) === 2)
      {
        if (md5($stack[1] . $password) == $stack[0])
        {
          return TRUE;
        }
      }
    }

    return FALSE;
  }

  function get_store_credit($customers_id)
  {
    $result = $this->db->select('customers_credits')->from('customers')->where('customers_id', $customers_id)->get();

    $store_credit = FALSE;
    if ($result->num_rows() > 0)
    {
      $row = $result->row_array();
      $store_credit = $row['customers_credits'];
    }

    return $store_credit;
  }

  function get_address($customers_id, $address_id)
  {
    $result = $this->db
    ->select('*')
    ->from('address_book')
    ->where('customers_id', $customers_id)
    ->where('address_book_id', $address_id)
    ->get();

    $data = FALSE;
    if ($query->num_rows() > 0)
    {
      $data = $query->row_array();
    }

    return $data;
  }

  /**
   * Get the settings
   * Don't retrieves the language depending settings
   *
   * @return	The settings array
   */
  function insert($data)
  {
    $this->db->insert('customers', $data);
  }
}
/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */