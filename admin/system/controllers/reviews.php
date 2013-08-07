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
 * Reviews Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Reviews extends TOC_Controller
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
      
      $this->load->model('reviews_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List the reviews
     *
     * @access public
     * @return string
     */
    public function list_reviews()
    {
        $this->load->helper('date');
        $this->load->helper('html_output');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $reviews = $this->reviews_model->get_reviews($start, $limit);
        
        $records = array();
        if ($reviews !== NULL)
        {
            foreach($reviews as $review)
            {
                $records[] = array('reviews_id' => $review['reviews_id'],
                                   'date_added' => mdate('%Y/%m/%d', human_to_unix($review['date_added'])),
                                   'reviews_rating' => image('images/stars_' . $review['reviews_rating'] . '.png', sprintf(lang('rating_from_5_stars'), $review['reviews_rating'])),
                                   'products_name' => $review['products_name'],
                                   'reviews_status' => $review['reviews_status'],
                                   'code' => show_image($review['languages_code']));
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->reviews_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Set the status of the review
     *
     * @access public
     * @return string
     */
    public function set_status()
    {
        if ($this->reviews_model->set_status($this->input->post('reviews_id'), $this->input->post('flag')))
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
     * Load the review
     *
     * @access public
     * @return string
     */
    public function load_reviews()
    {
        $this->load->helper('date');
        $this->load->helper('html_output');
        
        $data = $this->reviews_model->get_data($this->input->post('reviews_id'));
        
        if ($data !== NULL)
        {
            $data['detailed_rating'] = $data['reviews_rating'];
            $data['reviews_rating'] = image('images/stars_' . $data['reviews_rating'] . '.png', sprintf(lang('rating_from_5_stars'), $data['reviews_rating']));
            
            //get the summary rating
            $average = $this->reviews_model->get_avg_rating($data['products_id']);
            
            if ($average !== NULL)
            {
                $data['average_rating'] = $average['reviews_rating'] / 5 * 100;
            }
            
            //get ratings
            $ratings = $this->reviews_model->get_customers_ratings($this->input->post('reviews_id'));
            
            if ($ratings !== NULL)
            {
                $customers_ratings = array();
                foreach($ratings as $rating)
                {
                    $customers_ratings[] = array('customers_ratings_id' => $rating['customers_ratings_id'],
                                                 'ratings_id' => $rating['ratings_id'],
                                                 'name'  => $rating['ratings_text'],
                                                 'value' => $rating['ratings_value']); 
                }
                
                if (count($customers_ratings) > 0)
                {
                    $data['ratings'] = $customers_ratings;
                }
                else
                {
                    $data['ratings'] = NULL;
                }
            }
            
            $data['date_added'] = mdate('%Y/%m/%d', human_to_unix($data['date_added']));
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the review
     *
     * @access public
     * @return string
     */
    public function save_reviews()
    {
        $total = 0;
        $data = array('review' => $this->input->post('reviews_text'), 'reviews_status' => $this->input->post('reviews_status'));
        
        //verify whether some ratings are included
        $ratings = array();
        foreach($this->input->post() as $key => $value)
        {
            if (substr($key, 0, 13) == 'ratings_value') 
            {
                $customers_ratings_id = substr($key, 13);
                
                $ratings[$customers_ratings_id] = $value;
                $total += $value;
            }
        }
        
        if (count($ratings) > 0) 
        {
            $data['rating'] = $total / count($ratings);
            $data['ratings'] = $ratings;
        } 
        else 
        {
            $data['rating'] = $this->input->post('detailed_rating');
        }
        
        if ($this->reviews_model->save($this->input->post('reviews_id'), $data))
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
     * Delete the review
     *
     * @access public
     * @return string
     */
    public function delete_review()
    {
        if ($this->reviews_model->delete($this->input->post('reviews_id')))
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
     * Batch delete the reviews
     *
     * @access public
     * @return string
     */
    public function delete_reviews()
    {
        $error = FALSE;
        
        $reviews_ids = json_decode($this->input->post('batch'));
        
        if (count($reviews_ids) > 0)
        {
            foreach($reviews_ids as $id)
            {
                if (!$this->reviews_model->delete($id))
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
            $response = array('success' => FALSE ,'feedback' => lang('ms_error_action_not_performed'));               
        }
        
        $this->output->set_output(json_encode($response));
    }
}

/* End of file reviews.php */
/* Location: ./system/controllers/reviews.php */