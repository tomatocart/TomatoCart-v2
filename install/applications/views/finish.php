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

    //get www location
    $www_location = 'http://' . $_SERVER['HTTP_HOST'];
    
    if (isset($_SERVER['REQUEST_URI']) && (empty($_SERVER['REQUEST_URI']) === false)) {
        $www_location .= $_SERVER['REQUEST_URI'];
    } else {
        $www_location .= $_SERVER['SCRIPT_FILENAME'];
    }
    
    $www_location = substr($www_location, 0, strpos($www_location, 'install'));
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
    		<h1><?php echo lang('page_title_finished'); ?></h1>
    		
    		<div><?php echo lang('text_finished'); ?></div>
    		
    		<p class="alert alert-error">
    		    <b><?php echo lang('text_remove_install_dir'); ?></b>
    		</p>
    		
    		<div class="row-fluid large">
    			<div class="span6"><a class="btn btn-large catalog" alt="Catalog" href="<?php echo $www_location; ?>" target="_blank"><i class="icon-home"></i>&nbsp;&nbsp;Catalog</a></div>
    			<div class="span6"><a class="btn btn-large admin" alt="Administration Tool" href="<?php echo $www_location; ?>admin" target="_blank"><i class="icon-user"></i>&nbsp;&nbsp;Administration Tool</a></div>
    		</div>
    	</div>
	</div>
</div>