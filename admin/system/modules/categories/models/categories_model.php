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
 * @filesource modules/categories/models/categories_model.php
 */

class Categories_Model extends CI_Model 
{
  public function get_categories($start, $limit, $parent_id = 0, $search = NULL)
  {
    $this->db
    ->select('c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.categories_status, c.date_added, c.last_modified')
    ->from('categories c')
    ->join('categories_description cd', 'c.categories_id = cd.categories_id')
    ->where(array('cd.language_id' => lang_id(), 'c.parent_id' => $parent_id));
    
    if (!empty($search))
    {
      $this->db->like('cd.categories_name', $search);
    }
    
    $Qcategories = $this->db
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qcategories->result_array();
  }
  
  public function delete($categories_id)
  {
    $error = FALSE;
    
    if (is_numeric($categories_id))
    {
      $this->category_tree->setBreadcrumbUsage(false);
      
      $categories = array_merge(array(array('id' => $categories_id, 'text' => '')), $this->category_tree->getTree($categories_id));
      $products = array();
      $products_delete = array();
      
      //Find the categories for one product in the categories that will be deleted.
      foreach ($categories as $c_entry)
      {
        $Qproducts = $this->db
        ->select('products_id')
        ->from('products_to_categories')
        ->where('categories_id', $c_entry['id'])
        ->get();
        
        if ($Qproducts->num_rows() > 0)
        {
          foreach($Qproducts->result_array() as $product)
          {
            $products[$product['products_id']]['categories'][] = $c_entry['id'];
          }
        }
        
        $Qproducts->free_result();
      }
      
      //If the product isn't in other categories, the product need to be deleted too. 
      foreach ($products as $key => $value)
      {
        $product_to_others = $this->db
        ->where('products_id', $key)
        ->where_not_in('categories_id', $value['categories'])
        ->from('products_to_categories')
        ->count_all_results();
        
        if ($product_to_others < 1)
        {
          $products_delete[$key] = $key;
        }
      }
      
      //Set the max execution time for this script.
      if (!('safe_mode')) 
      {
        set_time_limit(0);
      }
      
      //Begin to delete categories
      foreach ($categories as $c_entry)
      {
        $this->db->trans_begin();
        
        if ($error === FALSE)
        {
          //Delete the categorie image only for one category
          $Qimage = $this->db
          ->select('categories_image')
          ->from('categories')
          ->where('categories_id', $c_entry['id'])
          ->get();
          
          $image = $Qimage->row_array();
          
          $Qimage->free_result();
          
          if (!empty($image))
          {
            $image_to_categories = $this->db
            ->where('categories_image', $image['categories_image'])
            ->from('categories')
            ->count_all_results();
            
            if ($image_to_categories == 1)
            {
              $path = ROOTPATH . 'images/categories/' . $image['categories_image'];
              if (file_exists($path))
              {
                @unlink($path);
              }
            }
          }
          
          //Delete category
          $this->db->delete('categories', array('categories_id' => $c_entry['id']));
          
          if ($this->db->trans_status() === FALSE)
          {
            $error = TRUE;
          }
          
          //Detele category ratings
          if ($error === FALSE)
          {
            $this->db->delete('categories_ratings', array('categories_id' => $c_entry['id']));
            
            if ($this->db->trans_status() === FALSE)
            {
              $error = TRUE;
            }
          }
          
          //Delete category descriptions.
          if ($error === FALSE) {
            $this->db->delete('categories_description', array('categories_id' => $c_entry['id']));
            
            if ($this->db->trans_status() === FALSE)
            {
              $error = TRUE;
            }
          }
          
          //Delete products to categories
          if ($error === FALSE)
          {
            $this->db->delete('products_to_categories', array('categories_id' => $c_entry['id']));
            
            if ($this->db->trans_status() === FALSE)
            {
              $error = TRUE;
            }
          }
          
          if ($error === FALSE)
          {
            $this->db->trans_commit();
            
            //Check whether the category image belong to any category, if not, then delete
            if (!empty($image['categories_image']))
            {
              $image_to_categories = $this->db
              ->where('categories_image', $image['categories_image'])
              ->from('categories')
              ->count_all_results();
              
              if ($image_to_categories == 0)
              {
                $path = ROOTPATH . 'images/categories/' . $image['categories_image'];
                if (file_exists($path))
                {
                  @unlink($path);
                }
              }
            }
            
          }
          else
          {
             $this->db->trans_rollback();
          }
        }
      }
      //End Delete Categories
      
      //Delete products need to delete
      foreach ($products_delete as $id)
      {
        $this->products->get_model()->delete_product($id);
      }
    }
    
    if ($error === FALSE)
    {
      if ($this->cache->get('category_tree-' . $this->lang->get_code()) !== FALSE)
      {
        $this->cache->delete('category_tree-' . $this->lang->get_code());
      }
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function save($id = NULL, $data)
  {
    $category_id = '';
    $error = FALSE;
    
    $this->db->trans_begin();
    
    $categories_data = array('categories_status' => $data['categories_status'], 
                             'sort_order' => $data['sort_order']);
    if (is_numeric($id))
    {
      $categories_data['last_modified'] = date("Y-m-d H:i:s");
      
      $this->db->update('categories', $categories_data, array('categories_id' => $id));
    }
    else
    {
      $categories_data['date_added'] = date("Y-m-d H:i:s");
      $categories_data['parent_id'] = $data['parent_id'];
      
      $this->db->insert('categories', $categories_data);
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $category_id = (is_numeric($id)) ? $id : $this->db->insert_id();
      
      if (is_numeric($id))
      {
        $tbl_products = $this->db->protect_identifiers('products', TRUE);
        $tbl_products_to_categories = $this->db->protect_identifiers('products_to_categories', TRUE);
        
        $sql = 'update ' . $tbl_products . ' set products_status = ? where products_id in (select products_id from ' . $tbl_products_to_categories . ' where categories_id = ?)';
          
        if ($data['categories_status'])
        {
          $this->db->query($sql, array(1, $id));
        }
        else if ($data['flag'])
        {
          $this->db->query($sql, array(0, $id));
        }
        
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
        }
      }
      
      if ($error === FALSE)
      {
        foreach (lang_get_all() as $l)
        {
          $categories_description_data = array('categories_name' => $data['name'][$l['id']], 
                                               'categories_url' => $data['url'][$l['id']], 
                                               'categories_page_title' => $data['page_title'][$l['id']], 
                                               'categories_meta_keywords' => $data['meta_keywords'][$l['id']], 
                                               'categories_meta_description' => $data['meta_description'][$l['id']]);
           
          if (is_numeric($id))
          {
            $this->db->update('categories_description', $categories_description_data, array('categories_id' => $category_id, 'language_id' => $l['id']));
          }
          else
          {
            $categories_description_data['categories_id'] = $category_id;
            $categories_description_data['language_id'] = $l['id'];
            
            $this->db->insert('categories_description', $categories_description_data);
          }
          
          if ($this->db->trans_status() === FALSE)
          {
            $error = TRUE;
            
            break;
          }
        }
      }
      
      if ($error === FALSE)
      {
        $image_path = ROOTPATH . 'images/categories/';
        
        $config['upload_path'] = $image_path;
        $config['allowed_types'] = 'gif|jpg|png';
        
        $this->upload->initialize($config);
        
        if ($this->upload->do_upload($data['image']))
        {
          $categories_image_info = $this->upload->data();
          
          $Qimage = $this->db
          ->select('categories_image')
          ->from('categories')
          ->where('categories_id', $category_id)
          ->get();
          
          $image = $Qimage->row_array();
          
          if (!empty($image['categories_image']))
          {
            //check whether the image is used by several different categories.
            $check_image_counts = $this->db
            ->where('categories_image', $image['categories_image'])
            ->from('categories')
            ->count_all_results();
            
            if ($check_image_counts == 1)
            {
              $original_img_path = ROOTPATH . 'images/categories/' . $image['categories_image'];
              @unlink($original_img_path);
              
              $Qimage->free_result();
            }
          }
          
          $Qcf = $this->db->update('categories', array('categories_image' => $categories_image_info['file_name']), array('categories_id' => $category_id));
          
          if ($this->db->trans_status() === FALSE)
          {
            $error = TRUE;
          }
        }
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->trans_commit();
      
      if ($this->cache->get('category_tree-' . $this->lang->get_code()) !== FALSE)
      {
        $this->cache->delete('category_tree-' . $this->lang->get_code());
      }
      
      return $category_id;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_data($id)
  {
    $Qcategories = $this->db->get_where('categories', array('categories_id' => $id));
    
    $data = $Qcategories->row_array();
    
    $Qcategories->free_result();
    
    foreach(lang_get_all() as $l)
    {
      $Qcategories_descriptions = $this->db
      ->select('categories_name, categories_url, categories_page_title, categories_meta_keywords, categories_meta_description')
      ->from('categories_description')
      ->where(array('categories_id' => $id, 'language_id' => $l['id']))
      ->get();
      
      $description = $Qcategories_descriptions->row_array();
      
      $data['categories_name[' . $l['id'] . ']'] = $description['categories_name'];
      $data['page_title[' . $l['id'] . ']'] = $description['categories_page_title'];
      $data['meta_keywords[' . $l['id'] . ']'] = $description['categories_meta_keywords'];
      $data['meta_description[' . $l['id'] . ']'] = $description['categories_meta_description'];
      $data['categories_url[' . $l['id'] . ']'] = $description['categories_url'];
      
      $Qcategories_descriptions->free_result();
    }
    
    if (empty($data))
    {
      return FALSE;
    }
    
    $data['childs_count'] = sizeof($this->category_tree->get_children($id, $dummy = array()));
    $data['products_count'] = $this->category_tree->get_number_of_products($id);
    
    $cPath = explode('_', $this->category_tree->get_full_cpath($id));
    array_pop($cPath);
    $data['parent_category_id'] = !empty($cPath) ? implode('_',$cPath) : 0;
    
    $Qcategories->free_result();
    
    return $data;
  }
  
  public function set_status($id, $flag, $product_flag)
  {
    $categories_ids = array((int)$id);
    $sub_categories = array();
    
    $this->category_tree->get_children($id, $sub_categories);
    
    if (!empty($sub_categories))
    {
      foreach($sub_categories as $sub_category)
      {
        $categories_ids[] = $sub_category['id'];
      }
    }
    
    $this->db->where_in('categories_id', $categories_ids);
    $this->db->update('categories', array('categories_status' => $flag));
    
    if ($this->db->affected_rows() > 0)
    {
      if (($flag == 0) && ($product_flag == 1))
      {
        $tbl_products = $this->db->protect_identifiers('products', TRUE);
        $tbl_products_to_categories = $this->db->protect_identifiers('products_to_categories', TRUE);
        
        $sql = 'update ' . $tbl_products . ' set products_status = 0 where products_id in (select products_id from ' . $tbl_products_to_categories . ' where categories_id in (' . implode(',', $categories_ids) . '))';
        $this->db->query($sql);
      }
      
      if ($this->cache->get('category_tree-' . $this->lang->get_code()) !== FALSE)
      {
        $this->cache->delete('category_tree-' . $this->lang->get_code());
      }
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function move($id, $new_id)
  {
    $categories_ids = explode('_', $new_id);
    
    if (in_array($id, $categories_ids))
    {
      return FALSE;
    }
    
    $this->db->update('categories', array('parent_id' => end($categories_ids), 'last_modified' => date('Y-m-d H:i:s')), array('categories_id' => $id));
    
    if ($this->cache->get('category_tree-' . $this->lang->get_code()) !== FALSE)
    {
      $this->cache->delete('category_tree-' . $this->lang->get_code());
    }
    
    return TRUE;
  }
  
  public function get_totals($parent_id = 0, $search = NULL)
  {
    $this->db
    ->select('c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.categories_status, c.date_added, c.last_modified')
    ->from('categories c')
    ->join('categories_description cd', 'c.categories_id = cd.categories_id')
    ->where(array('cd.language_id' => lang_id(), 'c.parent_id' => $parent_id));
    
    if (!empty($search))
    {
      $this->db->like('cd.categories_name', $search);
    }
    
    $Qcategories = $this->db->get();
    
    return $Qcategories->num_rows();
  }
}

/* End of file categories.php */
/* Location: ./system/modules/categories/models/categories_model.php */
