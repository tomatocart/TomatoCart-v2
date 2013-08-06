<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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


// --------------------------------------------------------------------

/**
 * Copy param values from values array to param array
 *
 * @param array $params param array
 * @param array $values values array
 */
if( ! function_exists('copy_param_values'))
{
    function copy_array_values($params, $values) {
        if (is_array($params)) {
            foreach ($params as $key => $param) {
                $name = $param['name'];

                if (isset($values[$name])) {
                    $params[$key]['value'] = $values[$name];
                }
            }
        }

        return $params;
    }
}

// ------------------------------------------------------------------------

/**
 * Get template modules meta information
 *
 * @return array
 */
if( ! function_exists('get_template_modules_meta_data'))
{
    function get_template_modules_meta_data()
    {
        //get ci instance
        $ci = get_instance();

        //load the module parent class
        load_front_library('module.php');

        //load language resources
        $ci->lang->db_load('modules-boxes');

        //modules paths
        $path_array = array(
            store_front_path() . 'local/modules/',
            store_front_path() . 'system/tomatocart/modules/');

        $modules = array();
        $loaded = array();
        
        //check all path and find modules
        foreach($path_array as $path) 
        {
            $directories = directory_map($path, 1, TRUE);

            foreach ($directories as $directory)
            {
                $file = $path . $directory . '/' . $directory . '.php';
                if ( file_exists($file) && !in_array($directory, $loaded) )
                {
                    require_once $file;
                    
                    //class name
                    $class_name = 'Mod_' . $directory;

                    //create new instance
                    $class = new $class_name(array());

                    //append loaded module
                    $loaded[] = $directory;
                    
                    //append data
                    $modules[] = array('code' => $class->get_code(), 'text' => $class->get_title(), 'params' => $class->get_params());
                }
            }
        }

        return $modules;
    }
}

/**
 * Get layout modules
 *
 * @access private
 * @param string $templates_code
 * @param string $medium
 * @return string
 */
if( ! function_exists('get_layout_modules'))
{
    function get_layout_modules($templates_code)
    {
        //get ci instance
        $ci = get_instance();

        $file = store_front_path() . 'templates/' . $templates_code . '/template.xml';

        if(file_exists($file))
        {
            $xml = @simplexml_load_file($file);
            $template_modules = get_template_modules_meta_data();
            $groupsEl = $xml->WebLayout->ContentGroups->Group;

            //modules
            $modules = $ci->templates_model->get_layout_modules($templates_code);
            foreach ($modules as $index => $module)
            {
                $modules[$index]['title'] = get_module_title($module['module']);

                foreach ($template_modules as $template_module)
                {
                    if ($module['module'] == $template_module['code'])
                    {
                        //copy values to configuration params array
                        $modules[$index]['params'] = copy_array_values($template_module['params'], $module['params']);
                    }
                }
            }
            
            //groups
            $groups = array();
            if (sizeof($groupsEl) > 0)
            {
                foreach ($groupsEl as $groupEl)
                {
                    $group['name'] = (string) $groupEl;
                    $group['modules'] = array();

                    foreach ($modules as $module)
                    {
                        if ($module['content_group'] == (string) $groupEl)
                        {
                            $group['modules'][] = $module;
                        }
                    }

                    $groups[] = $group;
                }
            }

            return $groups;
        }

        return NULL;
    }
}

/**
 * Get box module Title
 *
 * @access public
 * @param string $code
 * @return mixed
 */
if( ! function_exists('get_module_title'))
{
    function get_module_title($code)
    {
        //load the module parent class
        load_front_library('module.php');

        //modules paths
        $path_array = array(
            store_front_path() . 'local/modules/',
            store_front_path() . 'system/tomatocart/modules/');

        $params = array();
        foreach($path_array as $path)
        {
            $file = $path . $code . '/' . $code . '.php';
            if (file_exists($file))
            {
                require_once $file;
                
                $class_name = 'Mod_' . $code;

                $class = new $class_name(array());

                return $class->get_title();
            }
        }

        return NULL;
    }
}