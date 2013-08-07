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
 * Template_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-departments-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Template_Model extends CI_Model
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

    /**
     * 
     * @access public
     * @param $customers_id
     * @return
     */
    public function get_modules($customers_id)
    {
    }

    /**
     * Get templates
     * 
     * @access public
     * @return array
     */
    public function get_templates()
    {
        $result = $this->db->select('id, code, title')->from('templates')->get();

        $templates = FALSE;
        if ($result->num_rows() > 0)
        {
            $templates = $result->result_array();
        }

        return $templates;
    }
}

/* End of file template_model.php */
/* Location: ./system/tomatocart/models/template_model.php */