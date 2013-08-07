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
 * Order Total Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Order_Total
{
    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    private $ci = null;

    /**
     * order totals modules array
     *
     * @access protected
     * @var array
     */
    private $modules = array();

    /**
     * data array
     *
     * @access protected
     * @var array
     */
    private $data = array();

    /**
     * module group
     *
     * @access protected
     * @var array
     */
    private $group = 'order_total';

    /**
     * Constructor
     *
     * @access public
     * @param string shipping module name
     */
    function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        //load extensions model
        $this->ci->load->model('extensions_model');
        $this->modules = $this->ci->extensions_model->get_modules('order_total');
        
        //load language resources
        $this->ci->lang->db_load('modules-order_total');
        
        if (!empty($this->modules))
        {
            //load shipping libraries
            foreach ($this->modules as $module)
            {
                //module class
                $module_class = strtolower('order_total_' . $module);

                //load library
                $this->ci->load->library('order_total/' . $module_class);
            }

            usort($this->modules, array('TOC_Order_Total', 'usort_modules'));
        }
    }

    /**
     * Get result
     * 
     * @access public
     * @return array
     */
    function get_result()
    {
        $this->data = array();
        
        foreach ($this->modules as $module)
        {
            $module_class = strtolower('order_total_' . $module);

            if ($this->ci->$module_class->is_enabled())
            {
                //use the cart total value to caculate the tax of order total module
                //cart total value before module process
                $pre_total = $this->ci->shopping_cart->get_total();
                
                //process order total module
                $this->ci->$module_class->process();

                //cart total value after module process
                $post_total = $this->ci->shopping_cart->get_total();
                
                foreach ($this->ci->$module_class->get_output() as $output) 
                {
                    if (!empty($output['title']) && !empty($output['text'])) 
                    {
                        $this->data[] = array('code' => $this->ci->$module_class->get_code(),
                                              'title' => $output['title'],
                                              'text' => $output['text'],
                                              'value' => $output['value'],
                                              'tax' => ($post_total - $pre_total - $output['value']),
                                              'sort_order' => $this->ci->$module_class->get_sort_order());
                    }
                }
            }
        }
        
        return $this->data;
    }

    /**
     * Has active
     *
     * @access public
     */
    function has_active()
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
     * Sort modules
     * 
     * @access public
     * @param $a
     * @param $b
     * @return boolean
     */
    public function usort_modules($a, $b)
    {
        $module_class_a = 'order_total_' . $a;
        $module_class_b = 'order_total_' . $b;

        if ($this->ci->$module_class_a->get_sort_order() == $this->ci->$module_class_b->get_sort_order())
        {
            return strnatcasecmp($this->ci->$module_class_a->get_title(), $this->ci->$module_class_a->get_title());
        }

        return ($this->ci->$module_class_a->get_sort_order() < $this->ci->$module_class_b->get_sort_order()) ? -1 : 1;
    }
}
// END Order Total

/* End of file order_total.php */
/* Location: ./system/tomatocart/libraries/order_total.php */