<?php
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 * 
 * @package     TomatoCart
 * @author      TomatoCart Dev Team
 * @copyright   Copyright (c) 2009 - 2013, TomatoCart. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl.html
 * @link        http://tomatocart.com
 * @since       2.0.0
 * @filesource  
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Languages Model Class
 * 
 * @package     TomatoCart
 * @subpackage  Libraries
 * @category    Model
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
class Languages_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the languages resources for specified group
     * 
     * @param   string  $group
     * 
     * @return  array
     * 
     * @since   2.0.0
     */
    public function load($group = 'general')
    {
        $result = $this->db->select('*')->from('languages_definitions')->where('languages_id', lang_id())->where('content_group', $group)->get();

        $definitions = array();

        if ($result->num_rows() > 0)
        {
            foreach ($result->result_array() as $row)
            {
                $definitions[$row['definition_key']] = $row['definition_value'];
            }
        }

        return $definitions;
    }

    /**
     * Get the system languages
     * 
     * @return array
     * 
     * @since   2.0.0
     */
    public function get_all()
    {
        $result = $this->db->select('*')->from('languages')->order_by('sort_order, name')->get();

        $languages = array();

        if ($result->num_rows() > 0)
        {
            foreach ($result->result_array() as $row)
            {
                $languages[$row['code']] = array(
                    'id' => $row['language_id'],
                    'code' => $row['code'],
                    'name' => $row['name'],
                    'locale' => $row['locale'],
                    'charset' => $row['charset'],
                    'parent_id' => $row['parent_id'],
                    'time_format' => $row['time_format'],
                    'country_iso' => strtolower(substr($row['code'], 3)),
                    'currencies_id' => $row['currencies_id'],
                    'text_direction' => $row['text_direction'],
                    'date_format_long' => $row['date_format_long'],
                    'date_format_short' => $row['date_format_short'],
                    'numeric_separator_decimal' => $row['numeric_separator_decimal'],
                    'numeric_separator_thousands' => $row['numeric_separator_thousands']
                );
            }
        }

        return $languages;
    }

    /**
     * Insert language definition
     * 
     * @param   array   $definition
     * 
     * @return  boolean TRUE on success or FALSE on failure.
     * 
     * @since   2.0.0
     */
    public function insert_definition($definition)
    {
        return $this->db->insert('languages_definitions', $definition);
    }

    /**
     * Check whether the language definition exists.
     * 
     * @param   array   $definition
     * 
     * @return  boolean TRUE on success or FALSE on failure.
     * 
     * @since   2.0.0
     */
    public function check_definition($definition)
    {
        $result = $this->db->select('*')->from('languages_definitions')->where($definition)->get();

        if ($result->num_rows() > 0)
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Remove definition
     * 
     * @param   array   $definition
     * 
     * @return  boolean TRUE on success or FALSE on failure.
     * 
     * @since   2.0.0
     */
    public function remove_definition($definition)
    {
        return $this->db->delete('languages_definitions', $definition);
    }

    /**
     * Import module resources
     * 
     * @param   string  $module The module name.
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    public function import_module_resources($module)
    {
        $languages = $this->lang->get_all();

        foreach ($languages as $key => $value)
        {
            $file = realpath(dirname(__FILE__) . '/../../../') . '/system/tomatocart/language/' . $key . '/modules/boxes/' . $module . '.xml';

            if (file_exists($file))
            {
                $resource = simplexml_load_file($file);

                foreach ($resource->definitions->definition as $definition)
                {
                    $data = array(
                        'languages_id' => $value['id'],
                        'content_group' => (string) $definition->group,
                        'definition_key' => (string) $definition->key,
                        'definition_value' => (string) $definition->value
                    );

                    if ( ! $this->check_definition($data))
                    {
                        $this->insert_definition($data);
                    }
                }
            }
        }
    }
}

/* End of file languages_model.php */
/* Location: ./admin/system/models/languages_model.php */