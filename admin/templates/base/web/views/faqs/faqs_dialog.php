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

Ext.define('Toc.faqs.FaqsDialog', {
  extend: 'Ext.Window', 
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'faqs-dialog-win';
    config.title = '<?php echo lang('heading_title_new_faq'); ?>';
    config.layout = 'fit';
    config.width = 680;
    config.height = 500;
    config.modal = true;
    config.iconCls = 'icon-faqs-win';
    
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
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);
  },
  
  show: function(id, cId) {
    var faqsId = id || null;
    
    if (faqsId > 0) { 
      this.frmFaq.form.baseParams['faqs_id'] = faqsId;
      
      this.frmFaq.load({
        url: '<?php echo site_url('faqs/load_faq'); ?>',
        success: function(form, action) {
           Toc.faqs.FaqsDialog.superclass.show.call(this);
        },
        failure: function(form, action) {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        }, 
        scope: this
      });
    } else {
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmFaq = Ext.create('Ext.form.Panel', {
      title: '<?php echo lang('heading_title_data'); ?>',
      url: '<?php echo site_url('faqs/save_faq'); ?>',
      baseParams: {},
      layout: 'border',
      border: false,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      deferredRender: false,
      items: [this.getDataPanel(), this.getContentPanel()]
    });
    
    return this.frmFaq;
  },
  
  getDataPanel: function() {
    this.pnlData = Ext.create('Ext.Panel', {
      region: 'north',
      border: false,
      bodyPadding: 6,
      layout: 'anchor',
      items: [
        {
          layout: 'column',
          border: false,
          columnWidth: .7,
          items: [
            {
              layout: 'column',
              border: false,
              items: [
                {
                  border: false,
                  width: 200,
                  items: [
                    {
                      fieldLabel: '<?php echo lang('field_publish'); ?>', 
                      xtype:'radio', 
                      name: 'faqs_status',
                      inputValue: '1',
                      checked: true,
                      boxLabel: '<?php echo lang('field_publish_yes'); ?>'
                    }
                  ]
                },
                {
                  border: false,
                  width: 200,
                  items: [
                    {
                      hideLabel: true,
                      xtype:'radio', 
                      name: 'faqs_status',
                      inputValue: '0',
                      boxLabel: '<?php echo lang('field_publish_no'); ?>'
                    }
                  ]
                }
              ]
            }
          ]
        },
        {xtype:'numberfield', fieldLabel: '<?php echo lang('field_order'); ?>', name: 'faqs_order', value: 0}
      ]
    });
    
    return this.pnlData;
  },
  
  getContentPanel: function() {
    var tabLanguage = Ext.create('Ext.tab.Panel', {
      activeTab: 0,
      region: 'center',
      border: false,
      defaults:{
        hideMode:'offsets'
      },
      deferredRender: false
    });
    
    <?php
      foreach(lang_get_all() as $l)
      {
    ?>
        var pnlLang<?php echo $l['code']; ?> = Ext.create('Ext.Panel', {
          title: '<?php echo $l['name']; ?>',
          iconCls: 'icon-<?php echo $l['country_iso']; ?>-win',
          border: false,
          layout: 'anchor',
          bodyPadding: 6,
          items: [
            {
              xtype: 'textfield', 
              fieldLabel: '<?php echo lang('field_faq_question'); ?>', 
              name: 'faqs_question[<?php echo $l['id']; ?>]', 
              allowBlank: false
            },
            {
              xtype: 'textfield', 
              fieldLabel: '<?php echo lang('field_faq_url'); ?>', 
              name: 'faqs_url[<?php echo $l['id']; ?>]'
            },
            {
              xtype: 'htmleditor',
              fieldLabel: '<?php echo lang('filed_faq_answer'); ?>',
              name: 'faqs_answer[<?php echo $l['id']; ?>]',
              height: 230
            }
          ]
        });
        
        tabLanguage.add(pnlLang<?php echo $l['code']; ?>);
    <?php
      }
    ?>
    
    return tabLanguage;
  },
  
  submitForm : function() {
    this.frmFaq.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action){
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

/* End of file faqs_dialog.php */
/* Location: ./templates/base/web/views/faqs/faqs_dialog.php */