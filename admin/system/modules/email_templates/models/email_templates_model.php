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

class Email_Templates_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_template_info($template_name)
  {
    $Qtemplate = $this->db
    ->select('et.email_templates_status, etd.email_title, etd.email_content')
    ->from('email_templates et')
    ->join('email_templates_description etd', 'et.email_templates_id = etd.email_templates_id')
    ->where(array('et.email_templates_name' => $template_name, 'etd.language_id' => lang_id()))
    ->get();
    
    return $Qtemplate->row_array();
  }
}


/* End of file email_templates_model.php */
/* Location: ./system/modules/email_templates/models/email_templates_model.php */