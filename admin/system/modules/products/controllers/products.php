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
 * @filesource products.php
 */

class Products extends TOC_Controller 
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('products_model');
    
    $this->load->library(array('image', 'admin_image'));
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('variants_data_panel');
    $this->load->view('variants_panel');
    $this->load->view('variants_groups_dialog');
    $this->load->view('images_grid');
    $this->load->view('images_panel');
    $this->load->view('accessories_panel');
    $this->load->view('xsell_products_panel');
    $this->load->view('categories_panel');
    $this->load->view('data_panel');
    $this->load->view('meta_panel');
    $this->load->view('general_panel');
    $this->load->view('categories_tree_panel');
    $this->load->view('products_main_panel');
    $this->load->view('products_grid');
    $this->load->view('products_dialog');
  }
  
  public function list_products()
  {
    $this->load->library('currencies');
    
    $start = $this->input->get_post('start');
    $limit = $this->input->get_post('limit');
    
    $start = empty($start) ? 0 : $start;
    $limit = empty($limit) ? MAX_DISPLAY_SEARCH_RESULTS : $limit;
    
    $search = $this->input->get_post('search');
    
    $categories_id = $this->input->get_post('categories_id');
    $categories_id = explode( '_' , (empty($categories_id) ? 0 : $categories_id) );
    $current_category_id = end($categories_id); 
    
    $in_categories = array();
    if ($current_category_id > 0)
    {
      $this->load->library('category_tree');
      $this->category_tree->setBreadcrumbUsage(false);
      
      $in_categories[] = $current_category_id;
      
      foreach($this->category_tree->getTree($current_category_id) as $category)
      {
        $in_categories[] = $category['id'];
      }
    }
    
    $products = $this->products_model->get_products($start, $limit, $in_categories, $search);
    
    return array(EXT_JSON_READER_TOTAL => $this->products_model->get_totals($search, $in_categories),
                 EXT_JSON_READER_ROOT => $products);
  }
  
  public function load_categories_tree()
  {
    $this->load->library('category_tree');
    
    $categories = $this->category_tree->build_ext_json_tree();
    
    return $categories;
  }
  
  public function get_manufacturers()
  {
    $manufacturers = $this->products_model->get_manufacturers();
    
    return array(EXT_JSON_READER_ROOT => $manufacturers);
  }
  
  public function get_weight_classes()
  {
    $weight_classes = $this->products_model->get_weight_classes();
    
    
    return array(EXT_JSON_READER_ROOT => $weight_classes);
  }
  
  public function get_tax_classes()
  {
    $this->load->library('tax');
    
    $tax_classes = $this->products_model->get_tax_classes();
    
    return array(EXT_JSON_READER_ROOT => $tax_classes);
  }
  
  public function get_quantity_discount_groups()
  {
    $quantity_discount_groups = $this->products_model->get_quantity_discount_groups();
    
    return array(EXT_JSON_READER_ROOT => $quantity_discount_groups);
  }
  
  public function get_quantity_units()
  {
    $units = $this->products_model->get_quantity_units();
    
    return array(EXT_JSON_READER_ROOT => $units);   
  }
  
  public function get_categories_tree()
  {
    $this->load->library('category_tree');
    
    $products_id = $this->input->get_post('productsId');
    
    if (!empty($products_id) && is_numeric($products_id))
    {
      $products_to_categories = $this->products_model->get_products_to_categories($products_id);
    }
    
    $checked = array();
    if (!empty($products_to_categories))
    {
      foreach($products_to_categories as $category)
      {
        $checked[] = $category['categories_id'];
      }
    }
    
    $categories = $this->category_tree->buildCheckTree(0, $checked);
    
    return $categories;
  }
  
  public function get_xsell_products()
  {
    $response = array(EXT_JSON_READER_TOTAL => 0, EXT_JSON_READER_ROOT => array());  
    
    $products_id = $this->input->get_post('products_id');
    
    if ($products_id > 0)
    {
      $products_id = $this->input->get_post('products_id');
      $products = $this->products_model->get_xsell_products($products_id);
      
      $response = array(EXT_JSON_READER_TOTAL => sizeof($products),
                        EXT_JSON_READER_ROOT => $products); 
    
    }
    
    return $response;
  }
  
  public function get_products()
  {
    $start = $this->input->get_post('start');
    $limit = $this->input->get_post('limit');
    $products_id = $this->input->get_post('products_id');
    
    $start = empty($start) ? 0 : $start;
    $limit = empty($limit) ? MAX_DISPLAY_SEARCH_RESULTS : $limit;
    $products_id = empty($products_id) ? NULL : $products_id;
    
    $products = $this->products_model->get_products_for_xsell($products_id, $start, $limit);
    
    if (empty($products_id))
    {
      $total = $this->products_model->get_totals();
    }
    else
    {
      $total = $this->products_model->get_totals() - 1;
    }
    
    return array(EXT_JSON_READER_TOTAL => $total,
                 EXT_JSON_READER_ROOT => $products);
  }
  
  public function get_accessories()
  {
    $response = array(EXT_JSON_READER_TOTAL => 0, EXT_JSON_READER_ROOT => array());
    
    $products_id = $this->input->get_post('products_id');
    
    $products = $this->products_model->get_accessories($products_id);
    
    if (!empty($products))
    {
      $response = array(EXT_JSON_READER_TOTAL => sizeof($products),
                        EXT_JSON_READER_ROOT => $products); 
    }
    
    return $response;
  }
  
  public function get_local_images()
  {
    $config = array('directory' => ROOTPATH . 'images/products/_upload', 'stats' => true);
    $this->load->library('directory_listing', $config);
    
    $this->directory_listing->setCheckExtension('gif');
    $this->directory_listing->setCheckExtension('jpg');
    $this->directory_listing->setCheckExtension('png');
    $this->directory_listing->setIncludeDirectories(false);
    
    $records = array();
    foreach ($this->directory_listing->getFiles() as $file) {
      $records[] = array('id' => $file['name'], 
                         'text' => $file['name']);
    }
    
    return array(EXT_JSON_READER_TOTAL => sizeof($records),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function assign_local_images()
  {
    $this->load->helper('directory');
    
    $localimages = $this->input->get_post('localimages');
    $products_id = $this->input->get_post('products_id');
    
    $error = FALSE;
    
    if (!empty($localimages))
    {
      $localimages = explode(',', $localimages);
      
      if (!empty($products_id))
      {
      
      }
      else
      {
        foreach($localimages as $image)
        {
          $image = basename($image);
          $image_path = ROOTPATH . 'images/products/_upload/' . $this->session->userdata('session_id') . '/';
          
          if (directory_make($image_path))
          {
            if (file_exists( ROOTPATH . 'images/products/_upload/' . $image))
            {
              copy(ROOTPATH . 'images/products/_upload/' . $image,  $image_path . $image);
            }
            else
            {
              $error = TRUE;
            }
          }
          else
          {
            $error = TRUE;
          }
        }
      }
    }
    
    if ($error === FALSE)
    {
      $response['success'] = true;
      $response['feedback'] = lang('ms_success_action_performed');
    }
    else
    {
      $response['success'] = false;
      $response['feedback'] = lang('ms_success_action_not_performed');
    }
    
    return $response;
  }
  
  public function upload_image()
  {
    $this->load->helper('directory');
    
    $products_id = $this->input->get_post('products_id');
    
    if (is_array($_FILES))
    {
      $images = array_keys($_FILES);
    }
    
    if (!empty($images))
    {
      if (empty($products_id))
      {
        $image_path = ROOTPATH . 'images/products/_upload/' . $this->session->userdata('session_id') . '/';
        
        if (directory_make($image_path)) 
        {
          $config['upload_path'] = $image_path;
          $config['allowed_types'] = 'gif|jpg|png';
          
          $this->load->library('upload', $config);
          
          foreach($images as $image)
          {
            if ( ! $this->upload->do_upload($image))
            {
              $error = array('error' => $this->upload->display_errors());
            }
          }
        }
      }
      else
      {
        $image_path = ROOTPATH . 'images/products/originals/';
        
        $config['upload_path'] = $image_path;
        $config['allowed_types'] = 'gif|jpg|png';
        
        $this->load->library('upload', $config);
        
        foreach($images as $image)
        {
          if ($this->upload->do_upload($image))
          {
            $data = $this->upload->data();
            
            $this->products_model->do_edit_upload($products_id, $data['file_name']);
          }
        }
      }
    }
    
    if (empty($error))
    {
      $response['success'] = true;
      $response['feedback'] = lang('ms_success_action_performed');
    }
    else
    {
      $response['success'] = false;
      $response['feedback'] = lang('ms_success_action_not_performed') . $error;
    }
    
    $this->output->set_header("Content-Type: text/html")->set_output(json_encode($response));
    
    return NULL;
  }
  
  public function get_images()
  {
    $products_id = $this->input->get_post('products_id');
    
    $records = array();
    if (!empty($products_id) && is_numeric($products_id))
    {
      $images = $this->products_model->get_images($products_id);
      
      if (!empty($images))
      {
        foreach($images as $image)
        {
          $records[] = array('id' => $image['id'],
                             'image' => '<img src="' . IMGHTTPPATH . 'products/mini/'. $image['image'] . '" border="0" />',
                             'name' => $image['image'],
                             'size' => number_format(@filesize(ROOTPATH . 'images/products/originals/' . $image['image'])) . ' bytes',
                             'default' => $image['default_flag']);
        }
      }
    }
    else
    {
      $image_path = ROOTPATH . 'images/products/_upload/' . $this->session->userdata('session_id') . '/';
      
      $config = array('directory' => $image_path, 'stats' => true);
      $this->load->library('directory_listing', $config);
      $this->directory_listing->setIncludeDirectories('false');
      
      foreach($this->directory_listing->getFiles() as $file)
      {
        $records[] = array('id' => '',
                           'image' => '<img src="' . IMGHTTPPATH . 'products/_upload/' . $this->session->userdata('session_id') . '/' . $file['name'] . '" border="0" width="' . $this->admin_image->getWidth('mini') . '" height="' .  $this->admin_image->getHeight('mini') . '" />',
                           'name' => $file['name'],
                           'size' => number_format($file['size']) . ' bytes',
                           'default' => ($this->session->userdata('default_images') == $file['name']) ? 1 : 0);
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => sizeof($records),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function set_default()
  {
    $error = FALSE;
    
    $image = $this->input->get_post('image');
    
    if (!empty($image) && is_numeric($image))
    {
      if (!$this->admin_image->setAsDefault($image))
      {
        $error = TRUE;
      }
    }
    else
    {
      $this->session->set_userdata('default_images', basename($image));
    }
    
    if ($error === FALSE)
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_image()
  {
    $error = false;
    $this->load->helper('file');
    
    $image = $this->input->get_post('image');
    
    if (!empty($image) && is_numeric($image))
    {
      if (!$this->admin_image->delete($image))
      {
        $error = true;
      }
    }
    else
    {
      $image_path = ROOTPATH . 'images/products/_upload/' . $this->session->userdata('session_id') . '/';
      
      if (!unlink($image_path . $image))
      {
        $error = true;
      }
    }
    
    if ($error === false) {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    } else {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }

    return $response;
  }
  
  public function load_variants_groups()
  {
    $groups = $this->products_model->load_variants_groups();
    
    return array(EXT_JSON_READER_ROOT => $groups);
  }
  
  public function get_variants_values()
  {
    $group_id = $this->input->get_post('group_id');
    
    $variant_values = $this->products_model->get_variants_values($group_id);
    
    $result = array();
    if (!empty($variant_values))
    {
      foreach($variant_values as $variant_value)
      {
        $result[] = array('id' => $variant_value['variants_id'],
                          'text' => $variant_value['variants_name']);
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $result);      
  }
  
  public function get_variants_products()
  {
    $products_id = $this->input->get_post('products_id');
    
    $variants = $this->products_model->get_products_variants($products_id);
    
    $records = array();
    if (!empty($variants))
    {
      foreach($variants as $variant)
      {
        $entries = $this->products_model->get_variants_entries($variant['products_variants_id']);
        
        $variants_values = array();
        $variants_values_name = array();
        
        $data = array();
        $variants_groups = array();
        
        if (!empty($entries))
        {
          foreach($entries as $entry)
          {
            $variants_values[] = $entry['gid'] . '_' . $entry['vid'];
            $variants_values_name[] = $entry['gname'] . ':' . $entry['vname'];
            $variants_groups[] = array('id' => $entry['gid'],
                                       'name' => $entry['gname'],
                                       'rawvalue' => $entry['vname'],
                                       'value' => $entry['vid']);
          }
        }
          
        $data['products_variants_id'] = $variant['products_variants_id'];
        $data['default'] =  $variant['is_default'];
        $data['variants_values_name'] = implode('; ', $variants_values_name);
        $data['variants_groups'] = $variants_groups;
        
        $ids = implode('-', $variants_values);
        $data['variants_values'] = $ids;
        
        $data['data'] = array('variants_quantity' => $variant['products_quantity'],
                              'variants_sku' => $variant['products_sku'],
                              'variants_net_price' => $variant['products_price'],
                              'variants_model' => $variant['products_model'],
                              'variants_weight' => $variant['products_weight'],
                              'variants_status' => $variant['products_status'],
                              'variants_image' => $variant['products_images_id']);
        
        $records[] = $data;
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => sizeof($records),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function save_product()
  {
    $this->load->helper('html_output');
    $this->load->helper('file');
    
    $image_path = ROOTPATH . 'images/products/_upload/' . $this->session->userdata('session_id') . '/';
      
    $config = array('directory' => $image_path, 'stats' => true);
    $this->load->library('directory_listing', $config);
    $this->directory_listing->setIncludeDirectories('false');
    
    //search engine friendly urls
    $formatted_urls = array();
    $urls = $this->input->post('products_friendly_url');
    
    if (is_array($urls) && !empty($urls)) {
      foreach($urls as $languages_id => $url) {
        $url = format_friendly_url($url);
        if (empty($url)) {
          $products_name = $this->input->post('products_name');
          $url = format_friendly_url($products_name[$languages_id]);
        }
        
        $formatted_urls[$languages_id] = $url;
      }
    }
    
    $products_quantity = $this->input->post('products_quantity');
    $products_max_order_quantity = $this->input->post('products_max_order_quantity');
    
    $data = array('products_type' => $this->input->post('products_type'),
                  'quantity' => !empty($products_quantity) ? $products_quantity : 0,
                  'products_moq' => $this->input->post('products_moq'),
                  'products_max_order_quantity' => !empty($products_max_order_quantity) ? $products_max_order_quantity : -1,
                  'order_increment' => $this->input->post('order_increment'),
                  'quantity_unit_class' => $this->input->post('quantity_unit_class'),
                  'price' => $this->input->post('products_price'),
                  'weight' => $this->input->post('products_weight'),
                  'quantity_discount_groups_id' => $this->input->post('quantity_discount_groups_id'),
                  'weight_class' => $this->input->post('products_weight_class'),
                  'status' => $this->input->post('products_status'),
                  'tax_class_id' => $this->input->post('products_tax_class_id'),
                  'manufacturers_id' => $this->input->post('manufacturers_id'),
                  'date_available' => $this->input->post('products_date_available'),
                  'products_name' => $this->input->post('products_name'),
                  'products_short_description' => $this->input->post('products_short_description'),
                  'products_description' => $this->input->post('products_description'),
                  'products_sku' => $this->input->post('products_sku'),
                  'products_model' => $this->input->post('products_model'),
                  'products_tags' => $this->input->post('products_tags'),
                  'products_url' => $this->input->post('products_url'),
                  'products_friendly_url' => $formatted_urls,
                  'products_page_title' => $this->input->post('products_page_title'),
                  'products_meta_keywords' => $this->input->post('products_meta_keywords'),
                  'products_meta_description' => $this->input->post('products_meta_description'),
                  'products_attributes_groups_id' => $this->input->post('products_attributes_groups_id'));
    
    $xsell_ids = $this->input->post('xsell_ids');
    if (!empty($xsell_ids)) 
    {
      $xsell_ids = explode(';', $xsell_ids);
      $data['xsell_id_array'] = $xsell_ids;
    }
    
    $categories_id = $this->input->post('categories_id');
    if (!empty($categories_id))
    {
      $data['categories'] = explode(',', $categories_id);
    }
    
    $accessories_ids = $this->input->post('accessories_ids');
    if (!empty($accessories_ids))
    {
      $accessories_ids = explode(';', $accessories_ids);
      $data['accessories_ids'] = $accessories_ids;
    }
    
    $localimages = $this->input->post('localimages');
    if (!empty($localimages))
    {
      $localimages = explode(',', $localimages);
      $data['localimages'] = $localimages;
    }
    
    $products_variants = $this->input->post('products_variants');
    if ( $data['products_type'] != PRODUCT_TYPE_GIFT_CERTIFICATE && !empty($products_variants) )
    {
      $products_variants = explode(';', $products_variants);
      
      $data['variants'] = $products_variants;
      $data['variants_quantity'] = array();
      $data['variants_status'] = array();
      $data['variants_price'] = array();
      $data['variants_sku'] = array();
      $data['variants_model'] = array();
      $data['variants_weight'] = array();
      $data['variants_change'] = array();
      
      foreach ($products_variants as $variant) {
        $variants = explode(':', $variant);
        
        $varaints_quantity = $this->input->post('variants_quantity');
        $variants_net_price = $this->input->post('variants_net_price');
        $variants_sku = $this->input->post('variants_sku');
        $variants_model = $this->input->post('variants_model');
        $variants_weight = $this->input->post('variants_weight');
        $variants_status = $this->input->post('variants_status_' . $variants[0]);
        
        $data['products_variants_id'][$variants[0]] = $variants[1];
        $data['variants_default'][$variants[0]] = $variants[2];
        $data['variants_quantity'][$variants[0]] = $varaints_quantity[$variants[0]];
        $data['variants_price'][$variants[0]] = $variants_net_price[$variants[0]];
        $data['variants_sku'][$variants[0]] = $variants_sku[$variants[0]];
        $data['variants_model'][$variants[0]] = $variants_model[$variants[0]];
        $data['variants_weight'][$variants[0]] = $variants_weight[$variants[0]];
        $data['variants_status'][$variants[0]] = $variants_status;
        
        $variants_image = $this->input->post('variants_image_' . $variants[0]);
        $data['variants_image'][$variants[0]] = !empty($variants_image) ? $variants_image : null;
      }
    }
    
    //search engine friendly urls
    $return_urls = array();
    if (is_array($formatted_urls) && !empty($formatted_urls)) {
      foreach($formatted_urls as $languages_id => $url) {
        $return_urls[] = array('languages_id' => $languages_id, 'url' => $url); 
      }
    }
    
    $pid = $this->input->post('products_id');
    $pid = (!empty($pid) && (is_numeric($pid) && ($pid != '-1'))) ? $pid : null;
    
    $products_id = $this->products_model->save($pid, $data);
    
    if ($products_id) {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'),  'productsId' => $products_id, 'urls' => $return_urls);
    } else {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    $this->output->set_header('Content-Type: text/html')->set_output(json_encode($response));

    return NULL;
  }
  
  public function load_product()
  {
    $data = $this->products_model->get_data($this->input->post('products_id'));
    
    $cboKeys = array('products_tax_class_id', 
                     'manufacturers_id', 
                     'products_weight_class', 
                     'quantity_discount_groups_id', 
                     'quantity_unit_class');
    
    $data = $this->_compose_cbodata($data, $cboKeys);
    
    if (!empty($data['products_date_available']))
    {
      $date = explode(' ', $data['products_date_available']);
      $data['products_date_available'] = $date[0];
    }
    
    $product_data = $this->products_model->load_product_data($this->input->post('products_id'), $data);
    
    return array('success' => true, 'data' => $product_data);     
  }
  
  public function delete_product()
  {
    $product_id = $this->input->post('products_id');
    
    if ($this->products_model->delete_product($product_id))
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_products()
  {
    $error = false;
    
    $batch = json_decode($this->input->post('batch'));
    
    if (!empty($batch))
    {
      foreach($batch as $id)
      {
        if (!$this->products_model->delete_product($id))
        {
          $error = TRUE;
          break;
        }
      }
    }
    else
    {
      $error = TRUE;
    }
    
    if ($error === FALSE) {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    } else {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }

    return $response;
  }
  
  public function set_frontpage() 
  {
    $products_id = $this->input->post('products_id');
    $flag = $this->input->post('flag');
    
    if (!empty($products_id) && $this->products_model->set_frontpage($products_id, $flag))
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function set_status()
  {
    $products_id = $this->input->post('products_id');
    $flag = $this->input->post('flag');
     
    if (!empty($products_id) && $this->products_model->set_status($products_id, $flag))
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function get_model()
  {
    return $this->products_model;
  }
  
  private function _compose_cbodata($data, $keys)
  {
    foreach($keys as $key)
    {
      if (is_numeric($data[$key]) && $data[$key] > 0)
      {
        $data[$key] = strval($data[$key]);
      }
    }
    
    return $data;
  }
}

/* End of file Products.php */
/* Location: ./system/modules/products/controllers/products.php */