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

<h1><?php echo (isset($address_book_id) && is_numeric($address_book_id)) ? lang('address_book_edit_entry_heading') : lang('address_book_add_entry_heading'); ?></h1>

<?php echo toc_validation_errors('address_book'); ?>

<form name="address_book" action="<?php echo site_url('account/address_book/save'); ?>" method="post" class="form-horizontal">

	<div class="module-box">

		<h6><em class="pull-right"><?php echo lang('form_required_information'); ?></em><?php echo lang('address_book_new_address_title'); ?></h6>
    
        <?php
            if (config('ACCOUNT_GENDER') > -1) :
        ?>
        <div class="control-group">
            <label class="control-label" for="gender1"><?php echo lang('field_customer_gender') . ((config('ACCOUNT_GENDER') > 0) ? '<em>*</em>' : ''); ?></label>
            <div class="controls">
            	<label class="radio inline" for="gender1"><input type="radio" value="m" id="gender1" name="gender" <?php echo set_radio('gender', 'm', isset($gender) && $gender == 'm'); ?> /><?php echo lang('gender_male'); ?></label>
            	<label class="radio inline" for="gender2"><input type="radio" value="f" id="gender2" name="gender" <?php echo set_radio('gender', 'f', isset($gender) && $gender == 'f'); ?> /><?php echo lang('gender_female'); ?></label>
            </div>
        </div>
        <?php
            endif;
        ?>
        <div class="control-group">
            <label class="control-label" for="firstname"><?php echo lang('field_customer_first_name'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="firstname" name="firstname" value="<?php echo (isset($firstname)) ? $firstname : set_value('firstname'); ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="firstname"><?php echo lang('field_customer_last_name'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="lastname" name="lastname" value="<?php echo isset($lastname) ? $lastname : set_value('lastname'); ?>" />
            </div>
        </div>
        <?php
            if (config('ACCOUNT_COMPANY') > -1) :
        ?>
        <div class="control-group">
            <label class="control-label" for="company"><?php echo lang('field_customer_company') . (config('ACCOUNT_COMPANY') > 0 ? '<em>*</em>' : '') ;?></label>
            <div class="controls">
            	<input type="text" id="company" name="company" value="<?php echo isset($company) ? $company : set_value('company'); ?>" />
            </div>
        </div>
        <?php
            endif;
        ?>
        <div class="control-group">
            <label class="control-label" for="street_address"><?php echo lang('field_customer_street_address'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="street_address" name="street_address" value="<?php echo isset($street_address) ? $street_address : set_value('street_address'); ?>" />
            </div>
        </div>
        <?php
            if (config('ACCOUNT_SUBURB') > -1) :
        ?>
        <div class="control-group">
            <label class="control-label" for="suburb"><?php echo lang('field_customer_suburb') . (config('ACCOUNT_SUBURB') > 0 ? '<em>*</em>' : '') ;?></label>
            <div class="controls">
            	<input type="text" id="suburb" name="suburb" value="<?php echo isset($suburb) ? $suburb : set_value('suburb'); ?>" />
            </div>
        </div>
        <?php
            endif;
            
            if (config('ACCOUNT_POST_CODE') > -1) :
        ?>
        <div class="control-group">
            <label class="control-label" for="postcode"><?php echo lang('field_customer_post_code') . (config('ACCOUNT_POST_CODE') > 0 ? '<em>*</em>' : '') ;?></label>
            <div class="controls">
            	<input type="text" id="postcode" name="postcode" value="<?php echo isset($postcode) ? $postcode : set_value('postcode'); ?>" />
            </div>
        </div>
        <?php
            endif;
        ?>
        <div class="control-group">
            <label class="control-label" for="city"><?php echo lang('field_customer_city'); ?><em>*</em></label>
            <div class="controls">
            	<input type="text" id="city" name="city" value="<?php echo isset($city) ? $city : set_value('city'); ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="country"><?php echo lang('field_customer_country'); ?><em>*</em></label>
            <div class="controls">
    			<select id="country" name="country">
                <?php
                    if (isset($countries) && !empty($countries)) :
                        foreach($countries as $country) :
                ?>
                        <option value="<?php echo $country['id']; ?>" <?php echo set_select('country', $country['id'], isset($country_id) && ($country_id == $country['id'])); ?>><?php echo $country['name']; ?></option>
                <?php
                        endforeach;
                    
                    else :
                ?>
                        <option value=""><?php echo lang('pull_down_default'); ?></option>
                <?php
                    endif;
                ?>
                </select>
            </div>
        </div>
        <?php
            if (config('ACCOUNT_STATE') > -1) :
        ?>
        <div class="control-group">
            <label class="control-label" for="state"><?php echo lang('field_customer_state') . (config('ACCOUNT_STATE') > 0 ? '<em>*</em>' : '') ;?></label>
            <div id="li-state" class="controls">
                <?php 
                    if (count($states) > 0) :
                ?>
                    <?php echo form_dropdown('state', $states, isset($zone_code) ? $zone_code : NULL, 'id="state"'); ?>
                <?php 
                    else :
                ?>
        			<input type="text" id="state" name="state" value="<?php echo $state; ?>" />
                <?php   
                    endif;
                ?>
            </div>
        </div>
        <?php
            endif;
            
            if (config('ACCOUNT_TELEPHONE') > -1) :
        ?>
        <div class="control-group">
            <label class="control-label" for="telephone"><?php echo lang('field_customer_telephone_number') . (config('ACCOUNT_TELEPHONE') > 0 ? '<em>*</em>' : '') ;?></label>
            <div class="controls">
            	<input type="text" id="telephone" name="telephone" value="<?php echo isset($telephone) ? $telephone : set_value('telephone'); ?>" />
            </div>
        </div>
        <?php
            endif;
        ?>
        <?php
            if (config('ACCOUNT_FAX') > -1) :
        ?>
        <div class="control-group">
            <label class="control-label" for="fax"><?php echo lang('field_customer_fax_number') . (config('ACCOUNT_FAX') > 0 ? '<em>*</em>' : '') ;?></label>
            <div class="controls">
            	<input type="text" id="fax" name="fax" value="<?php echo isset($fax) ? $fax : set_value('fax'); ?>" />
            </div>
        </div>
        <?php
            endif;
        ?>
        <?php
            if (isset($display_primary) && $display_primary === TRUE) :
        ?>
        <div class="control-group">
        	<label class="control-label" for="fax">&nbsp;</label>
            <label class="control-label checkbox" for="primary"><input type="checkbox" value="1" id="primary" name="primary" <?php echo set_checkbox('primary', '1'); ?> /> <?php echo lang('set_as_primary'); ?></label>
        </div>
        <?php
            endif;
        ?>
        
        <?php
            if (isset($address_book_id) && is_numeric($address_book_id)) :
        ?>
              <input type="hidden" name="address_book_id" value="<?php echo $address_book_id; ?>" />
        <?php
            endif;
        ?>
    </div>
    
    <div class="control-group">
    	<a href="<?php echo site_url('account/address_book'); ?>" class="btn btn-small btn-info pull-left"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_back'); ?></a>
        <button type="submit" class="btn btn-small btn-success pull-right"><i class="icon-ok-sign icon-white"></i> <?php echo lang('button_continue'); ?></button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        $('#country').bind('change', function() {
            $.ajax({
              type: 'post',
              cache: 'false',
              url: '<?php echo site_url('account/address_book/get_country_states') ?>',
              data: {countries_id: $('#country').val()},
              dataType: 'json',
              success: function(response) {
                  if (response.success == true) {
                      $('#li-state').html(response.options);
                  }
              }
            });
        });
    });
</script>