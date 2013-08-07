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
 * Cache Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Cache extends TOC_Controller
{
    /**
     * Path to cache directory
     *
     * @var string
     */
    private $_cache_map;

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
        $this->_cache_map = directory_map(ROOTPATH . '/local/cache');
        
    }
    
    // ------------------------------------------------------------------------

    /**
     * List the cache
     *
     * @access public
     * @return string
     */
    public function list_cache()
    {
        $this->load->helper('date');
        
        //get all the cached files
        $cached_files = array();
        if (count($this->_cache_map) > 0)
        {
            foreach($this->_cache_map as $key => $cache)
            {
                //ignore the sub folder
                if (@is_dir(ROOTPATH . '/local/cache/' . $key))
                {
                    continue;
                }
                
                $last_modified = filemtime(ROOTPATH . '/local/cache/' . $cache);
                
                //verify that the cache file named with the language code
                if (strpos($cache, '-') !== FALSE)
                {
                    $code = substr($cache, 0, strpos($cache, '-'));
                }
                else
                {
                    $code = $cache;
                }
                
                if(isset($cached_files[$code]))
                {
                    $cached_files[$code]['total']++;
                    
                    if ($last_modified > $cached_files[$code]['last_modified'])
                    {
                        $cached_files[$code]['last_modified'] = $last_modified;
                    }
                }
                else
                {
                    $cached_files[$code] = array('total' => 1, 
                                                 'last_modified' => $last_modified);
                } 
            }
        }
        
        //build the records of the cached files
        $records = array();
        if (count($cached_files) > 0)
        {
            foreach($cached_files as $code => $file)
            {
                $records[] = array('code' => $code, 
                                   'total' => $file['total'], 
                                   'last_modified' => mdate('%Y/%m/%d %H:%i:%s', $file['last_modified']));
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the cache
     *
     * @access public
     * @return string
     */
    public function delete_cache()
    {
        foreach($this->_cache_map as $cache)
        {
            if ( ($cache == $this->input->post('block')) || (substr($cache, 0, strpos($cache, '-')) == $this->input->post('block')) )  
            {
                if (@unlink(ROOTPATH . '/local/cache/' . $cache))
                {
                    $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
                }
                else
                {
                    $response = array('success' => FALSE ,'feedback' => lang('ms_error_action_not_performed'));
                }
            } 
        } 
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the caches
     *
     * @access public
     * @return string
     */
    public function delete_caches()
    {
        $cache_codes = json_decode($this->input->post('batch'));
        
        foreach($this->_cache_map as $cache)
          {
            if (in_array($cache, $cache_codes) || in_array(substr($cache, 0, strpos($cache, '-')), $cache_codes))  
            {
                if (@unlink(ROOTPATH . '/local/cache/' . $cache))
                {
                    $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
                }
                else
                {
                    $response = array('success' => FALSE ,'feedback' => lang('ms_error_action_not_performed'));
                }
            } 
        }
        
        $this->output->set_output(json_encode($response));
    }
}

/* End of file cache.php */
/* Location: ./system/controllers/cache.php */