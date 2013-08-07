<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */
?>

<div class="container clearfix">
	<div class="row-fluid">
    	<div class="span3">
            <ul class="nav nav-list">
                <li class="<?php echo ($step == 1) ? 'active' : ''; ?>"><a href="javascript:void(0);" title="<?php echo lang('nav_menu_step_1_text'); ?>"><i class="icon-chevron-right"></i> <?php echo lang('nav_menu_step_1_text'); ?></a></li>
                <li class="<?php echo ($step == 2) ? 'active' : ''; ?>"><a href="javascript:void(0);" title="<?php echo lang('nav_menu_step_2_text'); ?>"><i class="icon-chevron-right"></i> <?php echo lang('nav_menu_step_2_text'); ?></a></li>
                <li class="<?php echo ($step == 3) ? 'active' : ''; ?>"><a href="javascript:void(0);" title="<?php echo lang('nav_menu_step_3_text'); ?>"><i class="icon-chevron-right"></i> <?php echo lang('nav_menu_step_3_text'); ?></a></li>
                <li class="<?php echo ($step == 4) ? 'active' : ''; ?>"><a href="javascript:void(0);" title="<?php echo lang('nav_menu_step_4_text'); ?>"><i class="icon-chevron-right"></i> <?php echo lang('nav_menu_step_4_text'); ?></a></li>
                <li class="<?php echo ($step == 5) ? 'active' : ''; ?>"><a href="javascript:void(0);" title="<?php echo lang('nav_menu_step_5_text'); ?>"><i class="icon-chevron-right"></i> <?php echo lang('nav_menu_step_5_text'); ?></a></li>
			</ul>
    	</div>
    	<div class="span9 content">
        	<h1><?php echo lang('page_title_welcome'); ?></h1>
        
        	<p><?php echo lang('text_welcome'); ?></p>
            
			<h2><?php echo lang('box_title_license'); ?></h2>
        
            <div class="license">
                <?php echo $licence; ?>
            </div>
        	
        	<div class="control-group clearfix" style="margin-top: 8px;">
            	<label class="checkbox control-label pull-right" for="license"><?php echo lang('label_agree_to_the_license'); ?>&nbsp;&nbsp;<input type="checkbox" id="license" name="license" /></label>
            </div>
            
            <div class="control-group clearfix">
            	<a id="continue-button" href="<?php echo site_url('index/index/check'); ?>" class="btn btn-info pull-right disabled"><i class="icon-ok icon-white"></i>&nbsp;&nbsp;<?php echo lang('image_button_continue'); ?></a>
            </div>
    	</div>
    </div>
</div>

<script type="text/javascript">
(function($){
	var $button = $('#continue-button');

	//observe the checkbox event
	$('#license').on('change',function() {
		if($(this).attr('checked')) {
		    $button.removeClass('disabled');
		} else {
		    $button.addClass('disabled');
		}
	});

	$button.on('click',function() {
		var $this = $(this);

		//return false to disable link click
		if ($this.hasClass('disabled')) {
			return false;
		}
	});
})(jQuery);
</script>