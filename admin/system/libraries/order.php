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

class TOC_Order {
  private $_ci;
  
  private $_valid_order = FALSE;
  private $_contents = array();
  private $_sub_total = 0;
  private $_total = 0;
  private $_weight = 0;
  private $_tax = 0;
  private $_tax_groups = array();
  private $_is_gift_wrapping = FALSE;
  private $_gift_wrapping_message = '';
  private $_coupon_code = NULL;
  private $_coupon_amount = 0;
  private $_order_id = 0;
  private $_customers_id = 0;
  private $_invoice_number = NULL;
  private $_invoice_date = NULL;
  private $_payment_method = '';
  private $_payment_module = '';
  private $_deliver_method = '';
  private $_deliver_module = '';
  private $_gift_certificate_codes = array();
  private $_gift_certificate_redeem_amount = array();
  private $_content_type;
  private $_customer_credit = 0;
  private $_use_customer_credit = FALSE;
  private $_has_payment_method = TRUE;
  
  public function __construct($order_id = '')
  {
    // Set the super object to a local variable for use later
    $this->_ci = & get_instance();
    
    $this->_ci->load->model('order_model');
    
    $this->_valid_order = FALSE;
    
    if (is_numeric($order_id)) 
    {
      $this->_get_summary($order_id);
      $this->_get_products();
    }
  }
  
  public function get_products()
  {
    if (!isset($this->_contents))
    {
      $this->_getProducts();
    }

    return $this->_contents;
  }
  
  public function get_currency($id = 'code') 
  {
    if (isset($this->_currency[$id])) 
    {
      return $this->_currency[$id];
    }

    return false;
  }

  public function get_currency_value() 
  {
    return $this->get_currency('value');
  }
  
  public function get_totals() 
  {
    if (!isset($this->_order_totals)) 
    {
      $this->_get_totals();
    }

    return $this->_order_totals;
  }
  
  public function get_delivery($id = '')
  {
    if (empty($id)) 
    {
     return $this->_shipping_address;
    } 
    elseif (isset($this->_shipping_address[$id])) 
    {
     return $this->_shipping_address[$id];
    }

    return false;
  }
  
  public function get_deliver_method()
  {
    return $this->_deliver_method;
  }
  
  public function get_billing($id = '')
  {
    if (empty($id)) 
    {
      return $this->_billing_address;
    } 
    elseif (isset($this->_billing_address[$id])) 
    {
      return $this->_billing_address[$id];
    }

    return false;
  }
  
  public function get_payment_method()
  {
    return $this->_payment_method;
  }
  
  public function delete($id, $restock = FALSE)
  {
    $this->_ci->load->library('product', $id);
    
    $delete = $this->_ci->order_model->delete($id, $restock);
    
    return $delete;
  }
  
  public function get_customers_id() 
  {
    return $this->_customers_id;
  }
  
  public function get_customer($id = '')
  {
    if (empty($id)) 
    {
      return $this->_customer;
    } 
    elseif (isset($this->_customer[$id])) 
    {
      return $this->_customer[$id];
    }

    return FALSE;
  }
  
  public function is_valid_credit_card()
  {
    if (!empty($this->_credit_card['owner']) && !empty($this->_credit_card['number']) && !empty($this->_credit_card['expires']))
    {
      return true;
    }
    
    return FALSE;
  }
  
  public function get_credit_card_details($id = '')
  {
    if (empty($id)) 
    {
      return $this->_credit_card;
    } 
    elseif (isset($this->_credit_card[$id])) 
    {
      return $this->_credit_card[$id];
    }

    return FALSE;
  }
  
  public function get_status()
  {
    if (!isset($this->_status)) 
    {
      $this->_get_status();
    }
    
    return $this->_status;
  }
  
  public function get_datelast_modified()
  {
    return $this->_last_modified;
  }
  
  public function get_date_created()
  {
    return $this->_date_purchased;
  }
  
  public function get_number_of_comments()
  {
    $number_of_comments = 0;

    if (!isset($this->_status_history)) 
    {
      $this->_get_status_history();
    }

    foreach ($this->_status_history as $status_history) 
    {
      if (!empty($status_history['comment'])) 
      {
        $number_of_comments++;
      }
    }

    return $number_of_comments;
  }
  
  public function get_customers_comment()
  {
    return $this->_customers_comment;
  }
  
  public function get_admin_comment()
  {
    return $this->_admin_comment;
  }
  
