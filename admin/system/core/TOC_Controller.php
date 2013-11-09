<?php
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 * 
 * @package     TomatoCart
 * @author      TomatoCart Dev Team
 * @copyright   Copyright (c) 2009 - 2013, TomatoCart. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl.html
 * @link        http://tomatocart.com
 * @since       2.0.0
 * @filesource  
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * TOC Controller Class
 * 
 * @package     TomatoCart
 * @subpackage  Libraries
 * @category    Libraries
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
class TOC_Controller extends CI_Controller {

    /**
     * Class constructor
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    public function __construct()
    {
        parent::__construct();

        // Initialize system language
        $this->lang->initialize();
        $this->output->set_header('Content-Type: text/html; charset=' . $this->lang->get_charset());
        setlocale(LC_TIME, explode(',', $this->lang->get_locale()));

        // Load language resource
        $this->lang->ini_load();

        // Load module language resource
        $module = trim($this->router->class);
        $this->lang->ini_load($module);

        // To be delete, all constant must be access via the config helper function
        $this->configuration->extract();

        // Load template
        $this->load->library('template');

        // Load cache
        $this->load->driver('cache');

        log_message('debug', 'TOC Controller Class Initialized');
    }

    /**
     * Fetch an item from the GET array
     * 
     * @param   string  $index      Index for item to be fetched from $_GET
     * @param   boolean $xss_clean  Whether to apply XSS filtering
     * 
     * @return  mixed
     * 
     * @since   2.0.0
     */
    protected function get($index = NULL, $xss_clean = FALSE)
    {
        return $this->input->get($index, $xss_clean);
    }

    /**
     * Fetch an item from GET data with fallback to POST
     * 
     * @param   string  $index      Index for item to be fetched from $_GET or $_POST
     * @param   boolean $xss_clean  Whether to apply XSS filtering
     * 
     * @return  mixed
     * 
     * @since   2.0.0
     */
    protected function get_post($index = NULL, $xss_clean = FALSE)
    {
        return $this->input->get_post($index, $xss_clean);
    }

    /**
     * Is this a ajax request
     * 
     * @return  boolean
     * 
     * @since   2.0.0
     */
    protected function is_ajax()
    {
        return $this->input->is_ajax_request();
    }

    /**
     * Fetch an item from the POST array
     * 
     * @param   string  $index      Index for item to be fetched from $_POST
     * @param   boolean $xss_clean  Whether to apply XSS filtering
     * 
     * @return  mixed
     * 
     * @since   2.0.0
     */
    protected function post($index = NULL, $xss_clean = FALSE)
    {
        return $this->input->post($index, $xss_clean);
    }

    /**
     * Fetch an item from POST data with fallback to GET
     * 
     * @param   string  $index      Index for item to be fetched from $_POST or $_GET
     * @param   boolean $xss_clean  Whether to apply XSS filtering
     * 
     * @return  mixed
     * 
     * @since   2.0.0
     */
    protected function post_get($index = NULL, $xss_clean = FALSE)
    {
        return $this->input->post_get($index, $xss_clean);
    }

    /**
     * Record
     * 
     * @param   mixed   $output Output data
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    protected function record($output)
    {
        $this->set_output(array(EXT_JSON_READER_ROOT => $output));
    }

    /**
     * Response
     * 
     * @param   mixed   $output Output data
     * @param   integer $total  Output count data
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    protected function response($output, $total)
    {
        $this->set_output(array(
            EXT_JSON_READER_ROOT => $output,
            EXT_JSON_READER_TOTAL => $total
        ));
    }

    /**
     * Set output
     * 
     * @param   mixed   $output Output data
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    protected function set_output($output)
    {
        $type = gettype($output);

        if ($type === 'array')
        {
            $this->output_json($output);
        }

        if ($type === 'string')
        {
            $this->output_string($output);
        }
    }

    /**
     * Set output to a json string
     * 
     * @param   string  $output Output data
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    private function output_json($output)
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Set output to a string
     * 
     * @param   string  $output Output data
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    private function output_string($output)
    {
        $this->output->set_content_type('text/plain')->set_output($output);
    }
}

if ( ! function_exists('ci'))
{
    /**
     * Reference to the CI_Controller method.
     * 
     * Returns current CI instance object
     * 
     * @return  object
     * 
     * @since   2.0.0
     */
    function ci()
    {
        return get_instance();
    }
}

/* End of file TOC_Controller.php */
/* Location: ./admin/system/core/TOC_Controller.php */