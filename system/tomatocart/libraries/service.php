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
 * Registry Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Service
{
    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    protected $ci = null;

    /**
     * shipping modules array
     *
     * @access protected
     * @var array
     */
    protected $modules = array();

    /**
     * each shipping method quotation array
     *
     * @access protected
     * @var string
     */
    protected $group = 'shipping';

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        //load extensions model
        $this->ci->load->model('extensions_model');

        //get installed module
        $this->modules = $this->ci->extensions_model->get_modules('service');

        //load module
        foreach ($this->modules as $module)
        {
            $this->ci->load->library('service/service_' . $module);
        }
    }

    /**
     * Is installed
     *
     * @access public
     * @param $service
     * @return boolean
     */
    public function is_installed($module)
    {
        //check if the language group is loaded
        if (in_array($module, $this->modules, TRUE))
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Get service module
     *
     * @access public
     * @param $module
     * @return mixed
     */
    public function get_service($module)
    {
        if (isset($this->ci->{'service_' . $module}))
        {
            return $this->ci->{'service_' . $module};
        }

        return NULL;
    }

    /**
     * Run service
     *
     * @param $module
     */
    public function run($module)
    {
        $service = $this->get_service($module);

        //check whether
        if (method_exists($service, 'run'))
        {
            $service->run();
        }
    }
}