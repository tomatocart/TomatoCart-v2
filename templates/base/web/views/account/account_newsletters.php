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

<h1><?php echo lang('newsletters_heading'); ?></h1>

<?php echo toc_validation_errors('newsletters'); ?>



<form name="account_newsletter" action="<?php echo site_url('account/newsletters/save'); ?>" method="post">
	<div class="module-box">
    	<h6 class="title"><?php echo lang('newsletter_subscriptions_heading'); ?></h6>
    
        <div class="control-group">
        	<label class="checkbox"  for="newsletter_general">
            	<input type="checkbox" id="newsletter_general" name="newsletter_general" value="1" <?php echo set_checkbox('newsletter_general', '1', TRUE); ?> /><?php echo lang('newsletter_general'); ?>
            </label>
        </div>
        
        <p><?php echo lang('newsletter_general_description'); ?></p>
    </div>
    
    <div class="control-group clearfix">
		<button class="btn btn-small btn-info pull-right"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></button>
    </div>
</form>