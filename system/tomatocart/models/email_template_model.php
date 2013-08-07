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
 * Email Template Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-model
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Email_Template_Model extends CI_Model
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
     * Get email template data
     * 
     * @access public
     * @return mixed
     */
    public function get_data($template_name) 
    {
        $result = $this->db->select('et.email_templates_status, etd.email_title, etd.email_content')
            ->from('email_templates as et')
            ->join('email_templates_description as etd', 'et.email_templates_id = etd.email_templates_id', 'inner')
            ->where('et.email_templates_name', $template_name)
            ->where('etd.language_id', lang_id())
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
}

/* End of file email_template_model.php */
/* Location: ./system/tomatocart/models/email_template_model.php */