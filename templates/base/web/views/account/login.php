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

<h1><?php echo lang('sign_in_heading'); ?></h1>

<?php
    echo toc_validation_errors('login');
?>

<div class="module-box clearfix">
	<div class="row-fluid">
        <div class="span6">
                <h3><?php echo lang('login_new_customer_heading'); ?></h3>
                
                <p><?php echo lang('login_new_customer_text'); ?></p>
                
                <a class="btn btn-small btn-info pull-right" href="<?php echo site_url('account/create'); ?>"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></a>
        </div>
        
        <div class="span6">
            <h3><?php echo lang('login_returning_customer_heading'); ?></h3>
            
            <p><?php echo lang('login_returning_customer_text'); ?></p>
            
            <form id="login" name="login" action="<?php echo site_url('account/login/process');?>" method="post">
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
                        <button type="submit" class="btn btn-small btn-success pull-right"><i class="icon-ok-sign icon-white"></i> <?php echo lang('button_sign_in'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>