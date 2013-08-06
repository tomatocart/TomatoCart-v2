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
 * TOC Loader
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Loader extends CI_Loader {

    // --------------------------------------------------------------------

    /**
     * Override parent _ci_load to support public invoke of _ci_load function.
     *
     * @param array $_ci_data
     */
    public function _ci_load($_ci_data)
    {
        return parent::_ci_load($_ci_data);
    }

    // --------------------------------------------------------------------

    /**
     * Class Loader
     *
     * CI library loader require $params to be an array, so we override the library loader to support simple type for $param
     *
     * @param	string	the name of the class
     * @param	mixed	the optional parameters
     * @param	string	an optional object name
     * @return	void
     */
    public function library($library = '', $params = NULL, $object_name = NULL)
    {
        if (is_array($library))
        {
            foreach ($library as $class)
            {
                $this->library($class, $params);
            }

            return;
        }

        if ($library === '' OR isset($this->_base_classes[$library]))
        {
            return FALSE;
        }

        $this->_ci_load_class($library, $params, $object_name);
    }
}
/* End of file TOC_Loader.php */
/* Location: ./system/tomatocart/core/TOC_Loader.php */