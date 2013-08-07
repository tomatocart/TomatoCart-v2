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

Ext.define('Toc.logo_upload.LogoUploadDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'logo_upload-win';
    config.title = '<?php echo lang('heading_logo_upload_title'); ?>';
    config.width = 500;
    config.iconCls = 'icon-logo_upload-win';
    config.layout = 'fit';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: '<?php echo lang('button_save'); ?>',
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
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function () {
    Ext.Ajax.request({
      url: '<?php echo site_url('logo_upload/get_logo'); ?>',
      callback: function(options, success, response) {
        result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          this.pnlImage.update(result.image);
        }
      },
      scope: this
    });

    this.callParent();
  },
  
  buildForm: function() {
    var fieldWidth = Ext.create('Ext.form.field.Number', {
      name: 'logo_width',
      disabled: true,
      fieldLabel: '<?php echo lang('field_logo_width'); ?>'
    });
    
    var fieldHeight = Ext.create('Ext.form.field.Number', {
      name: 'logo_height',
      disabled: true,
      fieldLabel: '<?php echo lang('field_logo_height'); ?>'
    });
    
    var checkbox = Ext.create('Ext.form.field.Checkbox', {
      name: 'resize',
      fieldLabel: '<?php echo lang('field_resize_logo'); ?>',
      checked: false,
      inputValue: '1',
      listeners: {
        change: {
          fn: function(checkbox, value){
            if (value == '1') {
              fieldWidth.enable();
              fieldHeight.enable();
            }else {
              fieldWidth.disable();
              fieldHeight.disable();
            }
          }
        }
      }
    });
    this.frmUpload = Ext.create('Ext.form.Panel', {
      fileUpload: true,
      url: '<?php echo site_url('logo_upload/save_logo'); ?>',
      border: false,
      fieldDefaults: {
        anchor: '97%',
        labelSeparator: ''
      },
      items: [
        {
          region: 'north',
          layout: 'anchor',
          border: false,
          bodyPadding: 10,
          items: [
            checkbox,
            fieldWidth,
            fieldHeight,
            {xtype: 'filefield', fieldLabel: '<?php echo lang('field_logo_image'); ?>', name: 'logo_image'}
          ]
        }
      ]
    });
    
    this.pnlImage = Ext.create('Ext.Panel', {
      region: 'center',
      border: false,
      style: 'text-align: center'
    });
    
    this.frmUpload.add(this.pnlImage);
    
    return this.frmUpload;
  },
  
  submitForm : function() {
    this.frmUpload.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
        image = '<img src ="' + action.result.image + '" width="' + action.result.width + '" height="' + action.result.height + '" style="padding: 10px" />';
        this.pnlImage.update(image);
        this.doLayout();
         
        this.fireEvent('savesuccess', action.result.feedback);
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

/* End of file logo_upload_dialog.php */
/* Location: ./templates/base/web/views/logo_upload/logo_upload_dialog.php */