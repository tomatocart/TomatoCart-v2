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
?>

Ext.define('Toc.languages.LanguagesUploadDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
  
    config.id = 'languages-upload-dialog-win';
    config.title = '<?php echo lang('action_heading_upload_language'); ?>';
    config.width = 400;
    config.height = 200;
    config.modal = true;
    config.iconCls = 'icon-languages-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnUpload,
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
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    this.frmLanguage = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('languages/upload_language'); ?>',
      fileUpload: true,
      baseParams: {},
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelSeparator: '',
        labelWidth: 100,
        anchor: '98%'
      },
      items: [
        {
          xtype: 'filefield', 
          layout: 'anchor', 
          fieldLabel: '<?php echo lang('field_language_zip_file'); ?>', 
          name: 'file'
        },
        {
          xtype: 'displayfield',
          border: false,
          style: 'padding: 16px 0;',
          hideLabel: true,
          value:'<?php echo lang('introduction_upload_language'); ?>'
        }
      ]
    });
    
    return this.frmLanguage;
  },
  
  submitForm : function() {
    this.frmLanguage.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
         this.close();
         window.location.reload();  
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

/* End of file languages_upload_dialog.php */
/* Location: ./templates/base/web/views/languages/languages_upload_dialog.php */