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

<div class="box box-contact-us">
    <h4><?php echo lang('box_contact_us_heading'); ?></h4>
    
    <div class="contents">
    	<strong><?php echo config('STORE_NAME'); ?></strong>
    	<p><?php echo nl2br(config('STORE_NAME_ADDRESS')); ?></p>
    	<strong><?php echo lang('field_email'); ?></strong>
    	<p><?php echo nl2br(config('STORE_OWNER_EMAIL_ADDRESS')); ?></p>
    </div>
</div>