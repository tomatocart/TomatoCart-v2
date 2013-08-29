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
 * Zone Shipping -- Shipping Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

/**
 * USAGE
  By default, the module comes with support for 1 zone.  This can be
  easily changed by editing the line below in the zones constructor
  that defines $this->num_zones.

  Next, you will want to activate the module by going to the Admin screen,
  clicking on Modules, then clicking on Shipping.  A list of all shipping
  modules should appear.  Click on the green dot next to the one labeled
  zones.php.  A list of settings will appear to the right.  Click on the
  Edit button.

  PLEASE NOTE THAT YOU WILL LOSE YOUR CURRENT SHIPPING RATES AND OTHER
  SETTINGS IF YOU TURN OFF THIS SHIPPING METHOD.  Make sure you keep a
  backup of your shipping settings somewhere at all times.

  If you want an additional handling charge applied to orders that use this
  method, set the Handling Fee field.

  Next, you will need to define which countries are in each zone.  Determining
  this might take some time and effort.  You should group a set of countries
  that has similar shipping charges for the same weight.  For instance, when
  shipping from the US, the countries of Japan, Australia, New Zealand, and
  Singapore have similar shipping rates.  As an example, one of my customers
  is using this set of zones:
    1: USA
    2: Canada
    3: Austria, Belgium, Great Britain, France, Germany, Greenland, Iceland,
       Ireland, Italy, Norway, Holland/Netherlands, Denmark, Poland, Spain,
       Sweden, Switzerland, Finland, Portugal, Israel, Greece
    4: Japan, Australia, New Zealand, Singapore
    5: Taiwan, China, Hong Kong

  When you enter these country lists, enter them into the Zone X Countries
  fields, where "X" is the number of the zone.  They should be entered as
  two character ISO country codes in all capital letters.  They should be
  separated by commas with no spaces or other punctuation. For example:
    1: US
    2: CA
    3: AT,BE,GB,FR,DE,GL,IS,IE,IT,NO,NL,DK,PL,ES,SE,CH,FI,PT,IL,GR
    4: JP,AU,NZ,SG
    5: TW,CN,HK

  Now you need to set up the shipping rate tables for each zone.  Again,
  some time and effort will go into setting the appropriate rates.  You
  will define a set of weight ranges and the shipping price for each
  range.  For instance, you might want an order than weighs more than 0
  and less than or equal to 3 to cost 5.50 to ship to a certain zone.
  This would be defined by this:  3:5.5

  You should combine a bunch of these rates together in a comma delimited
  list and enter them into the "Zone X Shipping Table" fields where "X"
  is the zone number.  For example, this might be used for Zone 1:
    1:3.5,2:3.95,3:5.2,4:6.45,5:7.7,6:10.4,7:11.85, 8:13.3,9:14.75,10:16.2,11:17.65,
    12:19.1,13:20.55,14:22,15:23.45

  The above example includes weights over 0 and up to 15.  Note that
  units are not specified in this explanation since they should be
  specific to your locale.

  CAVEATS
  At this time, it does not deal with weights that are above the highest amount
  defined.  This will probably be the next area to be improved with the
  module.  For now, you could have one last very high range with a very
  high shipping rate to discourage orders of that magnitude.  For
  instance:  999:1000

  If you want to be able to ship to any country in the world, you will
  need to enter every country code into the Country fields. For most
  shops, you will not want to enter every country.  This is often
  because of too much fraud from certain places. If a country is not
  listed, then the module will add a $0.00 shipping charge and will
  indicate that shipping is not available to that destination.
  PLEASE NOTE THAT THE ORDER CAN STILL BE COMPLETED AND PROCESSED!

  It appears that the osC shipping system automatically rounds the
  shipping weight up to the nearest whole unit.  This makes it more
  difficult to design precise shipping tables.  If you want to, you
  can hack the shipping.php file to get rid of the rounding.

  Lastly, there is a limit of 255 characters on each of the Zone
  Shipping Tables and Zone Countries.
 *
 */
class TOC_Shipping_zones extends TOC_Shipping_Module {

	var $code = 'zones';
	var $numzones = 1;
	/**
	 * Template Module Params
	 *
	 * @access private
	 * @var array
	 */
	var $params = array(
			array('name' => 'MODULE_SHIPPING_ZONES_STATUS',
					'title' => 'Enable Zones Method', 'type' => 'combobox',
					'mode' => 'local', 'value' => 'True',
					'description' => 'Do you want to offer zone rate shipping?',
					'values' => array(array('id' => 'True', 'text' => 'True'),
							array('id' => 'False', 'text' => 'False'))),
			array('name' => 'MODULE_SHIPPING_ZONES_HANDLING',
					'title' => 'Handling Cost', 'type' => 'numberfield',
					'value' => '0',
					'description' => 'The handling cost for all orders using this shipping method.'),
			array('name' => 'MODULE_SHIPPING_ZONES_TAX_CLASS',
					'title' => 'Tax Class', 'type' => 'combobox',
					'mode' => 'remote', 'value' => '0',
					'description' => 'Use the following tax class on the shipping fee.',
					'action' => 'config/get_tax_class'),
			array('name' => 'MODULE_SHIPPING_ZONES_WEIGHT_UNIT',
					'title' => 'Module weight Unit', 'type' => 'combobox',
					'mode' => 'remote', 'value' => '0',
					'description' => 'What unit of weight does this shipping module use?.',
					'action' => 'config/get_weight_classes'),
			array('name' => 'MODULE_SHIPPING_ZONES_SORT_ORDER',
					'title' => 'Sort Order', 'type' => 'numberfield',
					'value' => '0', 'description' => 'Sort order of display.'));

