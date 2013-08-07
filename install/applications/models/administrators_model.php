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
 * Administrators_Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Administrators_Model extends CI_Model
{

	/**
	 * Constructor
	 *
	 * @access public
	 * @param string
	 */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Create customer data
     *
     * @access public
     * @param $username
     * @param $password
     * @param $email
     * @return boolean
     */
    public function create($username, $password, $email)
    {
        //insert into admin table
        $result = $this->db->insert('administrators', array('user_name' => $username, 'user_password' => encrypt_string($password), 'email_address' => $email));

        if ($result) {
            //get administartor id
            $administrators_id = $this->db->insert_id();

            //add access
            return $this->db->insert('administrators_access', array('administrators_id' => $administrators_id, 'module' => '*'));
        }
        
        return FALSE;
    }
}
/* End of file administrators_model.php */
/* Location: ./install/application/models/administrators_model.php */