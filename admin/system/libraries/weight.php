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

  class TOC_Weight
  {
    private $_ci;
    private $precision;
    private $weight_classes = array();
    
    public function __construct($precision = '2')
    {
      $this->_ci = & get_instance();
      $this->precision = $precision;
      
      $this->_ci->load->model('weight_model');
      $this->_prepare_rules();
    }
    
    private function _prepare_rules()
    {
      $rules = $this->_ci->weight_model->get_rules();
      
      if (!empty($rules))
      {
        foreach($rules as $rule)
        {
          $this->weight_classes[$rule['weight_class_from_id']][$rule['weight_class_to_id']] = $rule['weight_class_rule'];
        }
      }
      
      $classes = $this->_ci->weight_model->get_classes();
      
      if (!empty($classes))
      {
        foreach($classes as $class)
        {
          $this->weight_classes[$class['weight_class_id']]['key'] = $class['weight_class_key'];
          $this->weight_classes[$class['weight_class_id']]['title'] = $class['weight_class_title'];
        }
      }
    }
    
    public function get_classes()
    {
      $classes = $this->_ci->weight_model->get_classes();
      
      $result = array();
      if (!empty($classes))
      {
        foreach($classes as $class)
        {
          $result[] = array('id' => $class['weight_class_id'], 'title' => $class['weight_class_title']);
        }
      }
      
      return $result;
    }
    
    public function get_title($id)
    {
      $weight = $this->_ci->weight_model->get_title($id);
      
      if (isset($weight['weight_class_title']) && !empty($weight['weight_class_title']))
      {
        return $weight['weight_class_title'];
      }
      
      return FALSE;
    }
  }

/* End of file weight.php */
/* Location: ./system/library/weight.php */