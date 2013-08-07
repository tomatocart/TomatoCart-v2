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

  if (!function_exists('cfg_set_boolean_value'))
  {
    function cfg_set_boolean_value($select_array, $default, $key = null) 
    {
      $string = '';
  
      $select_array = explode(',', substr($select_array, 6, -1));
  
      $name = (!empty($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  
      $values = array();
      for ($i=0, $n=sizeof($select_array); $i<$n; $i++) 
      {
        $value = trim($select_array[$i]);
  
        if (strpos($value, '\'') !== false) 
        {
          $value = substr($value, 1, -1);
        } 
        else 
        {
          $value = (int)$value;
        }
  
        $select_array[$i] = $value;
  
        if ($value === -1) 
        {
          $value = lang('parameter_false');
        } 
        elseif ($value === 0) 
        {
          $value = lang('parameter_optional');
        } 
        elseif ($value === 1) 
        {
          $value = lang('parameter_true');
        }
  
        $values[] = array(
          'id' => $select_array[$i],
          'text' => $value
        );
      }
  
      $control = array();
      $control['name'] = $name;
      $control['type'] = 'combobox';
      $control['mode'] = 'local';
      $control['values'] = $values;
  
      return $control;
    }
  
  }
  
/* End of file cfg_set_boolean_value_helper.php */
/* Location: ./system/helpers/cfg_parameters/cfg_set_boolean_value_helper.php */