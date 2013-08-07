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

<div class="register-box">
    <h3><?php echo lang('login_new_customer_heading'); ?></h3>
    
    <p><?php echo lang('login_new_customer_text'); ?></p>
    
    <p align="right">
		<a class="button" href="<?php echo site_url('account/create'); ?>"><?php echo lang('button_continue'); ?></a>
    </p>
</div>

<div class="login-box">
    <h3><?php echo lang('login_returning_customer_heading'); ?></h3>
    
    <div class="contents">
        <form id="login" name="login" action="<?php echo site_url('account/login/process');?>" method="post">
            
            <p><?php echo lang('login_returning_customer_text'); ?></p>
            
            <ul>
                <li>
                    <label for="email_address"><?php echo lang('field_customer_email_address');?><span class="required">*</span></label>
                    <input type="text" id="email_address" name="email_address" value="<?php echo set_value('email_address');?>" />
                </li>
                <li>
                    <label for="password"><?php echo lang('field_customer_password');?><em>*</em></label>
                    <input type="password" id="password" name="password" value="<?php echo set_value('password');?>" />
                </li>
            </ul>
            
            <p>
                <?php echo sprintf(lang('login_returning_customer_password_forgotten'), site_url('account/password_forgotten'));?>
            </p>
            
            <p align="right">
            	<button type="submit" class="button"><?php echo lang('button_sign_in'); ?></button>
            </p>
        </form>
    </div>
</div>

<div class="clear"></div>
