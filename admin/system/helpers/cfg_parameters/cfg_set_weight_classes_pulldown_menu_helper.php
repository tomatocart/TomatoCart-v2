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

  if (!function_exists('cfg_set_weight_classes_pulldown_menu'))
  {
    
    function cfg_set_weight_classes_pulldown_menu($default, $key = null)
    {
      $ci = &get_instance();
      $ci->load->library('weight');
      
      $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';
      
      $weight_class_array = array();
      foreach($ci->weight->get_classes() as $class)
      {
        $weight_class_array[] = array('id' => $class['id'],
                                      'text' => $class['title']);
      }
      
      $control = array();
      $control['name'] = $name;
      $control['type'] = 'combobox';
      $control['mode'] = 'local';
      $control['values'] = $weight_class_array;
      
      return $control;
    }
  }
  
/* End of file cfg_set_weight_classes_pulldown_menu.php */
/* Location: ./system/helpers/cfg_parameters/cfg_set_weight_classes_pulldown_menu.php */
  
