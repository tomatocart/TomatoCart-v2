<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @license   http://www.tomatocart.com/doc-license
 * @link    http://www.tomatocart.com
 */

class Desktop_Settings_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Get the settings
   * Don't retrieves the language depending settings
   *
   * @return  The settings array
   */
  public function get_settings($user_name)
  {
    $Qsettings = $this->db
    ->select('user_settings')
    ->from ('administrators')
    ->where('user_name', $user_name)
    ->get();
    
    if ($Qsettings->num_rows() > 0) {
      $settings = $Qsettings->row_array();
      $settings = unserialize($settings['user_settings']);
      
      return $settings;
    }
    
    return FALSE;
  }
  
  public function save_settings($user_name, $data)
  {
    $Qsettings = $this->db
    ->select('user_settings')
    ->from('administrators')
    ->where('user_name', $user_name)
    ->get();
    
    if ($Qsettings->num_rows() > 0)
    {
      $settings = $Qsettings->row_array();
      $settings = unserialize($settings['user_settings']);
    }
    
    if (is_array($data) && !empty($settings['desktop']))
    {
      $settings['desktop'] = array_merge($settings['desktop'] ,$data);
    }
    else
    {
      $settings['desktop'] = $data;
    }
    
    $update_data = array('user_settings' => serialize($settings));
    $this->db->where('user_name', $user_name);
    $this->db->update('administrators', $update_data);
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    
    }
    
    return FALSE;
  }
}
/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */