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

Ext.define('Toc.guest_book.GuestBookDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'guest_book-dialog';
    config.iconCls = 'icon-guest_book-win';
    config.title = '<?php echo lang('heading_guest_book_title'); ?>';
    config.modal = true;
    config.width = 500;
    config.items = this.buildForm();
    
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
    
    this.callParent([config]);
    
    this.addEvents({'savesuccess': true});
  },
  
  onDlgloaded: function(lanId) {
    this.cboLanguages.setValue(lanId)
  },
  
  show: function(guestBooksId) {
    var guestBooksId = guestBooksId || null;
    
    if (guestBooksId > 0)
    {
      this.frmGuestBook.load({
        url: '<?php echo site_url('guest_book/load_guest_book'); ?>',
        params: {
          guest_books_id: guestBooksId
        },
        success: function(form, action) {
          Toc.guest_book.GuestBookDialog.superclass.show.call(this);
          
          this.frmGuestBook.baseParams['guest_books_id'] = guestBooksId;
        },
        failure: function() {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this       
      });
    }
    else
    {
      this.callParent();
    }
  },
  
  buildForm: function() {
    var dsLanguages = Ext.create('Ext.data.Store', {
      fields: ['id', 'text'],
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('guest_book/get_languages'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      listeners: {
        load: function() {this.cboLanguages.setValue('<?php echo lang_id();?>')},
        scope: this
      },
      autoLoad: true
    });
    
    this.cboLanguages = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_language'); ?>', 
      store: dsLanguages, 
      name: 'languages_id', 
      displayField: 'text', 
      valueField: 'id', 
      editable: false, 
      forceSelection: true,
      queryMode: 'local'
    });
    
    this.frmGuestBook = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('guest_book/save_guest_book'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelSeparator: '',
        layout: 'anchor',
        anchor: '98%'
      },
      items: [
        {xtype:'textfield',fieldLabel: '<?php echo lang('field_title'); ?>', name: 'title', allowBlank: false},
        this.cboLanguages,
        {xtype:'textfield', fieldLabel: '<?php echo lang('field_email'); ?>', name: 'email'},
        {xtype:'textfield', fieldLabel: '<?php echo lang('field_url'); ?>', name: 'url'},
        {xtype:'textarea', fieldLabel: '<?php echo lang('field_content'); ?>', name: 'content', height: 200, allowBlank: false},
        {
          xtype: 'radiogroup',
          fieldLabel: '<?php echo lang('field_status'); ?>',
          items: [
            {boxLabel: '<?php echo lang('cbo_field_abled'); ?>', name: 'guest_books_status', inputValue: 1},
            {boxLabel: '<?php echo lang('cbo_field_disabled'); ?>', name: 'guest_books_status', inputValue: 0, checked: true}
          ]
        }
      ]
    });
    
    return this.frmGuestBook;
  },
  
  submitForm : function() {
    this.frmGuestBook.getForm().submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
         this.fireEvent('savesuccess', action.result.feedback);
         this.close();  
      },    
      failure: function(form, action) {
        switch (action.failureType) {
          case Ext.form.action.Action.CLIENT_INVALID:
            Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgClientINVALID);
            break;
          case Ext.form.action.Action.CONNECT_FAILURE:
            Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgCONNECTFAILURE);
            break;
          case Ext.form.action.Action.SERVER_INVALID:
            Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this,
    });    
  }
});

/* End of file guest_book_dialog.php */
/* Location: ./templates/base/web/views/guestbook/guest_book_dialog.php */