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
 * TOC Language Class
 * 
 * @package     TomatoCart
 * @subpackage  Libraries
 * @category    Language
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
class TOC_Lang extends CI_Lang {

    /**
     * Reference to CodeIgniter instance
     *
     * @var     object
     * 
     * @since   2.0.0
     */
    protected $ci = NULL;

    /**
     * Language code
     *
     * @var     string
     * 
     * @since   2.0.0
     */
    protected $code = FALSE;

    /**
     * Languages
     *
     * @var     array
     * 
     * @since   2.0.0
     */
    protected $languages = array();

    /**
     * Class constructor
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    public function __construct()
    {
        parent::__construct();

        log_message('debug', 'TOC Language Class Initialized');
    }

    /**
     * Initialize the languages class.
     * 
     * The class must be initialized in the controller
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    public function initialize()
    {
        if ($this->ci === NULL)
        {
            $this->ci =& get_instance();

            // Initialize languages
            $this->ci->load->model('languages_model');
            $this->languages = $this->ci->languages_model->get_all();
        }

        // Check the language in the query language and set in the system
        $language = ($this->ci->input->get('language') !== FALSE) ? $this->ci->input->get('language') : '';

        // Set the language to system
        $this->set($language);
    }

    /**
     * Load a language group in the database.
     * 
     * @param   mixed   $group  The group of the file to be loaded. Can be an array.
     * @param   boolean $return Whether to return the loaded array of translations.
     * 
     * @return  boolean If $group exists return key/value pair with the language definition, otherwise return NULL.
     * 
     * @since   2.0.0
     */
    public function db_load($group = '', $return = FALSE)
    {
        // Check if the language group is loaded
        if (in_array($group, $this->is_loaded, TRUE))
        {
            return;
        }

        $definitions = $this->ci->languages_model->load($group);
        $this->language = array_merge($this->language, $definitions);
        $this->is_loaded[] = $group;
        unset($definitions);

        if ($return === TRUE)
        {
            return $definitions;
        }

        log_message('debug', 'Language Group loaded: ' . $group);

        return TRUE;
    }

    /**
     * Parses a language file.
     * 
     * @param   string  $filename       The filename to be loaded.
     * @param   string  $comment        The comment start sign.
     * @param   string  $language_code  The language code.
     * 
     * @return  array   Array holding the found languages as filename => real name pairs.
     * 
     * @since   2.0.0
     */
    public function parse_ini_file($filename = NULL, $comment = '#', $language_code = NULL)
    {
        $filename = str_replace('.php', '', $filename);

        if ($filename === NULL)
        {
            $filename = $this->code;
        }

        $filename .= '.php';

        if ($language_code === NULL)
        {
            $language_code = $this->code;
        }

        $languages = array();

        foreach (get_instance()->load->get_package_paths(TRUE) as $package_path)
        {
            if (file_exists($package_path . 'language/' . $language_code . '/' . $filename))
            {
                $contents = file($package_path . 'language/' . $language_code . '/' . $filename);

                foreach ($contents as $line)
                {
                    $line = trim($line);

                    $firstchar = substr($line, 0, 1);

                    if ( ! empty($line) && ($firstchar !== $comment))
                    {
                        $delimiter = strpos($line, '=');

                        if ($delimiter !== FALSE)
                        {
                            $key = trim(substr($line, 0, $delimiter));
                            $value = trim(substr($line, $delimiter + 1));

                            $languages[$key] = $value;
                        }
                        elseif (isset($key))
                        {
                            $languages[$key] .= trim($line);
                        }
                    }
                }
            }
        }

        log_message('debug', 'Parse Ini File loaded: ' . $filename);

        return $languages;
    }

    /**
     * Gets a single language file and appends the results to the existing strings.
     * 
     * @param   string  $filename       The filename to be loaded.
     * @param   string  $comment        The comment start sign.
     * @param   string  $language_code  The language code.
     * 
     * @return  boolean True if the file has successfully loaded.
     * 
     * @since   2.0.0
     */
    public function ini_load($filename = NULL, $comment = '#', $language_code = NULL)
    {
        if ($filename === NULL)
        {
            $filename = $this->code;
        }

        $filename .= '.php';

        if ($language_code === NULL)
        {
            $language_code = $this->code;
        }

        if (in_array($filename, $this->is_loaded, TRUE))
        {
            return TRUE;
        }

        $languages = $this->parse_ini_file($filename, $comment, $language_code);

        $this->language = array_merge($this->language, $languages);
        $this->is_loaded[] = $filename;
        unset($languages);

        log_message('debug', 'Language File loaded: ' . $filename);

        return TRUE;
    }

