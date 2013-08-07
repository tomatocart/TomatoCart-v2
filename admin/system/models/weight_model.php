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

class Weight_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_rules()
  {
    $Qrules = $this->db
    ->select('r.weight_class_from_id, r.weight_class_to_id, r.weight_class_rule')
    ->from('weight_classes_rules r')
    ->join('weight_classes c', 'c.weight_class_id = r.weight_class_from_id')
    ->get();
    
    return $Qrules->result_array(); 
  }
  
  public function get_classes()
  {
    $Qclasses = $this->db
    ->select('weight_class_id, weight_class_key, weight_class_title')
    ->from('weight_classes')
    ->where('language_id', lang_id())
    ->get();
    
    return $Qclasses->result_array();
  }
  
  public function get_title($id)
  {
    $Qweight = $this->db
    ->select('weight_class_title')
    ->from('weight_classes')
    ->where(array('weight_class_id' => $id, 'language_id' => lang_id()))
    ->get();
    
    return $Qweight->row_array();
  }
}

/* End of file weight_model.php */
/* Location: ./system/models/weight_model.php */