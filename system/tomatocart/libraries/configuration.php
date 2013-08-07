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
 * Configuration Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Configuration
{
    /**
     * configuration
     *
     * @access protected
     * @var array
     */
    protected $configuration = array();

    /**
     * Constructor
     *
     * @access  public
     */
    function __construct()
    {
        $CI =& get_instance();
        $CI->load->model('settings_model');
        $this->configuration = $CI->settings_model->get_settings();

        log_message('debug', "Language Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a single line of text from the language array
     *
     * @access  public
     * @param $line the language line
     * @return  string
     */
    function line($key = '')
    {
        $value = ($key == '' OR ! isset($this->configuration[$key])) ? FALSE : $this->configuration[$key];

        // Because killer robots like unicorns!
        if ($value === FALSE)
        {
            log_message('error', 'Could not find the language definition "' . $key . '"');
        }

        return $value;
    }

}
// END Configuration Class

/* End of file configuration.php */
/* Location: ./system/tomatocart/library/configuration.php */
