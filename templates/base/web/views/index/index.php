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
?>

<h1><?php echo sprintf(lang('index_heading'), config('STORE_NAME')); ?></h1>

<?php 
    if ($is_logged_on) :
?>
<p>
    <?php echo sprintf(lang('greeting_customer'), $customer_firstname, site_url('products/latest')); ?>
</p>
<?php 
    else:
?>
<p>
    <?php echo sprintf(lang('greeting_guest'), site_url('account/login'), site_url('products/latest')); ?>
</p>
<?php 
    endif; 
?>

<p>
    <?php echo lang('index_text'); ?>
</p>