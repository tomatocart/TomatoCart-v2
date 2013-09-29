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
 * @filesource
 */

  class TOC_Access_Email_Templates extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'email_templates';
      $this->_group = 'tools';
      $this->_icon = 'email_edit.png';
      $this->_sort_order = 700;
      
      $this->_title = lang('access_email_templates_title');
    }
  }
  
/* End of file email_templates.php */
/* Location: ./system/modules/access/email_templates.php */