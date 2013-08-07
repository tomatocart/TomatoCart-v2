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
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "index";
$route['404_override'] = '';

$route['product/latest'] = "products/latest/index";
$route['product/specials'] = "products/specials/index";
$route['product/(:any)'] = "products/info/index/$1";

$route['cpath/(:any)'] = "index/cpath/index/$1";

$route['info/faqs'] = "info/faqs/index";
$route['info/faqs/(:any)'] = "info/faqs/index/$1";
$route['info/guestbooks'] = "info/guestbooks/index";
$route['info/guestbooks/add'] = "info/guestbooks/add";
$route['info/guestbooks/save'] = "info/guestbooks/save";
$route['info/(:any)'] = "info/info/index/$1";

$route['articles_categories/(:any)'] = "info/articles_categories/index/$1";
$route['articles/(:any)'] = "info/articles/index/$1";

$route['index/(:any)'] = "index/index/index/$1";
$route['cart_add/(:num)'] = "checkout/cart_add/index/$1";
$route['cart_delete/(:any)'] = "checkout/cart_delete/index/$1";
$route['cart_update'] = "checkout/cart_update/index";
$route['shopping_cart'] = "checkout/shopping_cart/index";

$route['wishlist'] = "account/wishlist";
$route['wishlist/add/(:any)'] = "account/wishlist/add/$1";
$route['wishlist/delete/(:any)'] = "account/wishlist/delete/$1";
$route['wishlist/update'] = "account/wishlist/update";

$route['compare'] = "products/compare";
$route['compare/add/(:any)'] = "products/compare/add/$1";
$route['compare/delete/(:any)'] = "products/compare/delete/$1";
$route['compare/clear'] = "products/compare/clear";

$route['latest'] = "products/latest";
$route['latest/(:any)'] = "products/latest/index/$1";
$route['specials'] = "products/specials";
$route['specials/(:any)'] = "products/specials/index/$1";

$route['account'] = "account/index";
$route['checkout'] = "checkout/index";
$route['checkout/process'] = "checkout/checkout/process";

$route['search'] = "search/search/index";

$route['contact_us'] = "info/contact_us";
$route['sitemap'] = "info/sitemap";
$route['contact_save'] = "info/contact_us/save";

/* End of file routes.php */
/* Location: ./application/config/routes.php */