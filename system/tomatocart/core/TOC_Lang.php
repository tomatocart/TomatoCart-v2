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
 * Frontend Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Lang extends CI_Lang {
    /**
     * ci instance
     *
     * @access private
     * @var object
     */
    private $ci = FALSE;

    /**
     * ci instance
     *
     * @access private
     * @var string
     */
    private $code = FALSE;

    /**
     * ci instance
     *
     * @access private
     * @var array
     */
    private $languages = array();

    /**
     * Constructor
     *
     * @access  public
     */
    public function __construct()
    {
        parent::__construct();

        log_message('debug', "TOC Language Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Initialize the languages class.
     *
     * The class must be initialized in the controller
     *
     * @access public
     */
    public function initialize()
    {
        if ($this->ci === FALSE)
        {
            $this->ci = get_instance();

            //initialize languages
            $this->ci->load->model('languages_model');
            $this->languages = $this->ci->languages_model->get_languages();
        }

        //check the language in the query language and set in the system
        $language = ($this->ci->input->get('language') !== FALSE) ? $this->ci->input->get('language') : '';
        $this->set($language);
    }

    // --------------------------------------------------------------------

    /**
     * get all the languages
     *
     * @access public
     * @return array
     */
    public function getlanguages()
    {
        return $this->languages;
    }

    // --------------------------------------------------------------------

    /**
     * Load a language file for admin panel
     *
     * @access  public
     * @param string the group
     * @param string the comment line singal
     * @param string the language code
     */
    public function db_load($group = '', $return = FALSE)
    {
        //check if the language group is loaded
        if (in_array($group, $this->is_loaded, TRUE))
        {
            return;
        }

        $definitions = $this->ci->languages_model->load($group);
        $this->language = array_merge($this->language, $definitions);
        $this->is_loaded[] = $group;
        unset($definitions);

        if ($return == TRUE)
        {
            return $definitions;
        }

        log_message('debug', 'Language Group loaded: ' . $group);

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a single line of text from the language array
     *
     * @access  public
     * @param string  $line the language line
     * @return  string
     */
    public function line($key = '')
    {
        $value = ($key == '' OR ! isset($this->language[$key])) ? $key : $this->language[$key];

        // Because killer robots like unicorns!
        if ($value === FALSE)
        {
            log_message('error', 'Could not find the language definition "' . $key . '"');
        }

        return $value;
    }

    // --------------------------------------------------------------------

    /**
     * Set the language code
     *
     * @access  public
     * @param string  The language code
     */
    public function set($code = '')
    {
        $this->code = $code;

        //if the language code is empty then we try to get the code from session or cookie
        if (empty($this->code))
        {
            //get language code from session
            if ($this->ci->session->userdata('language') !== FALSE)
            {
                $this->code = $this->ci->session->userdata('language');
            }
            //get language code from cookie
            elseif ($this->ci->input->cookie('language') !== FALSE)
            {
                $this->code = $this->ci->input->cookie('language');
            }
            //get language code from browser setting
            else
            {
                $this->code = $this->get_browser_setting();
            }
        }

        //no language found? then get the sytem default language
        if (empty($this->code) || ($this->exists($this->code) === false))
        {
            $this->code = config('DEFAULT_LANGUAGE');
        }

        //set language code in cookie
        $language = $this->ci->input->cookie('language');
        if (($language === FALSE) || (($language !== FALSE) && ($language != $this->code))) {
            $this->ci->input->set_cookie('language', $this->code, time()+60*60*24*90);
        }

        //set language code in session
        $language = $this->ci->session->userdata('language');
        if (($language === FALSE) || (($language !== FALSE) && ($language != $this->code))) {
            $this->ci->session->set_userdata('language', $this->code);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Get the browser setting language
     *
     * @access  public
     * @return string
     */
    public function get_browser_setting()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserlanguages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

            $languages = array('ar' => 'ar([-_][[:alpha:]]{2})?|arabic',
                         'bg' => 'bg|bulgarian',
                         'br' => 'pt[-_]br|brazilian portuguese',
                         'ca' => 'ca|catalan',
                         'cs' => 'cs|czech',
                         'da' => 'da|danish',
                         'de' => 'de([-_][[:alpha:]]{2})?|german',
                         'el' => 'el|greek',
                         'en' => 'en([-_][[:alpha:]]{2})?|english',
                         'es' => 'es([-_][[:alpha:]]{2})?|spanish',
                         'et' => 'et|estonian',
                         'fi' => 'fi|finnish',
                         'fr' => 'fr([-_][[:alpha:]]{2})?|french',
                         'gl' => 'gl|galician',
                         'he' => 'he|hebrew',
                         'hu' => 'hu|hungarian',
                         'id' => 'id|indonesian',
                         'it' => 'it|italian',
                         'ja' => 'ja|japanese',
                         'ko' => 'ko|korean',
                         'ka' => 'ka|georgian',
                         'lt' => 'lt|lithuanian',
                         'lv' => 'lv|latvian',
                         'nl' => 'nl([-_][[:alpha:]]{2})?|dutch',
                         'no' => 'no|norwegian',
                         'pl' => 'pl|polish',
                         'pt' => 'pt([-_][[:alpha:]]{2})?|portuguese',
                         'ro' => 'ro|romanian',
                         'ru' => 'ru|russian',
                         'sk' => 'sk|slovak',
                         'sr' => 'sr|serbian',
                         'sv' => 'sv|swedish',
                         'th' => 'th|thai',
                         'tr' => 'tr|turkish',
                         'uk' => 'uk|ukrainian',
                         'tw' => 'zh[-_]tw|chinese traditional',
                         'zh' => 'zh|chinese simplified');

            foreach ($browserlanguages as $browser_language)
            {
                foreach ($languages as $key => $value)
                {
                    if (preg_match('/^(' . $value . ')(;q=[0-9]\\.[0-9])?$/i', $browser_language) && $this->exists($key))
                    {
                        return $key;
                    }
                }
            }
        }

        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Whether the language code is existed
     *
     * @access public
     * @param string The language code
     * @return boolean
     */
    public function exists($code)
    {
        return array_key_exists($code, $this->languages);
    }

    // --------------------------------------------------------------------

    /**
     * Get all the languages
     *
     * @access  public
     * @return array
     */
    public function get_languages()
    {
        return $this->languages;
    }

    // --------------------------------------------------------------------

    /**
     * Get all the languages
     *
     * @access  public
     * @return array
     */
    public function get_all()
    {
        return $this->languages;
    }

    // --------------------------------------------------------------------

    /**
     * Get the language id of the current language
     *
     * @access  public
     * @return int
     */
    public function get_id()
    {
        if (!empty($this->code))
        {
            return $this->languages[$this->code]['id'];
        }
    }

    // --------------------------------------------------------------------

    /**
     * Get the language name of the current language
     *
     * @access  public
     * @return string
     */
    public function get_name()
    {
        return $this->languages[$this->code]['name'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the language code of the current language
     *
     * @access  public
     * @return string
     */
    public function get_code()
    {
        return $this->code;
    }

    // --------------------------------------------------------------------

    /**
     * Get the locale of the current language
     *
     * @access  public
     * @return string
     */
    public function get_locale()
    {
        return $this->languages[$this->code]['locale'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the charset of the current language
     *
     * @access  public
     * @return string
     */
    public function get_character_set()
    {
        return $this->languages[$this->code]['charset'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the format short of the current language
     *
     * @access  public
     * @param bool
     * @return string
     */
    public function get_date_format_short($with_time = false)
    {
        if ($with_time === true) {
            return $this->languages[$this->code]['date_format_short'] . ' ' . $this->getTimeFormat();
        }

        return $this->languages[$this->code]['date_format_short'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the date format long of the current language
     *
     * @access  public
     * @return string
     */
    public function get_date_format_long()
    {
        return $this->languages[$this->code]['date_format_long'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the time format of the current language
     *
     * @access  public
     * @return string
     */
    public function get_time_format()
    {
        return $this->languages[$this->code]['time_format'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the text direction of the current language
     *
     * @access  public
     * @return string
     */
    public function get_text_direction()
    {
        return $this->languages[$this->code]['text_direction'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the currency id of the current language
     *
     * @access  public
     * @return string
     */
    public function get_currency_id()
    {
        return $this->languages[$this->code]['currencies_id'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the decimal separator of the current language
     *
     * @access  public
     * @return string
     */
    public function get_numeric_decimal_separator()
    {
        return $this->languages[$this->code]['numeric_separator_decimal'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the thousands separator of the current language
     *
     * @access  public
     * @return string
     */
    public function get_numeric_thousands_separator()
    {
        return $this->languages[$this->code]['numeric_separator_thousands'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the wordflag image
     *
     * @access  public
     * @param string The language code
     * @param int The width
     * @param int The height
     * @param string
     * @return string
     */
    public function show_image($code = null, $width = '16', $height = '10', $parameters = null)
    {
        if ( empty($code) ) {
            $code = $this->code;
        }

        $imagecode = strtolower(substr($code, 3));

        if ( !is_numeric($width) ) {
            $width = 16;
        }

        if ( !is_numeric($height) ) {
            $height = 10;
        }

        return image('images/worldflags/' . $imagecode . '.png', $this->languages[$code]['name'], $width, $height, $parameters);
    }
    
    /**
     * Import xml into database
     * 
     * @access public
     * @param unknown_type $xml_file
     * @param unknown_type $languages_id
     * @return boolean
     */
    public function import_xml($xml_file, $languages_id) {
        if ( file_exists($xml_file) ) {
            $info = simplexml_load_file($xml_file);

            //insert definitions
            foreach ($info->definitions->definition as $definition) {
                $entry = array(
                	'languages_id' => $languages_id,
                    'content_group' => (string) $definition->group,
                    'definition_key' => (string) $definition->key,
                    'definition_value' => (string) $definition->value);

                $this->ci->languages_model->insert_definition($entry);
            }
            
            unset($info);
        }
        
        return FALSE;
    }
}
// END Language Class

/* End of file Lang.php */
/* Location: ./system/core/Lang.php */
