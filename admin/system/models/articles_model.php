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
 * Articles Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Articles_Model extends CI_Model
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
    
    // --------------------------------------------------------------------
    
    /**
     * Get Articles
     *
     * @access public
     * @param $start
     * @param $limit
     * @param $current_category_id
     * @param $search
     * @return mixed
     */
    public function get_articles($start = NULL, $limit = NULL, $current_category_id = 0, $search = NULL)
    {
        $this->db
            ->select('a.articles_id, a.articles_status, a.articles_order, ad.articles_name, acd.articles_categories_name')
            ->from('articles a')
            ->join('articles_description ad', 'a.articles_id = ad.articles_id and a.articles_id > 5')
            ->join('articles_categories_description acd', 'acd.articles_categories_id = a.articles_categories_id and acd.language_id = ad.language_id')
            ->where('ad.language_id', lang_id());
        
        if ($current_category_id > 0)
        {
            $this->db->where('a.articles_categories_id', $current_category_id);
        }
        
        if ($search !== NULL)
        {
            $this->db->like('ad.articles_name', $search);
        }
        
        $this->db->order_by('a.articles_id desc');
        
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
    
    // --------------------------------------------------------------------
  
    /**
     * Save an article
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save($id = NULL, $data)
    {
        //load libraries
        $this->load->library(array('image', 'upload'));
        
        //load helpers
        $this->load->helper(array('html_output', 'directory'));
        
        //flag to check errors
        $error = FALSE;
        
        //start transaction
        $this->db->trans_begin();
        
        //article information
        $article_info = array('articles_status' => $data['articles_status'], 
                              'articles_order' => $data['articles_order'], 
                              'articles_categories_id' => $data['articles_categories']);
        
        //editing article
        if (is_numeric($id))
        {
            $article_info['articles_last_modified'] = date('Y-m-d H:i:s');
            
            $this->db->update('articles', $article_info, array('articles_id' => $id));
        }
        //creating new article
        else
        {
            $article_info['articles_date_added'] = date('Y-m-d H:i:s');
            $article_info['articles_last_modified'] = date('Y-m-d H:i:s');
            
            $this->db->insert('articles', $article_info);
        }
        
        if ($this->db->trans_status() === FALSE)
        {
            $error = TRUE;
        }
        else
        {
            //get the article id as inserting the new article
            $articles_id = is_numeric($id) ? $id : $this->db->insert_id();
        }
        
        //articles images
        if ($error === FALSE && $data['delimage'] == 1)
        {
            $this->image->delete_articles_image($articles_id);
            
            $this->db->update('articles', array('articles_image' => NULL), array('articles_id' => $articles_id));
            
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
            }
        }
        
        //upload article image
        if ($error === FALSE)
        {
            //create the directory to store articles images
            if (!is_dir(ROOTPATH . 'images/articles'))
            {
                directory_make(ROOTPATH . 'images/articles');
                directory_make(ROOTPATH . 'images/articles/originals');
            }   
            
            //check whether the uploaded image is existed
            if (isset($_FILES[$data['articles_image']]) && isset($_FILES[$data['articles_image']]['tmp_name']) && !empty($_FILES[$data['articles_image']]['tmp_name']) && is_uploaded_file($_FILES[$data['articles_image']]['tmp_name']))
            {
                $config['upload_path'] = ROOTPATH . 'images/articles/originals';
                
                //only the gif, jpg, png, jpeg are allowed to be uploaded
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                
                $this->upload->initialize($config);
                
                //upload the image now
                if ($this->upload->do_upload($data['articles_image']))
                {
                    //upload the articles image field in the database
                    $this->db->update('articles', array('articles_image' => $this->upload->data('file_name')), array('articles_id' => $articles_id));
                
                    if ($this->db->trans_status() === FALSE)
                    {
                        $error = TRUE;
                    }
                    else
                    {
                        //resize the image for each image group
                        foreach($this->image->get_groups() as $group)
                        {
                            if ($group['id'] !== 1)
                            {
                                $this->image->resize($this->upload->data('file_name'), $group['id'], 'articles');
                            }
                        }
                    }
                }
                
                //upload the article image failed
                else
                {
                    $error = TRUE;
                }
            }
        }
        
        
        //Process articles description for each language
        if ($error === FALSE)
        {
            foreach(lang_get_all() as $l)
            {
                $articles_description = array('articles_name' => $data['articles_name'][$l['id']],
                                              'articles_url' => $data['articles_url'][$l['id']], 
                                              'articles_description' => $data['articles_description'][$l['id']], 
                                              'articles_page_title' => $data['page_title'][$l['id']], 
                                              'articles_meta_keywords' => $data['meta_keywords'][$l['id']], 
                                              'articles_meta_description' => $data['meta_description'][$l['id']]);
                //editing article
                if (is_numeric($id))
                {
                    $this->db->update('articles_description', $articles_description, array('articles_id' => $articles_id, 'language_id' => $l['id']));
                }
                //creating new article
                else
                {
                    $articles_description['articles_id'] = $articles_id;
                    $articles_description['language_id'] = $l['id'];
                    
                    $this->db->insert('articles_description', $articles_description);
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
    
    // --------------------------------------------------------------------
    
    /**
     * Delete an article
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->load->library('image');
        
        //start transaction
        $this->db->trans_begin();
        
        $this->image->delete_articles_image($id);
        
        $this->db->delete('articles_description', array('articles_id' => $id));
        
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('articles', array('articles_id' => $id));
        }
        
        if ($this->db->trans_status() === TRUE)
        {
            //commit transaction
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        //rollback
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set the status of an article
     *
     * @access public
     * @param $id
     * @param $flag
     * @return boolean
     */
    public function set_status($id, $flag)
    {
        $this->db->update('articles', array('articles_status' => $flag, 'articles_last_modified' => date('Y-m-d H:i:s')), array('articles_id' => $id));
                                      
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get the info of an anticle
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_info($id)
    {
        $result = $this->db
            ->select('a.*, ad.*')
            ->from('articles a')
            ->join('articles_description ad', 'a.articles_id = ad.articles_id')
            ->where('a.articles_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get the total number of the articles
     *
     * @access public
     * @param $current_category_id
     * @param $search
     * @return int
     */
    public function get_total($current_category_id = NULL, $search = NULL)
    {
        $this->db
            ->select('a.articles_id, a.articles_status, a.articles_order, ad.articles_name, acd.articles_categories_name')
            ->from('articles a')
            ->join('articles_description ad', 'a.articles_id = ad.articles_id and a.articles_id > 5')
            ->join('articles_categories_description acd', 'acd.articles_categories_id = a.articles_categories_id and acd.language_id = ad.language_id')
            ->where('ad.language_id', lang_id());
        
        if ($current_category_id > 0)
        {
            $this->db->where('a.articles_categories_id', $current_category_id);
        }
        
        if ($search !== NULL)
        {
            $this->db->like('ad.articles_name', $search);
        }
        
        $result = $this->db->get();
        
        return $result->num_rows();
    }
} 

/* End of file articles_model.php */
/* Location: ./system/models/articles_model.php */