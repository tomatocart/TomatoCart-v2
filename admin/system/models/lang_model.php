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
 * Lang Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Lang_Model extends CI_Model
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
     * Get the languages
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_languages($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('*')
            ->from('languages')
            ->order_by('sort_order, name');
         
        if ($start !== NULL && $limit !== NULL)
        {
            $this->db->limit($limit, $start);
        }
        
        $result = $this->db->get();
          
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Update the language
     *
     * @access public
     * @param $id
     * @param $language
     * @param $default
     * @return boolean
     */
    public function update($id, $language, $default = FALSE)
    {
        //start transaction
        $this->db->trans_begin();
        
        $this->db->update('languages', array('name' => $language['name'], 
                                             'code' => $language['code'], 
                                             'locale' => $language['locale'], 
                                             'charset' => $language['charset'], 
                                             'date_format_short' => $language['date_format_short'], 
                                             'date_format_long' => $language['date_format_long'], 
                                             'time_format' => $language['time_format'], 
                                             'text_direction' => $language['text_direction'], 
                                             'currencies_id' => $language['currencies_id'], 
                                             'numeric_separator_decimal' => $language['numeric_separator_decimal'], 
                                             'numeric_separator_thousands' => $language['numeric_separator_thousands'], 
                                             'parent_id' => $language['parent_id'], 
                                             'sort_order' => $language['sort_order']), array('languages_id' => $id));
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            if ($default === TRUE)
            {
                $this->db->update('configuration', array('configuration_value' => $language['code']), array('configuration_key' => 'DEFAULT_LANGUAGE'));
            }
        }
        
        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            //commit
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        //rollback
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Import the language definitions
     *
     * @access public
     * @param $file
     * @param $type
     * @return boolean
     */
    public function import($file, $type)
    {
        $this->load->library('currencies');
        $this->load->library('xml');
        $this->load->library('directory_listing', array());
        
        //path to the language xml file
        $xml_path = realpath(ROOTPATH . 'system/tomatocart/language/' . $file . '.xml');
        
        $languages_definitions = array();
        if (file_exists($xml_path))
        {
            //parse the languges definitions
            if ($this->xml->load($xml_path))
            {
                $language_definitions = $this->xml->parse();
                
                //language information
                $language = array('name' => $language_definitions['language'][0]['data'][0]['title'][0],
                                  'code' => $language_definitions['language'][0]['data'][0]['code'][0],
                                  'locale' => $language_definitions['language'][0]['data'][0]['locale'][0],
                                  'charset' => $language_definitions['language'][0]['data'][0]['character_set'][0],
                                  'date_format_short' => $language_definitions['language'][0]['data'][0]['date_format_short'][0],
                                  'date_format_long' => $language_definitions['language'][0]['data'][0]['date_format_long'][0],
                                  'time_format' => $language_definitions['language'][0]['data'][0]['time_format'][0],
                                  'text_direction' => $language_definitions['language'][0]['data'][0]['text_direction'][0],
                                  'numeric_separator_decimal' => $language_definitions['language'][0]['data'][0]['numerical_decimal_separator'][0],
                                  'numeric_separator_thousands' => $language_definitions['language'][0]['data'][0]['numerical_thousands_separator'][0],
                                  'parent_id' => 0
                                 );
                                 
                //set the currency                 
                $currency = $language_definitions['language'][0]['data'][0]['default_currency'][0];
                if ($this->currencies->exists($currency) === FALSE)
                {
                    $currency = DEFAULT_CURRENCY;
                }
                $language['currencies_id'] = $this->currencies->get_id($currency);
                
                $parent_language_code = $language_definitions['language'][0]['data'][0]['parent_language_code'][0];
                
                //process the parent language
                if (!empty($parent_language_code))
                {
                    $res_parent = $this->db
                        ->select('languages_id')
                        ->from('languages')
                        ->where('code', $parent_language_code)
                        ->get();
                    
                    if ($res_parent->num_rows() === 1)
                    {
                        $parent_lang = $res_parent->row_array();
                        $res_parent->free_result();
                        
                        $language['parent_id'] = $parent_lang['languages_id'];
                    }
                }
                
                //set the definitions and tables
                $definitions = array();
                if (isset($language_definitions['language'][0]['definitions'][0]['definition']))
                {
                  $definitions = $language_definitions['language'][0]['definitions'][0]['definition'];
                }
                
                $tables = array();
                if (isset($language_definitions['language'][0]['tables'][0]['table']))
                {
                  $tables = $language_definitions['language'][0]['tables'][0]['table'];
                }
                
                unset($language_definitions);
                
                //update or insert the language
                $error = FALSE;
                $add_category_and_product_placeholders = TRUE;
                
                //start transaction
                $this->db->trans_begin();
                
                //whether the language is existing in the database
                $res_language = $this->db
                    ->select('languages_id')
                    ->from('languages')
                    ->where('code', $language['code'])
                    ->get();
                
                if ($res_language->num_rows() === 1)
                {
                    $lang = $res_language->row_array();
                    $language_id = $lang['languages_id'];
                    $add_category_and_product_placeholders = FALSE;
                    
                    $this->db->update('languages', $language, array('languages_id' => $language_id));
                }
                else
                {
                    $this->db->insert('languages', $language);
                    
                    $language_id = $this->db->insert_id();
                }
                
                $res_language->free_result();
                
                //check transaction status
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
                }
                else
                {
                    $default_language_id = $this->get_data($this->get_id(DEFAULT_LANGUAGE), 'languages_id');
                    
                    //delete the originial language definitions as replacing the language definitions
                    if ($type == 'replace')
                    {
                        $this->db->delete('languages_definitions', array('languages_id' => $language_id));
                        
                        //check transaction status
                        if ($this->db->trans_status() === FALSE)
                        {
                            $error = TRUE;
                        }
                    }
                }
                
                //process languages definitions
                if ($error === FALSE)
                {
                    $this->directory_listing->set_directory(realpath(ROOTPATH . 'system/tomatocart/language/' . $file));
                    $this->directory_listing->set_recursive(TRUE);
                    $this->directory_listing->set_include_directories(FALSE);
                    $this->directory_listing->set_add_directory_to_filename(TRUE);
                    $this->directory_listing->set_check_extension('xml');
                    
                    //extract the languages definitons from the each xml file
                    foreach($this->directory_listing->get_files() as $sub_file)
                    {
                        $sub_definitions = array();
                        if (file_exists(realpath(ROOTPATH . 'system/tomatocart/language/' . $file . '/' . $sub_file['name'])))
                        {
                            if ($this->xml->load(realpath(ROOTPATH . 'system/tomatocart/language/' . $file . '/' . $sub_file['name'])))
                            {
                                $sub_definitions = $this->xml->parse();
                                
                                $sub_definitions = $sub_definitions['language'][0]['definitions'][0]['definition'];
                            }
                        }
        
                        foreach($sub_definitions as $definition)
                        {
                            $definitions[] = $definition;
                        }
                    }
                    
                    //insert definitions into database or update them in the database
                    foreach($definitions as $definition)
                    {
                        $insert = FALSE;
                        $update = FALSE;
                        
                        //insert the languages definitions as replacing them
                        if ($type == 'replace')
                        {
                            $insert = TRUE;
                        }
                        else
                        {
                            //check the language definition in the database
                            $res_definition = $this->db
                                ->select('definition_key, content_group')
                                ->from('languages_definitions')
                                ->where(array('definition_key' => $definition['key'][0], 'languages_id' => $language_id, 'content_group' => $definition['group'][0]))
                                ->get();
                            
                            
                            //the defintion already existed
                            if ($res_definition->num_rows() > 0)
                            {
                                if ($type == 'update')
                                {
                                    $update = TRUE;
                                }
                            }
                            else if ($type == 'add')
                            {
                                $insert = TRUE;
                            }
                            
                            $res_definition->free_result();
                        }
                         
                        //insert or update the language definiton in the database
                        if ($insert === TRUE || $update === TRUE)
                        {
                            if ($insert === TRUE)
                            {
                                $this->db->insert('languages_definitions',
                                    array('languages_id' => $language_id, 
                                          'content_group' => $definition['group'][0], 
                                          'definition_key' => $definition['key'][0], 
                                          'definition_value' => $definition['value'][0]));
                            }
                            else
                            {
                                $this->db->update('languages_definitions',
                                    array('content_group' => $definition['group'][0], 
                                           'definition_key' => $definition['key'][0], 
                                           'definition_value' => $definition['value'][0]), 
                                    array('definition_key' => $definition['key'][0], 
                                          'content_group' => $definition['group'][0], 
                                          'languages_id' => $language_id));
                            }
                            
                            //check transaction status
                            if ($this->db->trans_status() === FALSE)
                            {
                                $error = TRUE;
                                break;
                            }
                        }
                    }
                }
                
                //process tables
                if ($error === FALSE && $add_category_and_product_placeholders === TRUE)
                {
                    if (count($tables) > 0)
                    {
                        foreach($tables as $table)
                        {
                            $table_name = str_replace('toc_', '', $table['meta'][0]['name'][0]);
                            $key_field = $table['meta'][0]['key_field'][0];
                            $language_field = $table['meta'][0]['language_field'][0];
                            
                            $res_table = $this->db
                                ->select('*')
                                ->from($table_name)
                                ->where($language_field, $default_language_id)
                                ->get();
                            
                            if ($res_table->num_rows() > 0)
                            {
                                foreach($res_table->result_array() as $data)
                                {
                                    $data[$language_field] = $language_id;
                                    $insert = FALSE;
                                    
                                    foreach($table['definition'] as $definition)
                                    {
                                        if ($data[$key_field] == $definition['key'][0])
                                        {
                                            $insert = TRUE;
                                            foreach($definition as $key => $value)
                                            {
                                                if ($key != 'key' && array_key_exists($key, $data))
                                                {
                                                    $data[$key] = $this->db->escape_str($value[0]);
                                                }
                                            }
                                        }
                                    }
                                    
                                    if ($insert === TRUE)
                                    {
                                        $this->db->insert($table_name, $data);
                                        
                                        //check transaction status
                                        if ($this->db->trans_status() === FALSE)
                                        {
                                            $error = TRUE;
                                        }
                                    }
                                }
                            }
                            
                            $res_table->free_result();
                        }
                    }
                }
                
                //process the database tables
                if ($default_language_id !== $language_id)
                {
                    if ($error === FALSE)
                    {
                        //process category description table
                        $res_category_desc = $this->db
                            ->select('categories_id, categories_name, categories_url, categories_page_title, categories_meta_keywords, categories_meta_description')
                            ->from('categories_description')
                            ->where('language_id', $default_language_id)
                            ->get();
                      
                        $categories_descriptions = $res_category_desc->result_array();
                        $res_category_desc->free_result();
                      
                        if (count($categories_descriptions) > 0)
                        {
                            foreach($categories_descriptions as $category_description)
                            {
                                $this->db->insert('categories_description',
                                    array('categories_id' => $category_description['categories_id'], 
                                          'language_id' => $language_id, 
                                          'categories_name' => $category_description['categories_name'], 
                                          'categories_url' => $category_description['categories_url'], 
                                          'categories_page_title' => $category_description['categories_page_title'], 
                                          'categories_meta_keywords' => $category_description['categories_meta_keywords'], 
                                          'categories_meta_description' => $category_description['categories_meta_description']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process products description table
                    if ($error === FALSE)
                    {
                        $res_products_desc = $this->db
                            ->select('products_id, products_name, products_description, products_keyword, products_tags, products_url, products_friendly_url, products_page_title, products_meta_keywords, products_meta_description, products_viewed')
                            ->from('products_description')
                            ->where('language_id', $default_language_id)
                            ->get();
                        
                        $products_descriptions = $res_products_desc->result_array();
                        $res_products_desc->free_result();
                        
                        if (count($products_descriptions) > 0)
                        {
                            foreach($products_descriptions as $product_description)
                            {
                                $this->db->insert('products_description',
                                    array('products_id' => $product_description['products_id'], 
                                          'language_id' => $language_id, 
                                          'products_name' => $product_description['products_name'], 
                                          'products_description' => $product_description['products_description'], 
                                          'products_keyword' => $product_description['products_keyword'], 
                                          'products_tags' => $product_description['products_tags'], 
                                          'products_url' => $product_description['products_url'], 
                                          'products_friendly_url' => $product_description['products_friendly_url'], 
                                          'products_page_title' => $product_description['products_page_title'], 
                                          'products_meta_keywords' => $product_description['products_meta_keywords'], 
                                          'products_meta_description' => $product_description['products_meta_description'], 
                                          'products_viewed' => $product_description['products_viewed']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process customization fields description table
                    if ($error === FALSE)
                    {
                        $res_customization_fields_desc = $this->db
                            ->select('customization_fields_id, languages_id, name')
                            ->from('customization_fields_description')
                            ->where('languages_id', $default_language_id)
                            ->get();
                        
                        $customization_fields_descriptions = $res_customization_fields_desc->result_array();
                        $res_customization_fields_desc->free_result();
                        
                        if (count($customization_fields_descriptions) > 0)
                        {
                            foreach($customization_fields_descriptions as $customization_field_description)
                            {
                                $this->db->insert('customization_fields_description',
                                    array('customization_fields_id' => $customization_field_description['customization_fields_id'], 
                                          'languages_id' => $language_id, 
                                          'name' => $customization_field_description['name']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process products variants groups table
                    if ($error === FALSE)
                    {
                        $res_variants_groups = $this->db
                            ->select('products_variants_groups_id, products_variants_groups_name')
                            ->from('products_variants_groups')
                            ->where('language_id', $default_language_id)
                            ->get();
                        
                        $variants_groups = $res_variants_groups->result_array();
                        $res_variants_groups->free_result();
                        
                        if (count($variants_groups) > 0)
                        {
                            foreach($variants_groups as $variant_group)
                            {
                                $this->db->insert('products_variants_groups',
                                    array('products_variants_groups_id' => $variant_group['products_variants_groups_id'], 
                                          'language_id' => $language_id, 
                                          'products_variants_groups_name' => $variant_group['products_variants_groups_name']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process products variants values
                    if ($error === FALSE)
                    {
                        $res_variants_values = $this->db
                            ->select('products_variants_values_id, products_variants_values_name')
                            ->from('products_variants_values')
                            ->where('language_id', $default_language_id)
                            ->get();
                        
                        $variants_values = $res_variants_values->result_array();
                        $res_variants_values->free_result();
                        
                        if (count($variants_values) > 0)
                        {
                            foreach($variants_values as $variant_value)
                            {
                                $this->db->insert('products_variants_values', 
                                    array('products_variants_values_id' => $variant_value['products_variants_values_id'], 
                                          'language_id' => $language_id, 
                                          'products_variants_values_name' => $variant_value['products_variants_values_name']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process manufacturers info table
                    if ($error === FALSE)
                    {
                        $res_manufacturers_info = $this->db
                            ->select('manufacturers_id, manufacturers_url, manufacturers_friendly_url')
                            ->from('manufacturers_info')
                            ->where('languages_id', $default_language_id)
                            ->get();
                        
                        $manufacturers_infos = $res_manufacturers_info->result_array();
                        $res_manufacturers_info->free_result();
                        
                        if (count($manufacturers_infos) > 0)
                        {
                            foreach($manufacturers_infos as $manufacturer_info)
                            {
                                $this->db->insert('manufacturers_info', 
                                    array('manufacturers_id' => $manufacturer_info['manufacturers_id'], 
                                          'languages_id' => $language_id, 
                                          'manufacturers_url' => $manufacturer_info['manufacturers_url'], 
                                          'manufacturers_friendly_url' => $manufacturer_info['manufacturers_friendly_url']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process slide images table
                    if ($error === FALSE)
                    {
                        $res_slide_images = $this->db
                            ->select('image_id, description, image, image_url, sort_order, status')
                            ->from('slide_images')
                            ->where('language_id', $default_language_id)
                            ->get();
                        
                        $slide_images = $res_slide_images->result_array();
                        $res_slide_images->free_result();
                        
                        if (count($slide_images) > 0)
                        {
                            foreach($slide_images as $image)
                            {
                                $this->db->insert('slide_images', 
                                    array('image_id' => $image['image_id'], 
                                          'language_id' => $language_id, 
                                          'description' => $image['description'], 
                                          'image' => $image['image'], 
                                          'image_url' => $image['image_url'], 
                                          'sort_order' => $image['sort_order'], 
                                          'status' => $image['status']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process products attributes values table
                    if ($error === FALSE)
                    {
                        $res_attributes_values = $this->db
                            ->select('products_attributes_values_id, products_attributes_groups_id, name, module, value, status, sort_order')
                            ->from('products_attributes_values')
                            ->where('language_id', $default_language_id)
                            ->get();
                        
                        $attributes_values = $res_attributes_values->result_array();
                        $res_attributes_values->free_result();
                        
                        if (count($attributes_values) > 0)
                        {
                            foreach($attributes_values as $attribute_value)
                            {
                                $this->db->insert('products_attributes_values', 
                                    array('products_attributes_values_id' => $attribute_value['products_attributes_values_id'], 
                                          'products_attributes_groups_id' => $attribute_value['products_attributes_groups_id'], 
                                          'language_id' => $language_id, 
                                          'name' => $attribute_value['name'], 
                                          'module' => $attribute_value['module'], 
                                          'value' => $attribute_value['value'], 
                                          'status' => $attribute_value['status'], 
                                          'sort_order' => $attribute_value['sort_order']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process products attributes table
                    if ($error === FALSE)
                    {
                        $res_attributes = $this->db
                            ->select('products_id, products_attributes_values_id, value')
                            ->from('products_attributes')
                            ->where('language_id', $default_language_id)
                            ->get();
                        
                        $attributes = $res_attributes->result_array();
                        $res_attributes->free_result();
                        
                        if (count($attributes) > 0)
                        {
                            foreach($attributes as $attribute)
                            {
                                $this->db->insert('products_attributes',
                                    array('products_id' => $attribute['products_id'], 
                                          'products_attributes_values_id' => $attribute['products_attributes_values_id'], 
                                          'value' => $attribute['value'], 
                                          'language_id' => $language_id));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process faqs description table
                    if ($error === FALSE)
                    {
                        $res_faqs_desc = $this->db
                            ->select('faqs_id, faqs_question, faqs_url, faqs_answer')
                            ->from('faqs_description')
                            ->where('language_id', $default_language_id)
                            ->get();
                        
                        $faqs_desriptions = $res_faqs_desc->result_array();
                        $res_faqs_desc->free_result();
                        
                        if (count($faqs_desriptions) > 0)
                        {
                            foreach($faqs_desriptions as $fag_description)
                            {
                                $this->db->insert('faqs_description', 
                                    array('faqs_id' => $fag_description['faqs_id'], 
                                          'language_id' => $language_id, 
                                          'faqs_question' => $fag_description['faqs_question'], 
                                          'faqs_answer' => $fag_description['faqs_answer'], 
                                          'faqs_url' => $fag_description['faqs_url']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process coupons description
                    if ($error === FALSE)
                    {
                        $res_coupons_desc = $this->db
                            ->select('coupons_id, coupons_name, coupons_description')
                            ->from('coupons_description')
                            ->where('language_id', $default_language_id)
                            ->get();
                        
                        $coupons_descriptions = $res_coupons_desc->result_array();
                        $res_coupons_desc->free_result();
                        
                        if (count($coupons_descriptions) > 0)
                        {
                            foreach($coupons_descriptions as $coupon_description)
                            {
                                $this->db->insert('coupons_description', 
                                    array('coupons_id' => $coupon_description['coupons_id'], 
                                          'language_id' => $language_id, 
                                          'coupons_name' => $coupon_description['coupons_name'], 
                                          'coupons_description' => $coupon_description['coupons_description']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process articles description table
                    if ($error === FALSE)
                    {
                        $res_articles_desc = $this->db
                            ->select('articles_id, articles_name, articles_description, articles_url, articles_page_title, articles_meta_keywords, articles_meta_description')
                            ->from('articles_description')
                            ->where('language_id', $default_language_id)
                            ->get();
                        
                        $articles_descriptions = $res_articles_desc->result_array();
                        $res_articles_desc->free_result();
                        
                        if (count($articles_descriptions) > 0)
                        {
                            foreach($articles_descriptions as $article_description)
                            {
                                //check the articles description table for the processed language
                                $res_check_articles_desc = $this->db
                                    ->select('*')
                                    ->from('articles_description')
                                    ->where(array('articles_id' => $article_description['articles_id'], 'language_id' => $language_id))
                                    ->get();
                                
                                if ($res_check_articles_desc->num_rows() === 0)
                                {
                                    $this->db->insert('articles_description', 
                                        array('articles_id' => $article_description['articles_id'], 
                                              'language_id' => $language_id, 
                                              'articles_name' => $article_description['articles_name'], 
                                              'articles_description' => $article_description['articles_description'], 
                                              'articles_url' => $article_description['articles_url'], 
                                              'articles_page_title' => $article_description['articles_page_title'], 
                                              'articles_meta_keywords' => $article_description['articles_meta_keywords'], 
                                              'articles_meta_description' => $article_description['articles_meta_description']));
                                    
                                    //check transaction status
                                    if ($this->db->trans_status() === FALSE)
                                    {
                                        $error = TRUE;
                                        break;
                                    }
                                }
                                
                                $res_check_articles_desc->free_result();
                            }
                        }
                    }
                    
                    //process the articles categories description
                    if ($error === FALSE)
                    {
                        $res_article_categories_desc = $this->db
                            ->select('articles_categories_id, articles_categories_name, articles_categories_url, articles_categories_page_title, articles_categories_meta_keywords, articles_categories_meta_description')
                            ->from('articles_categories_description')
                            ->where('language_id', $default_language_id)
                            ->get();
                        
                        $article_categories_descriptions = $res_article_categories_desc->result_array();
                        $res_article_categories_desc->free_result();
                        
                        if (count($article_categories_descriptions) > 0)
                        {
                            foreach($article_categories_descriptions as $article_category_description)
                            {
                                $this->db->insert('articles_categories_description', 
                                    array('articles_categories_id' => $article_category_description['articles_categories_id'], 
                                          'language_id' => $language_id, 
                                          'articles_categories_name' => $article_category_description['articles_categories_name'], 
                                          'articles_categories_url' => $article_category_description['articles_categories_url'], 
                                          'articles_categories_page_title' => $article_category_description['articles_categories_page_title'], 
                                          'articles_categories_meta_keywords' => $article_category_description['articles_categories_meta_keywords'], 
                                          'articles_categories_meta_description' => $article_category_description['articles_categories_meta_description']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process the customers groups description table
                    if ($error === FALSE)
                    {
                        $res_customers_groups_desc = $this->db
                            ->select('customers_groups_id, customers_groups_name')
                            ->from('customers_groups_description')
                            ->where('language_id', $default_language_id)
                            ->get();
                        
                        $customers_groups_descriptions = $res_customers_groups_desc->result_array();
                        $res_customers_groups_desc->free_result();
                        
                        if (count($customers_groups_descriptions) > 0)
                        {
                            foreach($customers_groups_descriptions as $customer_group_description)
                            {
                                $this->db->insert('customers_groups_description', 
                                    array('customers_groups_id' => $customer_group_description['customers_groups_id'], 
                                          'language_id' => $language_id, 
                                          'customers_groups_name' => $customer_group_description['customers_groups_name']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                     
                    //proces ratings description table
                    if ($error === FALSE)
                    {
                        $res_ratings_desc = $this->db
                            ->select('ratings_id, ratings_text')
                            ->from('ratings_description')
                            ->where('languages_id', $default_language_id)
                            ->get();
                        
                        $ratings_descriptions = $res_ratings_desc->result_array();
                        $res_ratings_desc->free_result();
                        
                        if (count($ratings_descriptions) > 0)
                        {
                            foreach($ratings_descriptions as $rating_description)
                            {
                                $this->db->insert('ratings_description',
                                    array('ratings_id' => $rating_description['ratings_id'], 
                                          'languages_id' => $language_id, 
                                          'ratings_text' => $rating_description['ratings_text']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process polls description table
                    if ($error === FALSE)
                    {
                        $res_polls_desc = $this->db
                            ->select('polls_id, polls_title')
                            ->from('polls_description')
                            ->where('languages_id', $default_language_id)
                            ->get();
                        
                        $polls_descriptions = $res_polls_desc->result_array();
                        $res_polls_desc->free_result();
                        
                        if (count($polls_descriptions) > 0)
                        {
                            foreach($polls_descriptions as $poll_description)
                            {
                                $this->db->insert('polls_description',
                                    array('polls_id' => $poll_description['polls_id'], 
                                          'languages_id' => $language_id, 
                                          'polls_title' => $poll_description['polls_title']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process polls answers description
                    if ($error === FALSE)
                    {
                        $res_answers_desc = $this->db
                            ->select('polls_answers_id, answers_title')
                            ->from('polls_answers_description')
                            ->where('languages_id', $default_language_id)
                            ->get();
                        
                        $answers_descriptions = $res_answers_desc->result_array();
                        $res_answers_desc->free_result();
                        
                        if (count($answers_descriptions) > 0)
                        {
                            foreach($answers_descriptions as $answer_description)
                            {
                                $this->db->insert('polls_answers_description',
                                    array('polls_answers_id' => $answer_description['polls_answers_id'], 
                                          'languages_id' => $language_id, 
                                          'answers_title' => $answer_description['answers_title']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                    
                    //process products attachments description table
                    if ($error === FALSE)
                    {
                        $res_products_attachments_desc = $this->db
                            ->select('attachments_id, attachments_name, attachments_description')
                            ->from('products_attachments_description')
                            ->where('languages_id', $default_language_id)
                            ->get();
                        
                        $products_attachments_descriptions = $res_products_attachments_desc->result_array();
                        $res_products_attachments_desc->free_result();
                        
                        if (count($products_attachments_descriptions) > 0)
                        {
                            foreach($products_attachments_descriptions as $product_attachment_description)
                            {
                                $this->db->insert('products_attachments_description',
                                    array('attachments_id' => $product_attachment_description['attachments_id'], 
                                          'languages_id' => $language_id, 
                                          'attachments_name' => $product_attachment_description['attachments_name'], 
                                          'attachments_description' => $product_attachment_description['attachments_description']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                     
                    //process departments description
                    if ($error === FALSE)
                    {
                        $res_departments_desc = $this->db
                            ->select('departments_id, departments_title, departments_description')
                            ->from('departments_description')
                            ->where('languages_id', $default_language_id)
                            ->get();
                        
                        $departments_descriptions = $res_departments_desc->result_array();
                        $res_departments_desc->free_result();
                        
                        if (count($departments_descriptions) > 0)
                        {
                            foreach($departments_descriptions as $department_description)
                            {
                                $this->db->insert('departments_description',
                                    array('departments_id' => $department_description['departments_id'], 
                                          'languages_id' => $language_id, 
                                          'departments_title' => $department_description['departments_title'], 
                                          'departments_description' => $department_description['departments_description']));
                                
                                //check transaction status
                                if ($this->db->trans_status() === FALSE)
                                {
                                    $error = TRUE;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if ($error === FALSE)
        {
            //commit
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        //rollback
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Update the language
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function remove($id)
    {
        $error = FALSE;
        
        //whether deleting the default language
        $result = $this->db
            ->select('code')
            ->from('languages')
            ->where('languages_id', $id)
            ->get();
        
        $check = $result->row_array();
        
        if ($check['code'] == DEFAULT_LANGUAGE)
        {
          $error = TRUE;
        }
        
        $result->free_result();
        
        //start transaction
        $this->db->trans_begin();
        
        //process database tables
        if ($error === FALSE)
        {
            $tables = array(
                          array('table' => 'categories_description', 'key' => 'language_id'),
                          array('table' => 'customization_fields_description', 'key' => 'languages_id'),
                          array('table' => 'products_description', 'key' => 'language_id'),
                          array('table' => 'products_variants_groups', 'key' => 'language_id'),
                          array('table' => 'products_variants_values', 'key' => 'language_id'),
                          array('table' => 'manufacturers_info', 'key' => 'languages_id'),
                          array('table' => 'orders_status', 'key' => 'language_id'),
                          array('table' => 'orders_returns_status', 'key' => 'languages_id'),
                          array('table' => 'orders_transactions_status', 'key' => 'language_id'),
                          array('table' => 'products_images_groups', 'key' => 'language_id'),
                          array('table' => 'quantity_unit_classes', 'key' => 'language_id'),
                          array('table' => 'weight_classes', 'key' => 'language_id'),
                          array('table' => 'articles_description', 'key' => 'language_id'),
                          array('table' => 'articles_categories_description', 'key' => 'language_id'),
                          array('table' => 'coupons_description', 'key' => 'language_id'),
                          array('table' => 'customers_groups_description', 'key' => 'language_id'),
                          array('table' => 'email_templates_description', 'key' => 'language_id'),
                          array('table' => 'faqs_description', 'key' => 'language_id'),
                          array('table' => 'products_attributes_values', 'key' => 'language_id'),
                          array('table' => 'products_attributes', 'key' => 'language_id'),
                          array('table' => 'slide_images', 'key' => 'language_id'),
                          array('table' => 'languages', 'key' => 'languages_id'),
                          array('table' => 'languages_definitions', 'key' => 'languages_id'),
                          array('table' => 'ratings_description', 'key' => 'languages_id'),
                          array('table' => 'polls_description', 'key' => 'languages_id'),
                          array('table' => 'polls_answers_description', 'key' => 'languages_id'),
                          array('table' => 'products_attachments_description', 'key' => 'languages_id'),
                          array('table' => 'departments_description', 'key' => 'languages_id')
                      );
                      
            //remove the language and language definitions in each dtabase table
            $error = $this->remove_tables_definitions($id, $tables);
                              
        }
        
        if ($error === FALSE)
        {
            //commit
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        //rollback
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
     // ------------------------------------------------------------------------
    
    /**
     * Get the languages codes
     *
     * @access public
     * @param $languages_ids
     * @return mixed
     */
    
    public function check_codes($languages_ids)
    {
        $result = $this->db
            ->select('code')
            ->from('languages')
            ->where_in('languages_id', $languages_ids)
            ->get();
            
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get content groups
     *
     * @access public
     * @param $languages_id
     * @return mixed
     */
    public function get_content_groups($languages_id)
    {
        $result = $this->db
            ->select('content_group')
            ->from('languages_definitions')
            ->where('languages_id', $languages_id)
            ->group_by('content_group')
            ->get();
          
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Export language definitions
     *
     * @access public
     * @param $id
     * @param $groups
     * @param $include_language_data
     * @return array
     */
    public function export($id, $groups, $include_language_data = TRUE)
    {
        $this->load->library('currencies');
        $this->load->library('xml');
        
        $language = $this->get_data($id);
        
        $export_data = array();
        
        //include the data of the language in the xml
        if ($include_language_data === TRUE) 
        {
            $export_data['data'] = array('title-CDATA' => $language['name'],
                                          'code-CDATA' => $language['code'],
                                          'locale-CDATA' => $language['locale'],
                                          'character_set-CDATA' => $language['charset'],
                                          'text_direction-CDATA' => $language['text_direction'],
                                          'date_format_short-CDATA' => $language['date_format_short'],
                                          'date_format_long-CDATA' => $language['date_format_long'],
                                          'time_format-CDATA' => $language['time_format'],
                                          'default_currency-CDATA' => $this->currencies->get_code($language['currencies_id']),
                                          'numerical_decimal_separator-CDATA' => $language['numeric_separator_decimal'],
                                          'numerical_thousands_separator-CDATA' => $language['numeric_separator_thousands']);
            
            if ($language['parent_id'] > 0) 
            {
                $export_data['data']['parent_language_code'] = $this->get_code($language['parent_id']);
            }
            
            //get the languages definitions for each content group
            $result = $this->db
                ->select('content_group, definition_key, definition_value')
                ->from('languages_definitions')
                ->where('languages_id', $id)
                ->where_in('content_group', $groups)
                ->order_by('content_group, definition_key')
                ->get();
            
            $export_data['definitions'] = array();
            if ($result->num_rows() > 0)
            {
                foreach($result->result_array() as $definition)
                {
                    $export_data['definitions'][] = array('key' => $definition['definition_key'],
                                                          'value' => $definition['definition_value'],
                                                          'group' => $definition['content_group']);
                }
            }
            $result->free_result();
            
            //generate the xml
            $xml = $this->xml->get_xml('language', $export_data);
            
            return array('xml' => $xml, 'code' => $language['code']);
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the language id with the language code
     *
     * @access public
     * @param $code
     * @return mixed
     */
    public function get_id($code)
    {
        $result = $this->db
            ->select('languages_id')
            ->from('languages')
            ->where('code', $code)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            $langugage = $result->row_array();
            
            return $langugage['languages_id'];
        }
        
        return NULL;
    }
    
     // ------------------------------------------------------------------------
    
    /**
     * Get the language code with the language id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_code($id)
    {
        $result = $this->db
            ->select('code')
            ->from('languages')
            ->where('languages_id', $id)
            ->get();
            
        if ($result->num_rows() > 0)
        {
            $langugage = $result->row_array();
            
            return $language['code'];
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get language definitions
     *
     * @access public
     * @param $languages_id
     * @param $group
     * @param $search
     * @return mixed
     */
    public function get_definitions($languages_id, $group, $search = NULL)
    {
        $this->db
            ->select('*')
            ->from('languages_definitions')
            ->where(array('languages_id' => $languages_id, 'content_group' => $group));
        
        if ($search !== NULL)
        {
              $this->db
                  ->like('definition_key',$search)
                  ->or_like('definition_value', $search);
        }
        
        $result = $this->db->order_by('definition_key')->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save translation definition
     *
     * @access public
     * @param $id
     * @param $group
     * @param $key
     * @param $value
     * @return boolean
     */
    public function save_definition($id, $group, $key, $value)
    {
        $this->db->update('languages_definitions', 
            array('definition_value' => $value), 
            array('definition_key' => $key, 
                  'languages_id' => $id, 
                  'content_group' => $group));
                                                   
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete translation definition
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete_definition($id)
    {
        $this->db->delete('languages_definitions', array('id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Add definition
     *
     * @access public
     * @param $data
     * @return boolean
     */
    public function add_definition($data)
    {
        $this->db->insert('languages_definitions', $data);
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the data of language
     *
     * @access public
     * @param $id
     * @param $key
     * @return mixed
     */
    public function get_data($id, $key = null)
    {
        $result = $this->db
            ->select('*')
            ->from('languages')
            ->where('languages_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();
            
            if (!empty($key))
            {
                return $data[$key];
            }
            
            return $data;
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the total number of languages definitions
     *
     * @access public
     * @param $languages_id
     * @return int
     */
    public function get_total_definitions($languages_id)
    {
        return $this->db->where('languages_id', $languages_id)->from('languages_definitions')->count_all_results();
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the total number of languages
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->count_all('languages');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Remove language definitions in some database tables
     *
     * @access private
     * @param $language_id
     * @param $tables
     * @return boolean
     */
    private function remove_tables_definitions($language_id, $tables = array())
    {
        $error = FALSE;
        
        if (count($tables) > 0)
        {
            foreach($tables as $table)
            {
                $this->db->delete($table['table'], array($table['key']=> $language_id));
        
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
                     break;
                }
            }
        }
        
        return $error;
    }
}

/* End of file lang_model.php */
/* Location: ./system/models/lang_model.php */