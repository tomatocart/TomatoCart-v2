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
 * Currencies_Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-model
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Currencies_Model extends CI_Model
{
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all currencies
     * 
     * @access public
     * @return mixed
     */
    public function get_all()
    {
        $result = $this->db->select('*')->from('currencies')->get();

        $currencies = FALSE;
        foreach($result->result_array() as $row)
        {
            $currencies[$row['code']] = array(
                'id' => $row['currencies_id'],
                'title' => $row['title'],
                'symbol_left' => $row['symbol_left'],
                'symbol_right' => $row['symbol_right'],
                'decimal_places' => $row['decimal_places'],
                'value' => $row['value']);
        }

        return $currencies;
    }
}

/* End of file settings_model.php */
/* Location: ./system/tomatocart/models/currencies_model.php */