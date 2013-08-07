<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package   CodeIgniter
 * @author    ExpressionEngine Dev Team
 * @copyright Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license   http://codeigniter.com/user_guide/license.html
 * @link    http://codeigniter.com
 * @since   Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Language Class
 *
 * @package   CodeIgniter
 * @subpackage  Libraries
 * @category  Language
 * @author    ExpressionEngine Dev Team
 * @link    http://codeigniter.com/user_guide/libraries/language.html
 */
class TOC_Category_Tree {
  private $ci = null;
  private $root_category_id = 0;
  private $max_level = 0;
  private $data = array();
  private $root_start_string = '';
  private $root_end_string = '';
  private $parent_start_string;
  private $parent_end_string = '';
  private $parent_group_start_string = '<ul>';
  private $parent_group_end_string = '</ul>';
  private $child_start_string = '<li>';
  private $child_end_string = '</li>';
  private $breadcrumb_separator = '_';
  private $breadcrumb_usage = true;
  private $spacer_string = '';
  private $spacer_multiplier = 1;
  private $follow_cpath = false;
  private $cpath_array = array();
  private $cpath_start_string = '';
  private $cpath_end_string = '';
  private $show_category_product_count = false;
  private $load_from_database = true;
  private $load_all_categories = false;
  private $load_from_cache = true;
  private $category_product_count_start_string = '&nbsp;(';
  private $category_product_count_end_string = ')';
  
  public function __construct($config = array()) {
    //initialize the ci instance
    $this->ci = &get_instance();
    
    //cache
    $this->ci->load->driver('cache', array('adapter' => 'file'));
    
    if (config('SERVICES_CATEGORY_PATH_CALCULATE_PRODUCT_COUNT') == '1') {
      $this->show_category_product_count = true;
    }
    
    $this->initialize($config);
  }
  
  public function initialize($config = array())
  {
    if (!empty($config))
    {
      foreach ($config as $key => $val)
      {
        if (isset($this->$key))
        {
          $this->$key = $val;
        }
      }
    }
    
    //if load from database
    if ($this->load_from_database === true) {
      $is_cache_loaded = false;

      //if load from cache
      if ($this->load_from_cache === true) {
        $this->data = $this->ci->cache->get('category_tree-' . $this->ci->lang->get_code());
        if ($this->data != FALSE) {
          $is_cache_loaded = true;
        }
      }

      //if cache is not found
      if ($is_cache_loaded === false) {
        $this->ci->load->model('categories_tree_model');
        $rows = $this->ci->categories_tree_model->get_all($this->load_all_categories);

        $this->data = array();
        foreach ($rows as $row)
        {
          $this->data[$row['parent_id']][$row['categories_id']] = array(
            'name' => $row['categories_name'], 
            'url' => $row['categories_url'], 
            'page_title' => $row['categories_page_title'], 
            'meta_keywords' => $row['categories_meta_keywords'], 
            'meta_description' => $row['categories_meta_description'], 
            'image' => $row['categories_image'], 
            'count' => 0
          );
        }
        
        if ($this->show_category_product_count === true) {
          $this->calculate_category_product_count();
        }

        if ($this->load_from_cache === true) {
          $this->ci->cache->save('category_tree-' . $this->ci->lang->get_code(), $this->data, 3600);
        }
      }
    }
  }

  public function set_data(&$data_array) {
    if (is_array($data_array)) {
      $this->data = array();

      for ($i=0, $n=sizeof($data_array); $i<$n; $i++) {
        $this->data[$data_array[$i]['parent_id']][$data_array[$i]['categories_id']] = array(
          'name' => $data_array[$i]['categories_name'], 
          'count' => $data_array[$i]['categories_count']);
      }
    }
  }

  public function reset() {
    $this->root_category_id = 0;
    $this->max_level = 0;
    $this->root_start_string = '';
    $this->root_end_string = '';
    $this->parent_start_string = '';
    $this->parent_end_string = '';
    $this->parent_group_start_string = '<ul>';
    $this->parent_group_end_string = '</ul>';
    $this->child_start_string = '<li>';
    $this->child_end_string = '</li>';
    $this->breadcrumb_separator = '_';
    $this->breadcrumb_usage = true;
    $this->spacer_string = '';
    $this->spacer_multiplier = 1;
    $this->follow_cpath = false;
    $this->cpath_array = array();
    $this->cpath_start_string = '';
    $this->cpath_end_string = '';
    $this->load_from_database = true;
    $this->load_all_categories = false;
    $this->load_from_cache = true;
    $this->show_category_product_count = (config('SERVICES_CATEGORY_PATH_CALCULATE_PRODUCT_COUNT') == '1') ? true : false;
    $this->category_product_count_start_string = '&nbsp;(';
    $this->category_product_count_end_string = ')';
  }

