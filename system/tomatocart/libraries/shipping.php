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
 * Shipping Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Shipping
{
    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    protected $ci = null;

    /**
     * shipping modules array
     *
     * @access protected
     * @var array
     */
    protected $modules = array();

    /**
     * selected shipping modules array
     *
     * @access protected
     * @var array
     */
    protected $selected_module;

    /**
     * each shipping method quotation array
     *
     * @access protected
     * @var array
     */
    protected $quotes = array();

    /**
     * each shipping method quotation array
     *
     * @access protected
     * @var string
     */
    protected $group = 'shipping';

    /**
     * Constructor
     *
     * @access public
     * @param string shipping module name
     */
    public function __construct($module = '')
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        //load extensions model
        $this->ci->load->model('extensions_model');

        //get shipping modules
        $this->modules = $this->ci->extensions_model->get_modules('shipping');

        if (!empty($this->modules))
        {
            if (!empty($module) && in_array(substr($module, 0, strpos($module, '_')), $this->modules))
            {
                $this->selected_module = $module;
                $this->modules = array(substr($module, 0, strpos($module, '_')));
            }

            $this->ci->lang->db_load('modules-shipping');

            //load shipping libraries
            foreach ($this->modules as $module)
            {
                //module class
                $module_class = strtolower('shipping_' . $module);

                //load library
                $this->ci->load->library('shipping/' . $module_class);

                //initialize
                $this->ci->{$module_class}->initialize();
            }

            usort($this->modules, array('TOC_Shipping', 'usort_modules'));
        }

        $this->calculate();
    }

    /**
     *
     *
     * @param string $module
     */
    public function load_shipping_module($module)
    {
        //module class
        if (strpos($module, '_') > 0)
        {
            $module_class = strtolower('shipping_' . substr($module, 0, strpos($module, '_')));
        }
        else
        {
            $module_class = strtolower('shipping_' . $module);
        }

        //load library
        $this->ci->load->library('shipping/' . $module_class);

        return $this->ci->$module_class;
    }

    /**
     * Get shipping module quotes
     *
     * @return array shipping module quotes
     */
    public function has_quotes()
    {
        return !empty($this->quotes);
    }

    /**
     * Get number of quotes
     *
     * @return int number of quotes
     */
    public function number_of_quotes()
    {
        $total_quotes = 0;

        foreach ($this->quotes as $quotes)
        {
            $total_quotes += sizeof($quotes['methods']);
        }

        return $total_quotes;
    }

    /**
     * Get all quotes
     *
     * @return array quotes
     */
    public function get_quotes()
    {
        return $this->quotes;
    }

    /**
     * Get all quotes
     *
     * @return array quotes
     */
    public function get_quote($module = '')
    {
        if (empty($module))
        {
            $module = $this->selected_module;
        }

        list($module_id, $method_id) = explode('-', $module);

        $rate = array();

        foreach ($this->quotes as $quote)
        {
            if ($quote['id'] == $module_id)
            {
                foreach ($quote['methods'] as $method)
                {
                    if ($method['id'] == $method_id)
                    {
                        $rate = array('id' => $module,
                                      'title' => $quote['module'] . ((empty($method['title']) === FALSE) ? ' (' . $method['title'] . ')' : ''),
                                      'cost' => $method['cost'],
                                      'tax_class_id' => $quote['tax_class_id'],
                                      'is_cheapest' => null);

                        break 2;
                    }
                }
            }
        }

        return $rate;
    }

    public function get_cheapest_quote()
    {
        $rate = array();

        foreach ($this->quotes as $quote)
        {
            if (!empty($quote['methods']))
            {
                foreach ($quote['methods'] as $method)
                {
                    if (empty($rate) || ($method['cost'] < $rate['cost']))
                    {
                        $rate = array('id' => $quote['id'] . '_' . $method['id'],
                                      'title' => $quote['module'] . ((empty($method['title']) === FALSE) ? ' (' . $method['title'] . ')' : ''),
                                      'cost' => $method['cost'],
                                      'tax_class_id' => $quote['tax_class_id'],
                                      'is_cheapest' => FALSE);
                    }
                }
            }
        }

        if (!empty($rate))
        {
            $rate['is_cheapest'] = TRUE;
        }

        return $rate;
    }

    public function has_active()
    {
        static $has_active;

        if (isset($has_active) === FALSE)
        {
            $has_active = FALSE;

            foreach ($this->modules as $module)
            {
                $module_class = strtolower('shipping_' . $module);
                if ($this->ci->$module_class->is_enabled())
                {
                    $has_active = TRUE;
                    break;
                }
            }
        }

        return $has_active;
    }

    /**
     * Calcuate shipping costs
     *
     * @access private
     * @return void
     */
    private function calculate()
    {
        $this->quotes = array();

        if (is_array($this->modules))
        {
            $include_quotes = array();

            foreach ($this->modules as $module)
            {
                $module_class = strtolower('shipping_' . $module);
                if ($this->ci->$module_class->is_enabled())
                {
                    $include_quotes[] = $module_class;
                }
            }

            foreach ($include_quotes as $module)
            {
                $quotes = $this->ci->$module->quote();

                if (is_array($quotes))
                {
                    $this->quotes[] = $quotes;
                }
            }
        }
    }

    public function usort_modules($a, $b)
    {
        $module_class_a = 'shipping_' . $a;
        $module_class_b = 'shipping_' . $b;

        if ($this->ci->$module_class_a->get_sort_order() == $this->ci->$module_class_b->get_sort_order())
        {
            return strnatcasecmp($this->ci->$module_class_a->get_title(), $this->ci->$module_class_a->get_title());
        }

        return ($this->ci->$module_class_a->get_sort_order() < $this->ci->$module_class_b->get_sort_order()) ? -1 : 1;
    }
}
// END Shipping Class

/* End of file shipping.php */
/* Location: ./system/tomatocart/libraries/shipping.php */