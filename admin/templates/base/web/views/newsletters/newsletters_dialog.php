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

Ext.define('Toc.newsletters.NewslettersDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'newsletters-dialog-win';
    config.title = '<?php echo lang('action_heading_new_newsletter'); ?>';
    config.width = 700;
    config.height = 400;
    
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
  
  show: function (newslettersId) {
    this.newslettersId = newslettersId || null;
    
    if (this.newslettersId > 0) {
      this.frmNewsletter.form.baseParams['newsletters_id'] = this.newslettersId;
      
      this.frmNewsletter.load({
        url: '<?php echo site_url('newsletters/load_newsletter'); ?>',
        success: function(form, action) {
          Toc.newsletters.NewslettersDialog.superclass.show.call(this);
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
    this.dsModules = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('newsletters/get_modules'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.frmNewsletter = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('newsletters/save_newsletter'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%',
        labelWidth: 100
      },
      items: [ 
        {
          xtype: 'combo',
          name: 'newsletter_module',
          fieldLabel: '<?php echo lang('field_module'); ?>', 
          store: this.dsModules,
          queryMode: 'local',
          valueField: 'id',
          editable: false,
          displayField: 'text',
          forceSelection: true  
        },
        {
          xtype: 'textfield', 
          name: 'title', 
          fieldLabel: '<?php echo lang('field_title'); ?>', 
          allowBlank: false
        },
        {
          xtype: 'htmleditor',
          name: 'content', 
          fieldLabel: '<?php echo lang('field_content'); ?>', 
          height: 250
        }
      ]
    });
    
    return this.frmNewsletter;
  },
  
  submitForm : function() {
    this.frmNewsletter.form.submit({
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

/* End of file newsletters_dialog.php */
/* Location: ./templates/base/web/views/newsletters/newsletters_dialog.php */