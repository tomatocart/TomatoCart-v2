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

class Order_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_order($id)
  {
    $Qorder = $this->db
    ->select('*')
    ->from('orders')
    ->where('orders_id', $id)
    ->get();
    
    return $Qorder->row_array();
  }
  
  public function get_totals($id)
  {
    $Qtotals = $this->db
    ->select('title, text, value, class')
    ->from('orders_total')
    ->where('orders_id', $id)
    ->get();
    
    return $Qtotals->result_array();
  }
  
  public function get_products($id)
  {
    $Qproducts = $this->db
    ->select('op.orders_products_id, op.products_id, op.products_type, op.products_name, op.products_sku, op.products_price, op.products_tax, op.products_quantity, op.products_return_quantity, op.final_price, p.products_weight, p.products_weight_class, p.products_tax_class_id')
    ->from('orders_products op')
    ->join('products p', 'p.products_id = op.products_id')
    ->where('op.orders_id', $id)
    ->get();
    
    return $Qproducts->result_array();
  }
  
  public function get_certificate($orders_id, $orders_products_id)
  {
    $Qcertificate = $this->db
    ->select('gift_certificates_type, gift_certificates_code, senders_name, senders_email, recipients_name, recipients_email, messages')
    ->from('gift_certificates')
    ->where(array('orders_id' => $orders_id, 'orders_products_id' => $orders_products_id))
    ->get();
    
    return $Qcertificate->row_array();
  }
  
  public function get_variants($orders_id, $orders_products_id)
  {
    $Qvariants = $this->db
    ->select('products_variants_groups_id as groups_id, products_variants_groups as groups_name, products_variants_values_id as values_id, products_variants_values as values_name')
    ->from('orders_products_variants')
    ->where(array('orders_id' => $orders_id, 'orders_products_id' => $orders_products_id))
    ->get();
    
    return $Qvariants->result_array();
  }
  
  public function get_customizations($orders_id, $orders_products_id)
  {
    $Qcustomizations = $this->db
    ->select('orders_products_customizations_id, quantity')
    ->from('orders_products_customizations')
    ->where(array('orders_id' => $orders_id, 'orders_products_id' => $orders_products_id))
    ->get();
    
    return $Qcustomizations->result_array();
  }
  
  public function get_customization_fields($orders_products_customizations_id)
  {
    $Qfields = $this->db
    ->select('*')
    ->from('orders_products_customizations_values')
    ->where('orders_products_customizations_id', $orders_products_customizations_id)
    ->get();
    
    return $Qfields->result_array();
  }
  
  public function delete($id, $restock)
  {
    $error = FALSE;
    
    $this->db->trans_begin();
    
    if ($restock === TRUE)
    {
      $Qproducts = $this->db
      ->select('orders_products_id, products_id, products_type, products_quantity')
      ->from('orders_products')
      ->where('orders_id', $id)
      ->get();
      
      if ($Qproducts->num_rows() > 0)
      {
        foreach($Qproducts->result_array() as $product)
        {
          $result = $this->product->restock($id, $product['orders_products_id'], $product['products_id'], $product['products_quantity']);
          
          if ($result == FALSE)
          {
            $error = TRUE;
            
            break;
          }
        }
      }
    }
      
    if ($error === FALSE)
    {
      $sql = 'delete from ' . $this->db->protect_identifiers('orders_refunds_products', TRUE) . ' where orders_refunds_id = (select orders_refunds_id from ' . $this->db->protect_identifiers('orders_refunds', TRUE) . ' where orders_id = ?)';
      
      $this->db->query($sql, array($id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
      
    if ($error === FALSE)
    {
      $this->db->delete('orders_refunds', array('orders_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
      
    if ($error === FALSE)
    {
      $sql = 'delete from ' . $this->db->protect_identifiers('orders_returns_products', TRUE) . ' where orders_returns_id = (select orders_returns_id from ' . $this->db->protect_identifiers('orders_returns', TRUE) . ' where orders_id = ?)';
      $this->db->query($sql, array($id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
      
    if ($error === FALSE)
    {
      $this->db->delete('orders_returns', array('orders_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
      
    if ($error === FALSE)
    {
      $this->db->delete('orders_products_download', array('orders_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
      
    if ($error === FALSE)
    {
      $this->db->delete('gift_certificates', array('orders_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('orders_products_variants', array('orders_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('orders_products', array('orders_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('orders_transactions_history', array('orders_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('orders_status_history', array('orders_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('orders_total', array('orders_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('orders', array('orders_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_status($status_id)
  {
    $Qstatus = $this->db
    ->select('orders_status_name')
    ->from('orders_status')
    ->where(array('orders_status_id' => $status_id, 'language_id' => lang_id()))
    ->get();
    
    return $Qstatus->row_array();
  }
  
  public function get_status_history($orders_id)
  {
    $Qhistory = $this->db
    ->select('osh.orders_status_history_id, osh.orders_status_id, osh.date_added, osh.customer_notified, osh.comments, os.orders_status_name')
    ->from('orders_status_history osh')
    ->join('orders_status os', 'osh.orders_status_id = os.orders_status_id', 'left')
    ->where(array('os.language_id' => lang_id(), 'osh.orders_id' => $orders_id))
    ->get();
    
    return $Qhistory->result_array();
  }
  
  public function update_admin_comment($orders_id, $comment)
  {
    $this->db->update('orders', array('admin_comment' => $comment), array('orders_id' => $orders_id));
    
    return $this->db->affected_rows();
  }
  
  public function get_transaction_history($orders_id)
  {
    $Qhistory = $this->db
    ->select('oth.transaction_code, oth.transaction_return_value, oth.transaction_return_status, oth.date_added, ots.status_name')
    ->from('orders_transactions_history oth')
    ->join('orders_transactions_status ots', 'oth.transaction_code = ots.id', 'left')
    ->where(array('ots.language_id' => lang_id(), 'oth.orders_id' => $orders_id))
    ->order_by('oth.date_added')
    ->get();
    
    return $Qhistory->result_array();
  }
}

/* End of file order_model.php */
/* Location: ./system/models/order_model.php */