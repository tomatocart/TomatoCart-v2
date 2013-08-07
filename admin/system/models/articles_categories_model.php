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
 * Articles Categories Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/
 */
class Articles_Categories_Model extends CI_Model
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
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the articles categories
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_articles_categories($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('c.articles_categories_id, c.articles_categories_status, cd.articles_categories_name, c.articles_categories_order')
            ->from('articles_categories c')
            ->join('articles_categories_description cd', 'c.articles_categories_id = cd.articles_categories_id and c.articles_categories_id > 1')
            ->where('cd.language_id', lang_id())
            ->order_by('c.articles_categories_id desc');
        
        if ($start !== NULL && $limit !== NULL)
        {
            $this->db->limit($limit, $start);
        }
        
        $result = $this->db->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get total articles with the article category id
     *
     * @access public
     * @param $id
     * @return int
     */
    public function get_articles($id)
    {
        $result = $this->db
            ->select('count(articles_id) as num_of_articles')
            ->from('articles')
            ->where('articles_categories_id', $id)
            ->get();
        
        $data = $result->row_array();
        
        return $data['num_of_articles'];
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete an article category
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        if (is_numeric($id))
        {
            //start transaction
            $this->db->trans_begin();
            
            //delete articles_categories table
            $this->db->delete('articles_categories', array('articles_categories_id' => $id));
            
            if ($this->db->trans_status() === TRUE)
            {
                //delete articles_categories_description table
                $this->db->delete('articles_categories_description', array('articles_categories_id' => $id));
            } 
            
            if ($this->db->trans_status() === TRUE)
            {
                //commit
                $this->db->trans_commit();
                
                return TRUE;
            }
            
            //rollback
            $this->db->trans_rollback();
            return FALSE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the info of an article category
     *
     * @access public
     * @param $id
     * @param $language_id
     * @return mixed
     */
    public function get_data($id, $language_id = NULL)
    {
        if ($language_id === NULL)
        {
            $language_id = lang_id();
        }
        
        $result = $this->db
            ->select('c.*, cd.*')
            ->from('articles_categories c')
            ->join('articles_categories_description cd', 'c.articles_categories_id = cd.articles_categories_id')
            ->where(array('c.articles_categories_id' => $id, 'cd.language_id' => $language_id))
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Set the status of the article category
     *
     * @access public
     * @param $id
     * @param $flag
     * @return boolean
     */
    public function set_status($id, $flag)
    {
        $this->db->update('articles_categories', array('articles_categories_status' => $flag), array('articles_categories_id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Insert or update an article category
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save($id = NULL, $data)
    {
        $category_id = '';
        $error = FALSE;
        
        //start transaction
        $this->db->trans_begin();
        
        //article category
        $article_category = array('articles_categories_order' => $data['articles_order'], 
                                  'articles_categories_status' => $data['status']);
        if (is_numeric($id))
        {
            $this->db->update('articles_categories', $article_category,  array('articles_categories_id' => $id));
        }
        else
        {
            $this->db->insert('articles_categories', $article_category);
        }
        
        if ($this->db->trans_status() === TRUE)
        {
            $articles_category_id = (is_numeric($id)) ? $id : $this->db->insert_id();
            
            //languages
            foreach(lang_get_all() as $l)
            {
                $articles_category_description = array('articles_categories_name' => $data['name'][$l['id']], 
                                                       'articles_categories_url' => ($data['url'][$l['id']] == '') ? $data['name'][$l['id']] : $data['url'][$l['id']], 
                                                       'articles_categories_page_title' => $data['page_title'][$l['id']], 
                                                       'articles_categories_meta_keywords' => $data['meta_keywords'][$l['id']], 
                                                       'articles_categories_meta_description' => $data['meta_description'][$l['id']]);
                if (is_numeric($id))
                {
                    $this->db->update('articles_categories_description', 
                                      $articles_category_description, 
                                      array('articles_categories_id' => $articles_category_id, 'language_id' => $l['id']));
                }
                else
                {
                    $articles_category_description['articles_categories_id'] = $articles_category_id;
                    $articles_category_description['language_id'] = $l['id'];
                    
                    $this->db->insert('articles_categories_description', $articles_category_description);
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
            //commit transaction
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        //rollback
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * get the info of an article category
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_info($id)
    {
        $result = $this->db
            ->select('c.*, cd.*')
            ->from('articles_categories c')
            ->join('articles_categories_description cd', 'c.articles_categories_id = cd.articles_categories_id')
            ->where('c.articles_categories_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get total number of the articles categories
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        $this->db
            ->select('c.articles_categories_id, c.articles_categories_status, cd.articles_categories_name, c.articles_categories_order')
            ->from('articles_categories c')
            ->join('articles_categories_description cd', 'c.articles_categories_id = cd.articles_categories_id and c.articles_categories_id > 1')
            ->where('cd.language_id', lang_id())
            ->order_by('c.articles_categories_order');
        
        $result = $this->db->get();
        
        return $result->num_rows();
    }
} 

/* End of file articles_categories_model.php */
/* Location: ./system/models/articles_categories_model.php */