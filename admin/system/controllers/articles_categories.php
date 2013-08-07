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
 * Articles Categories Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Articles_Categories extends TOC_Controller
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

        $this->load->model('articles_categories_model');
    }
    
    // ------------------------------------------------------------------------

    /**
     * List the articles categories
     *
     * @access public
     * @return string
     */
    public function list_articles_categories()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;

        $articles_categories = $this->articles_categories_model->get_articles_categories($start, $limit);

        $records = array();
        if ($articles_categories !== NULL)
        {
            $records = $articles_categories;
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->articles_categories_model->get_total(), EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------

    /**
     * Delete an article cateogry
     *
     * @access public
     * @return string
     */
    public function delete_article_category()
    {
        $error = FALSE;

        $count = $this->articles_categories_model->get_articles($this->input->post('articles_categories_id'));
        if ($count > 0)
        {
            $error = TRUE;
            $feedback = sprintf(lang('delete_warning_category_in_use_articles'), $count);
        }

        if ($error === FALSE)
        {
            if ($this->articles_categories_model->delete($this->input->post('articles_categories_id')) === FALSE)
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
            else
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . $feedback);
        }

        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete several articles categories
     *
     * @access public
     * @return string
     */
    public function delete_articles_categories()
    {
        $error = FALSE;
        $feedback = array();

        $articles_categories_ids = json_decode($this->input->post('batch'));

        $check_categories = array();
        if (count($articles_categories_ids) > 0)
        {
            foreach($articles_categories_ids as $id)
            {
                $count = $this->articles_categories_model->get_articles($id);

                if ($count > 0)
                {
                    $data = $this->articles_categories_model->get_data($id);
                    $check_categories[] = $data['articles_categories_name'];
                }
            }
        }
        else
        {
            $error = TRUE;
        }

        if (count($check_categories) > 0)
        {
            $error = TRUE;
            $feedback[] = lang('batch_delete_error_articles_categories_in_use') . '<br />' . implode(', ', $check_categories);
        }

        if ($error === FALSE)
        {
            if (count($articles_categories_ids) > 0)
            {
                foreach($articles_categories_ids as $id)
                {
                    if ($this->articles_categories_model->delete($id) === FALSE)
                    {
                        $error = TRUE;
                        break;
                    }
                }
            }

            if ($error === FALSE)
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }

        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------

    /**
     * Set the status of an article category
     *
     * @access public
     * @return string
     */
    public function set_status()
    {
        if ($this->articles_categories_model->set_status($this->input->post('articles_categories_id'), $this->input->post('flag')))
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
     * Save an article category
     *
     * @access public
     * @return string
     */
    public function save_articles_category()
    {
        $this->load->helper('html_output');

        $categories_name = $this->input->post('articles_categories_name');
        $urls = $this->input->post('articles_categories_url');
        $formatted_urls = array();
        
        //search engine friendly urls
        if (is_array($urls) && count($urls) > 0)
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

        $data = array('name' => $this->input->post('articles_categories_name'),
                      'url' => $formatted_urls,
                      'status' => $this->input->post('articles_categories_status'),
                      'articles_order' => $this->input->post('articles_categories_order'),
                      'page_title' => $this->input->post('page_title'),
                      'meta_keywords' => $this->input->post('meta_keywords'),
                      'meta_description' => $this->input->post('meta_description'));

        //save data
        if ($this->articles_categories_model->save($this->input->post('articles_categories_id'), $data))
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
     * Load an article category
     *
     * @access public
     * @return string
     */
    public function load_articles_categories()
    {
        $articles_categories_infos = $this->articles_categories_model->get_info($this->input->post('articles_categories_id'));

        $data = array();
        if ($articles_categories_infos !== NULL)
        {
            foreach($articles_categories_infos as $info)
            {
                if ($info['language_id'] == lang_id())
                {
                    $data['articles_categories_status'] = $info['articles_categories_status'];
                    $data['articles_categories_order'] = $info['articles_categories_order'];
                }

                $data['articles_categories_name[' . $info['language_id'] . ']'] = $info['articles_categories_name'];
                $data['articles_categories_url[' . $info['language_id'] . ']'] = $info['articles_categories_url'];
                $data['page_title[' . $info['language_id'] . ']'] = $info['articles_categories_page_title'];
                $data['meta_keywords[' . $info['language_id'] . ']'] = $info['articles_categories_meta_keywords'];
                $data['meta_description[' . $info['language_id'] . ']'] = $info['articles_categories_meta_description'];
            }
        }

        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file articles_categories.php */
/* Location: ./system/controllers/articles_categories.php */