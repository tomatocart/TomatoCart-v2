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

<h1><?php echo lang('create_account_success_heading'); ?></h1>

<div class="module-box row-fluid">
    <div class="span3"><img src="<?php echo image_url('account_successs.png'); ?>" /></div>
    
    <div class="span9">
      	<p><?php echo sprintf(lang('success_account_created'), site_url('contact_us')); ?></p>
    </div>
</div>

<div class="controls clearfix">
	<a href="<?php echo site_url('account'); ?>" class="btn btn-small btn-info pull-right"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></a>
</div>