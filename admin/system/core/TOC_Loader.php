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
 * TOC Loader Class
 * 
 * @package     TomatoCart
 * @subpackage  Libraries
 * @category    Loader
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
class TOC_Loader extends CI_Loader {

    /**
     * Class constructor
     * 
     * @return	void
     * 
     * @since   2.0.0
     */
    public function __construct()
    {
        parent::__construct();

        log_message('debug', 'TOC Loader Class Initialized');
    }

    /**
     * CI Data Loader
     * 
     * Override parent _ci_load to support public invoke of _ci_load function.
     * 
     * @param   array   $_ci_data   Data to load
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    public function _ci_load($_ci_data)
    {
        return parent::_ci_load($_ci_data);
    }

    /**
     * Class Loader
     * 
     * CI library loader require $params to be an array, so we override the library loader to support simple type for $param
     * 
     * @param   string  $library        Library name
     * @param   mixed   $params         Optional parameters to pass to the library class constructor
     * @param   string  $object_name    An optional object name to assign to
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    public function library($library = '', $params = NULL, $object_name = NULL)
    {
        if (empty($library))
        {
            return;
        }
        elseif (is_array($library))
        {
            foreach ($library as $class)
            {
                $this->library($class, $params);
            }

            return;
        }

        $this->_ci_load_class($library, $params, $object_name);
    }
}

/* End of file TOC_Loader.php */
/* Location: ./admin/system/core/TOC_Loader.php */