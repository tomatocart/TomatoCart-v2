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
 * Logo Upload Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Logo_Upload extends TOC_Controller
{
    /**
     * Path to the logo
     *
     * @var string
     */
    private $logo_path;
    
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->helper('directory');
        $this->logo_path = ROOTPATH . 'images/';
        
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the logo
     *
     * @access public
     * @return string
     */
    public function save_logo()
    {
        if ($this->upload('logo_image'))
        {
            $image = $this->get_original_logo();
            
            if ($image !== NULL)
            {
                list($width, $height) = getimagesize($image);
            
                $response = array('success' => TRUE, 'image' => IMGHTTPPATH . $image, 'height' => $height, 'width' => $width, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed')); 
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed')); 
        }
        
        $this->output->set_header("Content-Type: text/html")->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the logo
     *
     * @access public
     * @return string
     */
    public function get_logo()
    {
        $image = $this->get_original_logo();
        
        if ($image != NULL)
        {
            list($width, $height) = getimagesize($this->logo_path . $image);
            
            $image = '<img src="' . IMGHTTPPATH . $image . '" width="' . $width . '" height="' . $height . '" style="padding: 10px" />';
      
            $response = array('success' => TRUE, 'image' => $image);
        }
        else
        {
            $response = array('success' => FALSE);   
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Upload the logo
     *
     * @access private
     * @param $field
     * @return boolean
     */
    private function upload($field)
    {
        $this->load->library('upload');
        $this->load->helper('core');
        
        //verify whether the resize logo checkbox is checked
        $resize_logo = $this->input->post('resize');
        
        //upload the lgogo
        $this->upload->initialize(array('upload_path' => $this->logo_path, 'allowed_types' => 'gif|jpg|png|jpeg'));
        
        if ($this->upload->do_upload($field))
        {
            //delete the original logo image
            $this->delete_logo('originals');
            
            //copy the uploade logo as the original logo
            $original_image =  $this->logo_path . 'logo_originals' . $this->upload->data('file_ext');
            copy($this->upload->data('full_path'), $original_image);
            @unlink($this->upload->data('full_path'));
            
            //resize the original logo for each template
            $templates_map = directory_map(ROOTPATH . 'templates', 1);
            foreach($templates_map as $template)
            {
                //ignore the base directory
                if ($template === 'base')
                {
                    continue;
                }
                
                //parse the template configuration file to get the logo width and height
                if (file_exists(ROOTPATH . 'templates/' . $template . '/template.xml'))
                {
                    $this->delete_logo($template);
                    
                    //the resize logo checkbox is checked, get the logo with the defined width and height
                    if ($resize_logo == '1')
                    {
                        $logo_width = $this->input->post('logo_width');
                        $logo_height = $this->input->post('logo_height');
                    }
                    //get the logo with the width and height defined in the template
                    else
                    {
                        $xml_info = simplexml_load_file(ROOTPATH . 'templates/' . $template . '/template.xml');
                    
                        $logo_info = $xml_info->Logo[0]->attributes();
                        
                        $logo_height = $logo_info['height'];
                        $logo_width = $logo_info['width'];
                    }
                    
                    //resize the logo
                    $dest_image = $this->logo_path . 'logo_' . $template . $this->upload->data('file_ext');
                    
                    if ( ! toc_gd_resize($original_image, $dest_image, (int)$logo_width, (int)$logo_height))
                    {
                       return FALSE;
                    }
                }
            }
            
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the logo
     *
     * @access private
     * @param $code
     * @return boolean
     */
    private function delete_logo($code)
    {
        $logo = 'logo_' . $code;
        
        $images_map = directory_map($this->logo_path);
        
        if (count($images_map) > 0)
        {
            foreach($images_map as $image)
            {
                if ( ! is_array($image))
                {
                    $filename = explode(".", $image);
                    
                    if ($filename[0] == $logo)
                    {
                        if(@unlink($this->logo_path . $image))
                        {
                            return TRUE;
                        }
                    }
                }
            }
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * get the orginal uploaded logo image
     *
     * @access private
     * @return mixed
     */
    private function get_original_logo()
    {
        $images_map = directory_map($this->logo_path);
        
        if (count($images_map) > 0)
        {
            foreach ($images_map as $image) {

                if ( ! is_array($image))
                {
                    $filename = explode(".", $image);
                
                    //it is the orignal uploaded logo image
                    if ($filename[0] == 'logo_originals')
                    {
                       return $this->logo_path . 'logo_originals.' . $filename[1];
                    }
                }
            }
        }
        
        return NULL;
    }
}

/* End of file logo_upload.php */
/* Location: ./system/controllers/logo_upload.php */