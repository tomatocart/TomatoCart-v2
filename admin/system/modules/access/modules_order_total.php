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
 * @filesource ./system/modules/access/orders_status.php
 */ 

class TOC_Access_Modules_order_total extends TOC_Access {
    public function __construct()
    {
        parent::__construct();

        $this->_module = 'modules_order_total';
        $this->_group = 'modules';
        $this->_icon = 'calculator.png';
        $this->_sort_order = 300;

        $this->_title = lang('access_modules_order_total_title');
    }
}