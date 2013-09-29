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

Ext.define('Toc.email_templates.EmailTemplatesDialog', {
	extend: 'Ext.Window',
	
	constructor: function(config) {
		config = config || {};
		
		config.id = 'email_templatesDialog-win';
		config.layout = 'fit';
		config.width = 720;
		config.height = 450;
		config.modal = true;
		config.iconCls = 'icon-email_templates-win';
		config.items = this.buildForm();
		
		config.buttons = [
		    {
				text:TocLanguage.btnSave,
				handler: function(){
					this.submitForm();
				},
				scope:this
		    },
		    {
				text: TocLanguage.btnClose,
				handler: function(){
					this.close();
				},
				scope:this
		    }
		];
		
		this.addEvents({'saveSuccess' : true});  
    
    	this.callParent([config]);
	},
	
	show: function(record) {
		var emailTemplateId = record.get('email_templates_id');
		
		this.frmEmailTemplate.form.reset();
		this.frmEmailTemplate.form.baseParams['email_templates_id'] = emailTemplateId;
		
		if (emailTemplateId > 0) {
			this.frmEmailTemplate.load({
				url: '<?php echo site_url('email_templates/load_email_template'); ?>',
				extraParams: {
					email_templates_id: emailTemplateId
				},
				success: function(form, action) {
					this.dsVariables.getProxy().extraParams['email_templates_name'] = record.get('email_templates_name');
					this.dsVariables.load();
					
					Toc.email_templates.EmailTemplatesDialog.superclass.show.call(this);
		        },
		        failure: function() {
					Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
		        },
		        scope: this   
			});
		}else {
			Toc.email_templates.EmailTemplatesDialog.superclass.show.call(this);
		}
	},
	
	getDataPanel: function() {
		this.pnlData = Ext.create('Ext.Panel', {
			region: 'north',
			title: '<?php echo lang('heading_title_data'); ?>',
			height: 80,
			bodyPadding: 5,
	     	border: false,
			defaults: {
		        labelSeparator: ' ',
		        border: false
	      	},
	      	items: [
	      		{ 
					xtype: 'displayfield', 
					labelWidth: 180,
					fieldLabel: '<?php echo lang('field_email_templates_name'); ?>', 
					name: 'email_templates_name'
		        },
		        {
		        	layout: 'column',
		        	defaults: {
				        border: false,
			      	},
		        	items: [
		        		{
		        			items: [
		        				{width: 280, fieldLabel: '<?php echo lang('field_email_templates_status'); ?>', labelSeparator: ' ', labelWidth: 180, boxLabel: '<?php echo lang('status_enabled'); ?>' , name: 'email_templates_status', xtype:'radio', inputValue: '1'}
		        			]
		        		},
		        		{
		        			items: [
		        				{hideLabel: true, boxLabel: '<?php echo lang('status_disabled'); ?>', xtype:'radio', name: 'email_templates_status', inputValue: '0'}
		        			]
		        		}
		        	]
		        }
	      	]
		});
		
		return this.pnlData;
	},
	
	getContentPanel: function() {
		this.tabLanguage = Ext.create('Ext.TabPanel', {
			region: 'center',
			border: false,
			defaults:{
				hideMode:'offsets'
			},
			activeTab: 0,
			deferredRender: false
		});
		
		this.dsVariables = Ext.create('Ext.data.Store', {
			fields:['id', 'value'],
			pageSize: Toc.CONF.GRID_PAGE_SIZE,
			proxy: {
				type: 'ajax',
				url : '<?php echo site_url('email_templates/get_variables'); ?>',
				reader: {
					type: 'json',
					root: Toc.CONF.JSON_READER_ROOT,
					totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
				}
			}
		});
		
		<?php 
			foreach (lang_get_all() as $l)
			{
				echo 'this.pnlLang' . $l['id'] . ' = Ext.create("Ext.Panel", {
					labelWidth: 150,
					title:\'' . $l['name'] . '\',
 					iconCls: \'icon-' . $l['country_iso'] . '-win\',
 					labelSeparator: \' \',
 					style: \'padding: 6px\',
 					border: false,
 					items: [
 						{
							xtype: \'textfield\',
	 						fieldLabel: \'' . lang('field_email_title') . '\',
	 						name: \'email_title[' . $l['id'] . ']\',
							id: \'title[' . $l['id'] . ']\', 
							allowBlank: false,
	 						width: 520
						},
 						{
							layout: \'column\',
 							border: false,
 							items: [
 								{
									width: 560,
 									labelSeparator: \' \',
 									border: false,
 									items: [
										{
											fieldLabel: \'' . lang('field_variables') . '\',
											name: \'variable[' . $l['id'] . ']\', 
											id: \'email-template-variables' . $l['id'] . '\', 
											xtype: \'combo\', 
											store: this.dsVariables, 
											displayField: \'value\',
											valueField: \'value\', 
											editable: false, 
											triggerAction: \'all\',
											width: 300 
										} 				
 									]
								},
								{
									width: 80,
									border: false,
									items: [
										{
											xtype: \'button\', 
											id: \'btn-insert-variables-'.$l['id'].'\', 
											text: \'' . lang('button_insert') . '\',
											handler: function(){
												this.insertVariable(' . $l['id'] . ');
											},
											scope: this
										}
									]
								}
 							]
						},
						{
							xtype: \'htmleditor\',
							fieldLabel: \'' . lang('field_email_content') . '\',
							name: \'email_content[' . $l['id'] . ']\', 
						 	id: \'email-template-content' . $l['id'] . '\',
							height: \'auto\',
							width: 520,
							listeners: {
								editmodechange: this.onEditModeChange
							}
						}
 					]
				});
									
				this.tabLanguage.add(this.pnlLang' . $l['id'] . ');
				';
			}
		?>
		
		return this.tabLanguage;
	},
	
	onEditModeChange: function(htmlEditor, sourceEdit) {
		var code = htmlEditor.getId().toString().substr(22);
		var btn = Ext.getCmp('btn-insert-variables-'+ code);
		
		if (sourceEdit === true) {
			btn.disable();
		}else {
			btn.enable();
		}
	},
	
	insertVariable: function(id) {
		var variable = Ext.getCmp('email-template-variables'+ id).getValue();
		
		var editor = Ext.getCmp('email-template-content'+ id);
		editor.focus();
		editor.insertAtCursor(variable); 
	},
	
	buildForm: function() {
		this.frmEmailTemplate = Ext.create('Ext.form.FormPanel', {
			url: '<?php echo site_url('email_templates/email_templates'); ?>',
			baseParams: {},
			layout: 'border',
			width: 700,
			border: false,
			items: [this.getDataPanel(), this.getContentPanel()]
		});
		
		return this.frmEmailTemplate;
	},
	
	submitForm : function() {
		this.frmEmailTemplate.form.submit({
			waitMsg: TocLanguage.formSubmitWaitMsg,
			success: function(form, action) {
				this.fireEvent('saveSuccess', action.result.feedback);
				this.close(); 
			},
			failure: function(form, action) {
				if (action.failureType != 'client') {
					Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);    
				}
			},
			scope: this
		});
	}
});

/* End of file email_templates_dialog.php */
/* Location: ./templates/base/web/views/email_templates/email_templates_dialog.php */