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
 * Languages_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Languages_Model extends CI_Model
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

    // ------------------------------------------------------------------------

    /**
     * Get the languages resources for specified group
     *
     * @access public
     * @param $group
     * @return array
     */
    public function load($group = 'general')
    {
        $result = $this->db->select('*')->from('languages_definitions')->where('languages_id', lang_id())->where('content_group', $group)->get();

        $definitions = array();
        foreach ($result->result() as $key => $row)
        {
            $definitions[$row->definition_key] = $row->definition_value;
        }

        return $definitions;
    }

    // ------------------------------------------------------------------------

    /**
     * Get the system languages
     *
     * @access public
     * @return array
     */
    public function get_languages()
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

    // ------------------------------------------------------------------------

    /**
     * Insert language definition
     *
     * @access public
     * @param $definition
     * @return boolean
     */
    function insert_definition ($definition)
    {
        return $this->db->insert('languages_definitions', $definition);
    }

    // ------------------------------------------------------------------------

    /**
     * Check whether the language definition exists
     *
     * @access public
     * @param $definition
     * @return boolean
     */
    function check_definition($definition) 
    {
        $result = $this->db->select('*')->from('languages_definitions')->where($definition)->get();

        if ($result->num_rows() > 0) 
        {
            return TRUE;
        }

        return FALSE;
    }

    // ------------------------------------------------------------------------
    
    /**
     * Remove definition
     *
     * @access public
     * @param $definition
     * @return boolean
     */
    function remove_definition ($definition) 
    {
        return $this->db->delete('languages_definitions', $definition);
    }


    /**
     * Import module resources
     *
     * @param $module
     */
    public function import_module_resources($module) {
        $languages = $this->lang->get_languages();

        foreach ($languages as $key => $value) {
            $file = realpath(dirname(__FILE__) . '/../../../') . '/system/tomatocart/language/' . $key . '/modules/boxes/' . $module . '.xml';

            if (file_exists($file)) {
                $resource = simplexml_load_file($file);

                foreach ($resource->definitions->definition as $definition)
                {
                    $definition = array(
                        'definition_key' => (string) $definition->key,
                        'definition_value' => (string) $definition->value,
                        'content_group' => (string) $definition->group,
                        'languages_id' => $value['id']);

                    if (!$this->check_definition($definition)) {
                        $this->insert_definition($definition);
                    }
                }
            }
        }
    }
}
/* End of file languages_model.php */
/* Location: ./admin/system/models/languages_model.php */