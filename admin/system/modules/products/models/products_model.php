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
 * @filesource models/products.php
 */

class Products_Model extends CI_Model 
{
  private $_total_products;
  
  public function __construct()
  {
    parent::__construct();
    
    $this->_total_products = $this->db->count_all('products');
  
  }  
  public function get_products($start, $limit, $in_categories, $search)
  {
    $records = array();
    
    $this->db
    ->select('p.products_id, p.products_type, pd.products_name, p.products_quantity, p.products_price, p.products_quantity, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status')
    ->from('products p')
    ->join('products_description pd', 'p.products_id = pd.products_id', 'inner');
         
    if (!empty($in_categories))
    {
      $this->db
      ->join('products_to_categories p2c', 'p.products_id = p2c.products_id', 'inner')
      ->where_in('p2c.categories_id', $in_categories);
    }
    
    $this->db
    ->where('pd.language_id', lang_id());
    
    if (!empty($search))
    {
      $this->db->like('pd.products_name', $search);
    }
    
    $this->db
    ->order_by('pd.products_id desc')
    ->limit($limit, $start > 0 ? $start-1 : $start);
    
    $Qproducts = $this->db->get();
    
    if ($Qproducts->num_rows() > 0)
    {
      foreach($Qproducts->result_array() as $product)
      {
        if ($product['products_type'] == PRODUCT_TYPE_GIFT_CERTIFICATE)
        {
          $Qcertificate = $this->db
          ->select('open_amount_min_value, open_amount_max_value')
          ->from('products_gift_certificates')
          ->where(array('gift_certificates_amount_type' => GIFT_CERTIFICATE_TYPE_OPEN_AMOUNT, 'products_id' => $product['products_id']))
          ->get();
        }
        
        $Qstatus = $this->db
        ->select('products_id')
        ->from('products_frontpage')
        ->where('products_id', $product['products_id'])
        ->get();
        
        if ($Qstatus->num_rows() > 0)
        {
          $products_frontpage = 1;
        }
        else
        {
          $products_frontpage = 0;
        }
        
        $records[] = array(
          'products_id'         => $product['products_id'],
          'products_name'       => $product['products_name'],
          'products_frontpage'  => $products_frontpage,
          'products_status'     => $product['products_status'],
          'products_price'      => $this->currencies->format($product['products_price']),
          'products_quantity'   => $product['products_quantity']
        );
      }
    }
    
    return $records;
  }
  
  public function get_manufacturers()
  {
    $manufacturers = array(array('id' => '0','text' => lang('none')));
    
    $Qmanufacturers = $this->db
    ->select('manufacturers_id, manufacturers_name')
    ->from('manufacturers')
    ->order_by('manufacturers_name')
    ->get();
    
    if ($Qmanufacturers->num_rows() > 0)
    {
      foreach($Qmanufacturers->result_array() as $manufacturer)
      {
        $manufacturers[] = array('id' => $manufacturer['manufacturers_id'], 
                                 'text' => $manufacturer['manufacturers_name']);
      }
    }
    
    return $manufacturers;
  }
  
  public function get_weight_classes()
  {
    $weight_classes = array();
    
    $Qwc = $this->db
    ->select('weight_class_id, weight_class_title')
    ->from('weight_classes')
    ->where('language_id', lang_id())
    ->order_by('weight_class_title')
    ->get();
    
    if ($Qwc->num_rows() > 0)
    {
      foreach($Qwc->result_array() as $weight_class)
      {
        $weight_classes[] = array('id' => $weight_class['weight_class_id'],
                                  'text' => $weight_class['weight_class_title']);
      }
    }
    
    return $weight_classes;
  }
  
