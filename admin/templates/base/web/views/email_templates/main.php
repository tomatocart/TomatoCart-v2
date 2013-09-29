<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------
	echo 'Ext.namespace("Toc.email_templates");';
	
	include('email_templates_grid.php');
	include('email_templates_dialog.php');
?>

Ext.override(Toc.desktop.EmailTemplatesWindow, {
	createWindow : function() {
	    var desktop = this.app.getDesktop();
	    var win = desktop.getWindow('email_templates-win');
	    
	    if (!win) {
	    	var grd = Ext.create('Toc.email_templates.EmailTemplatesGrid');
	    	
	    	grd.on('notifysuccess', this.onShowNotification, this);
	    	grd.on('edit', function(record) {this.onEdit(grd, record);}, this);
	    	
			win = desktop.createWindow({
				id: 'email_templates-win',
				title: '<?php echo lang('heading_email_templates_title'); ?>',
				width: 800,
				height: 400,
				iconCls: 'icon-email_templates-win',
				layout: 'fit',
				items: grd
			});
	    }   
	    
	    win.show();
	},
	
	onEdit: function(grd, record) {
		var dlg = this.createEmailTemplatesDialog(record.get('email_templates_name'));
		
		dlg.on('saveSuccess', function() {
			grd.onRefresh();
		});
		
		dlg.show(record);
	},
	
	createEmailTemplatesDialog: function(title) {
		var desktop = this.app.getDesktop();
    	var dlg = desktop.getWindow('email_templatesDialog-win');
    	
    	if (!dlg) {
			dlg = desktop.createWindow({title: title}, Toc.email_templates.EmailTemplatesDialog);             
	      
			dlg.on('saveSuccess', function(feedback) {
	        	this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
			}, this);
	    }
	    
	    return dlg;    
	},
	
	onShowNotification: function(feedback) {
    	this.app.showNotification( {title: TocLanguage.msgSuccessTitle, html: feedback} );
	}
});

/* End of file main.php */
/* Location: ./templates/base/web/views/email_templates/main.php */