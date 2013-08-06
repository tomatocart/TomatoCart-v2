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
 * TomatoCart Config Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Config extends CI_Config 
{
    
    /**
     * Constructor
     *
     * Override the CI_Config constructor and add local path to config paths
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->_config_paths = array(LOCALAPPPATH, APPPATH);

        log_message('debug', 'CI_Config Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Site URL
     * Returns base_url . index_page [. uri_string]
     *
     * @param	mixed	the URI string or an array of segments
     * @return	string
     */
    public function ci_site_url($uri = '')
    {
        return parent::site_url($uri);
    }

    // --------------------------------------------------------------------

    /**
     * Site URL
     * Returns base_url . index_page [. uri_string]
     *
     * @param	mixed	the URI string or an array of segments
     * @return	string
     */
    public function site_url($uri = '')
    {
        //get ci instance
        $ci = get_instance();
        
        //check whether sef service is installed
        if ($ci->service->is_installed('sef')) 
        {
            //get service
            $sef = $ci->service->get_service('sef');
            
            //process the search friendly url
            return $sef->site_url($uri);
        }
        else
        {
            return parent::site_url($uri);
        }
    }
}
// END Language Class

/* End of file TOC_Config.php */
/* Location: ./system/tomatocart/core/TOC_Config.php */