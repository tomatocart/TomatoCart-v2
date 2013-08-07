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
 * Email library
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-library
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
Class TOC_Email extends CI_Email
{
    /**
     * @access protected
     * @var boolean
     */
    protected $_send_out = FALSE;
    
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct($config = array())
    {
        //set the user agent to tomatocart
        $this->useragent = 'TomatoCart';
        
        //set mail protocol
        $this->protocol = EMAIL_TRANSPORT;
        
        //set smpt account info
        $this->smtp_host = SMTP_HOST;
        $this->smtp_user = SMTP_USERNAME;
        $this->smtp_pass = SMTP_PASSWORD;
        $this->smtp_port = SMTP_PORT;
        $this->smtp_timeout = 30;
        
        //set the smpt encryption
        if (EMAIL_SSL == '1') {
            $this->smtp_crypto = "ssl";
        }
        
        
        //set emial line feed
        if (EMAIL_LINEFEED == 'CRLF')
        {
            $this->newline = "\r\n";
            $this->crlf = "\r\n";
        }
        
        //set the email formatting
        if (EMAIL_USE_HTML == '1')
        {
            $this->mailtype = 'html';
        }
        
        //set the send out emails flag
        if (SEND_EMAILS == '1')
        {
            $this->_send_out = TRUE;
        }
        
        //set the default from email address
        $this->from(STORE_OWNER_EMAIL_ADDRESS, STORE_OWNER);
        
        parent::__construct($config);
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Send the email
     *
     * @access public
     * @return boolean
     */
    public function send()
    {
        //check the send out emails flag
        if ($this->_send_out)
        {
            
           return parent::send();
        }
    }
}

/* End of file email.php */
/* Location: ./system/libraries/email.php */