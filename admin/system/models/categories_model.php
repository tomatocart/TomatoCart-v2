<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Categories Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Categories_Model extends CI_Model 
{
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get all the categories
     *
     * @access public
     * @param $start
     * @param $limit
     * @param $parent_id
     * @param $search
     * @return mixed
     */
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
        
        $result = $this->db
        ->limit($limit, $start)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Delete a category
     *
     * @access public
     * @param $categories_id
     * @return boolean
     */
    public function delete($categories_id)
    {
        $this->load->model('products_model');
        $this->load->library('category_tree', array('load_from_database' => TRUE, 'load_all_categories' => TRUE, 'load_from_cache' => FALSE));
        
        $error = FALSE;
        
        if (is_numeric($categories_id))
        {
            $this->category_tree->set_breadcrumb_usage(FALSE);
            
            $categories = array_merge(array(array('id' => $categories_id, 'text' => '')), $this->category_tree->get_tree($categories_id));
            $products = array();
            $products_delete = array();
          
            //Find the categories for one product in the categories that will be deleted.
            foreach ($categories as $c_entry)
            {
                $result = $this->db
                ->select('products_id')
                ->from('products_to_categories')
                ->where('categories_id', $c_entry['id'])
                ->get();
                
                if ($result->num_rows() > 0)
                {
                    foreach($result->result_array() as $product)
                    {
                        $products[$product['products_id']]['categories'][] = $c_entry['id'];
                    }
                }
                
                $result->free_result();
            }
          
            //If the product isn't in other categories, the product need to be deleted too. 
            foreach ($products as $key => $value)
            {
                $result = $this->db
                ->where('products_id', $key)
                ->where_not_in('categories_id', $value['categories'])
                ->from('products_to_categories')
                ->count_all_results();
                
                if ($result < 1)
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
                    $result = $this->db
                    ->select('categories_image')
                    ->from('categories')
                    ->where('categories_id', $c_entry['id'])
                    ->get();
                    
                    $image = $result->row_array();
                    
                    $result->free_result();
                  
                    if (!empty($image))
                    {
                        $result = $this->db
                        ->where('categories_image', $image['categories_image'])
                        ->from('categories')
                        ->count_all_results();
                        
                        if ($result == 1)
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
                            $result = $this->db
                            ->where('categories_image', $image['categories_image'])
                            ->from('categories')
                            ->count_all_results();
                            
                            if ($result == 0)
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
                $this->products_model->delete_product($id);
            }
        }
      
        if ($error === FALSE)
        {
            return TRUE;
        }
      
        return FALSE;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Save an category
     *
     * @access public
     * @param $id
     * @param $data
     * @return mixed
     */
    public function save($id = NULL, $data)
    {
        $this->load->library('upload');
            
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
                    
                    $result = $this->db
                    ->select('categories_image')
                    ->from('categories')
                    ->where('categories_id', $category_id)
                    ->get();
                    
                    $image = $result->row_array();
                    
                    $result->free_result();
                    
                    if (!empty($image['categories_image']))
                    {
                        //check whether the image is used by several different categories.
                        $result = $this->db
                        ->where('categories_image', $image['categories_image'])
                        ->from('categories')
                        ->count_all_results();
                      
                        if ($result == 1)
                        {
                            $original_img_path = ROOTPATH . 'images/categories/' . $image['categories_image'];
                            @unlink($original_img_path);
                        }
                    }
                    
                    $this->db->update('categories', array('categories_image' => $categories_image_info['file_name']), array('categories_id' => $category_id));
                    
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
            
            return $category_id;
        }
      
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Get the data of a category
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $this->load->library('category_tree', array('load_from_database' => TRUE, 'load_all_categories' => TRUE, 'load_from_cache' => FALSE));
        
        $result = $this->db->get_where('categories', array('categories_id' => $id));
        $data = $result->row_array();
        $result->free_result();
        
        foreach(lang_get_all() as $l)
        {
            $result = $this->db
            ->select('categories_name, categories_url, categories_page_title, categories_meta_keywords, categories_meta_description')
            ->from('categories_description')
            ->where(array('categories_id' => $id, 'language_id' => $l['id']))
            ->get();
            
            $description = $result->row_array();
            
            $data['categories_name[' . $l['id'] . ']'] = $description['categories_name'];
            $data['page_title[' . $l['id'] . ']'] = $description['categories_page_title'];
            $data['meta_keywords[' . $l['id'] . ']'] = $description['categories_meta_keywords'];
            $data['meta_description[' . $l['id'] . ']'] = $description['categories_meta_description'];
            $data['categories_url[' . $l['id'] . ']'] = $description['categories_url'];
            
            $result->free_result();
        }
        
        if (!empty($data))
        {
            $data['childs_count'] = sizeof($this->category_tree->get_children($id, $dummy = array()));
            $data['products_count'] = $this->category_tree->get_number_of_products($id);
            
            $cPath = explode('_', $this->category_tree->get_full_cpath($id));
            array_pop($cPath);
            $data['parent_category_id'] = !empty($cPath) ? implode('_',$cPath) : 0;
            
            return $data;
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Set the status of a category
     *
     * @access public
     * @param $id
     * @param $flag
     * @param $product_flag
     * @return boolean
     */
    public function set_status($id, $flag, $product_flag)
    {
        $this->load->library('category_tree', array('load_from_database' => TRUE, 'load_all_categories' => TRUE, 'load_from_cache' => FALSE));
        
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
          
            return TRUE;
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Move a category
     *
     * @access public
     * @param $id
     * @param $new_id
     * @return boolean
     */
    public function move($id, $new_id)
    {
        $categories_ids = explode('_', $new_id);
        
        if (in_array($id, $categories_ids))
        {
            return FALSE;
        }
        
        $this->db->update('categories', array('parent_id' => end($categories_ids), 'last_modified' => date('Y-m-d H:i:s')), array('categories_id' => $id));
        
        return TRUE;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Get the total number of the categories
     *
     * @access public
     * @param $parent_id
     * @param $search
     * @return int
     */
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
        
        $result = $this->db->get();
        
        return $result->num_rows();
    }
}

/* End of file categories.php */
/* Location: ./system/models/categories_model.php */