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

<h1><?php echo lang('account_password_heading'); ?></h1>

<?php echo toc_validation_errors('account_password'); ?>

<h6><em class="pull-right"><?php echo lang('form_required_information'); ?></em><?php echo lang('my_password_title'); ?></h6>

<form name="account_password" action="<?php echo site_url('account/password/save'); ?>" method="post" class="form-horizontal">
	<div class="module-box">
        <div class="control-group">
            <label class="control-label" for="password_current"><?php echo lang('field_customer_password_current'); ?><em>*</em></label>
            <div class="controls">
            	<input type="password" id="password_current" name="password_current" value="<?php echo set_value('password_current'); ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="confirmation"><?php echo lang('field_customer_password_new'); ?><em>*</em></label>
            <div class="controls">
            	<input type="password" id="password_new" name="password_new" value="<?php echo set_value('password_new'); ?>" />
            </div>
        </div>    
        <div class="control-group">
            <label class="control-label" for="confirmation"><?php echo lang('field_customer_password_confirmation'); ?><em>*</em></label>
            <div class="controls">
            	<input type="password" id="password_confirmation" name="password_confirmation"  value="<?php echo set_value('password_confirmation'); ?>" />
            </div>
        </div>      
    </div>
    <div class="control-group">
    	<a href="<?php echo site_url('account'); ?>" class="btn btn-small btn-info pull-left"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_back'); ?></a>
        <button type="submit" class="btn btn-small btn-success pull-right"><i class="icon-ok-sign icon-white"></i> <?php echo lang('button_continue'); ?></button>
    </div>
</form>