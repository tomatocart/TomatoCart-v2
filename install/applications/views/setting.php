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
  
    //messages
    $messages = array(
        'store_name' => array('icon' => 'failed.gif', 'message' => lang('rpc_store_setting_store_name_error')),
        'store_owner' => array('icon' => 'failed.gif', 'message' => lang('rpc_store_setting_store_owner_error')),
        'email' => array('icon' => 'failed.gif', 'message' => lang('rpc_store_setting_email_error')),
        'username' => array('icon' => 'failed.gif', 'message' => lang('rpc_store_setting_username_error')),
        'password' => array('icon' => 'failed.gif', 'message' => lang('rpc_store_setting_password_error')),
        'confirm' => array('icon' => 'failed.gif', 'message' => lang('rpc_store_setting_confirm_error')),
        'database_sample_data_importing' => array('icon' => 'progress.gif', 'message' => lang('rpc_database_sample_data_importing')),
        'database_sample_data_imported' => array('icon' => 'success.gif', 'message' => lang('rpc_database_sample_data_imported')),
        'database_sample_data_import_error' => array('icon' => 'failed.gif', 'message' => lang('rpc_database_sample_data_import_error')));
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
      	  	<div id="mBox">
      	    	<div id="mBoxContents"></div>
  	  		</div>  
    
            <div id="mainBlock">
            </div>
    	</div>
    	<div class="span9 content">
          	<h1><?php echo lang('page_title_online_store_settings'); ?></h1>
          
          	<p><?php echo lang('text_online_store_settings'); ?></p>
          	
            <form name="install" id="installForm" action="<?php echo site_url('index/index/finish'); ?>" method="post" class="form-horizontal">
                <div class="info">
                    <div class="control-group">
                        <label class="control-label" for="CFG_STORE_NAME"><?php echo lang('param_store_name'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="CFG_STORE_NAME" name="CFG_STORE_NAME" value="<?php echo empty($store_name) ? '' : $store_name; ?>" />
                        </div>
                        <div class="description"><?php echo lang('param_store_name_description'); ?></div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="HTTP_WWW_ADDRESS"><?php echo lang('param_web_address'); ?></label>
                        <div class="controls">
                        	<input type="text" id="HTTP_WWW_ADDRESS" name="HTTP_WWW_ADDRESS" value="<?php echo empty($HTTP_WWW_ADDRESS) ? $www_location : $HTTP_WWW_ADDRESS; ?>" />
                        </div>
                        <div class="description"><?php echo lang('param_web_address_description'); ?></div>
                    </div>                

                    <div class="control-group">
                        <label class="control-label" for="CFG_STORE_OWNER_NAME"><?php echo lang('param_store_owner_name'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="CFG_STORE_OWNER_NAME" name="CFG_STORE_OWNER_NAME" value="<?php echo empty($store_owner_name) ? '' : $store_owner_name; ?>" />
                        </div>
                        <div class="description"><?php echo lang('param_store_owner_name_description'); ?></div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="CFG_STORE_OWNER_EMAIL_ADDRESS"><?php echo lang('param_store_owner_email_address'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="CFG_STORE_OWNER_EMAIL_ADDRESS" name="CFG_STORE_OWNER_EMAIL_ADDRESS" value="<?php echo empty($store_owner_email) ? '' : $store_owner_email; ?>" />
                        	<span class="help-inline"></span>
                        </div>
                        <div class="description"><?php echo lang('param_store_owner_email_address_description'); ?></div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="CFG_ADMINISTRATOR_USERNAME"><?php echo lang('param_administrator_username'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="CFG_ADMINISTRATOR_USERNAME" name="CFG_ADMINISTRATOR_USERNAME" value="<?php echo empty($admin_user_name) ? '' : $admin_user_name; ?>" />
                            <span class="help-inline"></span>
                        </div>
                        <div class="description"><?php echo lang('param_administrator_username_description'); ?></div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="CFG_ADMINISTRATOR_PASSWORD"><?php echo lang('param_administrator_password'); ?>:</label>
                        <div class="controls">
                        	<input type="password" id="CFG_ADMINISTRATOR_PASSWORD" name="CFG_ADMINISTRATOR_PASSWORD" value="<?php echo empty($admin_pwd) ? '' : $admin_pwd; ?>" />
                            <span class="help-inline"></span>
                        </div>
                        <div class="description"><?php echo lang('param_administrator_password_description'); ?></div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="CFG_CONFIRM_PASSWORD"><?php echo lang('param_confirm_password'); ?>:</label>
                        <div class="controls">
                        	<input type="password" id="CFG_CONFIRM_PASSWORD" name="CFG_CONFIRM_PASSWORD" value="<?php echo empty($admin_pwd) ? '' : $admin_pwd; ?>" />
                            <span class="help-inline"></span>
                        </div>
                        <div class="description"><?php echo lang('param_administrator_password_description'); ?></div>
                    </div>
                    
                	<div class="control-group">
                        <label class="control-label" for="DB_INSERT_SAMPLE_DATA"><?php echo lang('param_database_import_sample_data'); ?>:</label>
                        <div class="controls">
                        	<input type="checkbox" id="DB_INSERT_SAMPLE_DATA" name="DB_INSERT_SAMPLE_DATA" checked="checked" />
                        </div>
                        <div class="description"><?php echo lang('param_database_import_sample_data_description'); ?></div>
                    </div>
        	    </div>
                <div class="control-group">
                    <div class="controls pull-right">
                    	<a href="<?php echo site_url(); ?>" class="btn btn-info"><i class="icon-remove icon-white"></i> &nbsp;<?php echo lang('image_button_cancel'); ?></a>
                    	<a id="continue-button" class="btn btn-info"><i class="icon-ok icon-white"></i> &nbsp;<?php echo lang('image_button_continue'); ?></a>
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
        
    	$('#continue-button').on('click',function() {
            var $this = $(this);

            //disable continue button
            if ($this.hasClass('disabled')) {
				return;
            }

            //show message box
            $mBox.show();
            
            var error = false;

            if(error == false && document.getElementById("CFG_STORE_NAME").value.length == 0) {
                display_message('store_name');
                
              	error = true;
            }

            if(error == false && document.getElementById("CFG_STORE_OWNER_NAME").value.length == 0) {
                display_message('store_owner');
                
              	error = true;
            }

            if(error == false) {
                var reg = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
                if( !reg.test(document.getElementById('CFG_STORE_OWNER_EMAIL_ADDRESS').value) && document.getElementById("CFG_STORE_OWNER_EMAIL_ADDRESS").value.length > 0) {
                    display_message('email');
                    
                  	error = true;
                }
            }  
            
            if(error == false && document.getElementById("CFG_ADMINISTRATOR_USERNAME").value.length == 0) {
                display_message('username');
                
              	error = true;
            }
            
            if(error == false && document.getElementById("CFG_ADMINISTRATOR_PASSWORD").value.length == 0) {
                display_message('password');
                
              	error = true;
            }
            
            if(error == false && document.getElementById("CFG_ADMINISTRATOR_PASSWORD").value != document.getElementById("CFG_CONFIRM_PASSWORD").value) {
                display_message('confirm');
                
              	error = true;
            }

    		if (error == false) {
    		    display_message('database_sample_data_importing');
    		    
    		    $.ajax({
                    type: 'post',
                    url: '<?php echo site_url('setting/save') ?>',
                    data: $('input[name=HTTP_WWW_ADDRESS], input[name=CFG_STORE_NAME], input[name=CFG_STORE_OWNER_NAME], input[name=CFG_STORE_OWNER_EMAIL_ADDRESS], input[name=CFG_ADMINISTRATOR_USERNAME], input[name=CFG_ADMINISTRATOR_PASSWORD], input[name=DB_INSERT_SAMPLE_DATA]'),
                    dataType: 'json',
                    success: function(result) {
                        //if false then enable and button and retry
                        if (result.success == false) {
    						//enable the button
                            $this.removeClass('disabled');
                            
                            display_message('database_sample_data_import_error', result.error);
                        } 
    					//if success start to import sql
                        else {
                            display_message('database_sample_data_imported');

                            setTimeout(function() {
                                window.location = '<?php echo site_url('index/index/finish'); ?>';
                            }, 3000);
                        }
                    }
                });
        	}
    	});
    })($);
</script>
