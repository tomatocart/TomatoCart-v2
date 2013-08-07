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
 * Server Info Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Server_Info extends TOC_Controller
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
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the system info
     *
     * @access public
     * @return string
     */
    public function get_system_info()
    {
        
        $data = array('server_host' => $this->input->server('SERVER_NAME') . ' (' . gethostbyname($this->input->server('SERVER_NAME')) . ')',
                      'project_version' => $this->config->item('project_version'), 
                      'server_operating_system' => PHP_OS, 
                      'server_date' => date('Y-m-d H:i:s'), 
                      'database_host' => $this->db->hostname . ' (' . gethostbyname($this->db->hostname) . ')', 
                      'database_version' => 'MySQL ' . (function_exists('mysql_get_server_info') ? mysql_get_server_info() : ''), 
                      'http_server' => $this->input->server('SERVER_SOFTWARE'), 
                      'php_version' => 'PHP: ' . PHP_VERSION . ' / Zend: ' . (function_exists('zend_version') ? zend_version() : ''));
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
}

/* End of file server_info.php */
/* Location: ./system/controllers/server_info.php */