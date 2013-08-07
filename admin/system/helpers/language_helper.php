<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart, creative CMS
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.9.0
 *
 */

if( ! function_exists('show_product_image'))
{
  function show_product_image($image, $group = 'thumbnails')
  {
    return 'images/products/' . $group . '/' . $image;
  }
}

/**
 * Fetches a language variable and optionally outputs a form label
 *
 * @access	public
 * @param	string	the language line
 * @param	string	the id of the form element
 * @return	string
 */
if( ! function_exists('lang'))
{
  function lang($key)
  {
    $CI =& get_instance();
    $line = $CI->lang->line($key);
    
    if (empty($line)) {
      return $key;
    }

    return $line;
  }
}

if ( ! function_exists('lang_get_text_direction'))
{
  function lang_get_text_direction() {
    $CI =& get_instance();
    
    $text_direction = $CI->lang->get_text_direction();
    
    return $text_direction;
  }

}

if ( ! function_exists('lang_get_code'))
{
  function lang_get_code() {
    $CI =& get_instance();
    
    $lang_code = $CI->lang->get_code();
    
    return $lang_code;
  }
}

if ( ! function_exists('lang_id'))
{
  function lang_id() {
    $CI =& get_instance();
    
    $lang_id = $CI->lang->get_id();
    
    return $lang_id;
  }
}

if( ! function_exists('lang_get_all'))
{
  function lang_get_all()
  {
    $CI =& get_instance();
    $line = $CI->lang->get_all();
    
    if (empty($line)) {
      return $key;
    }

    return $line;
  }
}

if( ! function_exists('config'))
{
  function config($key)
  {
    $CI =& get_instance();
    $line = $CI->configuration->line($key);

    return $line;
  }
}

if( ! function_exists('show_image'))
{
  function show_image($code, $width = '16', $height = '10', $parameters = null)
  {
    $CI =& get_instance();
    $image = $CI->lang->show_image($code, $width, $height, $parameters);
    
    return $image;
  }
}

if( ! function_exists('encrypt_string'))
{
  function encrypt_string($plain)
  {
    $password = '';

    for ($i=0; $i<10; $i++)
    {
      $password .= mt_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
  }
}

/**
 * Generate a product ID string value containing its product variants combinations
 *
 * @param string $id The product ID
 * @param array $params An array of product variants
 * @access public
 */
if( ! function_exists('get_product_id_string'))
{
  function get_product_id_string($id, $params) {
    $string = (int)$id;

    if (is_array($params) && !empty($params)) {
      $variants_check = true;
      $variants_ids = array();

      //lei:sort the variant by the options id
      ksort($params);

      foreach ($params as $group => $value) {
        if (is_numeric($group) && is_numeric($value)) {
          $variants_ids[] = (int)$group . ':' . (int)$value;
        } else {
          $variants_check = false;
          break;
        }
      }

      if ($variants_check === true) {
        $string .= '#' . implode(';', $variants_ids);
      }
    }

    return $string;
  }
}
/* End of file language_helper.php */
/* Location: ./application/helpers/MY_language_helper.php */