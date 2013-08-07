<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

require_once 'shipping_module.php';

/**
 * Free Shipping -- Shipping Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Shipping_item extends TOC_Shipping_Module
{

    var $code = 'item';
    
    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
        array('name' => 'MODULE_SHIPPING_ITEM_STATUS',
              'title' => 'Enable Item Shipping', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'True',
              'description' => 'Do you want to offer per item rate shipping?',
              'values' => array(
                  array('id' => 'True', 'text' => 'True'),
                  array('id' => 'False', 'text' => 'False'))),
        array('name' => 'MODULE_SHIPPING_ITEM_COST',
              'title' => 'Shipping Cost', 
              'type' => 'numberfield',
              'value' => '2.50',
              'description' => 'The shipping cost will be multiplied by the number of items in an order that uses this shipping method.'),
        array('name' => 'MODULE_SHIPPING_ITEM_HANDLING',
              'title' => 'Handling Fee', 
              'type' => 'numberfield',
              'value' => '0',
              'description' => 'Handling fee for this shipping method.'),
        array('name' => 'MODULE_SHIPPING_ITEM_TAX_CLASS',
              'title' => 'Tax Class', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'Use the following tax class on the shipping fee.',
              'action' => 'config/get_tax_class'),
        array('name' => 'MODULE_SHIPPING_ITEM_ZONE',
              'title' => 'Shipping Zone', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'If a zone is selected, only enable this shipping method for that zone.',
              'action' => 'config/get_shipping_zone'),
        array('name' => 'MODULE_SHIPPING_ITEM_SORT_ORDER',
                        'title' => 'Sort Order', 
                        'type' => 'numberfield',
                        'value' => '0',
                        'description' => 'Sort order of display.'));

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct() {
        parent::__construct();

        //initialize
        $this->icon = 'item.jpg';
        $this->title = lang('shipping_item_title');
        $this->description = lang('shipping_item_description');
        $this->status = (isset($this->config['MODULE_SHIPPING_ITEM_STATUS']) && ($this->config['MODULE_SHIPPING_ITEM_STATUS'] == 'True')) ? TRUE : FALSE;
        $this->sort_order = isset($this->config['MODULE_SHIPPING_ITEM_SORT_ORDER']) ? $this->config['MODULE_SHIPPING_ITEM_SORT_ORDER'] : null;
    }

    /**
     * Initialize the shipping module
     *
     * @access public
     */
    public function initialize() {

        $this->tax_class = $this->config['MODULE_SHIPPING_ITEM_TAX_CLASS'];

        if ( ($this->status === TRUE) && ((int)$this->config['MODULE_SHIPPING_ITEM_ZONE'] > 0) ) 
        {
            $this->ci->load->model('address_model');

            $zones = $this->ci->address_model->get_zone_id_via_geo_zone($this->ci->shopping_cart->get_shipping_address('country_id'), $this->config['MODULE_SHIPPING_ITEM_ZONE']);

            $check_flag = FALSE;
            if ($zones !== NULL)
            {
                foreach($zones as $zone_id)
                {
                    if ($zone_id < 1)
                    {
                        $check_flag = TRUE;
                        break;
                    }
                    elseif ($zone_id == $this->ci->shopping_cart->get_shipping_address('zone_id'))
                    {
                        $check_flag = TRUE;
                        break;
                    }
                }
            }

            if ($check_flag == FALSE)
            {
                $this->status = FALSE;
            }
        }
    }

    /**
     * Calculate the shipping module quote
     *
     * @access public
     */
    public function quote() {
        $this->quotes = array('id' => $this->code,
                              'module' => $this->title,
                              'methods' => array(array('id' => $this->code,
                                                       'title' => lang('shipping_item_method'),
                                                       'cost' => ($this->config['MODULE_SHIPPING_ITEM_COST'] * $this->ci->shopping_cart->number_of_physical_items()) + $this->config['MODULE_SHIPPING_ITEM_HANDLING'])),
                              'tax_class_id' => $this->tax_class);

        if (!empty($this->icon)) $this->quotes['icon'] = image_url('images/shipping/' . $this->icon, $this->title);

        return $this->quotes;
    }
}
?>