    /**
     * Load a xml language file.
     * 
     * @param   string $filename        The XML filename to be loaded.
     * @param   string $language_code   The language code.
     * 
     * @return  boolean True if the file has successfully loaded.
     * 
     * @since   2.0.0
     */
    public function xml_load($filename, $language_code = NULL)
    {
        if ($language_code === NULL)
        {
            $language_code = $this->code;
        }

        if (in_array($filename, $this->is_loaded, TRUE))
        {
            return TRUE;
        }

        $file = '../system/application/language/' . $language_code . '/' . $filename . '.xml';

        if (file_exists($file))
        {
            $xml = @simplexml_load_file($file);

            if (isset($xml->definitions->definition))
            {
                $languages = array();

                foreach($xml->definitions->definition as $definition)
                {
                    $languages[(string) $definition->key] = (string)$definition->value;
                }

                $this->language = array_merge($this->language, $languages);
                $this->is_loaded[] = $filename;
                unset($languages);

                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Language line.
     * 
     * Fetches a single line of text from the language array
     * 
     * @param   string  $line       Language line key
     * @param   boolean $log_errors Whether to log an error message if the line is not found
     * 
     * @return  string  Translation
     * 
     * @since   2.0.0
     */
    public function line($line = '', $log_errors = TRUE)
    {
        $value = ($line === '' or ! isset($this->language[$line])) ? $line : $this->language[$line];

        // Because killer robots like unicorns!
        if ($value === $line && $log_errors === TRUE)
        {
            log_message('error', 'Could not find the language line "'.$line.'"');
        }

        return $value;
    }

    /**
     * Set the language.
     * 
     * @param   string  $code   Language code.
     * 
     * @return  void
     * 
     * @since   2.0.0
     */
    public function set($code = '')
    {
        $this->code = $code;

        // If the language code is empty then we try to get the code from session or cookie
        if (empty($this->code))
        {
            // Get language code from session
            if ($this->ci->session->userdata('admin_language') !== FALSE)
            {
                $this->code = $this->ci->session->userdata('admin_language');
            }
            elseif ($this->ci->input->cookie('admin_language') !== FALSE)
            {
                $this->code = $this->ci->input->cookie('admin_language');
            }
            else
            {
                $this->code = $this->get_browser_setting();
            }
        }

        // No language found? then get the sytem default language
        if (empty($this->code) || ($this->exists($this->code) === FALSE))
        {
            $this->code = config('DEFAULT_LANGUAGE');
        }

        // Set language code in cookie
        $cookie = $this->ci->input->cookie('admin_language');
        if (($cookie === FALSE) || (($cookie !== FALSE) && ($cookie !== $this->code)))
        {
            $this->ci->input->set_cookie('admin_language', $this->code, time() + 60*60*24*90);
        }

        // Set language code in session
        $session = $this->ci->session->userdata('admin_language');
        if (($session === FALSE) || (($session !== FALSE) && ($session !== $this->code)))
        {
            $this->ci->session->set_userdata('admin_language', $this->code);
        }
    }

    /**
     * Get the browser setting language.
     * 
     * @return  mixed
     * 
     * @since   2.0.0
     */
    public function get_browser_setting()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            $browsers = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

            foreach ($browsers as $browser)
            {
                $browser = substr($browser, 0, strcspn($browser, ';'));

                $primary = substr($browser, 0, 2);

                foreach ($this->languages as $language)
                {
                    $lang = $language['code'];

                    if (strlen($lang) < 6)
                    {
                        if (strtolower($browser) === strtolower(substr($language['code'], 0, strlen($browser))))
                        {
                            return $language['code'];
                        }
                        elseif ($primary === substr($language['code'], 0, 2))
                        {
                            $detect = $language['code'];
                        }
                    }
                }

                if (isset($detect))
                {
                    return $detect;
                }
            }
        }

        return FALSE;
    }

    /**
     * Whether the language code is existed.
     * 
     * @param   string  $code   The language code.
     * 
     * @return  boolean
     * 
     * @since   2.0.0
     */
    public function exists($code)
    {
        return array_key_exists($code, $this->languages);
    }

    /**
     * Get all the languages.
     * 
     * @return  array
     * 
     * @since   2.0.0
     */
    public function get_all()
    {
        return $this->languages;
    }

    /**
     * Get the language id of the current language.
     * 
     * @return  integer
     * 
     * @since   2.0.0
     */
    public function get_id()
    {
        if ( ! empty($this->code))
        {
            return $this->languages[$this->code]['id'];
        }
    }

    /**
     * Get the language name of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function get_name()
    {
        return $this->languages[$this->code]['name'];
    }

    /**
     * Get the language code of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function get_code()
    {
        return $this->code;
    }

    /**
     * Get the locale of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function get_locale()
    {
        return $this->languages[$this->code]['locale'];
    }

    /**
     * Get the charset of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function get_charset()
    {
        return $this->languages[$this->code]['charset'];
    }

    /**
     * Get the country iso.
     * 
     * @return string
     * 
     * @since   2.0.0
     */
    public function get_country_iso()
    {
        return $this->languages[$this->code]['country_iso'];
    }

    /**
     * Get the date format short of the current language.
     * 
     * @param   boolean $with_time
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function get_date_format_short($with_time = FALSE)
    {
        if ($with_time === TRUE)
        {
            return $this->languages[$this->code]['date_format_short'] . ' ' . $this->get_time_format();
        }

        return $this->languages[$this->code]['date_format_short'];
    }

    /**
     * Get the date format long of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function get_date_format_long()
    {
        return $this->languages[$this->code]['date_format_long'];
    }

    /**
     * Get the time format of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function get_time_format()
    {
        return $this->languages[$this->code]['time_format'];
    }

    /**
     * Get the text direction of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function get_text_direction()
    {
        return $this->languages[$this->code]['text_direction'];
    }

    /**
     * Get the currency id of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function get_currency_id()
    {
        return $this->languages[$this->code]['currencies_id'];
    }

    /**
     * Get the decimal separator of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function get_numeric_decimal_separator()
    {
        return $this->languages[$this->code]['numeric_separator_decimal'];
    }

    /**
     * Get the thousands separator of the current language.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function get_numeric_thousands_separator()
    {
        return $this->languages[$this->code]['numeric_separator_thousands'];
    }

    /**
     * Get the wordflag image.
     * 
     * @param   string  $code       The language code.
     * @param   integer $width      Optional width.
     * @param   integer $height     Optional height.
     * @param   string  $parameters Optional parameters.
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    public function show_image($code = NULL, $width = 16, $height = 10, $parameters = NULL)
    {
        if (empty($code))
        {
            $code = $this->code;
        }

        $imagecode = strtolower(substr($code, 3));

        if ( ! is_numeric($width))
        {
            $width = 16;
        }

        if ( ! is_numeric($height))
        {
            $height = 10;
        }

        return image('images/worldflags/' . $imagecode . '.png', $this->languages[$code]['name'], $width, $height, $parameters);
    }

    /**
     * Import xml language resource file.
     * 
     * @param   string  $filename       The xml filename.
     * @param   integer $language_id    Language id.
     * 
     * @return  boolean True if the file has successfully imported.
     * 
     * @since   2.0.0
     */
    public function import_xml($filename, $language_id)
    {
        if (file_exists($filename))
        {
            $info = simplexml_load_file($filename);

            if (($info !== FALSE))
            {
                foreach ($info->definitions->definition as $definition)
                {
                    $data = array(
                        'language_id' => $language_id,
                        'content_group' => (string)$definition->group,
                        'definition_key' => (string)$definition->key,
                        'definition_value' => (string) $definition->value
                    );

                    if ( ! $this->ci->languages_model->check_definition($data))
                    {
                        $this->ci->languages_model->insert_definition($data);
                    }
                }

                unset($info);

                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Remove the xml resource definition in the database.
     * 
     * @param   string  $filename       The xml filename.
     * @param   integer $language_id    Language id.
     * 
     * @return  boolean True if the file has successfully removed.
     * 
     * @since   2.0.0
     */
    public function remove_xml($filename, $language_id)
    {
        if (file_exists($filename))
        {
            $info = simplexml_load_file($filename);

            if (($info !== FALSE))
            {
                foreach ($info->definitions->definition as $definition)
                {
                    $data = array(
                        'language_id' => $language_id,
                        'content_group' => (string)$definition->group,
                        'definition_key' => (string)$definition->key,
                        'definition_value' => (string) $definition->value
                    );

                    $this->ci->languages_model->remove_definition($data);
                }

                unset($info);

                return TRUE;
            }
        }

        return FALSE;
    }
}

/* End of file TOC_Lang.php */
/* Location: ./admin/system/core/TOC_Lang.php */