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
class TOC_Service_debug extends TOC_Service_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'debug';

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
    array('name' => 'SERVICE_DEBUG_EXECUTION_TIME_LOG',
              'title' => 'Page Execution Time Log File', 
              'type' => 'textfield',
              'value' => '',
              'description' => 'Location of the page execution time log file (eg, /www/log/page_parse.log).'),
    array('name' => 'SERVICE_DEBUG_EXECUTION_DISPLAY',
              'title' => 'Show The Page Execution Time', 
              'type' => 'combobox',
              'mode' => 'local',
		  	  'value' => '1',
              'description' => 'Show the page execution time.',
              'values' => array(
    array('id' => '1', 'text' => 'True'),
    array('id' => '-1', 'text' => 'False'))),
    array('name' => 'SERVICE_DEBUG_LOG_DB_QUERIES',
              'title' => 'Log Database Queries', 
              'type' => 'combobox',
              'mode' => 'local',
		  	  'value' => '-1',
              'description' => 'Log all database queries in the page execution time log file.',
              'values' => array(
    array('id' => '1', 'text' => 'True'),
    array('id' => '-1', 'text' => 'False'))),
    array('name' => 'SERVICE_DEBUG_OUTPUT_DB_QUERIES',
              'title' => 'Show Database Queries', 
              'type' => 'combobox',
              'mode' => 'local',
		  	  'value' => '-1',
              'description' => 'Show all database queries made.',
              'values' => array(
    array('id' => '1', 'text' => 'True'),
    array('id' => '-1', 'text' => 'False'))),
    array('name' => 'SERVICE_DEBUG_SHOW_DEVELOPMENT_WARNING',
              'title' => 'Show Development Version Warning', 
              'type' => 'combobox',
              'mode' => 'local',
		  	  'value' => '1',
              'description' => 'Show an TomatoCart development version warning message.',
              'values' => array(
    array('id' => '1', 'text' => 'True'),
    array('id' => '-1', 'text' => 'False'))),
    array('name' => 'SERVICE_DEBUG_CHECK_LOCALE',
              'title' => 'Check Language Locale', 
              'type' => 'combobox',
              'mode' => 'local',
		  	  'value' => '1',
              'description' => 'Show a warning message if the set language locale does not exist on the server.',
              'values' => array(
    array('id' => '1', 'text' => 'True'),
    array('id' => '-1', 'text' => 'False'))),
    array('name' => 'SERVICE_DEBUG_CHECK_INSTALLATION_MODULE',
              'title' => 'Check Installation Module', 
              'type' => 'combobox',
              'mode' => 'local',
		  	  'value' => '1',
              'description' => 'Show a warning message if the installation module exists.',
              'values' => array(
    array('id' => '1', 'text' => 'True'),
    array('id' => '-1', 'text' => 'False'))),
    array('name' => 'SERVICE_DEBUG_CHECK_DOWNLOAD_DIRECTORY',
              'title' => 'Check Download Directory', 
              'type' => 'combobox',
              'mode' => 'local',
		  	  'value' => '1',
              'description' => 'Show a warning if the digital product download directory does not exist.',
              'values' => array(
    array('id' => '1', 'text' => 'True'),
    array('id' => '-1', 'text' => 'False'))));
     
    /**
     * Constructor
     *
     * @access public
     */
    function __construct()
    {
        parent::__construct();

        $this->title = lang('services_debug_title');
        $this->description = lang('services_debug_description');
    }

    function run()
    {
        if ($this->config['SERVICE_DEBUG_SHOW_DEVELOPMENT_WARNING'] == '1') {
            echo '<p class="messageStack">
            		<ul>
            			<li>This is a development version of TomatoCart (' . PROJECT_VERSION . ') - please use it for testing purposes only! [' . __CLASS__ . ']</li>
            		</ul>
            	  </p>';
        }
    }
}