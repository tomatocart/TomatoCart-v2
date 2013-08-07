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
 * Cache Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Payment
{

    protected $ci = null;
    protected $selected_module;

    protected $_modules = array();
    protected $_group = 'payment';

    // class constructor
    public function __construct($module = '') {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        //load extensions model
        $this->ci->load->model('extensions_model');

        //$this->quotes = $this->shopping_cart->get_shipping_quotes();
        $this->modules = $this->ci->extensions_model->get_modules('payment');

        if (empty($this->modules) === false) {
            if ((empty($module) === false) && in_array($module, $this->modules)) {
                $this->modules = array($module);
                $this->selected_module = 'payment_' . $module;
            }

            $this->ci->lang->db_load('modules-payment');

            //load shipping libraries
            foreach ($this->modules as $module) {
                //module class
                $module_class = strtolower('payment_' . $module);

                //load library
                $this->ci->load->library('payment/' . $module_class);

                //initialize
                $this->ci->{$module_class}->initialize();
            }

            usort($this->modules, array('TOC_Payment', 'usort_modules'));

            $module_class = 'payment_' . $module;
            if ( (!empty($module)) && (in_array($module, $this->modules)) && (isset($this->ci->$module_class->form_action_url)) ) {
                $this->form_action_url = $this->ci->$module_class->form_action_url;
            }
        }
    }

    /**
     *
     *
     * @param string $module
     */
    public function load_payment_module($module)
    {
        //module class
        $module_class = strtolower('payment_' . $module);

        //load library
        $this->ci->load->library('payment/' . $module_class);

        return $this->ci->$module_class;
    }

    // class methods
    public function send_transaction_to_gateway($url, $parameters, $header = '', $method = 'post', $certificate = '') {
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

    public function get_code() {
        return $this->code;
    }

    public function get_title() {
        return $this->title;
    }

    public function get_description() {
        return $this->description;
    }

    public function get_method_title() {
        return $this->method_title;
    }

    function is_enabled() {
        return $this->status;
    }

    function get_sort_order() {
        return $this->sort_order;
    }

    function get_javascript_block() {
    }

    function get_javascript_blocks() {
        $js = '';
        if (is_array($this->modules)) {
            $js = 'function check_form() {' . "\n" .
              '  var error = 0;' . "\n" .
              '  var error_message = "' . lang('js_error') . '";' . "\n" .
              '  var payment_value = null;' . "\n" .
              '  if (document.checkout_payment.payment_method.length) {' . "\n" .
              '    for (var i=0; i<document.checkout_payment.payment_method.length; i++) {' . "\n" .
              '      if (document.checkout_payment.payment_method[i].checked) {' . "\n" .
              '        payment_value = document.checkout_payment.payment_method[i].value;' . "\n" .
              '      }' . "\n" .
              '    }' . "\n" .
              '  } else if (document.checkout_payment.payment_method.checked) {' . "\n" .
              '    payment_value = document.checkout_payment.payment_method.value;' . "\n" .
              '  } else if (document.checkout_payment.payment_method.value) {' . "\n" .
              '    payment_value = document.checkout_payment.payment_method.value;' . "\n" .
              '  }' . "\n\n";

            foreach ($this->modules as $module) {
                $module_class = 'payment_' . $module;
                if ($this->ci->$module_class->is_enabled()) {
                    $js .= $this->ci->$module_class->get_javascript_block();
                }
            }

            $js .= "\n" . '  if (payment_value == null) {' . "\n" .
               '    error_message = error_message + "' . lang('js_no_payment_module_selected') . '\n";' . "\n" .
               '    error = 1;' . "\n" .
               '  }' . "\n\n" .
               '  if (error == 1) {' . "\n" .
               '    alert(error_message);' . "\n" .
               '    return false;' . "\n" .
               '  } else {' . "\n" .
               '    return true;' . "\n" .
               '  }' . "\n" .
               '}' ;
        }

        return $js;
    }

    function selection() {
        $selection_array = array();

        foreach ($this->modules as $module) {
            $module_class = 'payment_' . $module;

            if ($this->ci->$module_class->is_enabled()) {
                $selection = $this->ci->$module_class->selection();
                if (is_array($selection)) $selection_array[] = $selection;
            }
        }

        return $selection_array;
    }

    function checkout_initialization_method() {
        return false;
    }

    function get_checkout_initialization_methods() {
        $initialize_array = array();

        if (is_array($this->modules)) {
            reset($this->modules);

            foreach($this->modules as $module) {
                $module_class = 'payment_' . $module;

                if ($this->ci->$module_class->is_enabled() && method_exists($this->ci->$module_class, 'checkout_initialization_method')) {
                    $initialize_array[] = $this->ci->$module_class->checkout_initialization_method();
                }
            }
        }

        return $initialize_array;
    }

    function pre_confirmation_check() {
        if (isset($this->modules) && is_array($this->modules)) {
            $module_class = $this->selected_module;

            if (isset($this->ci->$module_class) && is_object($this->ci->$module_class) && $this->ci->$module_class->is_enabled()) {
                //$this->ci->$module_class->pre_confirmation_check();
            }
        }
    }

    function confirmation() {
        if (isset($this->modules) && is_array($this->modules)) {
            $module_class = $this->selected_module;

            if (isset($this->ci->$module_class) && is_object($this->ci->$module_class) && $this->ci->$module_class->is_enabled()) {
                return $this->ci->$module_class->confirmation();
            }
        }
    }

    function process_button() {
        if (isset($this->modules) && is_array($this->modules)) {
            $module_class = $this->selected_module;

            if (isset($this->ci->$module_class) && is_object($this->ci->$module_class) && $this->ci->$module_class->is_enabled()) {
                return $this->ci->$module_class->process_button();
            }
        }
    }

    function process() {
        if (is_array($this->modules)) {
            $module_class = $this->selected_module;

            if (isset($this->ci->$module_class) && is_object($this->ci->$module_class) && $this->ci->$module_class->is_enabled()) {
                return $this->ci->$module_class->process();
            }
        }
    }

    function get_error() {
        if (is_array($this->modules)) {
            $module_class = $this->selected_module;

            if (isset($this->ci->$module_class) && is_object($this->ci->$module_class) && $this->ci->$module_class->is_enabled()) {
                return $this->ci->$module_class->get_error();
            }
        }
    }

    function has_action_url() {
        if (is_array($this->modules)) {
            $module_class = $this->selected_module;

            if (isset($this->ci->$module_class) && is_object($this->ci->$module_class) && $this->ci->$module_class->is_enabled()) {
                if (isset($this->ci->$module_class->form_action_url) && (empty($this->ci->$module_class->form_action_url) === false)) {
                    return true;
                }
            }
        }

        return false;
    }

    function get_action_url() {
        $module_class = $this->selected_module;

        return $this->ci->$module_class->form_action_url;
    }

    function has_active() {
        static $has_active;

        if (isset($has_active) === false) {
            $has_active = false;

            foreach ($this->modules as $module) {
                $module_class = 'payment_' . $module;

                if ($this->ci->$module_class->is_enabled()) {
                    $has_active = true;
                    break;
                }
            }
        }

        return $has_active;
    }

    function number_of_active() {
        static $active;

        if (isset($active) === false) {
            $active = 0;

            foreach ($this->modules as $module) {
                $module_class = 'payment_' . $module;

                if ($this->ci->$module_class->is_enabled()) {
                    $active++;
                }
            }
        }

        return $active;
    }

    public function usort_modules($a, $b) {
        $module_class_a = 'payment_' . $a;
        $module_class_b = 'payment_' . $b;

        if ($this->ci->$module_class_a->get_sort_order() == $this->ci->$module_class_b->get_sort_order()) {
            return strnatcasecmp($this->ci->$module_class_a->get_title(), $this->ci->$module_class_a->get_title());
        }

        return ($this->ci->$module_class_a->get_sort_order() < $this->ci->$module_class_b->get_sort_order()) ? -1 : 1;
    }
}
?>
