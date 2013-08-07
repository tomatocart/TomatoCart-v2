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
class TOC_Registry
{
    /**
     * Cached data
     *
     * @access private
     * @var array
     */
    private $data = array();

    /**
     * Constructor
     */
    public function __construct() {}

    /**
     * Set Magic Method
     *
     * Sets data to the registry
     *
     * @param string $name
     * @param mixed $value
     */
    public function  __set($name, $value)
    {
        $this->set($name, $value);
    }



    /**
     * Get Magic Method
     *
     * Gets from the registry
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }



    /**
     * Set
     *
     * Sets to the registry
     *
     * @param string $name
     * @param mixed $value
     */
    final public function set($name, $value)
    {
        $this->data[$name] = $value;
    }



    /**
     * Get
     *
     * Gets from the registry
     *
     * @param string $name
     * @return mixed
     */
    final public function get($name)
    {
        if(array_key_exists($name, $this->data))
        {
            return $this->data[$name];
        }
        else
        {
            return NULL;
        }
    }
}
// END Registry Class

/* End of file registry.php */
/* Location: ./system/tomatocart/libraries/registry.php */