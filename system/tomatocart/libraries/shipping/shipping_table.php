<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
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
class TOC_Shipping_table extends TOC_Shipping_Module {

	var $code = 'table';

	/**
	 * Template Module Params
	 *
	 * @access private
	 * @var array
	 */
	var $params = array(
			array('name' => 'MODULE_SHIPPING_TABLE_STATUS',
					'title' => 'Enable Table Method', 'type' => 'combobox',
					'mode' => 'local', 'value' => 'True',
					'description' => 'Do you want to offer table rate shipping?',
					'values' => array(
							array('id' => 'True', 'text' => 'True'),
							array('id' => 'False', 'text' => 'False'))),
			array('name' => 'MODULE_SHIPPING_TABLE_COST',
					'title' => 'Shipping Table', 'type' => 'textfield',
					'value' => '25:8.50,50:5.50,10000:0.00',
					'description' => 'The shipping cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc'),
			array('name' => 'MODULE_SHIPPING_TABLE_HANDLING',
					'title' => 'Handling Cost', 'type' => 'numberfield',
					'value' => '0',
					'description' => 'The handling cost for all orders using this shipping method.'),
			array('name' => 'MODULE_SHIPPING_TABLE_MODE',
					'title' => 'Shipping Table  Mode', 
					'type' => 'combobox', 'mode'=>'local',
					'value' => 'weight',
					'description' => 'The shipping cost is based on the order total or the total weight of the items ordered.',
					'values' => array(
							array('id' => 'weight', 'text' => 'weight'),
							array('id' => 'price', 'text' => 'price'))),
			array('name' => 'MODULE_SHIPPING_TABLE_TAX_CLASS',
					'title' => 'Tax Class', 'type' => 'combobox',
					'mode' => 'remote', 'value' => '0',
					'description' => 'Use the following tax class on the shipping fee.',
					'action' => 'config/get_tax_class'),
			array('name' => 'MODULE_SHIPPING_TABLE_ZONE',
					'title' => 'Shipping Zone', 'type' => 'combobox',
					'mode' => 'remote', 'value' => '0',
					'description' => 'If a zone is selected, only enable this shipping method for that zone.',
					'action' => 'config/get_shipping_zone'),
			array('name' => 'MODULE_SHIPPING_TABLE_WEIGHT_UNIT',
					'title' => 'Module weight Unit', 'type' => 'combobox',
					'mode' => 'remote', 'value' => '0',
					'description' => 'What unit of weight does this shipping module use?.',
					'action' => 'config/get_weight_classes'),
			array('name' => 'MODULE_SHIPPING_TABLE_SORT_ORDER',
					'title' => 'Sort Order', 'type' => 'numberfield',
					'value' => '0', 'description' => 'Sort order of display.'));

	/**
	 * Constructor
	 *
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		// $this->icon = 'table.jpg';
		$this->title = lang('shipping_table_title');
		$this->description = lang('shipping_table_description');
		$this->status = (isset($this->config['MODULE_SHIPPING_TABLE_STATUS']) && ($this->config['MODULE_SHIPPING_TABLE_STATUS'] == 'True')) ? TRUE : FALSE;
		$this->sort_order = isset($this->config['MODULE_SHIPPING_TABLE_SORT_ORDER']) ? $this->config['MODULE_SHIPPING_TABLE_SORT_ORDER'] : NULL;
	}

	/**
	 * Initialize the shipping module
	 *
	 * @access public
	 */
	public function initialize() {

		$this->tax_class = $this->config['MODULE_SHIPPING_TABLE_TAX_CLASS'];

		if (($this->status === TRUE) && ((int) $this->config['MODULE_SHIPPING_TABLE_ZONE'] > 0)) {
			$this->ci->load->model('address_model');

			$zones = $this->ci->address_model->get_zone_id_via_geo_zone($this->ci->shopping_cart->get_shipping_address('country_id'), $this->config['MODULE_SHIPPING_TABLE_ZONE']);
		
            $check_flag = FALSE;
            if ($zones !== NULL)
            {
                foreach($zones as $zone_id)
                {
                    if ($zone_id < 1) {
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

	public function quote() {
		$this->ci->load->library('weight');
		
		if ($this->config['MODULE_SHIPPING_TABLE_MODE'] == 'price') 
		{
			$order_total = $this->ci->shopping_cart->get_sub_total();
		} 
		else 
		{
			$order_total = $this->ci->weight->convert($this->ci->shopping_cart->get_weight(), config('SHIPPING_WEIGHT_UNIT'), $this->config['MODULE_SHIPPING_TABLE_WEIGHT_UNIT']);
		}
		
		$table_cost = preg_split("/[:,]/", $this->config['MODULE_SHIPPING_TABLE_COST']);
		$size = sizeof($table_cost);
		
		for ($i = 0, $n = $size; $i < $n; $i += 2) 
		{
			if ($order_total <= $table_cost[$i]) 
			{
				$shipping = $table_cost[$i + 1];
				break;
			}
		}
		
		if ($this->config['MODULE_SHIPPING_TABLE_MODE'] == 'weight') 
		{
			$shipping = $shipping * $this->ci->shopping_cart->number_of_shipping_boxes();
		}

		$this->quotes = array('id' => $this->code, 
		                      'module' => $this->title,
				              'methods' => array(
                                  array('id' => $this->code,
                                		'title' => lang('shipping_table_method'),
                                		'cost' => $shipping)),
				              'tax_class_id' => $this->tax_class);

		if (!empty($this->icon))
			$this->quotes['icon'] = image_url('shipping/' . $this->icon, $this->title);

		return $this->quotes;
	}
}
?>
