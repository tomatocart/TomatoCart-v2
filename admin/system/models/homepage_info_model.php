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
 * Homepage Info Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Homepage_Info_Model extends CI_Model
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
     * Save the data of the home page
     *
     * @access public
     * @param $data
     * @return boolean
     */
    public function save_data($data)
    {
        $error = FALSE;
        
        //start transaction
        $this->db->trans_begin();
        
        //process page title
        foreach($data['page_title'] as $key => $value)
        {
            $this->db->update('configuration', array('configuration_value' => $value), array('configuration_key' => 'HOME_PAGE_TITLE_' . $key));
            
            //check transaction status
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
                break;
            }
        }
        
        //process meta keywords
        if ($error === FALSE)
        {
            foreach($data['keywords'] as $key => $value)
            {
                $this->db->update('configuration', array('configuration_value' => $value), array('configuration_key' => 'HOME_META_KEYWORD_' . $key));
                
                //check transaction status
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
                    break;
                }
            }
        }
        
        //process meta description
        if ($error === FALSE)
        {
            foreach($data['descriptions'] as $key => $value)
            {
                $this->db->update('configuration', array('configuration_value' => $value), array('configuration_key' => 'HOME_META_DESCRIPTION_' . $key));
                
                //check transaction status
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
                    break;
                }
            }
        }
        
        //process index text
        if ($error === FALSE)
        {
            foreach($data['index_text'] as $languages_id => $value)
            {
                $this->db->update('languages_definitions', array('definition_value' => $value), array('definition_key' => 'index_text', 'languages_id' => $languages_id));
                
                //check transaction status
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
                    break;
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
     * Get the data of home page
     *
     * @access public
     * @return array
     */
    public function get_data()
    {
        $data = array();
        
        //process homepage info for each language
        foreach(lang_get_all() as $l)
        {
            $name = $l['name'];
            $code = strtoupper($l['code']);
            
            //check page title for language
            if ( ! defined('HOME_PAGE_TITLE_' . $code))
            {
                $this->db->insert('configuration', array('configuration_title' => 'Homepage Page Title For ' . $name, 
                                                         'configuration_key' => 'HOME_PAGE_TITLE_' . $code, 
                                                         'configuration_value' => '', 
                                                         'configuration_description' => 'the page title for the front page', 
                                                         'configuration_group_id' => '6', 
                                                         'sort_order' => '0', 
                                                         'date_added' => date('Y-m-d H:i:s')));
                
                define('HOME_PAGE_TITLE_' . $code, '');
            }
            
            //check meta keywords for language
            if ( ! defined('HOME_META_KEYWORD_' . $code))
            {
                $this->db->insert('configuration', array('configuration_title' => 'Homepage Meta Keywords For ' . $name, 
                                                         'configuration_key' => 'HOME_META_KEYWORD_' . $code, 
                                                         'configuration_value' => '', 
                                                         'configuration_description' => 'the meta keywords for the front page', 
                                                         'configuration_group_id' => '6', 
                                                         'sort_order' => '0', 
                                                         'date_added' => date('Y-m-d H:i:s')));
                
                define('HOME_META_KEYWORD_' . $code, '');
            }
            
            //check meta description for language
            if ( ! defined('HOME_META_DESCRIPTION_' . $code))
            {
                $this->db->insert('configuration', array('configuration_title' => 'Homepage Meta Description For ' . $name, 
                                                         'configuration_key' => 'HOME_META_DESCRIPTION_' . $code, 
                                                         'configuration_value' => '', 
                                                         'configuration_description' => 'the meta description for the front page', 
                                                         'configuration_group_id' => '6', 
                                                         'sort_order' => '0', 
                                                         'date_added' => date('Y-m-d H:i:s')));
                
                define('HOME_META_DESCRIPTION_' . $code, '');
            }
            
            //process the index text
            $result = $this->db
                ->select('*')
                ->from('languages_definitions')
                ->where(array('definition_key' => 'index_text', 'languages_id' => $l['id']))
                ->get();
            
            if ($result->num_rows() > 0)
            {
                foreach($result->result_array() as $homepage)
                {
                    $data['index_text[' . $l['id'] . ']'] = $homepage['definition_value'];
                }
            }
            
            $data['HOME_PAGE_TITLE[' . $code . ']'] = constant('HOME_PAGE_TITLE_' . $code);
            $data['HOME_META_KEYWORD[' . $code . ']'] = constant('HOME_META_KEYWORD_' . $code);
            $data['HOME_META_DESCRIPTION[' . $code . ']'] = constant('HOME_META_DESCRIPTION_' . $code);
        }
        
        return $data;
    }
}

/* End of file homepage_info_model.php */
/* Location: ./system/models/homepage_info_model.php */