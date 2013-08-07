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
 * Weight Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Weight
{
    /**
     * Exclude pages
     *
     * @access protected
     * @var array
     */
    protected $weight_classes = array();
    
    /**
     * Weight precision
     *
     * @access protected
     * @var float
     */
    protected $precision;

    /**
     * Constructor 
     * 
     * @access public
     * @param $precision
     */
    public function __construct($precision = '2')
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        $this->ci->load->model('weight_model');

        $this->precision = $precision;

        $this->prepare_rules();
    }

    /**
     * Get weight title
     *
     * @param $id
     * @return string
     */
    public function get_title($id)
    {
        return $this->ci->weight_model->get_title($id);
    }

    /**
     * Prepare rules
     * 
     * @access public
     * @return void
     */
    public function prepare_rules()
    {
        $this->weight_classes = $this->ci->weight_model->get_rules();
    }

    /**
     * Convert weight from specified unit to unit
     * 
     * @param $value
     * @param @unit_from
     * @param @unit_to
     * @return float
     */
    public function convert($value, $unit_from, $unit_to)
    {
        global $osC_Language;

        if ($unit_from == $unit_to) 
        {
            return number_format($value, (int)$this->precision, $this->ci->lang->get_numeric_decimal_separator(), $this->ci->lang->get_numeric_thousands_separator());
        } 
        else 
        {
            return number_format($value * $this->weight_classes[(int)$unit_from][(int)$unit_to], (int)$this->precision, $this->ci->lang->get_numeric_decimal_separator(), $this->ci->lang->get_numeric_thousands_separator());
        }
    }

    /**
     * Display weight value
     *
     * @param $value
     * @param $class
     * @return float
     */
    public function display($value, $class)
    {
        global $osC_Language;

        return number_format($value, (int)$this->precision, $this->ci->lang->get_numeric_decimal_separator(), $this->ci->lang->get_numeric_thousands_separator()) . $this->weight_classes[$class]['key'];
    }
}
// END Weight Class

/* End of file weight.php */
/* Location: ./system/tomatocart/libraries/weight.php */