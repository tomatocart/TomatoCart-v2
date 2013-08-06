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

<h1><?php echo lang('password_forgotten_heading'); ?></h1>

<?php echo toc_validation_errors('password_forgotten'); ?>

<form name="password_forgotten" action="<?php echo site_url('account/password_forgotten/process');?>" method="post">

    <div class="module-box">
        <h6 class="title"><?php echo lang('password_forgotten_heading'); ?></h6>
        
        <p><?php echo lang('password_forgotten'); ?></p>
		
		<label for="email_address"><?php echo lang('field_customer_email_address'); ?></label>
        <input type="text" id="email_address" name="email_address">
    </div>
    
    <div class="row-fluid">
        <div class="span6"><a class="btn btn-small btn-info" href="<?php echo site_url('account/login'); ?>"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_back'); ?></a></div>
        <div class="span6"><button type="submit" class="btn btn-small btn-info pull-right"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></button></div>
    </div>

</form>
