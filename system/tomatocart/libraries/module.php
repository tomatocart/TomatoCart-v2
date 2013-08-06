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
 * Template Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Module {

    /**
     * ci instance
     *
     * @access protected
     * @var string
     */
    protected $ci = NULL;

    /**
     * Template Module Code
     *
     * @access protected
     * @var string
     */
    protected $code = NULL;

    /**
     * Template Module Title
     *
     * @access protected
     * @var string
     */
    protected $title = NULL;

    /**
     * Template Module Title Link
     *
     * @access protected
     * @var string
     */
    protected $title_link = NULL;

    /**
     * Template Module Content
     *
     * @access protected
     * @var string
     */
    protected $content = '';

    /**
     * Template Module Author Name
     *
     * @access protected
     * @var string
     */
    protected $author_name = NULL;

    /**
     * Template Module Author Url
     *
     * @access protected
     * @var string
     */
    protected $author_url = NULL;

    /**
     * Template Module Version
     *
     * @access protected
     * @var string
     */
    protected $version = NULL;

    /**
     * Template Module Configuration Parameters
     *
     * @access protected
     * @var string
     */
    protected $config = array();

    /**
     * Template Module Parameter Definitions
     *
     * @access protected
     * @var string
     */
    protected $params = array();

    /**
     * Template Module Constructor
     *
     * @access public
     * @param string
     */
    function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        //load module model
        $this->load_model($this->code);
    }

    /**
     * Install Template Module
     *
     * @access public
     * @param $templates_id
     * @param $group
     * @return mixed
     */
    public function install($templates_id, $group)
    {
        $languages = $this->ci->lang->get_languages();
        foreach ($languages as $key => $language) 
        {
            $file = '../system/tomatocart/language/' . $key . '/modules/boxes/' . $this->code . '.xml';
            
            $this->ci->lang->import_xml($file, $language['id']);
        }

        $data = array(
            'templates_id' => $templates_id, 
            'module' => $this->code,
            'status' => 0,
            'content_page' => '*',
            'content_group' => $group,
            'sort_order' => 0,
            'page_specific' => 0,
            'params' => json_encode($this->params));

        $id = $this->ci->templates_model->insert_template_module($data);

        if (is_numeric($id)) {
            return $id;
        }

        return FALSE;
    }

    /**
     * Load Template Module Model Class
     *
     * @access public
     * @param string $model model name
     * @param string $name
     */
    public function load_model($module, $name = '')
    {
        $filename = 'system/tomatocart/modules/' . $this->code . '/model.' . $module . '.php';
        if (file_exists($filename))
        {
            include_once $filename;
            $model_class = $module . '_model';

            $this->$module = new $model_class;
        }
    }

    /**
     * Load Template Module View
     *
     * @access public
     * @param string $view view name
     * @param string $data data array passed to view
     */
    public function load_view($view, $data = array())
    {
        if ($this->ci->agent->is_pad())
        {
            $path_array = array(
              	'templates/' . $this->ci->template->get_code() . '/pad/views/modules/' . $this->code . '/' . $view,
              	'templates/base/pad/views/modules/' . $this->code . '/' . $view);
        }
        else if ($this->ci->agent->is_mobile())
        {
            $path_array = array(
              	'templates/' . $this->ci->template->get_code() . '/mobile/views/modules/' . $this->code . '/' . $view,
              	'templates/base/mobile/views/modules/' . $this->code . '/' . $view);
        }
        else
        {
            $path_array = array(
              	'templates/' . $this->ci->template->get_code() . '/web/views/modules/' . $this->code . '/' . $view,
              	'templates/base/web/views/modules/' . $this->code . '/' . $view);
        }

        foreach ($path_array as $path)
        {
            if (file_exists($path)) {
                return $this->ci->load->_ci_load(array(
    				'_ci_path'		=> $path,
    				'_ci_vars'		=> $data,
    				'_ci_return'	=> TRUE ));
            }
        }
    }

    /**
     * Set Configuration Parameters
     *
     * @access public
     * @param string $config configuration parameters
     */
    public function set_config($config)
    {
        $this->config = $config;
    }

    /**
     * Get Template Module Code
     *
     * @access public
     * @param string template module code
     */
    public function get_code()
    {
        return $this->code;
    }

    /**
     * Get Template Module Title
     *
     * @access public
     * @return string template module title
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * Get Template Module Title Link
     *
     * @access public
     * @return string template module title link
     */
    public function get_title_link()
    {
        return $this->title_link;
    }

    /**
     * If The Template Module Has Title Link
     *
     * @access public
     * @return boolean
     */
    public function has_title_link()
    {
        return !empty($this->title_link);
    }

    /**
     * Get Template Module Version
     *
     * @access public
     * @return string version
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Get Template Module Author Name
     *
     * @access public
     * @return string author name
     */
    public function get_author_name()
    {
        return $this->author_name;
    }

    /**
     * Get Template Module Author Url
     *
     * @access public
     * @return string author url
     */
    public function get_author_url()
    {
        return $this->author_url;
    }

    /**
     * Get Parameter Definitions
     *
     * @access public
     * @return array parameter difinitions array
     */
    public function get_params()
    {
        if (isset($this->params) && is_array($this->params)) {
            return $this->params;
        }

        return FALSE;
    }
}

/* End of file module.php */
/* Location: ./system/tomatocart/libraries/module.php */