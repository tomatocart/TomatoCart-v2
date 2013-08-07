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

Ext.define('Toc.languages.TranslationEditDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'translation-edit-win';
    config.title = config.definitionKey;
    config.layout = 'fit';
    config.width = 400;
    config.height = 240;
    config.modal = true;
    config.iconCls = 'icon-languages-win';
    
    config.items = this.buildForm(config);
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function () {
          this.submitForm();
        },
        scope: this
      }, 
      {
        text: TocLanguage.btnClose,
        handler: function () {
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  buildForm: function(config) {
    this.txtTranslation = Ext.create('Ext.form.TextArea', {
      region: 'center',
      emptyText: TocLanguage.gridNoRecords,
      name: 'definition_value',
      allowBlank: false,
      value: config.definitionValue
    });
    
    this.frmTranslationEdit = Ext.create('Ext.form.Panel', {
      baseParams: {
        languages_id: config.languagesId,
        group: config.group,
        definition_key: config.definitionKey
      },
      layout: 'border',
      border: false,
      bodyPadding: 8,
      items: [
        this.txtTranslation
      ]
    });
    
    return this.frmTranslationEdit;
  },
  
  submitForm : function() {
    this.frmTranslationEdit.form.submit({
      url: '<?php echo site_url('languages/update_translation'); ?>',
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();   
      },    
      failure: function(form, action) {
        if(action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },   
      scope: this
    });    
  }
});

/* End of file translations_edit_dialog.php */
/* Location: ./templates/base/web/views/languages/translations_edit_dialog.php */