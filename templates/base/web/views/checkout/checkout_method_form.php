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
<div class="row-fluid clearfix">
    <!--  Begin: New Customer Form  -->
    <div class="span6">
        <h6><?php echo lang('login_new_customer_heading');?></h6>
    
        <div class="control-group">
            <div class="controls">
            	<label class="radio inline" for="checkout_method_register">
            		<input type="radio" id="checkout_method_register" value="register" id="checkout_method" name="checkout_method" <?php echo set_radio('gender', 'm', TRUE); ?> />Register Account
            	</label>
            </div>
        </div>
        
        <?php
        /* comment out by lei 
        <div class="control-group">
            <div class="controls">
            	<label class="radio inline" for="checkout_method_guest">
            		<input type="radio" id="checkout_method_guest" value="guest" id="checkout_method" name="checkout_method" <?php echo set_radio('gender', 'f'); ?> />Guest Checkout
            	</label>
            </div>
        </div>
        */
        ?>
    
        <p><?php echo lang('login_new_customer_text');?></p>
    
        <p align="right">
            <button type="submit" class="btn btn-small btn-info" id="btn-new-customer"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></button>
        </p>
    </div>
    <!--  End: New Customer Form  -->
    
    <!--  Begin: Login Form  -->
    <div class="span6">
        <form name="login" action="<?php echo site_url('account/create'); ?>" method="post">
            <h6><?php echo lang('login_returning_customer_heading');?></h6>
    
            <p><?php echo lang('login_returning_customer_text');?></p>
            
            <div class="control-group">
                <label for="email_address"><?php echo lang('field_customer_email_address');?><span class="required">*</span></label>
                <div class="controls">
                	<input class="input-large" type="text" id="email_address" name="email_address" value="<?php echo set_value('email_address');?>" />
                </div>
            </div>
            
            <div class="control-group">
                <label for="password"><?php echo lang('field_customer_password');?><em>*</em></label>
                <div class="controls">
                	<input class="input-large" type="password" id="password" name="password" value="<?php echo set_value('password');?>" />
                </div>
            </div>
            
            <p>
                <?php echo sprintf(lang('login_returning_customer_password_forgotten'), site_url('account/password_forgotten'));?>
            </p>
            
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-small btn-small btn-success pull-right" id="btn-login"><i class="icon-ok-sign icon-white"></i> <?php echo lang('button_sign_in'); ?></button>
                </div>
            </div>
        </form>
    </div>
    <!--  End: Login Form  -->
</div>