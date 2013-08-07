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

Ext.define('Toc.information.InformationDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'information-dialog-win';
    config.title = '<?php echo lang('action_heading_new_information'); ?>';
    config.layout = 'fit';
    config.width = 850;
    config.height = 550;
    config.modal = true;
    config.iconCls = 'icon-information-win';
    
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
    
    this.addEvents({'savesuccess' : true})
    
    this.callParent([config]);
  },
  
  show: function(id) {
    var articlesId = id || null;
    
    if(articlesId > 0) {
      this.frmArticle.form.baseParams['articles_id'] = articlesId;
      
      this.frmArticle.load({
        url: '<?php echo site_url('information/load_article'); ?>',
        params:{
          articles_id: articlesId
        },
        success: function(form, action) {
          var img = action.result.data.articles_image;
          
          if (img != null) {
            var img = '<?php echo IMGHTTPPATH; ?>/articles/thumbnails/' + img;
            var pnlImg = Ext.create('Ext.Panel', {
              height: 90,
              border: false,
              html: '<img src="' + img + '" style="border: solid 1px #B5B8C8;" />'
            });
            
            var checkboxDel = Ext.create('Ext.form.field.Checkbox', {
              name: 'delimage',
              boxLabel: '<?php echo lang('field_delete'); ?>'
            });
            
            this.pnlImgUrl.add(pnlImg);
            this.pnlImgUrl.add(checkboxDel);
          }
          
          Toc.information.InformationDialog.superclass.show.call(this);
        },
        failure: function(form, action) {
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
    this.frmArticle = Ext.create('Ext.form.Panel', {
      fileUpload: true,
      layout: 'border',
      border: false,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      title:'<?php echo lang('heading_title_data'); ?>',
      url: '<?php echo site_url('information/save_article'); ?>',
      baseParams: {},
      deferredRender: false,
      items: [this.getContentPanel(), this.getDataPanel()]
    });
    
    return this.frmArticle;
  },
  
  getDataPanel: function() {
    var me = this;
    
    this.pnlImgUrl = Ext.create('Ext.Panel', {
      name: 'img_url',
      border: false,
      width: 200
    });
    
    this.pnlData = Ext.create('Ext.Panel', {
      layout: 'column',
      region: 'north',
      border: false,
      bodyPadding: 6,
      items: [
        {
          layout: 'anchor',
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
                      name: 'articles_status',
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
                      name: 'articles_status',
                      inputValue: '0',
                      boxLabel: '<?php echo lang('field_publish_no'); ?>'
                    }
                  ]
                }
              ]
            },
            {xtype:'numberfield', fieldLabel: '<?php echo lang('field_order'); ?>', name: 'articles_order', id: 'articles_order', value: 0},
            {xtype:'fileuploadfield', fieldLabel: '<?php echo lang('field_image'); ?>', name: 'articles_image'}
          ]
        },
        {
          border: false,
          columnWidth: .3,
          items: [
            me.pnlImgUrl
          ]
        }
      ]
    });
    
    return this.pnlData;
  },
  
  getContentPanel: function() {
    this.pnlGeneral = Ext.create('Toc.information.GeneralPanel');
    this.pnlMetaInfo = Ext.create('Toc.information.MetaInfoPanel');
    
    var tabInformation = Ext.create('Ext.tab.Panel', {
      activeTab: 0,
      region: 'center',
      border: false,
      defaults:{
        hideMode:'offsets'
      },
      deferredRender: false,
      items: [
        this.pnlGeneral,
        this.pnlMetaInfo  
      ]
    });
    
    return tabInformation;
  },
  
  submitForm : function() {
    this.frmArticle.form.submit({
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

/* End of file information_dialog.php */
/* Location: ./templates/base/web/views/information/information_dialog.php */