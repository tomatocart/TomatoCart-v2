<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Guestbooks Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-guestbooks-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Guestbooks_Model extends CI_Model
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

    /**
     * Get the list of guestbooks
     *
     * @access public
     * @return array
     */
    public function get_listing()
    {
        $result = $this->db
            ->select('guest_books_id, title, email, url, guest_books_status, languages_id, content, date_added')
            ->from('guest_books')
            ->where('languages_id', lang_id())
            ->where('guest_books_status', 1)
            ->order_by('guest_books_id', 'desc')
            ->get();

        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }

    /**
     * Save a new guestbook
     *
     * @param array
     * @return bool
     */
    public function save($data)
    {
        $data['languages_id'] = lang_id();
        
        return $this->db->insert('guest_books', $data);
    }

}

/* End of file guestbooks_model.php */
/* Location: ./system/tomatocart/models/guestbooks_model.php */