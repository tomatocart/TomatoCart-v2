<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource 
 */
?>

Ext.define('Toc.templates.TemplatesUplaodDialog', {
    extend: 'Ext.Window',
    
    constructor: function(config) {
        config = config || {};
        
        config.id = 'templates-upload-dialog-win';
        config.title = '<?php echo lang('heading_title'); ?>';
        config.width = 400;
        config.modal = false;
        config.iconCls = 'icon-templates-win';
        config.items =  this.buildForm();  
        
        config.buttons = [
            {
                text: TocLanguage.btnSave,
                handler: function() {
                	this.submitForm();
                },
                scope: this
            },
            {
                text: TocLanguage.btnClose,
                handler: function() { 
                	this.close();
                },
                scope: this
            }
        ];
        
        this.addEvents({'saveSuccess' : true});  
          
        this.callParent([config]);
    },
      
    buildForm: function() {
        this.frmTemplates = Ext.create('Ext.form.FormPanel', {
            fileUpload: true,
            url: '<?php echo site_url('templates/upload_template'); ?>',
            layoutConfig: {
            	labelSeparator: ''
            },
            fieldDefaults: {
              labelAlign: 'top',
              labelWidth: 149
            },
            items: [
            	{xtype: 'fileuploadfield', fieldLabel: '<?php echo lang('field_upload_template'); ?>', name: 'template_file', anchor: '97%'}
            ]
        });
        
        return this.frmTemplates;
    },
    
    submitForm : function() {
        this.frmTemplates.form.submit({
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