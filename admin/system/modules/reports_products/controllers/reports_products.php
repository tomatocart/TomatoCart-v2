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
 * @filesource system/modules/reports_products/controllers/reports_products.php
 */

class Reports_Products extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->library('category_tree');
    $this->load->library('currencies');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('products_purchased_panel');
    $this->load->view('products_viewed_panel');
    $this->load->view('categories_purchased_panel');
    $this->load->view('low_stock_panel');
  }
  
  public function get_categories()
  {
    $records = array(array('id' => '0', 'text' => lang('top_category')));
    foreach ($this->category_tree->getTree() as $value) {
      $category_id = strval($value['id']);
      $margin = 0;
      
      if (strpos($category_id, '_') !== FALSE)
      {
        $n = count(explode('_', $category_id)) - 1;
        
        $margin = $n * 10;
      }
      
      $records[] = array('id' => $value['id'],
                         'text' => $value['title'], 
                         'margin' => $margin);
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function list_products_purchased()
  {
    $this->load->model('products_purchased_model');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    $categories_id = $this->input->get_post('categories_id');
    $start_date = $this->input->get_post('start_date');
    $end_date = $this->input->get_post('end_date');
    
    $products_purchased = $this->products_purchased_model->get_products($categories_id, $start_date, $end_date, $start, $limit);
    
    $records = array();
    if (!empty($products_purchased))
    {
      foreach($products_purchased as $product)
      {
        $records[] = array('products_id' => $product['products_id'],
                           'products_name' => $product['products_name'],
                           'quantity' => $product['quantity'],
                           'final_price' => (float)$product['final_price'],
                           'total' => (float)$product['total'],
                           'average_price' => (float)($product['total']/$product['quantity']));
      }
    }
    else
    {
      $records[] = array('products_id' => 0,
                         'products_name' => lang('none'),
                         'quantity' =>0,
                         'final_price' => 0,
                         'total' => 0,
                         'average_price' => 0);
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->products_purchased_model->get_total($categories_id, $start_date, $end_date),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function get_languages()
  {
    $records = array();
    foreach(lang_get_all() as $l)
    {
      $records[] = array('id' => $l['id'], 'text' => $l['name']);
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function list_products_viewed()
  {
    $this->load->model('products_viewed_model');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    $categories_id = $this->input->get_post('categories_id');
    $language_id = $this->input->get_post('language_id');
    
    $products_viewed = $this->products_viewed_model->get_products_viewed($categories_id, $language_id, $start, $limit);
    
    $records = array();
    if (!empty($products_viewed))
    {
      foreach($products_viewed as $product)
      {
        $records[] = array('products_id' => $product['products_id'],
                           'products_name' => $product['products_name'],
                           'products_viewed' => intval($product['products_viewed']),
                           'language' => show_image($product['code']));
      }
    }
    else
    {
      $records[] = array('products_id' => 0,
                         'products_name' => lang('none'),
                         'products_viewed' => 0,
                         'language' => lang('none'));
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->products_viewed_model->get_total($categories_id, $language_id),
                 EXT_JSON_READER_ROOT => $records);
                      
  }
  
  public function list_categories_purchased()
  {
    $this->load->model('categories_purchased_model');
    
    $start_date = $this->input->get_post('start_date');
    $end_date = $this->input->get_post('end_date');
    
    $categories_id = $this->input->get_post('categories_id');
    $categories_id = end(explode('_', empty($categories_id) ? 0 : $categories_id));
    
    $categories = $this->categories_purchased_model->get_categories($categories_id);
    
    $records = array();
    if (!empty($categories))
    {
      foreach($categories as $category)
      {
        $sub_categories = array();
        $this->category_tree->get_children($category['categories_id'], $sub_categories);
        
        $categories_ids = array();
        if (!empty($sub_categories))
        {
          foreach($sub_categories as $sub_category)
          {
            $categories_ids[] = $sub_category['id'];
          }
        }
        $categories_ids[] = $category['categories_id'];
        
        $products_ids = $this->categories_purchased_model->get_products_ids($categories_ids);
        
        if (!empty($products_ids))
        {
          $category_products = $this->categories_purchased_model->get_category_products($products_ids, $start_date, $end_date);
          
          $records[] = array('categories_id' => $category['categories_id'], 
                             'total' => (float)$category_products['total'], 
                             'quantity' => intval($category_products['quantity']), 
                             'categories_name' => $category['categories_name'], 
                             'path' => $this->category_tree->build_breadcrumb($category['categories_id']));
        }
        else
        {
          $records[] = array('categories_id' => 0, 
                             'total' => 0, 
                             'quantity' => 0, 
                             'categories_name' => $category['categories_name'], 
                             'path' => $this->category_tree->build_breadcrumb($category['categories_id']));
        }
      }
    }
    else
    {
      $records[] = array('categories_id' => 0, 
                         'total' => 0, 
                         'quantity' => 0, 
                         'categories_name' => lang('none'), 
                         'path' => lang('none'));
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function list_low_stock()
  {
    $this->load->model('low_stock_model');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    $categories_id = $this->input->get_post('categories_id');
    
    $products = $this->low_stock_model->get_products($categories_id, $start, $limit);
    
    if (empty($products))
    {
      $products = array('products_id' => 0, 'products_name' => lang('none'), 'products_quantity' => 0);
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->low_stock_model->get_total($categories_id),
                 EXT_JSON_READER_ROOT => $products);
  }
}

/* End of file reports_products.php */
/* Location: system/modules/reports_products/controllers/reports_products.php */