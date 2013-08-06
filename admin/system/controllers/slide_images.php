<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Slide Images Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com
 */
class Slide_Images extends TOC_Controller
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

        $this->load->model('slide_images_model');
    }

    // --------------------------------------------------------------------
    
    /**
     * List slide images
     * 
     * @access public
     * @return string
     */
    public function list_slide_images()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        $group = $this->input->get_post('group') ? $this->input->get_post('group') : NULL;

        $images = $this->slide_images_model->get_images($start, $limit, $group);

        $records = array();
        if ($images !== NULL)
        {
            foreach($images as $image)
            {
                $slide_image = '';
                if (file_exists(ROOTPATH . 'images/' . $image['image']))
                {
                    list($orig_width, $orig_height) = getimagesize(ROOTPATH . 'images/' . $image['image']);
                    $width = intval($orig_width * 60 / $orig_height);

                    $slide_image = '<img src="' . IMGHTTPPATH . $image['image'] . '" width="' . $width . '" height="80" />';
                }

                $records[] = array('image_id' => $image['image_id'],
                                   'image' =>  $slide_image,
                                   'image_url' => $image['image_url'],
                                   'sort_order' => $image['sort_order'],
                                   'group' => $image['group'],
                                   'status' => $image['status']);
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->slide_images_model->get_totals($group), EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------
    
    /**
     * Get image groups filter
     *
     * @access public
     * @return string
     */
    public function get_image_groups_filter()
    {
        $groups = $this->slide_images_model->get_groups();

        $records = array(array('id' => '', 'text' => lang('--All--')));
        if ($groups !== NULL)
        {
            foreach ($groups as $group) {
                $records[] = array('id' => $group, 'text' => $group);
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------
    
    /**
     * Get image groups
     *
     * @access public
     * @return string
     */
    public function get_image_groups()
    {
        $groups = $this->slide_images_model->get_groups();

        $records = array();
        if ($groups !== NULL)
        {
            foreach ($groups as $group)
            {
                $records[] = array('id' => $group, 'text' => $group);
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------
    
    /**
     * Save slide images
     * 
     * @access public
     * @return string
     */
    public function save_slide_images()
    {
        //check new image group or old group
        $new_image_group = $this->input->post('new_image_group');
        $group = empty($new_image_group) ? $this->input->post('group') : $new_image_group;

        $data = array('status' => $this->input->post('status'),
                      'image_url' => $this->input->post('image_url'),
                      'description' => $this->input->post('description'),
                      'group' => $group,
                      'sort_order' => $this->input->post('sort_order'));

        $image_id = $this->input->post('image_id');

        $error = FALSE;
        $feedback = array();
        
        //check images for all languages
        if (empty($image_id))
        {
            foreach(lang_get_all() as $l)
            {
                //there isn't any image uploaded, throw the error feedback
                if (empty($_FILES['image' . $l['id']]['name']))
                {
                    $error = TRUE;
                    
                    $feedback[] = sprintf(lang('ms_error_image_empty'), $l['name']);

                    break;
                }
            }
        }
        
        //save image
        if ($error === FALSE)
        {
            if ($this->slide_images_model->save($image_id, $data))
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
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br>' . implode('<br>', $feedback));
        }

        $this->output->set_header("Content-Type: text/html")->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------
    
    /**
     * Delete slide image
     * 
     * @access public
     * @return string
     */
    public function delete_slide_image()
    {
        if ($this->slide_images_model->delete($this->input->post('image_id')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------
    
    /**
     * Bacth delete slide image
     * 
     * @access public
     * @return string
     */
    public function batch_delete()
    {
        $error = FALSE;

        $batch = $this->input->post('batch');

        $images_ids = json_decode($batch);

        if (count($images_ids) > 0)
        {
            foreach($images_ids as $id)
            {
                if ($this->slide_images_model->delete($id) == FALSE)
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
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------
    
    /**
     * Set status
     * 
     * @access public
     * @return string
     */
    public function set_status()
    {
        if ($this->slide_images_model->set_status($this->input->post('image_id'), $this->input->post('flag')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------
    
    /**
     * Load a slide image
     * 
     * @access public
     * @return string
     */
    public function load_slide_images()
    {
        $data = $this->slide_images_model->get_data($this->input->post('image_id'));

        if ($data !== NULL) 
        {
            //languages
            $languages = lang_get_all();
            
            foreach ($languages as $l)
            {
                if (isset($data['slide_image' . $l['id']]))
                {
                    $image = $data['slide_image' . $l['id']];
                    
                    list($orig_width, $orig_height) = getimagesize(ROOTPATH . 'images/' . $image);
                    $width = intval($orig_width * 80 / $orig_height);
                    
                    $data['slide_image' . $l['id']] = '<img src="' . IMGHTTPPATH . $image . '" width="' . $width . '" height="80" style="margin-left: 112px" />';
                }
            }
        }

        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file slide_images.php */
/* Location: ./system/controllers/slide_images.php */