  public function get_tax_classes()
  {
    $tax_classes = array(array('id' => '0',
                               'rate' => '0',
                               'text' => lang('none')));
    
    $Qtc = $this->db
    ->select('tax_class_id, tax_class_title')
    ->from('tax_class')
    ->order_by('tax_class_title')
    ->get();
    
    if ($Qtc->num_rows() > 0)
    {
      foreach($Qtc->result_array() as $tax_class)
      {
        $tax_classes[] = array('id' => $tax_class['tax_class_id'],
                               'rate' => $this->tax->get_tax_rate($tax_class['tax_class_id']),
                               'text' => $tax_class['tax_class_title']);
      }
    }
    
    return $tax_classes;
  }
  
  public function get_quantity_discount_groups()
  {
    $quantity_discount_groups = array(array('id' => '0',
                                            'text' => lang('none')));
    
    $Qgroups = $this->db
    ->select('quantity_discount_groups_id, quantity_discount_groups_name')
    ->from('quantity_discount_groups')
    ->order_by('quantity_discount_groups_id')
    ->get();
    
    if ($Qgroups->num_rows() > 0)
    {
      foreach($Qgroups->result_array() as $group)
      {
        $quantity_discount_groups[] = array('id' => $quantity_discount_group['quantity_discount_groups_id'],
                                            'text' => $quantity_discount_group['quantity_discount_groups_name']);
      }
    }
    
    return $quantity_discount_groups;
  }
  
  public function get_quantity_units()
  {
    $units = array();
    
    $Qunits = $this->db
    ->select('quantity_unit_class_id, quantity_unit_class_title')
    ->from('quantity_unit_classes')
    ->where('language_id', lang_id())
    ->order_by('quantity_unit_class_title')
    ->get();
    
    if ($Qunits->num_rows() > 0)
    {
      foreach($Qunits->result_array() as $unit)
      {
        $units[] = array('id' => $unit['quantity_unit_class_id'],
                         'text' => $unit['quantity_unit_class_title']);
      }
    }
    
    return $units;
  }
  
  public function get_xsell_products($products_id)
  {
    $Qxsell = $this->db
    ->select('pd.products_id, pd.products_name')
    ->from('products_xsell px')
    ->join('products_description pd', 'px.xsell_products_id = pd.products_id', 'inner')
    ->where(array('px.products_id' => $products_id, 'pd.language_id' => lang_id()))
    ->get();
    
    return $Qxsell->result_array();
  }
  
  public function get_products_for_xsell($products_id, $start, $limit)
  {
    $products = array();
    
    $this->db
    ->select('p.products_id, pd.products_name, p.products_quantity, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status')
    ->from('products p')
    ->join('products_description pd', 'p.products_id = pd.products_id', 'inner')
    ->where('pd.language_id', lang_id());
    
    if (is_numeric($products_id) && $products_id > 0)
    {
      $this->db
      ->where('p.products_id !=', $products_id);
    }
    
    $Qproducts = $this->db
    ->limit($limit, $start)
    ->get();
    
    if ($Qproducts->num_rows() > 0)
    {
      foreach($Qproducts->result_array() as $product)
      {
        $products[] = array('id' => $product['products_id'],
                            'text' => $product['products_name']);
      }
    }
    
    return $products;
  }
  
  public function get_accessories($products_id) 
  {
    $products = array();
    
    if (!empty($products_id))
    {
      $Qaccessories = $this->db
      ->select('pd.products_id, pd.products_name')
      ->from('products_accessories pa')
      ->join('products_description pd', 'pa.accessories_id = pd.products_id', 'inner')
      ->where(array('pa.products_id' => $products_id, 'pd.language_id' => lang_id()))
      ->get();
      
      if ($Qaccessories->num_rows() > 0)
      {
        foreach($Qaccessories->result_array() as $accessory)
        {
          $products[] = array('accessories_id' => $accessory['products_id'],
                              'products_name' => $accessory['products_name']);
        }
      }
    }
    
    return $products;
  }
  
  public function get_images($products_id)
  {
    $Qimages = $this->db
    ->select('id, image, default_flag')
    ->from('products_images')
    ->where('products_id', $products_id)
    ->order_by('sort_order')
    ->get();
    
    return $Qimages->result_array();
  }
  