  public function build_branch($parent_id, $level = 0) {
    $result = $this->parent_group_start_string;

    if (isset($this->data[$parent_id])) {
      foreach ($this->data[$parent_id] as $category_id => $category) {
        if ($this->breadcrumb_usage == true) {
          $category_link = $this->build_breadcrumb($category_id);
        } else {
          $category_link = $category_id;
        }

        $result .= $this->child_start_string;

        if (isset($this->data[$category_id])) {
          $result .= $this->parent_start_string;
        }

        if ($level == 0) {
          $result .= $this->root_start_string;
        }

        if ( ($this->follow_cpath === true) && in_array($category_id, $this->cpath_array) ) {
          $link_title = $this->cpath_start_string . $category['name'] . $this->cpath_end_string;
        } else {
          $link_title = $category['name'];
        }

        $result .= str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . anchor(site_url('category/' . $category_link), $link_title);

        if ($this->show_category_product_count === true) {
          $result .= $this->category_product_count_start_string . $category['count'] . $this->category_product_count_end_string;
        }

        if ($level == 0) {
          $result .= $this->root_end_string;
        }

        if (isset($this->data[$category_id])) {
          $result .= $this->parent_end_string;
        }

        $result .= $this->child_end_string;

        if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
          if ($this->follow_cpath === true) {
            if (in_array($category_id, $this->cpath_array)) {
              $result .= $this->build_branch($category_id, $level+1);
            }
          } else {
            $result .= $this->build_branch($category_id, $level+1);
          }
        }
      }
    }

    $result .= $this->parent_group_end_string;

