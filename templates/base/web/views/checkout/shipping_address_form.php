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
        if ($is_logged_on && count($address_books) > 0) :
    ?>
        <li>
            <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
            <?php echo '<b>' . lang('please_select') . '</b><br />'; ?>
            </div>
            
            <p style="margin-top: 0px;"><?php echo lang('choose_shipping_address'); ?></p>
        </li>    
    	<li style="margin-bottom: 10px">
        <?php
            $address = array();
            foreach ($address_books as $address_book) :
                $address[$address_book['address_book_id']] = address_format($address_book, ', ');
            endforeach;
            
            echo form_dropdown('sel_shipping_address', $address, null, 'id="sel_shipping_address"');
        ?>
    </li>
<?php
    endif;
?>
    
    <div id="shippingAddressDetails" style="display: <?php echo ($create_shipping_address == FALSE) ? 'none' : ''; ?>">
    
    <?php 
     if (config('ACCOUNT_GENDER') > -1) :
    ?>
        <div class="control-group">
            <?php echo draw_label(lang('field_customer_gender'), null, 'fake', (config('ACCOUNT_GENDER') > 0), 'class="control-label"'); ?>
            <div class="controls">
            	<label class="radio inline" for="gender1"><input type="radio" value="m" id="gender1" name="shipping_gender" <?php echo (isset($shipping_gender) && $shipping_gender == 'm') ? 'checked="checked"' : ''; ?> /><?php echo lang('gender_male'); ?></label>
            	<label class="radio inline" for="gender2"><input type="radio" value="f" id="gender2" name="shipping_gender" <?php echo (isset($shipping_gender) && $shipping_gender == 'f') ? 'checked="checked"' : ''; ?> /><?php echo lang('gender_female'); ?></label>
            </div>
        </div>
    <?php 
     endif;
    ?>
        <div class="control-group">
            <label class="control-label" for="shipping_firstname"><?php echo lang('field_customer_first_name'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="shipping_firstname" name="shipping_firstname" value="<?php echo $shipping_firstname; ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="shipping_lastname"><?php echo lang('field_customer_last_name'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="shipping_lastname" name="shipping_lastname" value="<?php echo $shipping_lastname; ?>" />
            </div>
        </div>
    <?php 
     if (config('ACCOUNT_COMPANY') > -1) :
    ?>
        <div class="control-group">
            <label class="control-label" for="shipping_company"><?php echo lang('field_customer_company') . ((config('ACCOUNT_COMPANY') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<input type="text" id="shipping_company" name="shipping_company" value="<?php echo $shipping_company; ?>" />
            </div>
        </div>
    <?php 
     endif;
    ?>
        <div class="control-group">
            <label class="control-label" for="shipping_street_address"><?php echo lang('field_customer_street_address'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="shipping_street_address" name="shipping_street_address" value="<?php echo $shipping_street_address; ?>" />
            </div>
        </div>
    <?php
      if (config('ACCOUNT_SUBURB') > -1) :
    ?>
        <div class="control-group">
            <label class="control-label" for="shipping_suburb"><?php echo lang('field_customer_suburb') . ((config('ACCOUNT_SUBURB') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<input type="text" id="shipping_suburb" name="shipping_suburb" value="<?php echo $shipping_suburb; ?>" />
            </div>
        </div>
    <?php
      endif;
    ?>
    
    <?php
      if (config('ACCOUNT_POST_CODE') > -1) :
    ?>
        <div class="control-group">
            <label class="control-label" for="shipping_postcode"><?php echo lang('field_customer_post_code') . ((config('ACCOUNT_POST_CODE') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<input type="text" id="shipping_postcode" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" />
            </div>
        </div>
    <?php
      endif;
    ?>
        <div class="control-group">
            <label class="control-label" for="shipping_city"><?php echo lang('field_customer_city'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="shipping_city" name="shipping_city" value="<?php echo $shipping_city; ?>" />
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="shipping_country"><?php echo lang('field_customer_country'); ?><em>*</em></label>
            <div class="controls">
            	<?php echo form_dropdown('shipping_country', $countries, $shipping_country_id, 'id="shipping_country"'); ?>
            </div>
        </div>
        
    <?php 
        if (config('ACCOUNT_STATE') > -1) :
    ?>
        <div id="li-shipping-state" class="control-group">
        	<label class="control-label" for="shipping_state"><?php echo lang('field_customer_state') . ((config('ACCOUNT_STATE') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            <?php 
              if (count($states) > 0) :
            ?>
                <?php echo form_dropdown('shipping_state', $states, $zone_code, 'id="shipping_state"'); ?>
            <?php 
              else :
            ?>
    			<input type="text" id="shipping_state" name="shipping_state" value="<?php echo $shipping_state; ?>" />
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
            <label class="control-label" for="shipping_telephone"><?php echo lang('field_customer_telephone_number') . ((config('ACCOUNT_TELEPHONE') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<input type="text" id="shipping_telephone" name="shipping_telephone" value="<?php echo $shipping_telephone; ?>" />
            </div>
        </div>
    <?php 
        endif; 
    ?>
    
    <?php 
        if (config('ACCOUNT_FAX') > -1) :
    ?>
        <div class="control-group">
            <label class="control-label" for="shipping_fax"><?php echo lang('field_customer_fax_number') . ((config('ACCOUNT_FAX') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<input type="text" id="shipping_fax" name="shipping_fax" value="<?php echo $shipping_fax; ?>" />
            </div>
        </div>
    <?php 
        endif; 
    ?>
    </div>
    
    <li style="height:10px;line-height:10px">&nbsp;</li>
    <div class="control-group">
        <div class="controls">
    		<label class="control-label checkbox" for="create_shipping_address"><?php echo form_checkbox('create_shipping_address', 'on', $create_shipping_address, 'id="create_shipping_address"'); ?> <?php echo lang('create_new_shipping_address'); ?></label>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
      		<button type="submit" class="btn btn-small btn-info pull-right" id="btn-save-shipping-form"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></button>
        </div>
    </div>
</div>