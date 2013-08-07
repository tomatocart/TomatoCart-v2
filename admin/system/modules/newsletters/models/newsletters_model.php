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
 * @filesource ./system/modules/newsletters/models/newsletters_model.php
 */

class Newsletters_Model extends CI_Model
{
  public function get_newsletters($start, $limit)
  {
    $Qnewsletters = $this->db
    ->select('newsletters_id, title, length(content) as content_length, module, date_added, date_sent, status, locked')
    ->from('newsletters')
    ->order_by('date_added desc')
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qnewsletters->result_array();
  }
  
  public function delete($id)
  {
    $this->db->trans_begin();
    
    $this->db->delete('newsletters_log', array('newsletters_id' => $id));
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('newsletters', array('newsletters_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function save($id = NULL, $data)
  {
    if (is_numeric($id))
    {
      $this->db->update('newsletters', $data, array('newsletters_id' => $id));
    }
    else
    {
      $data['date_added'] = date('Y-m-d H:i:s');
      $data['status'] = 0;
      
      $this->db->insert('newsletters', $data);
    }
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_data($id)
  {
    $Qnewsletter = $this->db
    ->select('*')
    ->from('newsletters')
    ->where('newsletters_id', $id)
    ->get();
    
    return $Qnewsletter->row_array();
  }
  
  public function get_customers()
  {
    $Qcustomers = $this->db
    ->select('customers_id, customers_firstname, customers_lastname, customers_email_address')
    ->from('customers')
    ->order_by('customers_lastname')
    ->get();
    
    return $Qcustomers->result_array();
  }
  
  public function get_total_customers($newsletters_id, $customers_ids)
  {
    $this->db
    ->select('c.customers_id')
    ->from('customers c')
    ->join('newsletters_log nl', 'c.customers_email_address = nl.email_address and nl.email_address is null and nl.newsletters_id = ' . $newsletters_id, 'left');
    
    if (!in_array('***', $customers_ids))
    {
      $this->db->where_in('c.customers_id', $customers_ids);
    }
    
    $Qcustomers = $this->db->get();
    
    return $Qcustomers->num_rows();
  }
  
  public function get_audiences($newsletters_id, $customers_ids)
  {
    $this->db
    ->select('c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address')
    ->from('customers c')
    ->join('newsletters_log nl', 'c.customers_email_address = nl.email_address and nl.email_address is null and nl.newsletters_id = ' . $newsletters_id, 'left');
    
    if (!in_array('***', $customers_ids))
    {
      $this->db->where_in('c.customers_id', $customers_ids);
    }
    
    $Qcustomers = $this->db->get();
    
    return $Qcustomers->result_array();
  }
  
  public function log($newsletters_id, $email_address)
  {
    $this->db->insert('newsletters_log', array('newsletters_id' => $newsletters_id, 
                                               'email_address' => $email_address, 
                                               'date_sent' => date('Y-m-d H:i:s')));
  }
  
  public function update($newsletters_id)
  {
    $this->db->update('newsletters', array('date_sent' => date('Y-m-d H:i:s'), 'status' => 1), 
                                     array('newsletters_id' => $newsletters_id));
  }
  
  public function get_logs($start, $limit, $newsletters_id)
  {
    $Qlog = $this->db
    ->select('email_address, date_sent')
    ->from('newsletters_log')
    ->where('newsletters_id', $newsletters_id)
    ->order_by('date_sent desc')
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qlog->result_array();
  }
  
  public function get_newsletters_recipients($newsletters_id)
  {
    $Qrecipients = $this->db
    ->select('c.customers_firstname, c.customers_lastname, c.customers_email_address')
    ->from('customers c')
    ->join('newsletters_log nl', 'c.customers_email_address = nl.email_address and nl.email_address is null and nl.newsletters_id = ' . $newsletters_id, 'left')
    ->where('c.customers_newsletter', 1)
    ->get();
    
    return $Qrecipients->num_rows();
  }
  
  public function get_recipients($newsletters_id)
  {
    $Qrecipients = $this->db
    ->select('c.customers_firstname, c.customers_lastname, c.customers_email_address')
    ->from('customers c')
    ->join('newsletters_log nl', 'c.customers_email_address = nl.email_address and nl.email_address is null and nl.newsletters_id = ' . $newsletters_id, 'left')
    ->where('c.customers_newsletter', 1)
    ->get();
    
    return $Qrecipients->result_array();
  }
  
  public function get_products()
  {
    $Qproducts = $this->db
    ->select('pd.products_id, pd.products_name')
    ->from('products p')
    ->join('products_description pd', 'pd.products_id = p.products_id')
    ->where(array('p.products_status' => 1, 'pd.language_id' => lang_id()))
    ->order_by('pd.products_name')
    ->get();
    
    return $Qproducts->result_array();
  }
  
  public function get_total_logs($newsletters_id)
  {
    return $this->db->where('newsletters_id', $newsletters_id)->from('newsletters_log')->count_all_results();
  }
  
  public function get_total()
  {
    return $this->db->count_all('newsletters');
  }
}


/* End of file newsletters_model.php */
/* Location: ./system/modules/newsletters/models/newsletters_model.php */