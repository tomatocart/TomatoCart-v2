<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
 * TomatoCart install hook
 *
 * @package		TomatoCart
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

/**
 * Check installation. You could delete the code after installation
 *
 * @access public
 * @return string
 */
if( ! function_exists('install_hook'))
{
    function install_hook()
    {
       if (file_exists('install/index.php') && file_exists(LOCALAPPPATH . 'config/database.php'))
       {
           include(LOCALAPPPATH . 'config/database.php');
       
           if ( ! isset($db[$active_group]['username']) || empty($db[$active_group]['username']))
           {
               // Otherwise go to installer
               header('Location: '.rtrim($_SERVER['REQUEST_URI'], '/').'/install/');
       
               exit;
           }
       }
    }
}