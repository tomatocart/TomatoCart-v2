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

Ext.define('Toc.languages.LanguagesEditDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'languages-edit-dialog-win';
    config.width = 640;
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
  
  show: function (id) {
    var languagesId = id || null;
    
    if (languagesId > 0) {
      this.frmEditLanguage.form.baseParams['languages_id'] = languagesId;
      this.dsParentLanguages.getProxy().extraParams['languages_id'] = languagesId;
    
      this.frmEditLanguage.load({
        url: '<?php echo site_url('languages/load_language'); ?>',
        success: function(form, action) {
          if ( !action.result.data['default'] ) {
            this.frmEditLanguage.add({
              xtype: 'checkbox', 
              fieldLabel: '<?php echo lang('field_set_default'); ?>', 
              name: 'default', 
              inputValue: 'on'
            });
          }
          Toc.languages.LanguagesEditDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.dsTextDirections = Ext.create('Ext.data.ArrayStore', {
      fields: [
        'id',
        'text'
      ],
      data: [
        ['ltr', 'ltr'],
        ['rtl', 'rtl']
      ]
    });
    
    this.dsCurrencies = Ext.create('Ext.data.Store', {
      fields:[
        'currencies_id', 
        'text'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url: '<?php echo site_url('languages/get_currencies'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.dsParentLanguages = Ext.create('Ext.data.Store', {
      fields:[
        'parent_id', 
        'text'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url: '<?php echo site_url('languages/get_parent_language'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.frmEditLanguage = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('languages/save_language'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%',
        labelWidth: 200
      },
      items: [ 
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_name'); ?>', 
          name: 'name', 
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_code'); ?>', 
          name: 'code', 
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_locale'); ?>', 
          name: 'locale', 
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_character_set'); ?>', 
          name: 'charset', 
          allowBlank: false
        },
        {
          xtype: 'combo',
          fieldLabel: '<?php echo lang('field_text_direction'); ?>',
          name: 'text_id',
          id: 'text_direction', 
          queryMode: 'local', 
          store: this.dsTextDirections,
          displayField: 'text',
          valueField: 'id',
          triggerAction: 'all',
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_date_format_short'); ?>', 
          name: 'date_format_short', 
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_date_format_long'); ?>', 
          name: 'date_format_long', 
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_time_format'); ?>', 
          name: 'time_format', 
          allowBlank: false
        },
        {
          xtype: 'combo',
          name: 'currencies_id',
          queryMode: 'local',
          fieldLabel: '<?php echo  lang('field_currency'); ?>', 
          store: this.dsCurrencies,
          displayField: 'text',
          valueField: 'currencies_id',
          triggerAction: 'all',
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_currency_separator_decimal'); ?>', 
          name: 'numeric_separator_decimal', 
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_currency_separator_thousands'); ?>', 
          name: 'numeric_separator_thousands', 
          allowBlank: false
        },
        {
          xtype: 'combo',
          name: 'parent_id',
          queryMode: 'local',
          fieldLabel: '<?php echo lang('field_parent_language'); ?>', 
          store: this.dsParentLanguages,
          displayField: 'text',
          valueField: 'parent_id',
          triggerAction: 'all',
          allowBlank: false
        },
        {
          xtype: 'numberfield', 
          fieldLabel: '<?php echo lang('field_sort_order'); ?>', 
          name: 'sort_order'
        }
      ]
    });
    
    return this.frmEditLanguage;
  },
  
  submitForm : function() {
    this.frmEditLanguage.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action){
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

/* End of file languages_edit_dialog.php */
/* Location: ./templates/base/web/views/languages/languages_edit_dialog.php */