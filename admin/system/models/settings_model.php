<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize, creative CMS
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.9.0
 */

// ------------------------------------------------------------------------

/**
 * Ionize, creative CMS Settings Model
 *
 * @package		Ionize
 * @subpackage	Models
 * @category	Admin settings
 * @author		Ionize Dev Team
 */

class Settings_Model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }


  /**
   * Get the settings
   * Don't retrieves the language depending settings
   *
   * @return	The settings array
   */
  function get_settings()
  {
    $settings = array();

    $query = $this->db
    ->select('configuration_key as key1, configuration_value as value1')
    ->from('configuration')
    ->get();
    
    foreach ($query->result() as $key => $row){
      $settings[$row->key1] = $row->value1;
    }
    
    return $settings;
  }
}
/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */