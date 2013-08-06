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

$config['useragent'] = 'TomatoCart';
$config['protocol'] = (config('EMAIL_TRANSPORT') == 'sendmail') ? 'mail' : 'smtp';
$config['smtp_host'] = config('SMTP_HOST');
$config['smtp_user'] = config('SMTP_USERNAME');
$config['smtp_pass'] = config('SMTP_PASSWORD');
$config['smtp_port'] = config('SMTP_PORT');
$config['mailtype'] = 'html';

/* End of file email.php */
/* Location: ./system/tomatocart/config/email.php */
