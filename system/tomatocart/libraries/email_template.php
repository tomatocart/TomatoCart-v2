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
 * TOC_Email_Template
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Email_Template 
{
    
    /**
     * ci instance
     *
     * @access protected
     * @var string
     */
    protected $ci = NULL;

    /**
     * Email Template Constructor
     *
     * @access public
     */
    function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();
    }
    
    /**
     * Get email template
     * 
     * @access public
     * @param $template_name
     * @return object
     */
    function get_email_template($template_name)
    {
        //module class
        $module_class = strtolower('email_template_' . $template_name);

        //load library
        $this->ci->load->library('email_template/' . $module_class);
        
        //get object
        $object = $this->ci->{$module_class};
        
        if (isset($object) && is_object($object)) {
            return $object;
        }
        
        return NULL;
    }
}

/* End of file email_template.php */
/* Location: ./system/tomatocart/libraries/email_template.php */