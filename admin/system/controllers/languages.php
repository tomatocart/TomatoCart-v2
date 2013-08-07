<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Languages Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Languages extends TOC_Controller
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
        
        $this->load->model('lang_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List languages
     *
     * @access public
     * @return string
     */
    public function list_languages()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $languages = $this->lang_model->get_languages($start, $limit);
        
        $records = array();
        if ($languages !== NULL)
        {
            foreach($languages as $language)
            {
                $total_definitions = $this->lang_model->get_total_definitions($language['languages_id']);
                
                $languages_name = $language['name'];
                
                //verify that the language is the default language
                if ($language['code'] == DEFAULT_LANGUAGE)
                {
                    $languages_name .= ' (' . lang('default_entry') . ')';
                }
                
                $records[] = array(
                    'languages_id' => $language['languages_id'],
                    'code' => $language['code'],
                    'total_definitions' => $total_definitions,
                    'languages_name' => $languages_name,
                    'languages_flag' => show_image($language['code'])
                );
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->lang_model->get_total(), EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the currencies
     *
     * @access public
     * @return string
     */
    public function get_currencies()
    {
        $this->load->library('currencies');
        
        $records = array();
        foreach($this->currencies->get_data() as $currency)
        {
            $records[] = array('currencies_id' => $currency['id'],
                               'text' => $currency['title']);
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get the parent language
     *
     * @access public
     * @return string
     */
    public function get_parent_language()
    {
        $records =  array(array('parent_id' => '0', 'text' => lang('none')));
    
        foreach(lang_get_all() as $l)
        {
            if ($l['id'] != $this->input->post('languages_id')) 
            {
                $records[] = array('parent_id' => $l['id'], 'text' => $l['name'] . ' (' . $l['code'] . ')');
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Load the language
     *
     * @access public
     * @return string
     */
    public function load_language()
    {
        $data = $this->lang_model->get_data($this->input->post('languages_id'));
        $data['default'] = ($data['code'] == DEFAULT_LANGUAGE) ? TRUE : FALSE;
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save the language
     *
     * @access public
     * @return string
     */
    public function save_language()
    {
        $data = array('name' => $this->input->post('name'),
                      'code' => $this->input->post('code'),
                      'locale' => $this->input->post('locale'),
                      'charset' => $this->input->post('charset'),
                      'date_format_short' => $this->input->post('date_format_short'),
                      'date_format_long' => $this->input->post('date_format_long'),
                      'time_format' => $this->input->post('time_format'),
                      'text_direction' => $this->input->post('text_id'),
                      'currencies_id' => $this->input->post('currencies_id'),
                      'numeric_separator_decimal' => $this->input->post('numeric_separator_decimal'),
                      'numeric_separator_thousands' => $this->input->post('numeric_separator_thousands'),
                      'parent_id' => $this->input->post('parent_id'),
                      'sort_order' => $this->input->post('sort_order'));
        
        if ($this->lang_model->update($this->input->post('languages_id'), $data, $this->input->post('default') == 'on'))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Scan the language directory in ths store front to get the languages
     *
     * @access public
     * @return string
     */
    public function get_languages()
    {
        $this->load->helper('directory');
        
        $languages_map = directory_map(ROOTPATH . '/system/tomatocart/language');
        
        $records = array();
        if (count($languages_map) > 0)
        {
            foreach($languages_map as $language)
            {
                if ( ! is_array($language)) {
                    $records[] = array('id' => substr($language, 0, strrpos($language, '.')), 
                                       'text' => substr($language, 0, strrpos($language, '.')));
                }
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Import the language definitions
     *
     * @access public
     * @return string
     */
    public function import_language()
    {
        if ($this->lang_model->import($this->input->post('languages_id'), $this->input->post('import_type')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the language
     *
     * @access public
     * @return string
     */
    public function delete_language()
    {
        $error = FALSE;
        $feedback = array();
        
        //verify that the deleting language is the default language
        if ($this->input->post('code') == DEFAULT_LANGUAGE)
        {
            $error = TRUE;
            $feedback[] = lang('introduction_delete_language_invalid');
        }
        
        if ($error === FALSE)
        {
            if ($this->lang_model->remove($this->input->post('languages_id')))
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Batch delete the languages
     *
     * @access public
     * @return string
     */
    public function delete_languages()
    {
        $error = FALSE;
        $feedback = array();
        
        $batch = json_decode($this->input->post('batch'));
        
        //get the laguages codes
        $codes = $this->lang_model->check_codes($batch);
        
        //verify whether the default language is within the deleted languages
        if ($codes !== NULL)
        {
            foreach($codes as $code)
            {
                if ($code['code'] == DEFAULT_LANGUAGE)
                {
                    $error = TRUE;
                    
                    $feedback[] = lang('introduction_delete_language_invalid');
                    break;
                }
            }
        }
        
        //delete the languages
        if ($error === FALSE)
        {
            foreach($batch as $id)
            {
                if ($this->lang_model->remove($id) === FALSE)
                {
                    $error = TRUE;
                    break;
                }
            }
            
            if ($error === FALSE)
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List translation groups
     *
     * @access public
     * @return string
     */
    public function get_groups()
    {
        $groups = $this->lang_model->get_content_groups($this->input->get_post('languages_id'));
        
        $records = array();
        if ($groups !== NULL)
        {
            foreach($groups as $group)
            {
                $records[] = array('id' => $group['content_group'],
                                   'text' => $group['content_group']);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Export languages definitions
     *
     * @access public
     * @return string
     */
    public function export()
    {
        $this->load->library('xml');
        
        $groups = explode(',', $this->input->get_post('export'));
        $languages_id = $this->input->get_post('languages_id');
        $include_language_data = FALSE;
        
        if ($this->input->get_post('include_data') == TRUE)
        {
            $include_language_data = TRUE;
        }
        
        $export_data = $this->lang_model->export($languages_id, $groups, $include_language_data);
        
        $this->xml->put_header($export_data['code'] . '-' . $this->input->get_post('export'), strlen($export_data['xml']));
            
        $this->output->set_output($export_data['xml']);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List translations for one content group
     *
     * @access public
     * @return string
     */
    public function list_translations()
    {
        $languages_id = $this->input->get_post('languages_id');
        $group = $this->input->get_post('group') ? $this->input->get_post('group') : 'general';
        
        $definitions = $this->lang_model->get_definitions($languages_id, $group, $this->input->get_post('search'));
        
        $records = array();
        if ($definitions !== NULL)
        {
            foreach($definitions as $definition)
            {
                $records[] = array('languages_definitions_id' => $definition['id'], 
                                   'languages_id' => $languages_id, 
                                   'definition_key' => $definition['definition_key'], 
                                   'definition_value' => $definition['definition_value'], 
                                   'content_group' => $group);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * List translation groups
     *
     * @access public
     * @return string
     */
    public function list_translation_groups()
    {
        $groups = $this->lang_model->get_content_groups($this->input->get_post('languages_id'));
        
        $records = array();
        if ($groups !== NULL)
        {
            foreach($groups as $group)
            {
                $records[] = array('id' => $group['content_group'], 
                                   'text' => $group['content_group'],
                                   'leaf' => TRUE);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Update one translation
     *
     * @access public
     * @return string
     */
    public function update_translation()
    {
        $value = rtrim($this->input->post('definition_value'));
        
        if ($this->lang_model->save_definition($this->input->post('languages_id'), $this->input->post('group'), $this->input->post('definition_key'), $value))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete the translation
     *
     * @access public
     * @return string
     */
    public function delete_translation()
    {
        if ($this->lang_model->delete_definition($this->input->post('languages_definitions_id')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Add translation definition
     *
     * @access public
     * @return string
     */
    public function add_translation()
    {
        $data = array('languages_id' => $this->input->post('languages_id'),
                      'content_group' => $this->input->post('definition_group'),
                      'definition_key' => $this->input->post('definition_key'),
                      'definition_value' => rtrim($this->input->post('definition_value')));
        
        if ($this->lang_model->add_definition($data))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Add translation definition
     *
     * @access public
     * @return string
     */
    public function upload_language()
    {
        $this->load->library('currencies');
        $this->load->helper('directory');
        
        $error = FALSE;
        $feedback = array();
        
        //the cache directory in the store front 
        $front_cache_path =  @realpath(ROOTPATH . 'local/cache');
        
        //create the writable language directory in the cache directory
        if ( ! @is_dir($front_cache_path . '/language'))
        {
            if ( ! @mkdir($front_cache_path . '/language', 0777))
            {
                $error = TRUE;
            }
        }
        
        //create a temporary directory to store the uploaded zip file
        $temp_langauge_path = $front_cache_path . '/language/' . time();
        
        if ($error === FALSE && @mkdir($temp_langauge_path))
        {
            //upload the zip file and extract it, put the language files into temporary directory
            $config = array('upload_path' => $temp_langauge_path, 
                            'allowed_types' => 'zip');
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('file'))
            {
                $this->load->library('pclzip', $this->upload->data('full_path'));
                
                if ($this->pclzip->extract(PCLZIP_OPT_PATH, $temp_langauge_path) == 0)
                {
                    $error = TRUE;
                    $feedback[] = lang('ms_error_wrong_zip_file_format');
                }
            }
            else
            {
                //upload failed
                $error = TRUE;
                $feedback[] = $this->upload->display_errors();
            }
        }
        else
        {
            //create the temporary language directory failed
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_creating_directory_failed'), $front_cache_path);
        }
        
        if ($error === FALSE)
        {
            //scan the temporary directory to get the language files
            $this->load->library('directory_listing');
            $this->directory_listing->set_directory($temp_langauge_path);
            $this->directory_listing->set_include_directories(TRUE);
            $this->directory_listing->set_include_files(FALSE);
            $files = $this->directory_listing->get_files();
            
            $code = NULL;
            foreach($files as $file)
            {
                if (@is_dir($temp_langauge_path . '/' . $file['name']) && is_dir($temp_langauge_path . '/' . $file['name'] . '/admin'))
                {
                    $code = $file['name'];
                    
                    break;
                }
            }
            
            //copy the uploaded languages files into install, store front and admin panel
            if ($code !== NULL)
            {
                dircopy($temp_langauge_path . '/' . $code . "/language", @realpath(ROOTPATH . 'system/tomatocart/language'));
                dircopy($temp_langauge_path . '/' . $code . "/admin/language", APPPATH . 'language');
                dircopy($temp_langauge_path . '/' . $code . "/install/language", @realpath(ROOTPATH . 'install/applications/language'));
                
                @unlink($temp_langauge_path);
            }
            else
            {
                $error = TRUE;
                $feedback[] = lang('ms_error_wrong_language_package');
            }
        }
        
        if ($error === FALSE)
        {
            //import the language definitons into database
            if ($this->lang_model->import($code, 'replace'))
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => $osC_Language->get('ms_error_action_not_performed') . "<br />" . implode("<br />", $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
}

/* End of file languages.php */
/* Location: ./system/controllers/languages.php */