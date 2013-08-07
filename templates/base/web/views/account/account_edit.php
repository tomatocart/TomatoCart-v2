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
<script type="text/javascript">
<!--
	$('head').append('<link rel="stylesheet" href="<?php echo base_url();?>templates/base/web/css/datepicker.css" type="text/css" />');
//-->
</script>
<script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/bootstrap/bootstrap-datepicker.js"></script>

<h1><?php echo lang('account_edit_heading'); ?></h1>

<?php echo toc_validation_errors('account_edit'); ?>


<form name="account_edit" action="<?php echo site_url('account/edit/save'); ?>" method="post" class="form-horizontal">

	<div class="module-box"> 

    	<h6><em class="pull-right"><?php echo lang('form_required_information'); ?></em><?php echo lang('my_account_title'); ?></h6>
    
        <?php
            if (config('ACCOUNT_GENDER') > -1) :
        ?>
        <div class="control-group">
            <label class="control-label" for="gender1"><?php echo lang('field_customer_gender') . ((config('ACCOUNT_GENDER') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
                <label class="radio inline" for="gender1"><input type="radio" value="m" id="gender1" name="gender" <?php echo set_radio('gender', 'm', isset($customers_gender) && $customers_gender == 'm'); ?> /><?php echo lang('gender_male'); ?></label>
                <label class="radio inline" for="gender2"><input type="radio" value="f" id="gender2" name="gender" <?php echo set_radio('gender', 'f', isset($customers_gender) && $customers_gender == 'f'); ?> /><?php echo lang('gender_female'); ?></label>
            </div>
        </div>
            
        <?php
            endif;
        ?>
        <div class="control-group">
            <label class="control-label" for="firstname"><?php echo lang('field_customer_first_name'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="firstname" name="firstname" value="<?php echo isset($customers_firstname) ? $customers_firstname : set_value('firstname'); ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="firstname"><?php echo lang('field_customer_last_name'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="lastname" name="lastname" value="<?php echo isset($customers_lastname) ? $customers_lastname : set_value('lastname'); ?>" />
            </div>
        </div>
        <?php 
            if (config('ACCOUNT_DATE_OF_BIRTH') == '1') :
        ?>
        <div class="control-group">
            <label class="control-label" for="dob_days"><?php echo lang('field_customer_date_of_birth'); ?><em>*</em></label>
            <div class="controls">
                <div id="dob_days" class="input-append date" data-date="<?php echo isset($dob_days) ? $dob_days : set_value('dob_days'); ?>" data-date-format="yyyy-mm-dd">
                    <input type="text" name="dob_days" value="<?php echo isset($dob_days) ? $dob_days : set_value('dob_days'); ?>" /><span class="add-on"><i class="icon-th"></i></span>
                </div>
            </div>
        </div>
        <?php 
            endif;
        ?>
        <div class="control-group">
            <label class="control-label" for="email_address"><?php echo lang('field_customer_email_address'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="email_address" name="email_address" value="<?php echo isset($customers_email_address) ? $customers_email_address : set_value('email_address'); ?>" />
            </div>
        </div>
        <?php 
            if (config('ACCOUNT_NEWSLETTER') == '1') :
        ?>
        <div class="control-group">
            <label class="control-label" for="newsletter"><?php echo lang('field_customer_newsletter'); ?></label>
            <div class="controls">
            	<input type="checkbox" value="1" id="newsletter" name="newsletter" <?php echo set_checkbox('newsletter', '1', isset($customers_newsletter) && $customers_newsletter == '1'); ?> />
            </div>
        </div>
        <?php 
            endif;
        ?>
    </div>
    <div class="control-group">
    	<a href="<?php echo site_url('account'); ?>" class="btn btn-small btn-info pull-left"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_back'); ?></a>
        <button type="submit" class="btn btn-small btn-info pull-right"><i class="icon-ok-sign icon-white"></i> <?php echo lang('button_continue'); ?></button>
    </div>
</form>

<script type="text/javascript">
<!--
$( "#dob_days").datepicker();
//-->
</script>