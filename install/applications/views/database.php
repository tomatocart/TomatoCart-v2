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

    $db_table_types = array('mysql'=> 'MySQL - MyISAM (Default)',
                            'mysqli'=> 'MySQLi (PHP 5 / MySQL 4.1)');
  
    $messages = array(
        'connection_test' => array('icon' => 'progress.gif', 'message' => lang('rpc_database_connection_test')),
        'database_connection_error' => array('icon' => 'failed.gif', 'message' => lang('rpc_database_connection_error')),
        'database_importing' => array('icon' => 'progress.gif', 'message' => lang('rpc_database_importing')),
        'database_imported' => array('icon' => 'success.gif', 'message' => lang('rpc_database_imported')),
        'database_import_error' => array('icon' => 'failed.gif', 'message' => lang('rpc_database_import_error')));
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
      	  	<div id="mBox"><div id="mBoxContents"></div></div>  
    
            <div id="mainBlock">
            </div>
    	</div>
    	<div class="span9 content">
          	<h1><?php echo lang('page_title_database_server_setup'); ?></h1>
          
          	<p><?php echo lang('text_database_server_setup'); ?></p>
            
            <form name="install" id="installForm" action="<?php echo site_url('index/index/db_create'); ?>" method="post" onsubmit="prepareDB(); return false;" class="form-horizontal">
                <div class="info">
                    <div class="control-group">
                        <label class="control-label" for="DB_SERVER"><?php echo lang('param_database_server'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="DB_SERVER" name="DB_SERVER" />
                        </div>
                        <div class="description"><?php echo lang('param_database_server_description'); ?></div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="DB_SERVER_USERNAME"><?php echo lang('param_database_username'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="DB_SERVER_USERNAME" name="DB_SERVER_USERNAME" />
                        </div>
                        <div class="description"><?php echo lang('param_database_username_description'); ?></div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="DB_SERVER_PASSWORD"><?php echo lang('param_database_password'); ?>:</label>
                        <div class="controls">
                        	<input type="password" id="DB_SERVER_PASSWORD" name="DB_SERVER_PASSWORD" />
                        </div>
                        <div class="description"><?php echo lang('param_database_password_description'); ?></div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="DB_DATABASE"><?php echo lang('param_database_name'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="DB_DATABASE" name="DB_DATABASE" />
                        </div>
                        <div class="description"><?php echo lang('param_database_name_description'); ?></div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="DB_DATABASE_CLASS"><?php echo lang('param_database_type'); ?>:</label>
                        <div class="controls">
                        	<?php echo form_dropdown('DB_DATABASE_CLASS', $db_table_types); ?>
                        </div>
                        <div class="description"><?php echo lang('param_database_type_description'); ?></div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="DB_TABLE_PREFIX"><?php echo lang('param_database_prefix'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="DB_TABLE_PREFIX" name="DB_TABLE_PREFIX" <?php if(!empty($DB_TABLE_PREFIX)){echo "value='$DB_TABLE_PREFIX'";}else{echo "value='toc_'";} ?> />
                        </div>
                        <div class="description"><?php echo lang('param_database_prefix_description'); ?></div>
                    </div>
                </div>
                
                <div class="control-group">
                    <div class="controls pull-right">
                    	<a href="<?php echo site_url(); ?>" class="btn btn-info" href="<?php echo site_url(); ?>"><i class="icon-remove icon-white"></i> &nbsp;<?php echo lang('image_button_cancel'); ?></a>
                        <button id="continue-button" type="button" class="btn btn-info"><i class="icon-ok icon-white"></i> &nbsp;<?php echo lang('image_button_continue'); ?></button>
                    </div>
                </div>
            </form>
        </div>
	</div>
</div>
<script type="text/javascript">
    (function($){
        var $mBox = $('#mBox');
        var $mBoxContents = $('#mBoxContents');
        var tpl_message = '<p style="width:180px;"><img src="<?php echo base_url(); ?>assets/img/{info_icon}" align="right" hspace="5" vspace="5" border="0" />{info_message}</p>';
        var messages = <?php echo json_encode($messages); ?>;

        /**
		 * Display message
		 */
		function display_message(type, feedback) {
			var data = messages[type];
			var info = tpl_message.replace('{info_icon}', data.icon);

			if (feedback == undefined) {
			    info = info.replace('{info_message}', data.message);
			} else {
				feedback = data.message.replace('%s', feedback);
				info = info.replace('{info_message}', feedback);
		    }
			    
			$mBoxContents.html(info);
		}
        
        $('#continue-button').bind('click', function() {
            var $this = $(this);

            //disable continue button
            if ($this.hasClass('disabled')) {
				return;
            }

            //show message box
            $mBox.show();
            display_message('connection_test');
            
            $this.addClass('disabled');
            $.ajax({
                type: 'post',
                url: '<?php echo site_url('database/connect_db') ?>',
                data: $('input[name=DB_SERVER], input[name=DB_SERVER_USERNAME], input[name=DB_SERVER_PASSWORD], input[name=DB_DATABASE], select[name=DB_DATABASE_CLASS], input[name=DB_TABLE_PREFIX]'),
                dataType: 'json',
                success: function(result) {
                    //if false then enable and button and retry
                    if (result.success == false) {
						//enable the button
                        $this.removeClass('disabled');
                        
                        display_message('database_connection_error', result.error);
                    } 
					//if success start to import sql
                    else {
                        display_message('database_importing');
                        
                        $.ajax({
                            type: 'post',
                            url: '<?php echo site_url('database/import_sql') ?>',
                            data: $('input[name=DB_SERVER], input[name=DB_SERVER_USERNAME], input[name=DB_SERVER_PASSWORD], input[name=DB_DATABASE], select[name=DB_DATABASE_CLASS], input[name=DB_TABLE_PREFIX]'),
                            dataType: 'json',
                            error: function() {
                                $this.removeClass('disabled');
                            },
                            success: function(result) {
                                $this.removeClass('disabled');
                              
                                if (result.success == false) {
                                    display_message('database_connection_error', result.error);
                                } else {
                                    display_message('database_imported');

                                    setTimeout(function() {
                                        window.location = '<?php echo site_url('index/index/setting'); ?>';
                                    }, 3000);
                                }
                            }
                        });
                    }
                }
            });
        });
    })($);
</script>