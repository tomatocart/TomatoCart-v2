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

Ext.define('Toc.languages.LanguagesAddDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'languages-add-dialog-win';
    config.title = '<?php echo lang('action_heading_import_language'); ?>';
    config.width = 500;
    config.modal = true;
    config.iconCls = 'icon-languages-win';
    
    config.items = this.buildForm();
    
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
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    this.dsLanguages = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text'
      ],
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('languages/get_languages'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.frmLanguage = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('languages/import_language'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelSeparator: '',
        labelWidth: 130,
        anchor: '98%'
      },
      items: [
        {
          xtype: 'combo', 
          fieldLabel: '<?php echo lang('field_language_selection'); ?>', 
          name: 'languages_id',
          queryMode: 'local', 
          store: this.dsLanguages,
          displayField: 'text',
          valueField: 'id',
          triggerAction: 'all',
          allowBlank: false
        },
        {
          xtype: 'radio', 
          name: 'import_type',
          inputValue: 'add',
          checked: true,
          style: 'margin-left: 135px',
          boxLabel: '<?php echo lang('only_add_new_records'); ?>'
        },
        {
          xtype: 'radio',
          name: 'import_type',
          inputValue: 'update',
          boxLabel: '<?php echo lang('only_update_existing_records'); ?>',
          fieldLabel: '<?php echo lang('field_import_type'); ?>'
        },
        {
          xtype: 'radio',
          name: 'import_type',
          inputValue: 'replace',
          style: 'margin-left: 135px',
          boxLabel: '<?php echo lang('replace_all'); ?>'
        }
      ]
    });
    
    return this.frmLanguage;
  },
  
  submitForm : function() {
    this.frmLanguage.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
         this.fireEvent('savesuccess', action.result.feedback);
         this.close();  
      },    
      failure: function(form, action) {
        if (action.failureType != 'client') {
          Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },  
      scope: this
    });   
  }
});

/* End of file languages_add_dialog.php */
/* Location: ./templates/base/web/views/languages/languages_add_dialog.php */