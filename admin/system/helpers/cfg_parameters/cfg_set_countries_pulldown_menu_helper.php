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

  if (!function_exists('cfg_set_countries_pulldown_menu'))
  {
    function cfg_set_countries_pulldown_menu($default, $key = null) 
    {
      $name = (!empty($key) ? 'configuration[' . $key . ']' : 'configuration_value');
     
      $control = array();
      $control['name'] = $name;
      $control['type'] = 'combobox';
      $control['mode'] = 'remote';
      $control['module'] = 'configurations';
      $control['action'] = 'get_countries';
  
      return $control;
    }
  }
  
/* End of file cfg_set_countries_pulldown_menu_helper.php */
/* Location: ./system/helpers/cfg_parameters/cfg_set_countries_pulldown_menu_helper.php */
