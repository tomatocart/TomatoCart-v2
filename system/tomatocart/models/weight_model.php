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
 * Departments_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-departments-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Weight_Model extends CI_Model
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
     * Get title
     * 
     * @access public
     * @param $id
     * @return boolean
     */
    function get_title($id)
    {
        $result = $this->db->select('weight_class_title')->from('weight_class')->where('weight_class_id', $id)->where('language_id', lang_id())->get();

        $title = FALSE;
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();
            $tile = $data['weight_class_title'];
        }

        return $title;
    }

    /**
     * Get weight rules
     * 
     * @access public
     * @return array
     */
    function get_rules()
    {
        $weight_classes = FALSE;

        //weight class rules
        $result = $this->db->select('r.weight_class_from_id, r.weight_class_to_id, r.weight_class_rule')->from('weight_classes_rules as r')->join('weight_classes as c', 'c.weight_class_id = r.weight_class_from_id', 'left')->get();
        if ($result->num_rows() > 0)
        {
            foreach($result->result_array() as $row)
            {
                $weight_classes[$row['weight_class_from_id']][$row['weight_class_to_id']] = $row['weight_class_rule'];
            }
        }

        //weight classes
        $result = $this->db->select('weight_class_id, weight_class_key, weight_class_title')->from('weight_classes')->where('language_id', lang_id())->get();
        if ($result->num_rows() > 0)
        {
            foreach($result->result_array() as $row)
            {
                $weight_classes[$row['weight_class_id']]['key'] = $row['weight_class_key'];
                $weight_classes[$row['weight_class_id']]['title'] = $row['weight_class_title'];
            }
        }

        return $weight_classes;
    }

    /**
     * Get classes
     * 
     * @access public
     * @return array
     */
    function get_classes()
    {
        $result = $this->db->select('weight_class_id, weight_class_title')->from('weight_class')->where('language_id', lang_id())->order_by('weight_class_title')->get();

        $weight_classes = FALSE;
        if ($result->num_rows() > 0)
        {
            $weight_classes = $result->result_array();
        }

        return $weight_classes;
    }
}

/* End of file weight_model.php */
/* Location: ./system/tomatocart/models/weight_model.php */