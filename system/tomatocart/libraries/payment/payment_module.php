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
 * Payment Module Class
 *
 * This class is the parent class for all payment modules
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Payment_Module {

    /**
     * Module group
     *
     * @access private
     * @var string
     */
    private $group = 'payment';

    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    protected $ci = NULL;

    /**
     * payment module code
     *
     * @access protected
     * @var string
     */
    protected $code = NULL;

    /**
     * payment module icon
     *
     * @access protected
     * @var string
     */
    protected $icon = NULL;

    /**
     * payment module title
     *
     * @access protected
     * @var string
     */
    protected $title = NULL;

    /**
     * payment module description
     *
     * @access protected
     * @var string
     */
    protected $description = NULL;

    /**
     * payment module status
     *
     * @access protected
     * @var boolean
     */
    protected $status = FALSE;

    /**
     * payment module sort order
     *
     * @access protected
     * @var int
     */
    protected $sort_order = 0;

    /**
     * order id
     *
     * @access protected
     * @var int
     */
    protected $order_id;

    /**
     * payment module configuration
     *
     * @access protected
     * @var array
     */
    protected $config = array();

    /**
     * payment module configuration parameters
     *
     * @access protected
     * @var array
     */
    protected $params = array();

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        // Load extension model
        $this->ci->load->model('extensions_model');

        // Get extension params
        $data = $this->ci->extensions_model->get_module('payment', $this->code);

        // Load data
        if ($data !== NULL)
        {
            $this->config = json_decode($data['params'], TRUE);
        }
    }

    /**
     * Install module
     *
     * @access public
     * @return boolean
     */
    public function install() {
        //load extensions model
        $this->ci->load->model('extensions_model');

        //check whether the module is installed
        $data = $this->ci->extensions_model->get_module($this->group, $this->code);

        if ($data == NULL) {
            $config = array();

            if (isset($this->params) && is_array($this->params)) {
                foreach ($this->params as $param) {
                    $config[$param['name']] = $param['value'];
                }
            }
            
            $data = array(
                'title' => $this->title,
                'code' => $this->code,
                'author_name' => '',
                'author_www' => '',
                'modules_group' => $this->group,
                'params' => json_encode($config));

            $result = $this->ci->extensions_model->install($data);

            //insert language definition
            if ($result) {
                $languages_all = $this->ci->lang->get_all();

                foreach ($languages_all as $l)
                {
                    $xml_file = '../system/tomatocart/language/' . $l['code'] . '/modules/' . $this->group . '/' . $this->code . '.xml';
                    $this->ci->lang->import_xml($xml_file, $l['id']);
                }
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Uninstall module
     *
     * @access public
     * @return boolean
     */
    public function uninstall() {
        //load extensions model
        $this->ci->load->model('extensions_model');

        $result = $this->ci->extensions_model->uninstall($this->group, $this->code);

        //remove language definition
        if ($result) {
            $languages_all = $this->ci->lang->get_all();

            foreach ($languages_all as $l)
            {
                $xml_file = '../system/tomatocart/language/' . $l['code'] . '/modules/' . $this->group . '/' . $this->code . '.xml';
                $this->ci->lang->remove_xml($xml_file, $l['id']);
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Get Payment Module Code
     *
     * @access public
     * @return string payment module code
     */
    public function get_code()
    {
        return $this->code;
    }

    /**
     * Get Payment Module Title
     *
     * @access public
     * @return string payment module title
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * Get Payment Module Description
     *
     * @access public
     * @return string payment module description
     */
    public function get_description()
    {
        return $this->description;
    }

    /**
     * Get Payment Module Configurations
     *
     * @access public
     * @return array payment module configurations
     */
    public function get_config()
    {
        return $this->config;
    }

    /**
     * Get Payment Module Parameters
     *
     * @access public
     * @return array shipping module paramers
     */
    public function get_params()
    {
        return $this->params;
    }

    function get_error() {
        return NULL;
    }
    
    /**
     * Whether the payment module is installed
     *
     * @access public
     * @return boolean payment module installed
     */
    public function is_installed()
    {
        return is_array($this->config) && !empty($this->config);
    }

    /**
     * Whether the payment module is enabled
     *
     * @access public
     * @return boolean payment module status
     */
    public function is_enabled()
    {
        return $this->status;
    }

    /**
     * Get payment module sort order
     *
     * @access public
     * @return int payment module sort order
     */
    public function get_sort_order()
    {
        return $this->sort_order;
    }

    /**
     * Get selected payment module
     *
     * @access public
     * @return payment module selection
     */
    function selection()
    {
        return array('id' => $this->code, 'module' => $this->method_title);
    }

    /**
     * Process the payment module
     *
     * @access public
     * @return void
     */
    public function process(){}

    /**
     * Process button
     *
     * @access public
     * @return string
     */
    public function process_button() {}

    /**
     * Send Transaction To Gateway
     *
     * @access public
     * @param $url
     * @param $parameters
     * @param $header
     * @param $method
     * $param $certificate
     */
    function send_transaction_to_gateway($url, $parameters, $header = '', $method = 'post', $certificate = '') {
        if (empty($header) || !is_array($header)) {
            $header = array();
        }

        $server = parse_url($url);

        if (isset($server['port']) === false) {
            $server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
        }

        if (isset($server['path']) === false) {
            $server['path'] = '/';
        }

        if (isset($server['user']) && isset($server['pass'])) {
            $header[] = 'Authorization: Basic ' . base64_encode($server['user'] . ':' . $server['pass']);
        }

        $connection_method = 0;

        if (function_exists('curl_init')) {
            $connection_method = 1;
        } elseif ( ($server['scheme'] == 'http') || (($server['scheme'] == 'https') && extension_loaded('openssl')) ) {
            if (function_exists('stream_context_create')) {
                $connection_method = 3;
            } else {
                $connection_method = 2;
            }
        }

        $result = '';

        switch ($connection_method) {
            case 1:
                $curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
                curl_setopt($curl, CURLOPT_PORT, $server['port']);

                if (!empty($header)) {
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                }

                if (!empty($certificate)) {
                    curl_setopt($curl, CURLOPT_SSLCERT, $certificate);
                }

                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
                curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);

                $result = curl_exec($curl);

                curl_close($curl);

                break;

            case 2:
                if ($fp = @fsockopen(($server['scheme'] == 'https' ? 'ssl' : $server['scheme']) . '://' . $server['host'], $server['port'])) {
                    @fputs($fp, 'POST ' . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : '') . ' HTTP/1.1' . "\r\n" .
                        'Host: ' . $server['host'] . "\r\n" .
                        'Content-type: application/x-www-form-urlencoded' . "\r\n" .
                        'Content-length: ' . strlen($parameters) . "\r\n" .
                    (!empty($header) ? implode("\r\n", $header) . "\r\n" : '') .
                        'Connection: close' . "\r\n\r\n" .
                    $parameters . "\r\n\r\n");

                    $result = @stream_get_contents($fp);

                    @fclose($fp);

                    $result = trim(substr($result, strpos($result, "\r\n\r\n", strpos(strtolower($result), 'content-length:'))));
                }

                break;

            case 3:
                $options = array('http' => array('method' => 'POST',
                                           'header' => 'Host: ' . $server['host'] . "\r\n" .
                                                       'Content-type: application/x-www-form-urlencoded' . "\r\n" .
                                                       'Content-length: ' . strlen($parameters) . "\r\n" .
                (!empty($header) ? implode("\r\n", $header) . "\r\n" : '') .
                                                       'Connection: close',
                                           'content' => $parameters));

                if (!empty($certificate)) {
                    $options['ssl'] = array('local_cert' => $certificate);
                }

                $context = stream_context_create($options);

                if ($fp = fopen($url, 'r', false, $context)) {
                    $result = '';

                    while (!feof($fp)) {
                        $result .= fgets($fp, 4096);
                    }

                    fclose($fp);
                }

                break;

            default:
                exec(escapeshellarg(CFG_APP_CURL) . ' -d ' . escapeshellarg($parameters) . ' "' . $server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : '') . '" -P ' . $server['port'] . ' -k' . (!empty($header) ? ' -H ' . escapeshellarg(implode("\r\n", $header)) : '') . (!empty($certificate) ? ' -E ' . escapeshellarg($certificate) : ''), $result);
                $result = implode("\n", $result);
        }

        return $result;
    }
}

/* End of file payment_module.php */
/* Location: ./system/tomatocart/libraries/payment/payment_module.php */