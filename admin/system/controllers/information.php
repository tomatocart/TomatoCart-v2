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
 * Information Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Information extends TOC_Controller
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
        
        $this->load->model('information_model');
        $this->load->model('articles_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the articles
     *
     * @access public
     * @return string
     */
    public function list_articles()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
         
        $articles = $this->information_model->get_articles($start, $limit);
        
        $records = array();
        if ($articles !== NULL)
        {
            $records = $articles;
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->information_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Set the status of the article
     *
     * @access public
     * @return string
     */
    public function set_status()
    {
        if ($this->articles_model->set_status($this->input->post('aID'), $this->input->post('flag')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete an artcile
     *
     * @access public
     * @return string
     */
    public function delete_article()
    {
        if ($this->articles_model->delete($this->input->post('articles_id')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Batach delete the articles
     *
     * @access public
     * @return string
     */
    public function delete_articles()
    {
        $error = FALSE;
        
        $articles_ids = json_decode($this->input->post('batch'));
        
        if (count($articles_ids) > 0)
        {
            foreach($articles_ids as $id)
            {
                if ($this->articles_model->delete($id) === FALSE)
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
       
        
        if ($error === FALSE)
        {
            $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));    
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save an article
     *
     * @access public
     * @return string
     */
    public function save_article()
    {
        $this->load->helper('html_output');
        
        //search engine friendly urls
        $formatted_urls = array();
        $urls = $this->input->post('articles_url');
        $article_name = $this->input->post('articles_name');
        if (is_array($urls) && count($urls) > 0)
        {
            foreach($urls as $languages_id => $url)
            {
                $url = format_friendly_url($url);
                
                if (empty($url))
                {
                    $article_name = $this->input->post('articles_name');
                    $url = format_friendly_url($article_name[$languages_id]);
                }
                
                $formatted_urls[$languages_id] = $url;
            }
        }
        
        $data = array('articles_name' => $this->input->post('articles_name'),
                      'articles_url' => $formatted_urls,
                      'articles_image' => 'articles_image',
                      'articles_description' => $this->input->post('articles_description'),
                      'articles_order' => $this->input->post('articles_order'),
                      'articles_status' => $this->input->post('articles_status'),
                      'delimage' => $this->input->post('delimage') == 'on' ? 1 : 0,
                      'articles_categories' => $this->input->post('articles_categories_id') ? $this->input->post('articles_categories_id') : '1',
                      'page_title' => $this->input->post('page_title'),
                      'meta_keywords' => $this->input->post('meta_keywords'),
                      'meta_description' => $this->input->post('meta_description'));
        
        if ($this->articles_model->save(($this->input->post('articles_id') != -1 ? $this->input->post('articles_id') : NULL), $data))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_header('Content-Type: text/html')->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Load an article
     *
     * @access public
     * @return string
     */
    public function load_article()
    {
        $articles_infos = $this->articles_model->get_info($this->input->post('articles_id'));
        
        $data = array();
        if ($articles_infos !== NULL)
        {
            foreach($articles_infos as $articles_info)
            {
                if ($articles_info['language_id'] == lang_id())
                {
                    $data['articles_status'] = $articles_info['articles_status'];
                    $data['articles_order'] = $articles_info['articles_order'];
                    $data['articles_image'] = $articles_info['articles_image'];
                }
              
              $data['articles_name[' . $articles_info['language_id'] . ']'] = $articles_info['articles_name'];
              $data['articles_url[' . $articles_info['language_id'] . ']'] = $articles_info['articles_url'];
              $data['articles_description[' . $articles_info['language_id'] . ']'] = $articles_info['articles_description'];
              $data['page_title[' . $articles_info['language_id'] . ']'] = $articles_info['articles_page_title'];
              $data['meta_keywords[' . $articles_info['language_id'] . ']'] = $articles_info['articles_meta_keywords'];
              $data['meta_description[' . $articles_info['language_id'] . ']'] = $articles_info['articles_meta_description'];
            }
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file information.php */
/* Location: ./system/controllers/information.php */