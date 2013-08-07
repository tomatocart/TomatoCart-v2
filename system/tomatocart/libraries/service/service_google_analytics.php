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

require_once 'service_module.php';

/**
 * Free Shipping -- Shipping Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Service_Google_Analytics extends TOC_Service_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'google_analytics';

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
        array('name' => 'SERVICES_GOOGLE_ANALYTICS_CODE',
                  'title' => 'Google Analytics Code', 
                  'type' => 'textarea',
                  'value' => '',
                  'description' => 'Google Analytics Code.'));

    /**
     * Constructor
     *
     * @access public
     */
    function __construct()
    {
        parent::__construct();

        $this->title = lang('services_google_analytics_title');
        $this->description = lang('services_google_analytics_description');
    }
    
    /**
     * 
     */
    function run()
    {
        echo $this->config['SERVICES_GOOGLE_ANALYTICS_CODE'];
    }
}