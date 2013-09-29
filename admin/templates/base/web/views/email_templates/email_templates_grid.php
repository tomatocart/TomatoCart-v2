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

Ext.define('Toc.email_templates.EmailTemplatesGrid', {
	extend: 'Ext.grid.Panel',
	
	statics: {
		renderPublish: function(status) {
			if(status == 1) {
				return '<img class="img-button" src="<?php echo icon_status_url('icon_status_green.gif'); ?>" />&nbsp;<img class="img-button btn-status-off" style="cursor: pointer" src="<?php echo icon_status_url('icon_status_red_light.gif'); ?>" />';
			}else {
				return '<img class="img-button btn-status-on" style="cursor: pointer" src="<?php echo icon_status_url('icon_status_green_light.gif'); ?>" />&nbsp;<img class="img-button" src= "<?php echo icon_status_url('icon_status_red.gif'); ?>" />';
			}
		}
	},
	
	constructor: function(config) {
		var statics = this.statics();
    
    	config = config || {};
    
		config.border = false;
		config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
		
		config.store = Ext.create('Ext.data.Store', {
			fields:[
				'email_templates_id',
				'email_templates_name',
				'email_title',
				'email_templates_status'
			],
			pageSize: Toc.CONF.GRID_PAGE_SIZE,
			proxy: {
				type: 'ajax',
				url : '<?php echo site_url('email_templates/list_email_templates'); ?>',
				reader: {
					type: 'json',
					root: Toc.CONF.JSON_READER_ROOT,
					totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
				}
			},
			autoLoad: true
		});
		
		config.columns =[
			{ header: '<?php echo lang('table_heading_email_template_name'); ?>', dataIndex: 'email_templates_name', width: 200},
			{ header: '<?php echo lang('table_heading_email_title'); ?>', dataIndex: 'email_title', width: 300, flex: 1},
			{ header: '<?php echo lang('table_heading_email_template_status'); ?>', dataIndex: 'email_templates_status', width: 80, align: 'center', renderer: statics.renderPublish},
			{
		        xtype:'actioncolumn', 
		        width:80,
		        header: '<?php echo lang("table_heading_action"); ?>',
		        items: [
			        {
			          iconCls: 'icon-action icon-edit-record',
			          tooltip: TocLanguage.tipEdit,
			          handler: function(grid, rowIndex, colIndex) {
			            var rec = grid.getStore().getAt(rowIndex);
			            
			            this.fireEvent('edit', rec);
			          },
			          scope: this
			        }
	        	]
	      	}
	    ];
	    
	    config.listeners = {
	      itemclick: this.onClick
	    };
	    
	    this.addEvents({'edit': true, 'notifysuccess': true});  
	    
	    this.callParent([config]);
	},
	
	onClick: function(view, record, item, index, e) {
	    var action = false;
	    var module = 'set_status';
	  
	    if (index !== false) {
			var btn = e.getTarget(".img-button");
	      
			if (btn) {
				action = btn.className.replace(/img-button btn-/, '').trim();
			}
	
			if (action != 'img-button') {
				var record = this.getStore().getAt(index);
				var emailTemplatesId = record.get('email_templates_id');
	        
				switch(action) {
					case 'status-off':
					case 'status-on':
						flag = (action == 'status-on') ? 1 : 0;
						this.onAction(module, emailTemplatesId, index, flag);
	
					break;
				}
			}
	    }
	},
	
	onAction: function(action, emailTemplatesId, index, flag) {
	    Ext.Ajax.request({
			url: '<?php echo site_url('email_templates'); ?>/' + action,
			params: {
				email_templates_id: emailTemplatesId,
				flag: flag
			},
			callback: function(options, success, response) {
				var result = Ext.decode(response.responseText);
	        
				if (result.success == true) {
					var store = this.getStore();
					store.getAt(index).set('email_templates_status', flag);
					store.getAt(index).commit();
					
					this.fireEvent('notifysuccess', result.feedback);
				}else {
					Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
				}
			},
			scope: this
	    });
	},
	
	onRefresh: function() {
		this.getStore().load();
	},
});

/* End of file main.php */
/* Location: ./templates/base/web/views/email_templates/email_templates_grid.php */