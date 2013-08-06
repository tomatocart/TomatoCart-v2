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

/**
 * Customers Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Config extends TOC_Controller
{
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * List available tax classes
     *
     * @access public
     * @return string
     */
    public function get_tax_class()
    {
        $this->load->model('tax_model');
        
        $tax_classes = array(array('id' => '0', 'text' => lang('parameter_none')));
        $classes = $this->tax_model->get_tax_class();
        if ($classes !== NULL) 
        {
            foreach ($classes as $class)
            {
                $tax_classes[] = array('id' => $class['tax_class_id'], 'text' => $class['tax_class_title']);
            }
        }
        
        $this->output->set_output(json_encode($tax_classes));
    }

    // --------------------------------------------------------------------

    /**
     * List available order status
     *
     * @access public
     * @return string
     */
    public function get_order_status()
    {
        $this->load->model('orders_status_model');
        
        $tax_classes = array(array('id' => '0', 'text' => lang('parameter_none')));
        $classes = $this->orders_status_model->get_order_status();
        if ($classes !== NULL) 
        {
            foreach ($classes as $class)
            {
                $tax_classes[] = array('id' => $class['orders_status_id'], 'text' => $class['orders_status_name']);
            }
        }
        
        $this->output->set_output(json_encode($tax_classes));
    }

    // --------------------------------------------------------------------

    /**
     * List available shipping zones
     *
     * @access public
     * @return string
     */
    public function get_shipping_zone()
    {
        $this->load->model('zone_groups_model');
        
        $tax_classes = array(array('id' => '0', 'text' => lang('parameter_none')));
        $classes = $this->zone_groups_model->get_all_geo_zones();
      
        if ($classes !== NULL) 
        {
            foreach ($classes as $class)
            {
                $tax_classes[] = array('id' => $class['geo_zone_id'], 'text' => $class['geo_zone_name']);
            }
        }
        
        $this->output->set_output(json_encode($tax_classes));
    }
    
    /**
     * List weight classes
     *
     * @access public
     * @return string
     */
    public function get_weight_classes()
    {
    	$this->load->model('weight_model');
    
    	$weight_classes = array();
    	$classes = $this->weight_model->get_classes();
    
    	if ($classes !== NULL)
    	{
    		foreach ($classes as $class)
    		{
    			$weight_classes[] = array('id' => $class['weight_class_id'], 'text' => $class['weight_class_title']);
    		}
    	}
    
    	$this->output->set_output(json_encode($weight_classes));
    }
}

/* End of file customers.php */
/* Location: ./system/tomatocart/controllers/customers.php */