  public function get_total($id = 'total')
  {
    if (!isset($this->_order_totals)) 
    {
      $this->_get_totals();
    }

    foreach ($this->_order_totals as $total) 
    {
      if ($total['class'] == $id) 
      {
        return strip_tags($total['text']);
      }
    }
    
    return FALSE;
  }
  
  public function get_number_of_products()
  {
    if (!isset($this->_contents)) 
    {
      $this->_get_products();
    }

    return sizeof($this->_contents);
  }
  
  public function get_number_of_items()
  {
    $number_of_items = 0;

    if (!isset($this->_contents)) 
    {
      $this->_get_products();
    }

    foreach ($this->_contents as $product) 
    {
      $number_of_items += $product['quantity'];
    }

    return $number_of_items;
  }
  
  public function update_admin_comment($orders_id, $comment)
  {
    $comment_updated = $this->_ci->order_model->update_admin_comment($orders_id, $comment);

    if ($comment_updated > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_transaction_history()
  {
    if (!isset($this->_transaction_history)) 
    {
      $this->_get_transaction_history();
    }

    return $this->_transaction_history;
  }
  
  public function get_status_history()
  {
    if (!isset($this->_status_history)) 
    {
      $this->_get_status_history();
    }

    return $this->_status_history;
  }
  
  private function _get_transaction_history()
  {
    $this->_transaction_history = array();
    
    $histories = $this->_ci->order_model->get_transaction_history($this->_order_id);
    
    if (!empty($histories))
    {
      foreach($histories as $history)
      {
        $this->_transaction_history[] = array('status_id' => $history['transaction_code'],
                                              'status' => $history['status_name'],
                                              'return_value' => $history['transaction_return_value'],
                                              'return_status' => $history['transaction_return_status'],
                                              'date_added' => $history['date_added']);
      }
    }
  }
  
  private function _get_status_history()
  {
    $histories = $this->_ci->order_model->get_status_history($this->_order_id);
    
    $result = array();
    if (!empty($histories))
    {
      foreach($histories as $history)
      {
        $result[] = array('status_id' => $history['orders_status_id'],
                          'orders_status_history_id' => $history['orders_status_history_id'],
                          'status' => $history['orders_status_name'],
                          'date_added' => $history['date_added'],
                          'customer_notified' => $history['customer_notified'],
                          'comment' => $history['comments']);
      }
    }
    
    $this->_status_history = $result;
  }
  
  private function _get_status()
  {
    $status = $this->_ci->order_model->get_status($this->_status_id);
    
    if (!empty($status))
    {
      $this->_status = $status['orders_status_name'];
    }
    else
    {
      $this->_status = $this->_status_id;
    }
  }
  
  private function _get_summary($order_id)
  {
    $order = $this->_ci->order_model->get_order($order_id);
    
    if (!empty($order))
    {
      $this->_valid_order = TRUE;
      $this->_order_id = $order['orders_id'];
      $this->_customers_id = $order['customers_id'];
      $this->_invoice_number = $order['invoice_number'];
      $this->_invoice_date = $order['invoice_date'];
      $this->_is_gift_wrapping = $order['gift_wrapping'];
      
      $customers_name = explode(' ', $order['customers_name']);
      $first_name = (isset($customers_name[0]) ? $customers_name[0] : '');
      $last_name = (isset($customers_name[1]) ? $customers_name[1] : '');
      
      $this->_customer = array('firstname' => $first_name,
                               'lastname' => $last_name,
                               'name' => $order['customers_name'],
                               'customers_id' => $order['customers_id'],
                               'company' => $order['customers_company'],
                               'street_address' => $order['customers_street_address'],
                               'suburb' => $order['customers_suburb'],
                               'city' => $order['customers_city'],
                               'postcode' => $order['customers_postcode'],
                               'state' => $order['customers_state'],
                               'zone_code' => $order['customers_state_code'],
                               'country_title' => $order['customers_country'],
                               'country_iso2' => $order['customers_country_iso2'],
                               'country_iso3' => $order['customers_country_iso3'],
                               'format' => $order['customers_address_format'],
                               'gift_wrapping' => $order['gift_wrapping'],
                               'wrapping_message' => $order['wrapping_message'],
                               'telephone' => $order['customers_telephone'],
                               'email_address' => $order['customers_email_address']);
      
      $delivery_name = explode(' ', $order['delivery_name']);
      $first_name = ( isset($delivery_name[0]) ? $delivery_name[0] : '');
      $last_name = ( isset($delivery_name[1]) ? $delivery_name[1] : '');
      
      $this->_shipping_address = array('firstname' => $first_name,
                                       'lastname' => $last_name,
                                       'name' => $order['delivery_name'],
                                       'company' => $order['delivery_company'],
                                       'street_address' => $order['delivery_street_address'],
                                       'suburb' => $order['delivery_suburb'],
                                       'city' => $order['delivery_city'],
                                       'postcode' => $order['delivery_postcode'],
                                       'state' => $order['delivery_state'],
                                       'zone_id' => $order['delivery_zone_id'],
                                       'zone_code' => $order['delivery_state_code'],
                                       'country_id' => $order['delivery_country_id'],
                                       'country_title' => $order['delivery_country'],
                                       'country_iso_code_2' => $order['delivery_country_iso2'],
                                       'country_iso_code_3' => $order['delivery_country_iso3'],
                                       'format' => $order['delivery_address_format']);
      
      $billing_name = explode(' ', $order['billing_name']);
      $first_name = ( isset($billing_name[0]) ? $billing_name[0] : '');
      $last_name = ( isset($billing_name[1]) ? $billing_name[1] : '');
      
      $this->_billing_address = array('firstname' => $first_name,
                                      'lastname' => $last_name,
                                      'name' => $order['billing_name'],
                                      'company' => $order['billing_company'],
                                      'street_address' => $order['billing_street_address'],
                                      'suburb' => $order['billing_suburb'],
                                      'city' => $order['billing_city'],
                                      'postcode' => $order['billing_postcode'],
                                      'state' => $order['billing_state'],
                                      'zone_id' => $order['billing_zone_id'],
                                      'zone_code' => $order['billing_state_code'],
                                      'country_id' => $order['billing_country_id'],
                                      'country_title' => $order['billing_country'],
                                      'country_iso_code_2' => $order['billing_country_iso2'],
                                      'country_iso_code_3' => $order['billing_country_iso3'],
                                      'format' => $order['billing_address_format']);
      
      $payment_methods = $order['payment_method'];
      $payment_modules = $order['payment_module'];
      
      $payment_methods = explode(',', $payment_methods);
      $payment_modules = explode(',', $payment_modules);
      
      if (current($payment_modules) == 'store_credit') {
        $this->_use_customer_credit = TRUE;
        
        if (sizeof($payment_modules) == 2) {
          $this->_payment_method = next($payment_methods);
          $this->_payment_module = next($payment_modules);
        } else {
          $this->_has_payment_method = FALSE;
          $this->_payment_method = $order['payment_method'];
        }
      } else {
        $this->_payment_method = current($payment_methods);
        $this->_payment_module = current($payment_modules);
      }
      
      $this->_date_purchased = $order['date_purchased'];
      $this->_last_modified = $order['last_modified'];
      $this->_status_id = $order['orders_status'];
      
      $this->_customers_comment = ($order['customers_comment'] == NULL) ? '' : $order['customers_comment'];
      $this->_admin_comment = ($order['admin_comment'] == NULL) ? '' : $order['admin_comment'];
      
      $this->_currency = array('code' => $order['currency'],
                               'value' => $order['currency_value']);
      
      $this->_get_totals();
    }
  }
  
  private function _get_products()
  {
    $this->_ci->load->helper('core');
    
    $order_products = $this->_ci->order_model->get_products($this->_order_id);
    
    if (!empty($order_products))
    {
      foreach($order_products as $order_product)
      {
        $product = array('id' => $order_product['products_id'],
                         'orders_products_id' => $order_product['orders_products_id'],
                         'type' => $order_product['products_type'],
                         'quantity' => $order_product['products_quantity'],
                         'return_quantity' => $order_product['products_return_quantity'],
                         'name' => $order_product['products_name'],
                         'sku' => $order_product['products_sku'],
                         'tax' => $order_product['products_tax'],
                         'tax_class_id' => $order_product['products_tax_class_id'],
                         'price' => $order_product['products_price'],
                         'final_price' => $order_product['final_price'],
                         'weight' => $order_product['products_weight'],
                         'tax_class_id' => $order_product['products_tax_class_id'],
                         'weight_class_id' => $order_product['products_weight_class']);
        
        if ($product['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE)
        {
          $certificate = $this->_ci->order_model->get_certificate($this->_order_id, $product['orders_products_id']);
          
          if (!empty($certificate))
          {
            $product['gift_certificates_type'] = $certificate['gift_certificates_type'];
            $product['gift_certificates_code'] = $certificate['gift_certificates_code'];
            $product['senders_name'] = $certificate['senders_name'];
            $product['senders_email'] = $certificate['senders_email'];
            $product['recipients_name'] = $certificate['recipients_name'];
            $product['recipients_email'] = $certificate['recipients_email'];
            $product['messages'] = $certificate['messages'];
          }
        }
        
        $variants_result = $this->_ci->order_model->get_variants($this->_order_id, $product['orders_products_id']);
        
        $variants = array();
        if (!empty($variants_result))
        {
          foreach($variants_result as $variant)
          {
            $product['variants'][] = array('groups_id' => $variant['groups_id'],
                                           'values_id' => $variant['values_id'], 
                                           'groups_name' => $variant['groups_name'], 
                                           'values_name' => $variant['values_name']);
            
            $variants[$variant['groups_id']] = $variant['values_id'];
          }
        }
        
        $customizations_result = $this->_ci->order_model->get_customizations($this->_order_id, $product['orders_products_id']);
        
        $customizations = null;
        if (!empty($customizations_result))
        {
          foreach($customizations_result as $customization)
          {
            $fields_result = $this->_ci->order_model->get_customization_fields($customization['orders_products_customizations_id']);
            
            $fields = array();
            if (!empty($fields_result))
            {
              foreach($fields_result as $field)
              {
                $fields[$field['orders_products_customizations_values_id']] = array('customization_fields_id' => $field['customization_fields_id'], 
                                                                                    'customization_fields_name' => $field['customization_fields_name'], 
                                                                                    'customization_type' => $field['customization_fields_type'], 
                                                                                    'customization_value' => $field['customization_fields_value'], 
                                                                                    'cache_filename' => $field['cache_file_name']);
              }
            }
            
            $customizations[] = array('qty' => $customization['quantity'], 'fields' => $fields);
          }
        }
        
        if ($customizations != null) 
        {
          $product['customizations'] = $customizations;
        }
        
        if ($product['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE) 
        {
          $products_id_string = $product['id'] . '#' . $product['orders_products_id'];
        } 
        else 
        {
          $products_id_string = get_product_id_string($product['id'], $variants);
        }
        
        $this->_contents[$products_id_string] = $product;
      }
    }
  }
  
  private function _get_totals()
  {
    $totals = $this->_ci->order_model->get_totals($this->_order_id);
    
    $result = array();
    if (!empty($totals))
    {
      foreach($totals as $total)
      {
        $result[] = array('title' => $total['title'], 
                          'text' => $total['text'], 
                          'value' => $total['value'], 
                          'class' => $total['class']);
        
        $class = $total['class'];
        
        //shipping
        if (strpos($class, 'shipping') !== FALSE)
        {
          list($shipping, $module) = explode('-', $class);
          
          $tmp = explode(':', $total['title']);
          $this->_deliver_method = $tmp[0];
          $this->_deliver_module = $module;
        }
        //coupon
        else if ($class == 'coupon')
        {
          $this->_coupon_code = substr(strstr($total['title'], '('), 1, -3);
          $this->_coupon_amount = $total['value'];
        }
        //gift certificate
        else if ($class == 'gift_certificate')
        {
          $this->_gift_certificate_codes = array();
          $this->_gift_certificate_redeem_amount = array();
          
          $codes = explode(",", substr(strstr($total['title'], '('), 1, -3));
          foreach ($codes as $code) 
          {
            $gift_certificate = explode("[", trim($code));
            $this->_gift_certificate_codes[] = trim($gift_certificate[0]);
            $this->_gift_certificate_redeem_amount[] = substr(trim($gift_certificate[1]), 0, -1);
          }
        }
        //sub total
        else if ($class == 'sub_total')
        {
          $this->_sub_total = $total['value'];
        }
        //tax
        else if ($class == 'tax')
        {
          $this->_tax = $total['value'];
        }
        //total
        else if ($class == 'total')
        {
          $this->_total = $total['value'];
        }
      }
    }
    
    $this->_order_totals = $result;
  }
  
  public function get_invoice_number()
  {
    return $this->_invoice_number;
  }
  
  public function get_invoice_date()
  {
    return $this->_invoice_date;
  }
  
  public function get_order_id() 
  {
    return $this->_order_id;
  }
}

/* End of file address.php */
/* Location: ./system/library/order.php */