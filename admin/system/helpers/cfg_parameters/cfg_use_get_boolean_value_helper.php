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

  if (!function_exists('cfg_use_get_boolean_value'))
  {
    function cfg_use_get_boolean_value($string) 
    {
      switch ($string) 
      {
        case -1:
        case '-1':
          return lang('parameter_false');
          break;
  
        case 0:
        case '0':
          return lang('parameter_optional');
          break;
  
        case 1:
        case '1':
          return lang('parameter_true');
          break;
  
        default:
          return $string;
          break;
      }
    }
  }

/* End of file cfg_use_get_boolean_value_helper.php */
/* Location: ./system/helpers/cfg_parameters/cfg_use_get_boolean_value_helper.php */
