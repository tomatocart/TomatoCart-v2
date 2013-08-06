<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package      TomatoCart
 * @author       TomatoCart Dev Team
 * @copyright    Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html
 * @link         http://tomatocart.com
 * @since        Version 2.0
 * @filesource
*/
?>

<h1><?php echo lang('account_heading');?></h1>

<?php echo toc_validation_errors('account'); ?>

<div class="module-box">
    <h6><?php echo lang('my_account_title');?></h6>
    
    <div class="row-fluid">
        <div class="span2 visible-desktop">
    		<img title="<?php echo lang('my_account_title');?>" alt="<?php echo lang('my_account_title');?>" src="<?php echo image_url('my_account.png');?>" />
        </div>
        <ul class="span10">
            <li><a href="<?php echo site_url('account/edit');?>"><?php echo lang('my_account_information');?></a></li>
            <li><a href="<?php echo site_url('account/address_book');?>"><?php echo lang('my_account_address_book');?></a></li>
            <li><a href="<?php echo site_url('account/password');?>"><?php echo lang('my_account_password');?></a></li>
        </ul>
    </div>
</div>

<div class="module-box">
    <h6><?php echo lang('my_orders_title');?></h6>
    
    <div class="row-fluid">
        <div class="span2 visible-desktop">
            <img title="<?php echo lang('my_orders_title');?>" alt="<?php echo lang('my_orders_title');?>" src="<?php echo image_url('my_orders.png');?>" />
        </div>
        <ul class="span10">
            <li><a href="<?php echo site_url('account/orders');?>"><?php echo lang('my_orders_view');?></a></li>
        </ul>
    </div>
</div>

<div class="module-box">
    <h6><?php echo lang('my_notifications_title');?></h6>
    
    <div class="row-fluid">
        <div class="span2 visible-desktop">
            <img title="<?php echo lang('my_notifications_title');?>" alt="<?php echo lang('my_notifications_title');?>" src="<?php echo image_url('my_notifications.png');?>" />
        </div>
        
        <ul class="span10">
            <li><a href="<?php echo site_url('account/newsletters');?>"><?php echo lang('my_notifications_newsletters');?></a></li>
        </ul>
    </div>
</div>