  public function load_variants_groups()
  {
    $Qgroups = $this->db
    ->select('products_variants_groups_id as groups_id, products_variants_groups_name as groups_name')
    ->from('products_variants_groups')
    ->where('language_id', lang_id())
    ->order_by('products_variants_groups_name')
    ->get();
    
    return $Qgroups->result_array();
  }
  
  public function get_variants_values($group_id)
  {
    $Qvalues = $this->db
    ->select('pvv.products_variants_values_id as variants_id, pvv.products_variants_values_name as variants_name')
    ->from('products_variants_values pvv')
    ->join('products_variants_values_to_products_variants_groups pv2pv', 'pvv.products_variants_values_id = pv2pv.products_variants_values_id', 'inner')
    ->where(array('pv2pv.products_variants_groups_id' => $group_id, 'pvv.language_id' => lang_id()))
    ->get();
    
    return $Qvalues->result_array();
  }
  
  public function save($id = NULL, $data)
  {
    $this->db->trans_begin();
    
    //products
    $products_data = array('products_type' => $data['products_type'], 
                           'products_sku' => $data['products_sku'], 
                           'products_model' => $data['products_model'], 
                           'products_price' => $data['price'], 
                           'products_quantity' => $data['quantity'], 
                           'products_moq' => $data['products_moq'], 
                           'products_max_order_quantity' => $data['products_max_order_quantity'], 
                           'order_increment' => $data['order_increment'], 
                           'products_weight' => $data['weight'], 
                           'products_weight_class' => $data['weight_class'], 
                           'products_status' => $data['status'], 
                           'products_tax_class_id' => $data['tax_class_id'], 
                           'manufacturers_id' => $data['manufacturers_id'], 
                           'quantity_discount_groups_id' => $data['quantity_discount_groups_id'],
                           'quantity_unit_class' => $data['quantity_unit_class'],
                           'products_last_modified' => date('Y-m-d'));
    
    if (date('Y-m-d') < $data['date_available'])
    {
      $products_data['products_date_available'] = $data['date_available'];
    }
    else
    {
      $products_data['products_date_available'] = NULL;
    }
    
    if (is_numeric($id))
    {
      $products_data['products_last_modified'] = date('Y-m-d');
      
      $Qproducts = $this->db->where('products_id', $id)->update('products', $products_data);
    }
    else
    {
      $products_data['products_date_added'] = date('Y-m-d');
      
      $Qproducts = $this->db->insert('products', $products_data);
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      if (is_numeric($id))
      {
        $products_id = $id;
      }
      else
      {
        $products_id = $this->db->insert_id();
      }
      
      //products_to_categories
      $Qcategories = $this->db->delete('products_to_categories', array('products_id' => $products_id));
      
      if (isset($data['categories']) && !empty($data['categories']))
      {
        foreach ($data['categories'] as $category_id) {
          $products_to_categories = array('products_id' => $products_id, 'categories_id' => $category_id);
          
          $Qp2c = $this->db->insert('products_to_categories', $products_to_categories);
        }
      }
    }
    
    //products_accessories
    if ($this->db->trans_status() === TRUE)
    {
      if (is_numeric($id))
      {
        $Qdelete = $this->db->delete('products_accessories', array('products_id' => $products_id));
      }
      
      if (isset($data['accessories_ids']) && sizeof($data['accessories_ids']) > 0)
      {
        foreach ($data['accessories_ids'] as $accessories_id)
        {
          $accessory_data = array('products_id' => $products_id, 'accessories_id' => $accessories_id);
          
          $this->db->insert('products_accessories', $accessory_data);
        }
      }
    }
    
    //products_description
    if ($this->db->trans_status() === TRUE)
    {
      foreach(lang_get_all() as $l)
      {
        $products_description_data = array('products_name' => $data['products_name'][$l['id']], 
                                           'products_short_description' => $data['products_short_description'][$l['id']], 
                                           'products_description' => $data['products_description'][$l['id']], 
                                           'products_tags' => $data['products_tags'][$l['id']], 
                                           'products_url' => $data['products_url'][$l['id']], 
                                           'products_friendly_url' => $data['products_friendly_url'][$l['id']], 
                                           'products_page_title' => $data['products_page_title'][$l['id']], 
                                           'products_meta_keywords' => $data['products_meta_keywords'][$l['id']], 
                                           'products_meta_description' => $data['products_meta_description'][$l['id']]);
                                           
        
        if (is_numeric($id))
        {
          $this->db->update('products_description', $products_description_data, array('products_id' => $products_id, 'language_id' => $l['id']));
        }
        else
        {
          $products_description_data['language_id'] = $l['id'];
          
          $this->db->insert('products_description', $products_description_data);
        }
      }
    }
    
    //BEGIN: products images
    if ($this->db->trans_status() === TRUE)
    {
      $images = array();
      
      $image_path = ROOTPATH . 'images/products/_upload/' . $this->session->userdata('session_id') . '/';
      
      foreach($this->directory_listing->getFiles() as $file)
      {
        @copy($image_path . $file['name'], ROOTPATH . 'images/products/originals/' . $file['name']);
        @unlink($image_path . $file['name']);
        
        $images[$file['name']] = -1;
      }
      
      delete_files($image_path);
      
      $default_flag = 1;
      
      $images_keys = array_keys($images);
      foreach ($images_keys as $image)
      {
        $image_data = array('products_id' => $products_id, 
                            'default_flag' => $default_flag, 
                            'sort_order' => 0,
                            'image' => '', 
                            'date_added' => date('Y-m-d'));
        
        $this->db->insert('products_images', $image_data);
        
        $image_id = $this->db->insert_id();
        
        $images[$image] = $image_id;
        
        $new_image_name =  $products_id . '_' . $image_id . '_' . $image;
        @rename(ROOTPATH . 'images/products/originals/' . $image, ROOTPATH . 'images/products/originals/' . $new_image_name);
        
        $this->db->update('products_images', array('image' => $new_image_name), array('id' => $image_id));
        
        foreach ($this->admin_image->getGroups() as $group) 
        {
          if ($group['id'] != '1')
          {
            $this->admin_image->resize($new_image_name, $group['id'], 'products');
          }
        }
        
        $default_flag = 0;
      }
    }
    //END: products images
    
    //BEGIN: products variants
    if ($this->db->trans_status() === TRUE)
    {
      //if edit product, delete variant first
      if (is_numeric($id))
      {
        $Qvariants = $this->db
        ->select('*')
        ->from('products_variants')
        ->where('products_id', $id)
        ->order_by('products_variants_id')
        ->get();
        
        $records = array();
        if ($Qvariants->num_rows() > 0)
        {
          foreach($Qvariants->result_array() as $product_variant)
          {
            $Qentries = $this->db
            ->select('products_variants_id, products_variants_groups_id, products_variants_values_id')
            ->from('products_variants_entries')
            ->where('products_variants_id', $product_variant['products_variants_id'])
            ->order_by('products_variants_groups_id', 'products_variants_values_id')
            ->get();
            
            $variants_values = array();
            if ($Qentries->num_rows() > 0)
            {
              foreach($Qentries->result_array() as $entry)
              {
                $variants_values[] = $entry['products_variants_groups_id'] . '_' . $entry['products_variants_values_id'];
              }
            }

            $variant = implode('-', $variants_values);
            
            if (!isset($data['products_variants_id'][$variant]))
            {
              //delete variants
              $this->db->delete('products_variants', array('products_variants_id' => $product_variant['products_variants_id']));
              
              //delete variants entries
              if ($this->db->trans_status() === TRUE)
              {
                $this->db->delete('products_variants_entries', array('products_variants_id' => $product_variant['products_variants_id']));
              }
            }
          }
        }
      }
      
      $products_quantity = 0;
      
      //insert or update variant
      if (isset($data['products_variants_id']) && is_array($data['products_variants_id']))
      {
        foreach($data['products_variants_id'] as $key => $variants_id)
        {
          $product_variants_data = array('is_default' => $data['variants_default'][$key], 
                                         'products_price' => $data['variants_price'][$key], 
                                         'products_sku' => $data['variants_sku'][$key], 
                                         'products_model' => $data['variants_model'][$key], 
                                         'products_quantity' => $data['variants_quantity'][$key], 
                                         'products_weight' => $data['variants_weight'][$key], 
                                         'products_status' => $data['variants_status'][$key], 
                                         'filename' => '', 
                                         'cache_filename' => '');
          
          $products_images_id = is_numeric($data['variants_image'][$key]) ? $data['variants_image'][$key] : $images[$data['variants_image'][$key]];
          $product_variants_data['products_images_id'] = $products_images_id;
          
          if ($variants_id > 0)
          {
            $this->db->update('products_variants', $product_variants_data, array('products_variants_id' => $variants_id));
          }
          else
          {
            $product_variants_data['products_id'] = $products_id;
            
            $this->db->insert('products_variants', $product_variants_data);
          }
          
          if ($this->db->trans_status() === FALSE)
          {
            break;
          }
          else
          {
            if ( is_numeric($variants_id) && ($variants_id > 0) ) {
              $products_variants_id = $variants_id;
            } 
            else 
            {
              $products_variants_id = $this->db->insert_id();
            }
            
            $products_quantity += $data['variants_quantity'][$key];
          }
          
          //variant entries
          if ( ($this->db->trans_status() === TRUE) && ($variants_id == '-1') )
          {
            $assigned_variants = explode('-', $key);
            
            for ($i = 0; $i < sizeof($assigned_variants); $i++)
            {
              $assigned_variant = explode('_', $assigned_variants[$i]);
              
              $entries_data = array('products_variants_id' => $products_variants_id, 
                                    'products_variants_groups_id' => $assigned_variant[0], 
                                    'products_variants_values_id' => $assigned_variant[1]);
              
              $this->db->insert('products_variants_entries', $entries_data);
              
              if ($this->db->trans_status() === FALSE)
              {
                break;
              }
            }
          }
        }
        
        if ($this->db->trans_status() === TRUE)
        {
          $this->db->update('products', array('products_quantity' => $products_quantity), array('products_id' => $products_id));
        }
      }
    }
    //END: products variants
    
    //BEGIN: xsell products
    if ($this->db->trans_status() === TRUE)
    {
      if (is_numeric($id))
      {
        $this->db->delete('products_xsell', array('products_id' => $id));
      }
      
      if ($this->db->trans_status() === TRUE)
      {
        if (isset($data['xsell_id_array']) && !empty($data['xsell_id_array']))
        {
          foreach ($data['xsell_id_array'] as $xsell_products_id)
          {
            $this->db->insert('products_xsell', array('products_id' => $products_id, 'xsell_products_id' => $xsell_products_id));
            
            if ($this->db->trans_status() === FALSE)
            {
              break;
            }
          }
        }
      }
    }
    //END: xsell products
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      return $products_id;
    }
    else
    {
      $this->db->trans_rollback();
      
      return FALSE;
    }
  }
  
  public function get_data($products_id)
  {
    $Qproducts = $this->db
    ->select('p.*, pd.*, ptoc.*')
    ->from('products p')
    ->join('products_description pd', 'p.products_id = pd.products_id', 'left')
    ->join('products_to_categories ptoc', 'ptoc.products_id = p.products_id', 'left')
    ->where(array('p.products_id' => $products_id, 'pd.language_id' => lang_id()))
    ->get();
    
    return $Qproducts->row_array();
  }
  
  public function load_product_data($products_id, $data)
  {
    if (empty($data))
    {
      $data = array();
    }
    
    $Qproduct_description = $this->db
    ->select('*')
    ->from('products_description')
    ->where('products_id', $products_id)
    ->get();
    
    if ($Qproduct_description->num_rows() > 0)
    {
      foreach($Qproduct_description->result_array() as $product_description)
      {
        $language_id = $product_description['language_id'];
        
        $data['products_name[' . $language_id . ']'] = $product_description['products_name'];
        $data['products_short_description[' . $language_id . ']'] = $product_description['products_short_description'];
        $data['products_description[' . $language_id . ']'] = $product_description['products_description'];
        $data['products_tags[' .$language_id . ']'] = $product_description['products_tags'];
        $data['products_url[' . $language_id . ']'] = $product_description['products_url'];
        $data['products_friendly_url[' . $language_id . ']'] = $product_description['products_friendly_url'];
        $data['products_page_title[' . $language_id . ']'] = $product_description['products_page_title'];
        $data['products_meta_keywords[' . $language_id . ']'] = $product_description['products_meta_keywords'];
        $data['products_meta_description[' . $language_id . ']'] = $product_description['products_meta_description'];
      }
    }
    
    return $data;
  }
  
  public function delete_product($product_id)
  {
    
    $this->db->trans_begin();
    
     //reviews
    $this->db->delete('reviews', array('products_id' => $product_id));
    
    //customers basket
    if ($this->db->trans_status() === TRUE)
    {
      $this->db
      ->where('products_id', $product_id)
      ->or_like('products_id', $product_id)
      ->delete('customers_basket');
    }
    
    //categories
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('products_to_categories', array('products_id' => $product_id));
    }
    
    //xsell
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('products_xsell', array('products_id' => $product_id));
    }
    
   //variants entries
    if ($this->db->trans_status() === TRUE)
    {
      $tbl_pve = $this->db->protect_identifiers('products_variants_entries', TRUE);
      $tbl_pv = $this->db->protect_identifiers('products_variants', TRUE);
      
      $sql = 'delete from ' . $tbl_pve . 'where products_variants_id in (select products_variants_id from ' . $tbl_pv . ' where products_id = ?)';
      $this->db->query($sql, array((int)$product_id));
    }
    
    //variants
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('products_variants', array('products_id' => $product_id));
    }
   
    //products description
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('products_description', array('products_id' => $product_id));
    }
    
    //product accessories
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('products_accessories', array('products_id' => $product_id));
    }
    
    //products
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('products', array('products_id' => $product_id));
    }
    
    //images
    if ($this->db->trans_status() === TRUE)
    {
      $Qim = $this->db
      ->select('id')
      ->from('products_images')
      ->where('products_id', $product_id)
      ->get();
      
      if ($Qim->num_rows() > 0)
      {
        foreach($Qim->result_array() as $image)
        {
          $this->admin_image->delete($image['id']);
        }
      }
    }
    
    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      
      return FALSE;
    }
    else
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
  }
  
  public function get_products_to_categories($products_id)
  {
    $Qcategories = $this->db
    ->select('categories_id')
    ->from('products_to_categories')
    ->where('products_id', $products_id)
    ->get();
      
    return $Qcategories->result_array();
  }
  
  public function get_products_variants($products_id)
  {
    $Qvariants = $this->db
    ->select('*')
    ->from('products_variants')
    ->where('products_id', $products_id)
    ->order_by('products_variants_id')
    ->get();
    
    return $Qvariants->result_array();
  }
  
  public function get_variants_entries($products_varaints_id)
  {
    $Qentries = $this->db
    ->select('e.products_variants_id, e.products_variants_groups_id as gid, e.products_variants_values_id as vid, g.products_variants_groups_name as gname, v.products_variants_values_name as vname')
    ->from('products_variants_entries e')
    ->join('products_variants_groups g', 'e.products_variants_groups_id = g.products_variants_groups_id', 'inner')
    ->join('products_variants_values v', 'e.products_variants_values_id = v.products_variants_values_id', 'inner')
    ->where('g.language_id = v.language_id')
    ->where(array('g.language_id' => lang_id(), 'e.products_variants_id' => $products_varaints_id))
    ->order_by('g.products_variants_groups_id, v.products_variants_values_id')
    ->get();
    
    return $Qentries->result_array();
  }
  
  public function get_totals($search = NULL, $in_categories = NULL)
  {
    if (empty($search))
    {
      return $this->_total_products;
    }
    else
    {
      $this->db
      ->select('p.products_id, p.products_type, pd.products_name, p.products_quantity, p.products_price, p.products_quantity, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status')
      ->from('products p')
      ->join('products_description pd', 'p.products_id = pd.products_id', 'inner');
           
      if (!empty($in_categories))
      {
        $this->db
        ->join('products_to_categories p2c', 'p.products_id = p2c.products_id', 'inner')
        ->where_in('p2c.categories_id', $in_categories);
      }
      
      $this->db
      ->where('pd.language_id', lang_id());
      
      if (!empty($search))
      {
        $this->db->like('pd.products_name', $search);
      }
      
      $Qtotal = $this->db->get();
      
      return $Qtotal->num_rows();
    }
  }
  
  public function set_frontpage($id, $flag)
  {
    if ($flag == 1)
    {
      $Qcheck = $this->db
      ->select('products_id')
      ->from('products_frontpage')
      ->where('products_id', $id)
      ->get();
      
      if ($Qcheck->num_rows() > 0)
      {
        return TRUE;
      }
      
      $Qorder = $this->db
      ->select_max('sort_order')
      ->from('products_frontpage')
      ->get();
      
      $max = $Qorder->row_array();
      
      $sort_order =  $max['sort_order'] + 1;
      
      $Qstatus = $this->db->insert('products_frontpage', array('products_id' => $id, 'sort_order' => $sort_order));
    }
    else
    {
      $Qstatus = $this->db->delete('products_frontpage', array('products_id' => $id));
    }
    
    if ($this->db->affected_rows() == 1)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function set_status($id, $flag)
  {
    $error = FALSE;
    
    if ($flag == 0)
    {
      $this->db->like('products_id', (int)$id . '#', 'after')->or_where('products_id', $id);
      $this->db->delete('customers_basket');
    }
    
    $this->db->update('products', array('products_status' => $flag), array('products_id' => $id));
    
    if ($this->db->affected_rows() < 1)
    {
      return FALSE;
    }
    
    return TRUE;
  }
  
  public function do_edit_upload($products_id, $image)
  {
    $default_flag = 1;
    
    $Qcheck = $this->db
    ->select('id')
    ->from('products_images')
    ->where(array('products_id' => $products_id, 'default_flag' => $default_flag))
    ->limit(1)
    ->get();
    
    if ($Qcheck->num_rows() === 1)
    {
      $default_flag = 0;
    }
    
    $Qcheck->free_result();
    
    $this->db->insert('products_images', array('products_id' => $products_id, 
                                               'image' => $image, 
                                               'default_flag' => $default_flag, 
                                               'sort_order' => 0, 
                                               'date_added' => date('Y-m-d H:i:s')));
    
    if ($this->db->affected_rows() > 0)
    {
      $image_path = ROOTPATH . 'images/products/originals/';
      $image_id = $this->db->insert_id();
      $new_image_name =  $products_id . '_' . $image_id . '_' . $image;
      @rename($image_path . $image, $image_path . $new_image_name);
      
      $this->db->update('products_images', array('image' => $new_image_name), array('id' => $image_id));
    }
    
    foreach ($this->admin_image->getGroups() as $group) 
    {
      if ($group['id'] != '1')
      {
        $this->admin_image->resize($new_image_name, $group['id'], 'products');
      }
    }
  }
}

/* End of file products_model.php */
/* Location: ./system/modules/products/models/products_model.php */