	public function __construct() {
		parent::__construct();

		// $this->icon = 'zones.jpg';
		$this->title = lang('shipping_zones_title');
		$this->description = lang('shipping_zones_description');
		$this->status = (isset($this->config['MODULE_SHIPPING_ZONES_STATUS'])
				&& ($this->config['MODULE_SHIPPING_ZONES_STATUS'] == 'True')) ? TRUE
				: FALSE;
		$this->sort_order = isset(
				$this->config['MODULE_SHIPPING_ZONES_SORT_ORDER']) ? $this
						->config['MODULE_SHIPPING_ZONES_SORT_ORDER'] : null;
		$this->_construct();
		
	}
	
	protected function _construct() {
		for($i = 1; $i <= $this->numzones ; $i++) {
			$default_countries = '';
			
			if ($i == 1) {
				$default_countries = 'US,CA';
			}
			$this->params[] = array('name' => 'MODULE_SHIPPING_ZONES_COUNTRIES_' . $i,
					'title' => 'Zone ' . $i . ' Countries', 'type' => 'textfield',
					'value' => $default_countries,
					'description' => 'Comma separated list of two character ISO country codes that are part of Zone ' . $i . '.');
			$this->params[] = array('name' => 'MODULE_SHIPPING_ZONES_COST_' . $i,
					'title' => 'Zone ' . $i . ' Shipping Table ', 'type' => 'textfield',
					'value' => '3:8.50,7:10.50,99:20.00',
					'description' => 'Shipping rates to Zone ' . $i . ' destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone ' . $i . ' destinations');
			$this->params[] = array('name' => 'MODULE_SHIPPING_ZONES_HANDLING_' . $i,
					'title' => 'Zone ' . $i . ' Handling fee', 'type' => 'numberfield',
					'value' => 0,
					'description' => 'Handling Fee for this shipping zone ' . $i . '.');
		}
	}

	/**
	 * Initialize the shipping module
	 *
	 * @access public
	 */
	public function initialize() {

		$this->tax_class = $this->config['MODULE_SHIPPING_ZONES_TAX_CLASS'];		
	}

	public function quote() {
		$dest_country = $this->ci->shopping_cart->get_shipping_address('country_iso_code_2');
		$dest_zone = 0;
		$error = false;
		
		$this->ci->load->library('weight');
		
		$shipping_weight = $this->ci->weight->convert($this->ci->shopping_cart->get_weight(),
							config('SHIPPING_WEIGHT_UNIT'),
							$this->config['MODULE_SHIPPING_ZONES_WEIGHT_UNIT']);
		
		for ($i = 1; $i <= $this->numzones; $i++) {
			$countries_table = $this->config['MODULE_SHIPPING_ZONES_COUNTRIES_' . $i];
			$country_zones = split("[,]", $countries_table);
			if (in_array($dest_country, $country_zones)) {
				$dest_zone = $i;
				break;
			}
		}
		if ($dest_zone == 0) {
			$error = true;
		} else {
			$shipping = -1;
			$zones_cost = $this->config['MODULE_SHIPPING_ZONES_COST_' . $dest_zone];
			
			$zones_table = split("[:,]" , $zones_cost);
			$size = sizeof($zones_table);
			for ($i=0; $i<$size; $i+=2) {
				if ($shipping_weight <= $zones_table[$i]) {
					$shipping = $zones_table[$i+1];
					$shipping_method = lang('shipping_zones_method') . ' ' . $dest_country . ' : ' . 
					$this->ci->weight->display($this->ci->shopping_cart->get_weight(), $this->config['MODULE_SHIPPING_ZONES_WEIGHT_UNIT']);
					break;
				}
			}
			
			if ($shipping == -1) {
				$shipping_cost = 0;
				$shipping_method = lang('shipping_zones_undefined_rate');
			} else {
				$shipping_cost = ($shipping * $this->ci->shopping_cart->$shipping_weight()) + $this->config['MODULE_SHIPPING_ZONES_HANDLING_' . $dest_zone];
			}
		}


		$this->quotes = array('id' => $this->code, 'module' => $this->title,
				'methods' => array(
						array('id' => $this->code,
								'title' => lang('shipping_zones_method'),
								'cost' => $shipping_cost)),
				'tax_class_id' => $this->tax_class);

		if (!empty($this->icon))
			$this->quotes['icon'] = image_url('shipping/' . $this->icon,
					$this->title);

		return $this->quotes;
	}
}
