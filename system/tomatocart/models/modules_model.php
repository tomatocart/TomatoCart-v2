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
 * Modules_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-departments-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Modules_Model extends CI_Model
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
     * Get modules
     * 
     * @access public
     * @param $group
     * @param $page
     * @return array
     */
    public function get_modules($group, $page)
    {
        $data = array();

        $content_pages = array('*', $group . '/*', $group . '/' . $page);

        //page has specific module
        $result = $this->db->select('id, content_group, module, params')->from('templates_modules')->where('page_specific', '1')->where('templates_id', $this->template->get_id())->where_in('content_page', $content_pages)->where('status', 1)->order_by('content_group')->order_by('sort_order')->get();
        if ($result->num_rows() > 0)
        {
            foreach($result->result_array() as $row)
            {
                $data[$row['content_group']][] = array('id' => $row['id'], 'module' => $row['module'], 'params' => $row['params']);
            }
        }
        //get all page modules
        else
        {
            $data = array();

            $result = $this->db->select('id, content_group, content_page, module, params')->from('templates_modules')->where('templates_id', $this->template->get_id())->where_in('content_page', $content_pages)->where('status', 1)->order_by('content_group')->order_by('sort_order')->get();
            if ($result->num_rows() > 0)
            {
                foreach($result->result_array() as $row)
                {
                    $data[$row['content_group']][] = array('id' => $row['id'], 'module' => $row['module'], 'params' => $row['params'], 'page' => $row['content_page']);
                }
            }
        }

        return $data;
    }
}

/* End of file modules_model.php */
/* Location: ./system/tomatocart/models/modules_model.php */