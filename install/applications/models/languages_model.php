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
 * Languages_Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Languages_Model extends CI_Model
{

	/**
	 * Constructor
	 *
	 * @access public
	 * @param string
	 */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the settings
     * Don't retrieves the language depending settings
     *
     * @return	The settings array
     */
    function load($group = 'general')
    {
        $definitions = array();
        $this->db->select('*')->from('languages_definitions')->where('languages_id', lang_id())->where('content_group', $group);
        $qry = $this->db->get();

        foreach ($qry->result() as $key => $row){
            $definitions[$row->definition_key] = $row->definition_value;
        }

        return $definitions;
    }

    /**
     * Get languages
     * 
     * @access public
     * @return array
     */
    function get_languages()
    {
        $qry = $this->db->select('*')->from('languages')->order_by(' sort_order, name')->get();

        $languages = array();
        foreach ($qry->result_array() as $row){
            $languages[$row['code']] = array( 'id' => $row['languages_id'],
                                        'code' => $row['code'],
                                        'country_iso' => strtolower(substr($row['code'], 3)),
                                        'name' => $row['name'],
                                        'locale' => $row['locale'],
                                        'charset' => $row['charset'],
                                        'date_format_short' => $row['date_format_short'],
                                        'date_format_long' => $row['date_format_long'],
                                        'time_format' => $row['time_format'],
                                        'text_direction' => $row['text_direction'],
                                        'currencies_id' => $row['currencies_id'],
                                        'numeric_separator_decimal' => $row['numeric_separator_decimal'],
                                        'numeric_separator_thousands' => $row['numeric_separator_thousands'],
                                        'parent_id' => $row['parent_id']);
        }

        return $languages;
    }

    /**
     * Insert language 
     * 
     * @access public
     * @param $language
     * @return boolean
     */
    function insert($language) {
        if ( $this->db->insert('languages', $language) ) {
            //get insert id
            return $this->db->insert_id();
        }
        
        return FALSE;
    }
    
    /**
     * Insert definition
     * 
     * @access public
     * @param $definition
     * @param $table
     * @return boolean
     */
    function insert_definition ($definition, $table = 'languages_definitions') {
        return $this->db->insert($table, $definition);
    }
}
/* End of file languages_model.php */
/* Location: ./install/application/models/languages_model.php */