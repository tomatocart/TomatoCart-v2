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
 * Frontend Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Controller extends CI_Controller
{
    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();

        //initialize system language
        $this->lang->initialize();
        $this->output->set_header('Content-Type: text/html; charset=' . $this->lang->get_character_set());
        setlocale(LC_TIME, explode(',', $this->lang->get_locale()));

        //load language resource
        $this->lang->ini_load();

        //load module language resource
        $module = trim($this->router->class);
        $this->lang->ini_load($module . '.php');

        //initialize system language
        $this->output->set_header('Content-Type: text/html; charset=' . $this->lang->get_character_set());
        setlocale(LC_TIME, explode(',', $this->lang->get_locale()));

        //to be delete, all constant must be access via the config helper function
        $this->configuration->extract_all();

        //load template
        $this->load->library('template');

        //load cache
        $this->load->driver('cache');

        //set layout
        $this->template->set_layout('index.php');
    }

    // --------------------------------------------------------------------

    /**
     * Is this a ajax request
     *
     * @access protected
     * @return bool
     */
    protected function is_ajax()
    {
        return $this->input->is_ajax_request();
    }

    // --------------------------------------------------------------------


    /**
     * set output
     *
     * The sub class could override this method to extend the output type
     *
     * @access protected
     * @param array or string or xml etc...
     * @return void
     */
    protected function set_output($output) {
        $type = gettype($output);

        if ($type == 'array')
        {
            $this->output_json($output);
        }

        if ($type == 'string')
        {
            $this->output_string($output);
        }
    }

    // --------------------------------------------------------------------

    /**
     * set output to a json string
     *
     * @access private
     * @param array
     * @return void
     */
    private function output_json($output)
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    // --------------------------------------------------------------------

    /**
     * set output to a string
     *
     * @access private
     * @param string
     * @return void
     */

    private function output_string($output)
    {
        $this->output->set_content_type('text/plain')->set_output($output);
    }
}

/* End of file TOC_Controller.php */
/* Location: ./admin/system/core/TOC_Controller.php */