    return $result;
  }

  function build_branch_array($parent_id, $level = 0, $result = '') {
    if (empty($result)) {
      $result = array();
    }

    if (isset($this->data[$parent_id])) {
      foreach ($this->data[$parent_id] as $category_id => $category) {
        if ($this->breadcrumb_usage == true) {
          $category_link = $this->build_breadcrumb($category_id);
        } else {
          $category_link = $category_id;
        }

        $result[] = array('id' => $category_link,
                            'title' => str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . $category['name']);

        if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
          if ($this->follow_cpath === true) {
            if (in_array($category_id, $this->cpath_array)) {
              $result = $this->build_branch_array($category_id, $level+1, $result);
            }
          } else {
            $result = $this->build_branch_array($category_id, $level+1, $result);
          }
        }
      }
    }

    return $result;
  }


  function build_breadcrumb($category_id, $level = 0) {
    $breadcrumb = '';

    foreach ($this->data as $parent => $categories) {
      foreach ($categories as $id => $info) {
        if ($id == $category_id) {
          if ($level < 1) {
            $breadcrumb = $id;
          } else {
            $breadcrumb = $id . $this->breadcrumb_separator . $breadcrumb;
          }

          if ($parent != $this->root_category_id) {
            $breadcrumb = $this->build_breadcrumb($parent, $level+1) . $breadcrumb;
          }
        }
      }
    }

    return $breadcrumb;
  }

  function build_tree() {
    return $this->build_branch($this->root_category_id);
  }

  function get_tree($parent_id = '') {
    return $this->build_branch_array((empty($parent_id) ? $this->root_category_id : $parent_id));
  }

  function exists($id) {
    foreach ($this->data as $parent => $categories) {
      foreach ($categories as $category_id => $info) {
        if ($id == $category_id) {
          return true;
        }
      }
    }

    return false;
  }

  function get_children($category_id, &$array) {
    foreach ($this->data as $parent => $categories) {
      if ($parent == $category_id) {
        foreach ($categories as $id => $info) {
          $array[] = array('id' => $id, 'info' => $info);
          $this->get_children($id, $array);
        }
      }
    }

    return $array;
  }

  function get_data($id) {
    foreach ($this->data as $parent => $categories) {
      foreach ($categories as $category_id => $info) {
        if ($id == $category_id) {
          return array('id' => $id,
                         'name' => $info['name'],
                         'page_title' => $info['page_title'],
                         'meta_keywords' => $info['meta_keywords'],
                         'meta_description' => $info['meta_description'],
                         'parent_id' => $parent,
                         'image' => $info['image'],
                         'count' => $info['count']
          );
        }
      }
    }

    return false;
  }

  function calculate_category_product_count() {
    $totals = $this->ci->categories_tree_model->get_categories_products_count();

    foreach ($this->data as $parent => $categories) {
      foreach ($categories as $id => $info) {
        if (isset($totals[$id]) && ($totals[$id] > 0)) {
          $this->data[$parent][$id]['count'] = $totals[$id];

          $parent_category = $parent;
          while ($parent_category != $this->root_category_id) {
            foreach ($this->data as $parent_parent => $parent_categories) {
              foreach ($parent_categories as $parent_category_id => $parent_category_info) {
                if ($parent_category_id == $parent_category) {
                  $this->data[$parent_parent][$parent_category_id]['count'] += $this->data[$parent][$id]['count'];

                  $parent_category = $parent_parent;
                  break 2;
                }
              }
            }
          }
        }
      }
    }

    unset($totals);
  }

  function get_number_of_products($id) {
    foreach ($this->data as $parent => $categories) {
      foreach ($categories as $category_id => $info) {
        if ($id == $category_id) {
          return $info['count'];
        }
      }
    }

    return false;
  }

  function set_root_category_id($root_category_id) {
    $this->root_category_id = $root_category_id;
  }

  function set_maximum_level($max_level) {
    $this->max_level = $max_level;
  }

  function set_root_string($root_start_string, $root_end_string) {
    $this->root_start_string = $root_start_string;
    $this->root_end_string = $root_end_string;
  }

  function set_parent_string($parent_start_string, $parent_end_string) {
    $this->parent_start_string = $parent_start_string;
    $this->parent_end_string = $parent_end_string;
  }

  function set_parent_group_string($parent_group_start_string, $parent_group_end_string) {
    $this->parent_group_start_string = $parent_group_start_string;
    $this->parent_group_end_string = $parent_group_end_string;
  }

  function set_child_string($child_start_string, $child_end_string) {
    $this->child_start_string = $child_start_string;
    $this->child_end_string = $child_end_string;
  }

  function set_breadcrumb_separator($breadcrumb_separator) {
    $this->breadcrumb_separator = $breadcrumb_separator;
  }

  function set_breadcrumb_usage($breadcrumb_usage) {
    if ($breadcrumb_usage === true) {
      $this->breadcrumb_usage = true;
    } else {
      $this->breadcrumb_usage = false;
    }
  }

  function set_spacer_string($spacer_string, $spacer_multiplier = 2) {
    $this->spacer_string = $spacer_string;
    $this->spacer_multiplier = $spacer_multiplier;
  }

  function set_category_path($cpath, $cpath_start_string = '', $cpath_end_string = '') {
    $this->follow_cpath = true;
    $this->cpath_array = explode($this->breadcrumb_separator, $cpath);
    $this->cpath_start_string = $cpath_start_string;
    $this->cpath_end_string = $cpath_end_string;
  }

  function set_follow_category_path($follow_cpath) {
    if ($follow_cpath === true) {
      $this->follow_cpath = true;
    } else {
      $this->follow_cpath = false;
    }
  }

  function set_category_path_string($cpath_start_string, $cpath_end_string) {
    $this->cpath_start_string = $cpath_start_string;
    $this->cpath_end_string = $cpath_end_string;
  }

  function set_show_category_product_count($show_category_product_count) {
    if ($show_category_product_count === true) {
      $this->show_category_product_count = true;
    } else {
      $this->show_category_product_count = false;
    }
  }

  function set_category_product_count_string($category_product_count_start_string, $category_product_count_end_string) {
    $this->category_product_count_start_string = $category_product_count_start_string;
    $this->category_product_count_end_string = $category_product_count_end_string;
  }

  function get_parent_categories($category_id, &$categories) {
    foreach ($this->data as $parent => $sub_categories) {
      foreach ($sub_categories as $id => $info) {
        if ( ($id == $category_id) && ($parent != $this->root_category_id) ) {
          $categories[] = $parent;
          $this->get_parent_categories($parent, $categories);
        }
      }
    }
  }

  function get_full_cpath($categories_id){
    if ( preg_match('/_/', $categories_id) ){
      return $categories_id;
    } else {
      $categories = array();
      $this->get_parent_categories($categories_id, $categories);

      $categories = array_reverse($categories);
      $categories[] = $categories_id;
      $cpath = implode('_', $categories);

      return $cpath;
    }
  }

  function get_category_url($cpath) {
    $cpath = $this->get_full_cpath($cpath);
    $categories = @explode('_', $cpath);

    if(sizeof($categories) > 1){
      $category_id = end($categories);
      $parent_id = $categories[sizeof($categories)-2];
    }else{
      $category_id = $cpath;
      $parent_id = $this->root_category_id;
    }

    $category_url = $this->data[$parent_id][$category_id]['url'];

    return $category_url;
  }

  function get_category_name($cpath){
    $cpath = $this->get_full_cpath($cpath);
    $categories = @explode('_', $cpath);

    if(sizeof($categories) > 1){
      $category_id = end($categories);
      $parent_id = $categories[sizeof($categories)-2];
    }else{
      $category_id = $cpath;
      $parent_id = $this->root_category_id;
    }

    $category_name = $this->data[$parent_id][$category_id]['name'];
    return $category_name;
  }
  
  function build_ext_json_tree($parent_id = 0) {
    $result = array();
    
    if (isset($this->data[$parent_id])) {
      foreach ($this->data[$parent_id] as $category_id => $category) {
        $data = array('id' => $category_id, 'text' => $category['name']);

        if (isset($this->data[$category_id])) {
          $data['children'] = $this->build_ext_json_tree($category_id);
        } else {
          $data['leaf'] = true;
        }
        
        $result[] = $data;
      }
    }
    
    return $result;
  }
  
  public function build_check_tree($parent_id = 0,$checked = array()) {
    $result = array();
    
    if (isset($this->data[$parent_id])) {
      foreach ($this->data[$parent_id] as $category_id => $category) {
        $data = array('id' => $category_id, 'text' => $category['name']);
        if (!empty($checked) && in_array($category_id, $checked))
        {
          $data['checked'] = TRUE;
        }
        else
        {
          $data['checked'] = FALSE;
        }

        if (isset($this->data[$category_id])) {
          $data['children'] = $this->build_check_tree($category_id, $checked);
        } else {
          $data['leaf'] = TRUE;
        }
        
        $result[] = $data;
      }
    }
    
    return $result;
  }
  
  function buildBranchArray($parent_id, $level = 0, $result = '') {
    if (empty($result)) {
      $result = array();
    }

    if (isset($this->data[$parent_id])) {
      foreach ($this->data[$parent_id] as $category_id => $category) {
        if ($this->breadcrumb_usage == true) {
          $category_link = $this->build_bread_crumb($category_id);
        } else {
          $category_link = $category_id;
        }

        $result[] = array('id' => $category_link,
                          'title' => str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . $category['name']);

        if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
          if ($this->follow_cpath === true) {
            if (in_array($category_id, $this->cpath_array)) {
              $result = $this->buildBranchArray($category_id, $level+1, $result);
            }
          } else {
            $result = $this->buildBranchArray($category_id, $level+1, $result);
          }
        }
      }
    }

    return $result;
  }

  function build_bread_crumb($category_id, $level = 0) {
    $breadcrumb = '';

    foreach ($this->data as $parent => $categories) {
      foreach ($categories as $id => $info) {
        if ($id == $category_id) {
          if ($level < 1) {
            $breadcrumb = $id;
          } else {
            $breadcrumb = $id . $this->breadcrumb_separator . $breadcrumb;
          }

          if ($parent != $this->root_category_id) {
            $breadcrumb = $this->build_bread_crumb($parent, $level+1) . $breadcrumb;
          }
        }
      }
    }

    return $breadcrumb;
  }
}
// END Category Tree Class

/* End of file category_tree.php */
/* Location: ./system/tomatocart/libraries/category_tree.php */