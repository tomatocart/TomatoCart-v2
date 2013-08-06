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

Ext.define('Toc.articles_categories.ArticlesCategoriesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'articles_categories-dialog-win';
    config.title = '<?php echo lang('action_heading_new_category'); ?>';
    config.layout = 'fit';
    config.width = 440;
    config.height = 380;
    config.modal = true;
    config.iconCls = 'icon-articles_categories-win';
    
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
    
    this.callParent([config]); 
  },
  
  show: function(id) {
    var categoriesId = id || null;
    
    this.frmArticlesCategory.form.baseParams['articles_categories_id'] = categoriesId;
    
    if (categoriesId > 0) {
      this.frmArticlesCategory.load({
        url: '<?php echo site_url('articles_categories/load_articles_categories'); ?>',
        success: function(form, action) {
          Toc.articles_categories.ArticlesCategoriesDialog.superclass.show.call(this);
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
    this.pnlGeneral = Ext.create('Toc.articles_categories.GeneralPanel');
    this.pnlMetaInfo = Ext.create('Toc.articles_categories.MetaInfoPanel');
    
    var tabArticlesCategories = Ext.create('Ext.tab.Panel', {
      activeTab: 0,
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
    
    this.frmArticlesCategory = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('articles_categories/save_articles_category'); ?>',
      baseParams: {},
      layout: 'fit',
      fieldDefaults: {
        labelWidth: 120,
        labelSeparator: '',
        anchor: '97%'
      },
      border: false,
      items: tabArticlesCategories
    });
    
    return this.frmArticlesCategory;
  },
  
  submitForm : function() {
    this.frmArticlesCategory.form.submit({
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

/* End of file articles_categories_dialog.php */
/* Location: ./templates/base/web/views/articles_categories/articles_categories_dialog.php */