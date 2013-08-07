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

  class TOC_Access_Configuration extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'configuration';
      $this->_group = 'configuration';
      $this->_icon = 'configure.png';
      $this->_sort_order = 300;
      
      $this->_title = lang('access_configuration_title');
      
      $this->_subgroups = array();
      
      $this->get_subgroups();
    }
    
    public function get_subgroups()
    {
      $ci = & get_instance();
      $ci->load->database();
      
      $Qgroups = $ci->db
      ->select('configuration_group_id, configuration_group_title')
      ->from('configuration_group')
      ->where('visible', 1)
      ->order_by('sort_order, configuration_group_title')
      ->get();
      
      if ($Qgroups->num_rows() > 0)
      {
        foreach($Qgroups->result_array() as $group)
        {
          $title = str_replace(' ', '_', strtolower($group['configuration_group_title']));
          $title = str_replace('/', '_', $title);
          $title = 'configuration_' . $title . '_title';

          $this->_subgroups[] = array('iconCls' => 'icon-configuration-win',
                                      'shortcutIconCls' => 'icon-configuration-shortcut',
                                      'title' => lang($title),
                                      'identifier' => 'configurations-' . $group['configuration_group_id'] . '-win',
                                      'params' => array('gID' => $group['configuration_group_id']));
        }
      }
    }
  }

/* End of file configurations.php */
/* Location: ./system/modules/access/configurations.php */