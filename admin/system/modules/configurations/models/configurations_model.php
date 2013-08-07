<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

class Configurations_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_configurations($id)
  {
    $Qcfg = $this->db
    ->select('configuration_id, configuration_key, configuration_title, configuration_description, configuration_value, use_function, set_function')
    ->from('configuration')
    ->where('configuration_group_id', $id)
    ->order_by('sort_order')
    ->get();
    
    return $Qcfg->result_array();
  }
  
  public function save($id, $value)
  {
    $this->db->update('configuration', array('configuration_value' => $value) , array('configuration_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
}

/* End of file configuration_model.php */
/* Location: ./system/modules/configuration/models/configuration_model.php */