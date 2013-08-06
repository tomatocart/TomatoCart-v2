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
 * Form Validation Class
 *
 * @package   CodeIgniter
 * @subpackage  Libraries
 * @category  Validation
 * @author    ExpressionEngine Dev Team
 * @link    http://codeigniter.com/user_guide/libraries/form_validation.html
 */
class TOC_Form_Validation {

  var $CI;
  var $_field_data      = array();
  var $_config_rules      = array();
  var $_error_array     = array();
  var $_error_messages    = array();
  var $_error_prefix      = '<li>';
  var $_error_suffix      = '</li>';
  var $error_string     = '';
  var $_safe_form_data    = FALSE;


  /**
   * Constructor
   */
  public function __construct()
  {
    $this->CI =& get_instance();

    // Automatically load the form helper
    $this->CI->load->helper('form');

    // Set the character encoding in MB.
    if (function_exists('mb_internal_encoding'))
    {
      mb_internal_encoding($this->CI->config->item('charset'));
    }

    log_message('debug', "TOC Form Validation Class Initialized");
  }

  // --------------------------------------------------------------------

  /**
   * Get Error Message
   *
   * Gets the error message associated with a particular field
   *
   * @access  public
   * @param string  the field name
   * @return  void
   */
  function error($field = '', $prefix = '', $suffix = '')
  {
    if ( ! isset($this->_field_data[$field]['error']) OR $this->_field_data[$field]['error'] == '')
    {
      return '';
    }

    if ($prefix == '')
    {
      $prefix = $this->_error_prefix;
    }

    if ($suffix == '')
    {
      $suffix = $this->_error_suffix;
    }

    return $prefix.$this->_field_data[$field]['error'].$suffix;
  }

  // --------------------------------------------------------------------

  /**
   * Error String
   *
   * Returns the error messages as a string, wrapped in the error delimiters
   *
   * @access  public
   * @param string
   * @param string
   * @return  str
   */
  function error_string($prefix = '', $suffix = '')
  {
    // No errrors, validation passes!
    if (count($this->_error_array) === 0)
    {
      return '';
    }

    if ($prefix == '')
    {
      $prefix = $this->_error_prefix;
    }

    if ($suffix == '')
    {
      $suffix = $this->_error_suffix;
    }

    // Generate the error string
    $str = '<div class="messageStack"><ul>';
    foreach ($this->_error_array as $val)
    {
      if ($val != '')
      {
        $str .= $prefix.$val.$suffix."\n";
      }
    }
    $str .= '</ul></div>';

    return $str;
  }

  // --------------------------------------------------------------------

  /**
   * Set Rules
   *
   * This function takes an array of field names and validation
   * rules as input, validates the info, and stores it
   *
   * @access  public
   * @param mixed
   * @param string
   * @return  void
   */
  function set_rule($field, $rule, $error)
  {
    // No reason to set rules if we have no POST data
    if (count($_POST) == 0)
    {
      return $this;
    }

    // No fields? Nothing to do...
    if ( ! is_string($field) OR ! is_string($rule) OR $field == '')
    {
      return $this;
    }

    // Build our master array
    $this->_field_data[] = array(
      'field'         => $field,
      'rule'          => $rule,
      'postdata'      => NULL,
      'error'         => $error
    );

    return $this;
  }

  // --------------------------------------------------------------------

  /**
   * Set The Error Delimiter
   *
   * Permits a prefix/suffix to be added to each error message
   *
   * @access  public
   * @param string
   * @param string
   * @return  void
   */
  function set_error_delimiters($prefix = '<p>', $suffix = '</p>')
  {
    $this->_error_prefix = $prefix;
    $this->_error_suffix = $suffix;

    return $this;
  }

  // --------------------------------------------------------------------

  /**
   * Run the Validator
   *
   * This function does all the work.
   *
   * @access  public
   * @return  bool
   */
  function run($obj)
  {
    // Do we even have any data to process? Mm?
    if (count($_POST) == 0)
    {
      return FALSE;
    }

    if (isset($obj) && is_object($obj))
    {
      $this->obj = $obj;
    }

    // Cycle through the rules for each field, match the
    // corresponding $_POST item and test for errors
    foreach ($this->_field_data as $id => $field)
    {
      // Fetch the data from the corresponding $_POST array and cache it in the _field_data array.

      if (isset($_POST[$field['field']]) AND $_POST[$field['field']] != "")
      {
        $this->_field_data[$id]['postdata'] = $_POST[$field['field']];
      }

      $this->_execute($field, $field['rule'], $this->_field_data[$id]['postdata']);
    }

    // Did we end up with any errors?
    $total_errors = count($this->_error_array);

    if ($total_errors > 0)
    {
      $this->_safe_form_data = TRUE;
    }

    // No errors, validation passes!
    if ($total_errors == 0)
    {
      return TRUE;
    }

    // Validation fails
    return FALSE;
  }

  // --------------------------------------------------------------------

  /**
   * Executes the Validation routines
   *
   * @access  private
   * @param array
   * @param array
   * @param mixed
   * @param integer
   * @return  mixed
   */
  function _execute($field, $rule, $postdata = NULL)
  {
    // --------------------------------------------------------------------

    // Is the rule a callback?
    $callback = FALSE;
    if (substr($rule, 0, 9) == 'callback_')
    {
      $rule = substr($rule, 9);
      $callback = TRUE;
    }

    // Strip the parameter (if exists) from the rule
    // Rules can contain a parameter: max_length[5]
    $param = FALSE;
    if (preg_match("/(.*?)\[(.*)\]/", $rule, $match))
    {
      $rule = $match[1];
      $param  = $match[2];
    }

    // Call the function that corresponds to the rule
    if ($callback === TRUE)
    {
      if ( ! method_exists($this->obj, $rule))
      {
        return;
      }

      // Run the function and grab the result
      $result = $this->obj->$rule($postdata, $param);

      // Re-assign the result to the master data array
      $this->_field_data[$field['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
    } else
    {
      // --------------------------------------------------------------------

      if ( ! method_exists($this, $rule))
      {
        // If our own wrapper function doesn't exist we see if a native PHP function does.
        // Users can use any native PHP function call that has one param.
        if (function_exists($rule))
        {
          $result = $rule($postdata);

          $this->_field_data[$field['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
        }
        else
        {
          log_message('debug', "Unable to find validation rule: ".$rule);
        }

        return;
      }

      $result = $this->$rule($postdata, $param);

      $this->_field_data[$field['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
    }

    // Did the rule test negatively? If so, grab the error.
    if ($result === FALSE)
    {
      $this->_error_array[] = $field['error'];

      return;
    }
  }


  // --------------------------------------------------------------------

  /**
   * Get the value from a form
   *
   * Permits you to repopulate a form field with the value it was submitted
   * with, or, if that value doesn't exist, with the default
   *
   * @access  public
   * @param string  the field name
   * @param string
   * @return  void
   */
  function set_value($field = '', $default = '')
  {
    if ( ! isset($this->_field_data[$field]))
    {
      return $default;
    }

    // If the data is an array output them one at a time.
    //   E.g: form_input('name[]', set_value('name[]');
    if (is_array($this->_field_data[$field]['postdata']))
    {
      return array_shift($this->_field_data[$field]['postdata']);
    }

    return $this->_field_data[$field]['postdata'];
  }

  // --------------------------------------------------------------------

  /**
   * Set Select
   *
   * Enables pull-down lists to be set to the value the user
   * selected in the event of an error
   *
   * @access  public
   * @param string
   * @param string
   * @return  string
   */
  function set_select($field = '', $value = '', $default = FALSE)
  {
    if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata']))
    {
      if ($default === TRUE AND count($this->_field_data) === 0)
      {
        return ' selected="selected"';
      }
      return '';
    }

    $field = $this->_field_data[$field]['postdata'];

    if (is_array($field))
    {
      if ( ! in_array($value, $field))
      {
        return '';
      }
    }
    else
    {
      if (($field == '' OR $value == '') OR ($field != $value))
      {
        return '';
      }
    }

    return ' selected="selected"';
  }

  // --------------------------------------------------------------------

  /**
   * Set Radio
   *
   * Enables radio buttons to be set to the value the user
   * selected in the event of an error
   *
   * @access  public
   * @param string
   * @param string
   * @return  string
   */
  function set_radio($field = '', $value = '', $default = FALSE)
  {
    if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata']))
    {
      if ($default === TRUE AND count($this->_field_data) === 0)
      {
        return ' checked="checked"';
      }
      return '';
    }

    $field = $this->_field_data[$field]['postdata'];

    if (is_array($field))
    {
      if ( ! in_array($value, $field))
      {
        return '';
      }
    }
    else
    {
      if (($field == '' OR $value == '') OR ($field != $value))
      {
        return '';
      }
    }

    return ' checked="checked"';
  }

  // --------------------------------------------------------------------

  /**
   * Set Checkbox
   *
   * Enables checkboxes to be set to the value the user
   * selected in the event of an error
   *
   * @access  public
   * @param string
   * @param string
   * @return  string
   */
  function set_checkbox($field = '', $value = '', $default = FALSE)
  {
    if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata']))
    {
      if ($default === TRUE AND count($this->_field_data) === 0)
      {
        return ' checked="checked"';
      }
      return '';
    }

    $field = $this->_field_data[$field]['postdata'];

    if (is_array($field))
    {
      if ( ! in_array($value, $field))
      {
        return '';
      }
    }
    else
    {
      if (($field == '' OR $value == '') OR ($field != $value))
      {
        return '';
      }
    }

    return ' checked="checked"';
  }

  // --------------------------------------------------------------------

  /**
   * Required
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function required($str)
  {
    if ( ! is_array($str))
    {
      return (trim($str) == '') ? FALSE : TRUE;
    }
    else
    {
      return ( ! empty($str));
    }
  }

  // --------------------------------------------------------------------

  /**
   * Performs a Regular Expression match test.
   *
   * @access  public
   * @param string
   * @param regex
   * @return  bool
   */
  function regex_match($str, $regex)
  {
    if ( ! preg_match($regex, $str))
    {
      return FALSE;
    }

    return TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Match one field to another
   *
   * @access  public
   * @param string
   * @param field
   * @return  bool
   */
  function matches($str, $field)
  {
    if ( ! isset($_POST[$field]))
    {
      return FALSE;
    }

    $field = $_POST[$field];

    return ($str !== $field) ? FALSE : TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Minimum Length
   *
   * @access  public
   * @param string
   * @param value
   * @return  bool
   */
  function min_length($str, $val)
  {
    if (preg_match("/[^0-9]/", $val))
    {
      return FALSE;
    }

    if (function_exists('mb_strlen'))
    {
      return (mb_strlen($str) < $val) ? FALSE : TRUE;
    }

    return (strlen($str) < $val) ? FALSE : TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Max Length
   *
   * @access  public
   * @param string
   * @param value
   * @return  bool
   */
  function max_length($str, $val)
  {
    if (preg_match("/[^0-9]/", $val))
    {
      return FALSE;
    }

    if (function_exists('mb_strlen'))
    {
      return (mb_strlen($str) > $val) ? FALSE : TRUE;
    }

    return (strlen($str) > $val) ? FALSE : TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Exact Length
   *
   * @access  public
   * @param string
   * @param value
   * @return  bool
   */
  function exact_length($str, $val)
  {
    if (preg_match("/[^0-9]/", $val))
    {
      return FALSE;
    }

    if (function_exists('mb_strlen'))
    {
      return (mb_strlen($str) != $val) ? FALSE : TRUE;
    }

    return (strlen($str) != $val) ? FALSE : TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Valid Email
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function valid_email($str)
  {
    return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Valid Emails
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function valid_emails($str)
  {
    if (strpos($str, ',') === FALSE)
    {
      return $this->valid_email(trim($str));
    }

    foreach (explode(',', $str) as $email)
    {
      if (trim($email) != '' && $this->valid_email(trim($email)) === FALSE)
      {
        return FALSE;
      }
    }

    return TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Validate IP Address
   *
   * @access  public
   * @param string
   * @return  string
   */
  function valid_ip($ip)
  {
    return $this->CI->input->valid_ip($ip);
  }

  // --------------------------------------------------------------------

  /**
   * Alpha
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function alpha($str)
  {
    return ( ! preg_match("/^([a-z])+$/i", $str)) ? FALSE : TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Alpha-numeric
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function alpha_numeric($str)
  {
    return ( ! preg_match("/^([a-z0-9])+$/i", $str)) ? FALSE : TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Alpha-numeric with underscores and dashes
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function alpha_dash($str)
  {
    return ( ! preg_match("/^([-a-z0-9_-])+$/i", $str)) ? FALSE : TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Numeric
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function numeric($str)
  {
    return (bool)preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $str);

  }

  // --------------------------------------------------------------------

  /**
   * Is Numeric
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function is_numeric($str)
  {
    return ( ! is_numeric($str)) ? FALSE : TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Integer
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function integer($str)
  {
    return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
  }

  // --------------------------------------------------------------------

  /**
   * Decimal number
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function decimal($str)
  {
    return (bool) preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $str);
  }

  // --------------------------------------------------------------------

  /**
   * Greather than
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function greater_than($str, $min)
  {
    if ( ! is_numeric($str))
    {
      return FALSE;
    }
    return $str > $min;
  }

  // --------------------------------------------------------------------

  /**
   * Less than
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function less_than($str, $max)
  {
    if ( ! is_numeric($str))
    {
      return FALSE;
    }
    return $str < $max;
  }

  // --------------------------------------------------------------------

  /**
   * Is a Natural number (0,1,2,3, etc.)
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function is_natural($str)
  {
    return (bool) preg_match( '/^[0-9]+$/', $str);
  }

  // --------------------------------------------------------------------

  /**
   * Is a Natural number, but not a zero (1,2,3, etc.)
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function is_natural_no_zero($str)
  {
    if ( ! preg_match( '/^[0-9]+$/', $str))
    {
      return FALSE;
    }

    if ($str == 0)
    {
      return FALSE;
    }

    return TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Valid Base64
   *
   * Tests a string for characters outside of the Base64 alphabet
   * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function valid_base64($str)
  {
    return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
  }

  // --------------------------------------------------------------------

  /**
   * Prep data for form
   *
   * This function allows HTML to be safely shown in a form.
   * Special characters are converted.
   *
   * @access  public
   * @param string
   * @return  string
   */
  function prep_for_form($data = '')
  {
    if (is_array($data))
    {
      foreach ($data as $key => $val)
      {
        $data[$key] = $this->prep_for_form($val);
      }

      return $data;
    }

    if ($this->_safe_form_data == FALSE OR $data === '')
    {
      return $data;
    }

    return str_replace(array("'", '"', '<', '>'), array("&#39;", "&quot;", '&lt;', '&gt;'), stripslashes($data));
  }

  // --------------------------------------------------------------------

  /**
   * Prep URL
   *
   * @access  public
   * @param string
   * @return  string
   */
  function prep_url($str = '')
  {
    if ($str == 'http://' OR $str == '')
    {
      return '';
    }

    if (substr($str, 0, 7) != 'http://' && substr($str, 0, 8) != 'https://')
    {
      $str = 'http://'.$str;
    }

    return $str;
  }

  // --------------------------------------------------------------------

  /**
   * Strip Image Tags
   *
   * @access  public
   * @param string
   * @return  string
   */
  function strip_image_tags($str)
  {
    return $this->CI->input->strip_image_tags($str);
  }

  // --------------------------------------------------------------------

  /**
   * XSS Clean
   *
   * @access  public
   * @param string
   * @return  string
   */
  function xss_clean($str)
  {
    return $this->CI->security->xss_clean($str);
  }

  // --------------------------------------------------------------------

  /**
   * Convert PHP tags to entities
   *
   * @access  public
   * @param string
   * @return  string
   */
  function encode_php_tags($str)
  {
    return str_replace(array('<?php', '<?PHP', '<?', '?>'), array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
  }

}
// END Form Validation Class

/* End of file Form_validation.php */
/* Location: ./system/libraries/Form_validation.php */
