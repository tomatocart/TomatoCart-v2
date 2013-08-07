<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package   CodeIgniter
 * @author    ExpressionEngine Dev Team
 * @copyright Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license   http://codeigniter.com/user_guide/license.html
 * @link    http://codeigniter.com
 * @since   Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Language Class
 *
 * @package   CodeIgniter
 * @subpackage  Libraries
 * @category  Language
 * @author    ExpressionEngine Dev Team
 * @link    http://codeigniter.com/user_guide/libraries/language.html
 */
class Configuration {

  var $configuration = array();

  /**
   * Constructor
   *
   * @access  public
   */
  function __construct()
  {
    $CI =& get_instance();
    $CI->load->model('settings_model');
    $this->configuration = $CI->settings_model->get_settings();
    
    log_message('debug', "Language Class Initialized");
  }

  // --------------------------------------------------------------------

  /**
   * Fetch a single line of text from the language array
   *
   * @access  public
   * @param string  $line the language line
   * @return  string
   */
  function line($key = '')
  {
    $value = ($key == '' OR ! isset($this->configuration[$key])) ? FALSE : $this->configuration[$key];

    // Because killer robots like unicorns!
    if ($value === FALSE)
    {
      log_message('error', 'Could not find the language definition "' . $key . '"');
    }

    return $value;
  }
  
  function extract_all()
  {
    foreach($this->configuration as $key=>$value)
    {
      if (!defined($key))
      {
        define($key, $value);
      }
    }
  }
}
// END Language Class

/* End of file Lang.php */
/* Location: ./system/core/Lang.php */
  