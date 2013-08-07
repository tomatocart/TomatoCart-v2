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
 * Templates Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Templates extends TOC_Controller
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

        //load model
        $this->load->model('templates_model');

        //load helper
        $this->load->helper('template');
    }

    // --------------------------------------------------------------------

    /**
     * List all templates
     *
     * @access public
     * @return string
     */
    public function list_templates()
    {
        $this->load->helper('directory');
         
        $directories = directory_map('../templates', 1, TRUE);
         
        $templates = array();
        foreach ($directories as $directory)
        {
            $xml_file = '../templates/' . $directory . '/template.xml';
            if (file_exists($xml_file))
            {
                //load template meta xml file
                $info = simplexml_load_file($xml_file);
                $code = (string) $info->Code;

                if ($directory == $code)
                {
                    $title = $info->Title;
                    $defaultCls = 'icon-default-record';
                    $installCls = 'icon-install-record';
                    $templates_id = -1;
                     
                    //load template data from database and check whether it is installed
                    //
                    $data = $this->templates_model->get_template_data_via_code($code);
                    if ($data !== FALSE)
                    {
                        $templates_id = $data['id'];

                        if ($code == config('DEFAULT_TEMPLATE'))
                        {
                            $title = '&nbsp;(' . lang('default_entry') . ')';
                            $defaultCls = 'icon-default-record';
                        }
                        else
                        {
                            $defaultCls = 'icon-default-gray-record';
                        }
                         
                        $installCls = 'icon-uninstall-record';
                    }
                    else
                    {
                        $defaultCls = 'icon-empty-record';
                        $installCls = 'icon-install-record';
                    }
                     
                    $templates[] = array(
  						'id' => $templates_id, 
  						'code' => (string)$code, 
  						'title' => (string)$info->Title, 
  						'url' => (string)$info->URL, 
  						'compatible_version' => (string)$info->CompatibleVersion,
  						'default_cls' => $defaultCls,
  						'install_cls' => $installCls);
                }
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => sizeof($templates), EXT_JSON_READER_ROOT => $templates)));
    }

    // --------------------------------------------------------------------

    /**
     * install template
     *
     * @access public
     * @return string
     */
    public function install()
    {
        $code = $this->input->post('code');
        $info = simplexml_load_file('../templates/' . $code . '/template.xml');

        //load template meta description file and install params and layout data
        //
        if ($info !== FALSE) {
            //template info data
            //
            $data = array(
                'title' => (string) $info->Title,
                'code' => (string) $code,
                'author_name' => (string) $info->Author,
                'author_www'	=> (string) $info->URL,
                'markup_version' => 'XHTML 1.0 Transitional',
                'css_based' => '1',
                'medium' => 'Screen');

            //template params
            //
            $params = array();
            if ( isset($info->Params->Param) ) {
                foreach ($info->Params->Param as $paramEl) {
                    $attributes = $paramEl->attributes();

                    $params[(string) $attributes['name']] = (string) $attributes['default'];
                }
            }
            $data['params'] = $params;

            //web layout modules
            $modules = array();
            if ( isset($info->WebLayout->Modules->Module) )
            {
                foreach ($info->WebLayout->Modules->Module as $moduleEl)
                {
                    //Get module attributes
                    $attributes = $moduleEl->attributes();

                    //get template module meta info
                    $module = $this->get_template_module_info((string) $attributes['code']);

                    //get module configuration params
                    $params = $module['params'];

                    //if the module has param elements
                    $values = array();
                    if ( isset($moduleEl->Param) ) {
                        foreach ($moduleEl->Param as $paramEl) {
                            $attrib = $paramEl->attributes();

                            $values[(string)$attrib['name']] = (string) $attrib['value'];
                        }
                    }

                    //copy values to configuration params array
                    $params = $this->copy_param_values($params, $values);
                    $params = $this->convert_key_value_pairs($params);

                    //save to modules array
                    $modules[] = array(
                        'code' => (string) $attributes['code'],
                        'sort-order' => (string) $attributes['sort-order'],
                        'page'	=>  (string) $attributes['page'],
                        'group'	=>  (string) $attributes['group'],
                        'params' => $params);
                }
            }
            $data['web_layout']['modules'] = $modules;

            $this->templates_model->install($data);

            $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Uninstall template
     *
     * @access public
     * @return string
     */
    public function uninstall()
    {
        $code = $this->input->post('code');

        $error = false;
        $feedback = array();

        if ($code == config('DEFAULT_TEMPLATE'))
        {
            $error = true;
            $feedback[] = lang('uninstall_error_template_prohibited');
        }

        if($error === false)
        {
            if ($this->templates_model->uninstall($code))
            {
                $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Set Default Template
     *
     * @access public
     * @return string
     */
    public function set_default()
    {
        $code = $this->input->post('code');

        if ($this->templates_model->is_installed($code) === TRUE)
        {
            $this->templates_model->set_default($code);

            $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Upload Template
     *
     * @access public
     * @return string
     */
    public function upload_template()
    {
        $errors = array();

        //check whether the cache folder is writable
        $path = $this->config->item('cache_path');
        $cache_path = ($path === '') ? APPPATH . 'cache/' : $path;

        if ( ! is_dir($cache_path) OR ! is_really_writable($cache_path))
        {
            $errors[] = 'Unable to write cache file: ' . $cache_path;
        }


        $this->output->set_header("Content-Type: text/html")->set_output(json_encode(array('success' => true)));
    }

    // --------------------------------------------------------------------

    /**
     * Retrieve template params
     *
     * @access public
     * @return string
     */
    public function get_template_params()
    {
        $templates_id = $this->input->post('templates_id');
        $code = $this->input->post('code');
        $xml_file = '../templates/' . $code . '/template.xml';

        if (file_exists($xml_file))
        {
            $info = simplexml_load_file($xml_file);
            $data = $this->templates_model->get_template_data($templates_id);
            $template_params = $data['params'];

            //template params
            //
            $params = array();
            if ( isset($info->Params->Param) )
            {
                foreach ($info->Params->Param as $paramEl)
                {
                    $attributes = $paramEl->attributes();
                    $name = (string)$attributes['name'];

                    $param = array(
                        'name' => 'param[' . $name . ']',
                        'title' => (string)$attributes['title'],
                        'type' => (string) $attributes['type'],
                        'description' => (string) $attributes['description'],
                        'value' => (string) $attributes['default']);

                    //set value
                    if ( isset($template_params[$name]) )
                    {
                        $param['value'] = $template_params[$name];
                    }

                    if (((string)$attributes['type']) == 'combobox')
                    {
                        $options = $paramEl->Option;

                        $values = array();
                        foreach ($options as $option)
                        {
                            $option_attr = $option->attributes();

                            $values[] = array('id' => (string)$option_attr['value'], 'text' => (string)$option);
                        }

                        $param['values'] = $values;
                    }

                    $params[] = $param;
                }
            }
        }

        $this->output->set_output(json_encode($params));
    }

    // --------------------------------------------------------------------

    /**
     * Save template params
     *
     * @access public
     * @return string
     */
    public function save_template_params()
    {
        $tempates_id = $this->input->post('templates_id');
        $params = $this->input->post('param');

        $data = array();
        if (!empty($params) && is_array($params))
        {
            foreach ($params as $key => $value)
            {
                $data[$key] = $value;
            }

            if ($this->templates_model->save_template_params($tempates_id, $data))
            {
                $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Get modules tree
     *
     * @access public
     * @return string
     */
    public function get_modules_tree()
    {
        //get template modules
        $modules = get_template_modules_meta_data();

        foreach ($modules as $key => $module) {
            $modules[$key]['id'] = $module['code'];
            $modules[$key]['leaf'] = true;
        }

        return $this->output->set_output(json_encode($modules));
    }

    // --------------------------------------------------------------------

    /**
     * Get layout data
     *
     * @access public
     * @return string
     */
    public function get_layout_data()
    {
        $templates_code = $this->input->post('code');

        $data = array('web' => get_layout_modules($templates_code));

        $this->output->set_output(json_encode(array('success' => true, 'layout' => $data)));
    }

    // --------------------------------------------------------------------

    /**
     * Delete template Module
     *
     * @access public
     * @return string
     */
    public function delete_template_module()
    {
        $module_id = $this->input->post('mid');

        if ($this->templates_model->delete_template_module($module_id))
        {
            $response = array('success' => true);
        }
        else
        {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Add a template module to certain group
     *
     * @access public
     * @return string
     */
    public function add_template_module()
    {
        //load the module parent class
        load_front_library('module.php');

        //load language resources
        $this->lang->db_load('modules-boxes');

        $code = $this->input->post('code');
        $group = $this->input->post('group');
        $templates_id = $this->input->post('templates_id');

        //get module configuration params and insert default value into database
        $path_array = array(
            store_front_path() . 'local/modules/',
            store_front_path() . 'system/tomatocart/modules/');

        $result = FALSE;
        $data = array();

        foreach($path_array as $path)
        {
            $directories = directory_map($path, 1, TRUE);

            foreach ($directories as $directory)
            {
                $file = $path . $directory . '/' . $directory . '.php';
                if (file_exists($file) && ($code == $directory))
                {
                    require_once $file;

                    $class_name = 'Mod_' . $directory;
                    
                    $class = new $class_name(array());

                    $result = $class->install($templates_id, $group);

                    if ($result !== FALSE)
                    {
                        $data = array(
                        	'id' => $result, 
                        	'templates_id' => $templates_id, 
                            'title' => $class->get_title(),
                          	'module' => $code,
                        	'status' => 0,
                        	'content_page' => '*',
                        	'content_group' => $group,
                        	'sort_order' => 0,
                        	'page_specific' => 0,
                        	'params' => $class->get_params());

                        break;
                    }
                }
            }
        }

        if ($result !== FALSE) {
            $response = array('success' => true, 'data' => $data);
        } else {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        return $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Update template module group
     *
     * @access public
     * @return string
     */
    public function update_template_module_group()
    {
        $module_id = $this->input->post('mid');
        $group = $this->input->post('group');

        if ($this->templates_model->update_template_module_group($module_id, $group)) {
            $response = array('success' => true);
        } else {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        return $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Get all pages
     *
     * @access public
     * @return string
     */
    public function get_pages()
    {
        $path_array = array('../system/tomatocart/controllers/', '../local/controllers/');

        $controllers = array(array('id' => '*', 'text' => '*'));
        foreach($path_array as $path) {
            $directories = directory_map($path, 1);

            foreach ($directories as $directory) {
                if (is_dir($path . $directory)) {
                    $controllers[] = array('id' => $directory . '/*', 'text' => $directory . '/*');

                    $files = directory_map($path . $directory, 1);
                    foreach ($files as $file) {
                        if (strpos($file, '.php') !== FALSE) {
                            $controllers[] = array('id' => $directory . '/' . substr($file, 0, -4), 'text' => $directory . '/' . substr($file, 0, -4));
                        }
                    }
                }
            }
        }

        return $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $controllers)));
    }

    // --------------------------------------------------------------------

    /**
     * Get box module parameters
     *
     * @access public
     * @return string
     */
    public function get_module_params()
    {
        $module_id = $this->input->get_post('module_id');

        $params = $this->templates_model->get_module_params($module_id);

        return $params;
    }

    // --------------------------------------------------------------------

    /**
     * Get certain template module info
     *
     * @access public
     * @param $code
     * @return string
     */
    private function get_template_module_info($code) {
        $path_array = array('../local/modules/', '../system/tomatocart/modules/');

        $module = NULL;
        foreach($path_array as $path) {
            require_once '../system/tomatocart/libraries/module.php';
            $file = $path . $code . '/' . $code . '.php';
            if (file_exists($file)) {
                require_once $file;
                
                $class_name = 'Mod_' . $code;

                $class = new $class_name(array());

                $module = array('code' => $class->get_code(), 'text' => $class->get_title(), 'params' => $class->get_params());
            }
        }

        return $module;
    }

    // --------------------------------------------------------------------

    /**
     * Save box module settings
     *
     * @access public
     * @return string
     */
    public function save_module_settings() {
        $data['id'] = $this->input->post('id');
        $data['page_specific'] = ($this->input->post('page_specific') == 'on') ? 1 : 0;
        $data['status'] = $this->input->post('status');
        $data['content_page'] = $this->input->post('pages');
        $data['sort_order'] = $this->input->post('sort_order');
        $code = $this->input->post('code');

        $module = $this->get_template_module_info($code);
        $params = $module['params'];

        $params = $this->input->post('params');
        if (($params !== FALSE) && is_array($params)) {
            foreach ($params as $name => $value) {
                foreach ($params as $index => $key) {
                    if ($name == $key['name']) {
                        $key['value'] = $value;

                        $params[$index] = $key;
                    }
                }
            }
        }

        $data['params'] = $params;

        if ($this->templates_model->update_template_module($data)) {
            $data['templates_id'] = $this->input->post('templates_id');
            $data['medium'] = $this->input->post('medium');
            $data['module'] = $code;
            $data['content_group'] = $this->input->post('content_group');

            $response = array('success' => true, 'data' => $data);
        } else {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Convert param array to key value pairs array
     *
     * @param array $params param array
     */
    private function convert_key_value_pairs($params) {
        $data = array();

        if (is_array($params)) {
            foreach ($params as $key => $param) {
                $data[$param['name']] = $param['value'];
            }
        }

        return $data;
    }
}

/* End of file templates.php */
/* Location: ./system/modules/templates/controllers/templates.php */