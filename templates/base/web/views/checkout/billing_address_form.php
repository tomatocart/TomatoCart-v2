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

<div class="module-box form-horizontal">
    <?php 
        if (!$is_logged_on) : 
    ?>
    <div class="control-group">
        <label class="control-label" for="billing_email_address"><?php echo lang('field_customer_email_address'); ?><em>*</em></label>
        <div class="controls">
        	<input type="text" id="billing_email_address" name="billing_email_address" value="<?php echo $billing_email_address; ?>" />
        </div>
    </div>
    <?php 
        endif; 
    ?>
    		
    <?php 
        if ((!$is_logged_on) && ($checkout_method == 'register')) : 
    ?>
    <div class="control-group">
        <label class="control-label" for="billing_password"><?php echo lang('field_customer_password'); ?><em>*</em></label>
        <div class="controls">
        	<input type="password" id="billing_password" name="billing_password" />
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="confirmation"><?php echo lang('field_customer_password_confirmation'); ?><em>*</em></label>
        <div class="controls">
        	<input type="password" id="confirmation" name="confirmation" />
        </div>
    </div>
    <?php 
        endif; 
    ?>
    
    <?php
        if ($is_logged_on && count($address_books) > 0) :
    ?>
        <li>
            <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
            <?php echo '<b>' . lang('please_select') . '</b><br />'; ?>
            </div>
            
            <p style="margin-top: 0px;"><?php echo lang('choose_billing_address'); ?></p>
        </li>    
        <li style="margin-bottom: 10px">
        <?php
            $address = array();

            foreach ($address_books as $address_book) :
                $address[$address_book['address_book_id']] = address_format($address_book, ', ');
            endforeach;
            
            echo form_dropdown('sel_billing_address', $address, null, 'id="sel_billing_address"');
        ?>
        </li>
    <?php
        endif;
    ?>
    
    <div id="billingAddressDetails" style="display: <?php echo ($create_billing_address == FALSE) ? 'none' : ''; ?>">
    
        <?php 
            if (config('ACCOUNT_GENDER') > -1) :
        ?>
        <div class="control-group">
            <label class="control-label" for="gender1"><?php echo lang('field_customer_gender') . ((config('ACCOUNT_GENDER') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<label class="radio inline" for="gender1"><input type="radio" value="m" id="gender1" name="billing_gender" <?php echo (isset($billing_gender) && $billing_gender == 'm') ? 'checked="checked"' : ''; ?> /><?php echo lang('gender_male'); ?></label>
            	<label class="radio inline" for="gender2"><input type="radio" value="f" id="gender2" name="billing_gender" <?php echo (isset($billing_gender) && $billing_gender == 'f') ? 'checked="checked"' : ''; ?> /><?php echo lang('gender_female'); ?></label>
            </div>
        </div>
        <?php 
            endif;
        ?>
        <div class="control-group">
            <label class="control-label" for="billing_firstname"><?php echo lang('field_customer_first_name'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="billing_firstname" name="billing_firstname" value="<?php echo $billing_firstname; ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="billing_lastname"><?php echo lang('field_customer_last_name'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="billing_lastname" name="billing_lastname" value="<?php echo $billing_lastname; ?>" />
            </div>
        </div>
    <?php 
        if (config('ACCOUNT_COMPANY') > -1) :
    ?>
        <div class="control-group">
            <label class="control-label" for="billing_company"><?php echo lang('field_customer_company') . ((config('ACCOUNT_COMPANY') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<input type="text" id="billing_company" name="billing_company" value="<?php echo $billing_company; ?>" />
            </div>
        </div>
    <?php 
        endif;
    ?>
        <div class="control-group">
            <label class="control-label" for="billing_street_address"><?php echo lang('field_customer_street_address'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="billing_street_address" name="billing_street_address" value="<?php echo $billing_street_address; ?>" />
            </div>
        </div>
    <?php
        if (config('ACCOUNT_SUBURB') > -1) :
    ?>
        <div class="control-group">
            <label class="control-label" for="billing_suburb"><?php echo lang('field_customer_suburb') . ((config('ACCOUNT_SUBURB') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<input type="text" id="billing_suburb" name="billing_suburb" value="<?php echo $billing_suburb; ?>" />
            </div>
        </div>
    <?php
        endif;
    ?>
    
    <?php
        if (config('ACCOUNT_POST_CODE') > -1) :
    ?>
        <div class="control-group">
            <label class="control-label" for="billing_postcode"><?php echo lang('field_customer_post_code') . ((config('ACCOUNT_POST_CODE') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<input type="text" id="billing_postcode" name="billing_postcode" value="<?php echo $billing_postcode; ?>" />
            </div>
        </div>
    <?php
        endif;
    ?>
        <div class="control-group">
            <label class="control-label" for="billing_city"><?php echo lang('field_customer_city'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="billing_city" name="billing_city" value="<?php echo $billing_city; ?>" />
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="billing_country"><?php echo lang('field_customer_country'); ?><em>*</em></label>
            <div class="controls">
            	<?php echo form_dropdown('billing_country', $countries, $billing_country_id, 'id="billing_country"'); ?>
            </div>
        </div>
    
    <?php 
        if (config('ACCOUNT_STATE') > -1) :
    ?>
        <div id="li-billing-state" class="control-group">
        	<label class="control-label" for="billing_state"><?php echo lang('field_customer_state') . ((config('ACCOUNT_STATE') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            <?php 
                if (count($states) > 0) :
            ?>
                <?php echo form_dropdown('billing_state', $states, $zone_code, 'id="billing_state"'); ?>
            <?php 
                else :
            ?>
    			<input type="text" id="billing_state" name="billing_state" value="<?php echo $billing_state; ?>" />
            <?php   
                endif;
            ?>
            </div>
        </div>
    <?php 
        endif;
    ?>
    
    <?php 
        if (config('ACCOUNT_TELEPHONE') > -1) :
    ?>
        <div class="control-group">
            <label class="control-label" for="billing_telephone"><?php echo lang('field_customer_telephone_number') . ((config('ACCOUNT_TELEPHONE') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<input type="text" id="billing_telephone" name="billing_telephone" value="<?php echo $billing_telephone; ?>" />
            </div>
        </div>
    <?php 
        endif;
    ?>
    
    <?php 
        if (config('ACCOUNT_FAX') > -1) :
    ?>
        <div class="control-group">
            <label class="control-label" for="billing_fax"><?php echo lang('field_customer_fax_number') . ((config('ACCOUNT_FAX') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<input type="text" id="billing_fax" name="billing_fax" value="<?php echo $billing_fax; ?>" />
            </div>
        </div>
    <?php 
        endif;
    ?>
    </div>
    
    <div class="control-group">
        <div class="controls">
    		<label class="control-label checkbox" for="create_billing_address"><?php echo form_checkbox('create_billing_address', 'on', $create_billing_address, 'id="create_billing_address"'); ?> <?php echo lang('create_new_billing_address'); ?></label>
        </div>
    </div>
    
<?php 
    if ($is_virtual_cart === FALSE) :
?>
    <div class="control-group">
        <div class="controls">
    		<label class="control-label checkbox" for="ship_to_this_address"><?php echo form_checkbox('ship_to_this_address', 'on', $ship_to_this_address, 'id="ship_to_this_address"'); ?><?php echo lang('ship_to_this_address'); ?></label>
        </div>
    </div>
<?php 
    endif;
?> 
    <div class="control-group">
        <div class="controls">
    		<button type="submit" class="btn btn-small btn-info pull-right" id="btn-save-billing-form"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></button>
        </div>
    </div>
</div>