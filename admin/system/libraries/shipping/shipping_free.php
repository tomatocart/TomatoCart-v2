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
class TOC_Shipping_free extends TOC_Shipping_Module 
{

    var $code = 'free';
    
    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
        array('name' => 'MODULE_SHIPPING_FREE_STATUS',
              'title' => 'Enable Free Shipping', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'True',
              'description' => 'Do you want to offer flat rate shipping?',
              'values' => array(
                  array('id' => 'True', 'text' => 'True'),
                  array('id' => 'False', 'text' => 'False'))),
        array('name' => 'MODULE_SHIPPING_FREE_MINIMUM_ORDER',
              'title' => 'Shipping Cost', 
              'type' => 'numberfield',
              'value' => '20',
              'description' => 'The minimum order amount to apply free shipping to.'),
        array('name' => 'MODULE_SHIPPING_FREE_ZONE',
              'title' => 'Shipping Zone', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'If a zone is selected, only enable this shipping method for that zone.',
              'action' => 'config/get_shipping_zone'));

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct() {
        parent::__construct();

        //initialize 
        $this->icon = 'free.jpg';
        $this->title = lang('shipping_free_title');
        $this->description = lang('shipping_free_description');
        $this->status = (isset($this->config['MODULE_SHIPPING_FREE_STATUS']) && ($this->config['MODULE_SHIPPING_FREE_STATUS'] == 'TRUE')) ? TRUE : FALSE;
    }

    /**
     * Initialize the shipping module
     *
     * @access public
     */
    public function initialize() 
    {
        if ($this->ci->shopping_cart->get_total() >= $this->config['MODULE_SHIPPING_FREE_MINIMUM_ORDER']) 
        {
            if ($this->status === TRUE) 
            {
                if ((int) $this->config['MODULE_SHIPPING_FREE_ZONE'] > 0) 
                {
                    $this->ci->load->model('address_model');

                    $zones = $this->ci->address_model->get_zone_id_via_geo_zone($this->ci->shopping_cart->get_shipping_address('country_id'), $this->config['MODULE_SHIPPING_FREE_ZONE']);

                    $check_flag = FALSE;
                    if ($zones !== NULL) {
                        foreach($zones as $zone_id) {
                            if ($zone_id < 1) {
                                $check_flag = TRUE;
                                break;
                            } elseif ($zone_id == $this->ci->shopping_cart->get_shipping_address('zone_id')) {
                                $check_flag = TRUE;
                                break;
                            }
                        }
                    }

                    $this->status = $check_flag;
                } 
                else 
                {
                    $this->status = TRUE;
                }
            }
        } else {
            $this->status = FALSE;
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
                                                     'title' => sprintf(lang('shipping_free_for_amount'), $this->ci->currencies->format($this->config['MODULE_SHIPPING_FREE_MINIMUM_ORDER'])),
                                                     'cost' => 0)),
                              'tax_class_id' => 0);

        if (!empty($this->icon)) $this->quotes['icon'] = image_url('images/shipping/' . $this->icon, $this->title);

        return $this->quotes;
    }
}

/* End of file shipping_free.php */
/* Location: ./system/tomatocart/libraries/shipping/shipping_free.php */