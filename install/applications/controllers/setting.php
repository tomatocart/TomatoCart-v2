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
 * Setting Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-index-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Setting extends TOC_Controller
{
    /**
     * Constructor
     *
     * @access public
     * @param void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Connect to the database
     *
     * @access public
     * @return void
     */
    public function save () {
        //store settings
        $www = trim(urldecode($this->input->post('HTTP_WWW_ADDRESS')));
        $store_name = trim(urldecode($this->input->post('CFG_STORE_NAME')));
        $store_owner_name = trim(urldecode($this->input->post('CFG_STORE_OWNER_NAME')));
        $email = trim(urldecode($this->input->post('CFG_STORE_OWNER_EMAIL_ADDRESS')));
        $username = trim(urldecode($this->input->post('CFG_ADMINISTRATOR_USERNAME')));
        $password = trim(urldecode($this->input->post('CFG_ADMINISTRATOR_PASSWORD')));
        $sample = trim(urldecode($this->input->post('DB_INSERT_SAMPLE_DATA')));
        $db_config = $this->session->userdata('db_config');

        //connect to database
        $this->load->database($db_config);

        //load settings model
        $this->load->model('settings_model');
        //load administrators model
        $this->load->model('administrators_model');

        $this->settings_model->save_setting('STORE_NAME', $store_name);
        $this->settings_model->save_setting('STORE_OWNER', $store_owner_name);
        $this->settings_model->save_setting('STORE_OWNER_EMAIL_ADDRESS', $email);
        $this->settings_model->save_setting('EMAIL_FROM', '"' . $store_owner_name . '" <' . $email . '>');

        $this->administrators_model->create($username, $password, $email);

        //parse http, get http server & http path
        $http_url = parse_url($www);
        $http_server = $http_url['scheme'] . '://' . $http_url['host'];
        $http_path = $http_url['path'];
        if (isset($http_url['port']) && !empty($http_url['port'])) {
            $http_server .= ':' . $http_url['port'];
        }

        //http path
        if (substr($http_path, -1) != '/') {
            $http_path .= '/';
        }

        //write store frontend config file
        $this->write_config_file($http_server, $http_path, $http_url);


        //write store admin config file
        $this->write_admin_config_file($http_server, $http_path, $http_url);

        //write database configuration file
        $this->write_database_file('../local/config/database.php', $db_config);

        //import sample data
        if ($sample == 'on') {
            //import sample sql data
            $this->import_sample_sql();

            //copy sample data
            toc_copy('samples/images', '../images');

            //resize images
            $this->resize_product_images();
        }

        $this->output->set_output(json_encode(array('success' => TRUE)));
    }

    /**
     * Resize product images
     *
     * @access private
     * @return void
     */
    public function resize_product_images() {
        $directories = directory_map('samples/images/products/originals', 1, TRUE);
        $images_groups = array(
            'thumbnails' => array('width' => 140, 'height' => 140),
            'product_info' => array('width' => 285, 'height' => 255),
            'large' => array('width' => 480, 'height' => 360),
            'mini' => array('width' => 57, 'height' => 57)
        );
        
        foreach ($directories as $file)
        {
            if ((strpos($file, '.jpg') !== FALSE) || (strpos($file, '.png') !== FALSE))
            {
                foreach ($images_groups as $name => $size) {
                    $original_image = 'samples/images/products/originals/' . $file;
                    $dest_image = '../images/products/' . $name . '/' . $file;
                    
                    toc_gd_resize($original_image, $dest_image, $size['width'], $size['width']);
                }
            }
        }
    }

    /**
     * Import sample sql data
     *
     * @access private
     * @return boolean
     */
    private function import_sample_sql() {
        //get database configuration from session
        $config = $this->session->userdata('db_config');

        //connect to database
        $this->load->database($config);

        //database is connected
        if ($this->db) {
            $sql_data = $this->load->file(realpath(dirname(__FILE__) . '/../../../') . '/install/tomatocart_sample_data.sql', TRUE);
            $sql_data = str_replace('`toc_', '`' . $config['dbprefix'], $sql_data);

            //split sql data with ;
            $statements = preg_split("/;[\r\n]/", $sql_data) ;

            //execute the sql statement
            foreach ($statements as $statement) {
                $this->db->query($statement);
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Write configuration file
     *
     * @param string $http_server
     * @param string $http_path
     * @param array $http_url
     * @return boolean
     */
    private function write_config_file($http_server, $http_path, $http_url) {
        $file = file_get_contents('../local/config/config.php');
        $lines = explode("\n", $file);

        $output = array();
        foreach ($lines as $line) {
            //config -- base url
            if (strpos($line, '$config[\'base_url\']') === 0) {
                $output[] = '$config[\'base_url\']	= \'' . $http_server . $http_path . '\';';
            }
            //config -- cookie domain
            else if (strpos($line, '$config[\'cookie_domain\']') === 0) {
                $output[] = '$config[\'cookie_domain\']	= \'' . $http_url['host'] . '\';';
            }
            //config -- cookie path
            else if (strpos($line, '$config[\'cookie_path\']') === 0) {
                $output[] = '$config[\'cookie_path\']		= \'' . $http_path . '\';';
            }else {
                $output[] = $line;
            }
        }

        //write configuration file
        $fp = @fopen('../local/config/config.php', 'w');
        @fputs($fp, implode("\n", $output));
        fclose($fp);
    }

    /**
     * Write configuration file
     *
     * @param string $http_server
     * @param string $http_path
     * @param array $http_url
     * @return boolean
     */
    private function write_admin_config_file($http_server, $http_path, $http_url) {
        $file = file_get_contents('../admin/local/config/config.php');
        $lines = explode("\n", $file);

        $output = array();
        foreach ($lines as $line) {
            //config -- base url
            if (strpos($line, '$config[\'base_url\']') === 0) {
                $output[] = '$config[\'base_url\']	= \'' . $http_server . $http_path . 'admin/\';';
            }
            //config -- cookie domain
            else if (strpos($line, '$config[\'cookie_domain\']') === 0) {
                $output[] = '$config[\'cookie_domain\']	= \'' . $http_url['host'] . '\';';
            }
            //config -- cookie path
            else if (strpos($line, '$config[\'cookie_path\']') === 0) {
                $output[] = '$config[\'cookie_path\']		= \'' . $http_path . 'admin/\';';
            }else {
                $output[] = $line;
            }
        }

        //write configuration file
        $fp = @fopen('../admin/local/config/config.php', 'w');
        @fputs($fp, implode("\n", $output));
        fclose($fp);
    }

    /**
     * Write database config file
     *
     * @access private
     * @param string $location
     * @param array $config
     * @return boolean
     */
    private function write_database_file($location, $config) {
        $file = file_get_contents($location);
        $lines = explode("\n", $file);

        $output = array();
        foreach ($lines as $line) {
            //config -- base url
            if (strpos($line, '	\'hostname\'') === 0) {
                $output[] = '	\'hostname\' => \'' . $config['hostname'] . '\',';
            }
            //config -- cookie path
            else if (strpos($line, '	\'username\'') === 0) {
                $output[] = '	\'username\' => \'' . $config['username'] . '\',';
            }
            //config -- cookie path
            else if (strpos($line, '	\'password\'') === 0) {
                $output[] = '	\'password\' => \'' . $config['password'] . '\',';
            }
            //config -- cookie path
            else if (strpos($line, '	\'database\'') === 0) {
                $output[] = '	\'database\' => \'' . $config['database'] . '\',';
            }
            //config -- cookie path
            else if (strpos($line, '	\'dbdriver\'') === 0) {
                $output[] = '	\'dbdriver\' => \'' . $config['dbdriver'] . '\',';
            }
            //config -- cookie path
            else if (strpos($line, '	\'dbprefix\'') === 0) {
                $output[] = '	\'dbprefix\' => \'' . $config['dbprefix'] . '\',';
            } else {
                $output[] = $line;
            }
        }

        //write configuration file
        $fp = @fopen($location, 'w');
        if ($fp !== FALSE) {
            @fputs($fp, implode("\n", $output));
            @fclose($fp);

            return TRUE;
        }

        return FALSE;
    }
}

/* End of file setting.php */
/* Location: ./install/applications/controllers/setting.php */