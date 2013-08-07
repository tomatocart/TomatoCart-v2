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

<h1><?php echo lang('info_contact_heading'); ?></h1>

<?php
   echo toc_validation_errors('contact');
?>

<div class="module-box">
	<div class="row-fluid">
		<div class="span6">
            <form name="contact" action="<?php echo site_url('contact_save');?>" method="post">
                <?php 
                    if (!empty($departments)) : 
                ?>
                <div class="control-group">
                    <label class="control-label" for="department_email"><?php echo lang('contact_departments_title');?><em>*</em></label>
                    <div class="controls">
                        <select name="department_email" id="department_email">
                        <?php
                            foreach($departments as $email => $title) :
                        ?>
                              <option value="<?php echo $email; ?>" <?php echo set_select('department_email', $email); ?>><?php echo $title; ?></option>
                        <?php
                            endforeach;
                        ?>
                        </select>
                    </div>
                </div>
                <?php
                    endif;
                ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo lang('contact_name_title'); ?><em>*</em></label>
                    <div class="controls">
                    	<input type="text" id="name" name="name" value="<?php echo set_value('name'); ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="telephone"><?php echo lang('contact_telephone_title'); ?><em>*</em></label>
                    <div class="controls">
                    	<input type="text" id="telephone" name="telephone" value="<?php echo set_value('telephone'); ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email"><?php echo lang('contact_email_address_title'); ?><em>*</em></label>
                    <div class="controls">
                    	<input type="text" id="email" name="email" value="<?php echo set_value('email'); ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="enquiry"><?php echo lang('contact_enquiry_title'); ?><em>*</em></label>
                    <div class="controls">
                    	<textarea name="enquiry" id="enquiry" cols="39" rows="5"><?php echo set_value('enquiry'); ?></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                    	<button class="btn btn-small btn-info"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></button>
                    </div>
                </div>
            </form>
		</div>
		<div class="span6">
            <h6><?php echo lang('contact_title'); ?></h6>
            
            <div class="content">
                <div class="storeName"><?php echo nl2br(config('STORE_NAME_ADDRESS')); ?></div>
                <div class="storeAddress">
                	<strong><?php echo lang('contact_store_address_title'); ?></strong><br /><img src="<?php echo image_url('arrow_south_east.gif') ?>" />
                </div>
                <p><?php echo lang('contact'); ?></p>
            </div>
		</div>
	</div>
</div>

