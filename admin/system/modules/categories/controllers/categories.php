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
 * @filesource modules/categories/controllers/categories.php
 */

class Categories extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->library('category_tree', array('load_from_database' => TRUE, 'load_all_categories' => TRUE, 'load_from_cache' => FALSE));
    $this->load->model('categories_model');
  }

  public function list_categories()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;

    $search = $this->input->get_post('search');

    $parent_id = 0;
    $categories_id = $this->input->get_post('categories_id');
    if (!empty($categories_id))
    {
      $parent_id = $categories_id;
    }

    $categories = $this->categories_model->get_categories($start, $limit, $parent_id, $search);

    $records = array();
    if (!empty($categories))
    {
      foreach($categories as $category)
      {
        $records[] = array('categories_id' => $category['categories_id'],
                           'categories_name' => $category['categories_name'],
                           'status' => $category['categories_status'],
                           'path' => $this->category_tree->build_bread_crumb($category['categories_id'])); 
      }
    }

    return array(EXT_JSON_READER_TOTAL => $this->categories_model->get_totals($parent_id, $search),
                      EXT_JSON_READER_ROOT => $records);
  }

  public function load_categories_tree()
  {
    return $this->category_tree->build_ext_json_tree();
  }

  public function delete_category()
  {
    $this->load->model('products_model');
    $this->load->driver('cache', array('adapter' => 'file'));

    $categories_id = $this->input->post('categories_id');

    if (!empty($categories_id) && $this->categories_model->delete($categories_id))
    {
      $response = array('success' => true ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }

  public function delete_categories()
  {
    $this->load->module('products');

    $batch = $this->input->post('batch');

    $category_ids = json_decode($batch);

    $error = FALSE;
    foreach($category_ids as $id)
    {
      if (!$this->categories_model->delete($id))
      {
        $error = TRUE;
        break;
      }
    }

    if ($error === FALSE)
    {
      $response = array('success' => true ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }

  public function list_parent_category()
  {
    $records = array(array('id' => 0,
                           'text' => lang('top_category')));

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

  public function save_category()
  {
    $this->load->library('upload');
    $this->load->helper('html_output');

    $parent_id = $this->input->post('parent_category_id');
    $flag = $this->input->post('product_flag');
    $categories_id = $this->input->post('categories_id');
    $categories_name = $this->input->post('categories_name');

    $parent_id = !empty($parent_id) ? end(explode('_', $parent_id)) : 0;
    $flag = !empty($flag) ? $flag : 0;
    $categories_id = (!empty($categories_id) && is_numeric($categories_id)) ? $categories_id : NULL;

    //search engine friendly urls
    $formatted_urls = array();
    $urls = $this->input->post('categories_url');

    if (is_array($urls) && !empty($urls))
    {
      foreach($urls as $languages_id => $url)
      {
        $url = format_friendly_url($url);

        if (empty($url))
        {
          $url = format_friendly_url($categories_name[$languages_id]);
        }

        $formatted_urls[$languages_id] = $url;
      }
    }

    $data = array('parent_id' => $parent_id,
                  'sort_order' => $this->input->post('sort_order'), 
                  'image' => 'image', 
                  'categories_status' => $this->input->post('categories_status'), 
                  'name' => $this->input->post('categories_name'), 
                  'url' => $formatted_urls, 
                  'page_title' => $this->input->post('page_title'), 
                  'meta_keywords' => $this->input->post('meta_keywords'), 
                  'meta_description' => $this->input->post('meta_description'), 
                  'flag' => $flag);
    
    $category_id = $this->categories_model->save($categories_id, $data);

    if ($category_id > 0)
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'), 'categories_id' => $category_id, 'text' => $categories_name[lang_id()]);
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }

    $this->output->set_header("Content-Type: text/html")->set_output(json_encode($response));
    
    return NULL;
  }

  public function load_category()
  {
    $data = $this->categories_model->get_data($this->input->post('categories_id'));

    if (!empty($data))
    {
      $response = array('success' => true, 'data' => $data);
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }

  public function load_parent_category()
  {
    $categories_ids = json_decode($this->input->post('categories_ids'));

    $cPath = explode('_', $this->category_tree->get_full_cpath((int)$categories_ids[0]));
    array_pop($cPath);
    $parent_id = !empty($cPath) ? implode('_', $cPath) : 0;

    if ($parent_id == 0 || !empty($parent_id))
    {
      $response = array('success' => true, 'data' => array('parent_id' => $parent_id));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }

  public function set_status()
  {
    $categories_id = $this->input->post('categories_id');
    $flag = $this->input->post('flag') ? $this->input->post('flag') : 0;
    $product_flag = $this->input->post('product_flag') ? $this->input->post('product_flag') : 0;
    
    $this->load->driver('cache', array('adapter' => 'file'));
    
    if (!empty($categories_id) && $this->categories_model->set_status($categories_id, $flag, $product_flag))
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }

    return $response;
  }

  public function move_categories()
  {
    $this->load->driver('cache', array('adapter' => 'file'));

    $error = FALSE;
    $batch = $this->input->post('categories_ids');

    $categories_ids = json_decode($batch);

    foreach($categories_ids as $id)
    {
      if ($this->categories_model->move((int)$id, $this->input->post('parent_category_id')) === FALSE)
      {
        $error = TRUE;
        break;
      }
    }

    if ($error === FALSE)
    {
      $response = array('success' => true ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
}

/* End of file categories.php */
/* Location: ./system/modules/categories/controllers/categories.php */