<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Academic Free License version 3.0
 *
 * This source file is subject to the Academic Free License (AFL 3.0) that is
 * bundled with this package in the files license_afl.txt / license_afl.rst.
 * It is also available through the world wide web at this URL:
 * http://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2012, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

/*
 |--------------------------------------------------------------------------
 | File and Directory Modes
 |--------------------------------------------------------------------------
 |
 | These prefs are used when checking and setting modes when working
 | with the file system.  The defaults are fine on servers with proper
 | security, but you may wish (or even need) to change the values in
 | certain environments (Apache running a separate process for each
 | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
 | always be used to set the mode correctly.
 |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
 |--------------------------------------------------------------------------
 | Define max cache time
 |--------------------------------------------------------------------------
 */
define('CACHE_MAX_TIME', 36000000);

/*
 |--------------------------------------------------------------------------
 | File Stream Modes
 |--------------------------------------------------------------------------
 |
 | These modes are used when working with fopen()/popen()
 |
 */

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
 |--------------------------------------------------------------------------
 | Display Debug backtrace
 |--------------------------------------------------------------------------
 |
 | If set to TRUE, a backtrace will be displayed along with php errors. If
 | error_reporting is disabled, the backtrace will not display, regardless
 | of this setting
 |
 */
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
 |--------------------------------------------------------------------------
 | products type
 |--------------------------------------------------------------------------
 |
 | These modes are used when working with fopen()/popen()
 |
 */
define('PRODUCT_TYPE_SIMPLE',  0);
define('PRODUCT_TYPE_VIRTUAL', 1);
define('PRODUCT_TYPE_DOWNLOADABLE', 2);
define('PRODUCT_TYPE_GIFT_CERTIFICATE', 3);


/*
 |--------------------------------------------------------------------------
 | orders status
 |--------------------------------------------------------------------------
 |
 |
 */
define('ORDERS_STATUS_PENDING', 1);
define('ORDERS_STATUS_PROCESSING', 2);
define('ORDERS_STATUS_PREPARING', 3);
define('ORDERS_STATUS_PARTLY_PAID', 4);
define('ORDERS_STATUS_PAID', 5);
define('ORDERS_STATUS_PARTLY_DELIVERED', 6);
define('ORDERS_STATUS_DELIVERED', 7);
define('ORDERS_STATUS_CANCELLED', 8);

/*
 |--------------------------------------------------------------------------
 | orders returns
 |--------------------------------------------------------------------------
 |
 |
 */
define('ORDERS_RETURNS_STATUS_PENDING', 1);
define('ORDERS_RETURNS_STATUS_CONFIRMED', 2);
define('ORDERS_RETURNS_STATUS_RECEIVED', 3);
define('ORDERS_RETURNS_STATUS_AUTHORIZED', 4);
define('ORDERS_RETURNS_STATUS_REFUNDED_CREDIT_MEMO', 5);
define('ORDERS_RETURNS_STATUS_REFUNDED_STORE_CREDIT', 6);
define('ORDERS_RETURNS_STATUS_REJECT', 7);

/*
 |--------------------------------------------------------------------------
 | store credit action
 |--------------------------------------------------------------------------
 |
 |
 */
define('STORE_CREDIT_ACTION_TYPE_ORDER_PURCHASE', 1);
define('STORE_CREDIT_ACTION_TYPE_ORDER_REFUNDED', 2);
define('STORE_CREDIT_ACTION_TYPE_ADMIN', 3);

/*
 |--------------------------------------------------------------------------
 | orders returns type
 |--------------------------------------------------------------------------
 |
 |
 */
define('ORDERS_RETURNS_TYPE_CREDIT_SLIP', 0);
define('ORDERS_RETURNS_TYPE_STORE_CREDIT', 1);

/*
 |--------------------------------------------------------------------------
 | orders returns type
 |--------------------------------------------------------------------------
 |
 |
 */
define('COUPONS_RESTRICTION_NONE', 0);
define('COUPONS_RESTRICTION_CATEGOREIS', 1);
define('COUPONS_RESTRICTION_PRODUCTS', 2);

/*
 |--------------------------------------------------------------------------
 | email folder flag
 |--------------------------------------------------------------------------
 |
 |
 */
define('EMAIL_FOLDER_UNKNOWN', 0);
define('EMAIL_FOLDER_INBOX', 1);
define('EMAIL_FOLDER_SENTBOX', 2);
define('EMAIL_FOLDER_DRAFT', 3);
define('EMAIL_FOLDER_SPAM', 4);
define('EMAIL_FOLDER_TRASH', 5);

/*
 |--------------------------------------------------------------------------
 | email message flag
 |--------------------------------------------------------------------------
 |
 |
 */
define('EMAIL_MESSAGE_SENT_ITEM', 2);
define('EMAIL_MESSAGE_DRAFT', 3);

/*
 |--------------------------------------------------------------------------
 | email message flag
 |--------------------------------------------------------------------------
 |
 |
 */
define('CUSTOMIZATION_FIELD_TYPE_INPUT_FILE', 0);
define('CUSTOMIZATION_FIELD_TYPE_INPUT_TEXT', 1);

/*
 |--------------------------------------------------------------------------
 | information
 |--------------------------------------------------------------------------
 |
 |
 */
define('INFORMATION_ABOUT_US', 1);
define('INFORMATION_SHIPPING_RETURNS', 2);
define('INFORMATION_PRIVACY_NOTICE', 3);
//define('INFORMATION_SHIPPING_RETURNS', 4);
define('INFORMATION_IMPRINT', 5);

define('EXT', '.php');

//project version
define('PROJECT_VERSION', 'TomatoCart v2.0 Alpha5');

//class
define('__CLASS__', 'TomatoCart v2.0');
/* End of file constants.php */
/* Location: ./application/config/constants.php */