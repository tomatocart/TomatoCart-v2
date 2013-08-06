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


Ext.define('Toc.categories.CategoriesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'categories-dialog-win';
    config.title = '<?php echo lang("action_heading_new_category"); ?>';
    config.layout = 'fit';
    config.width = 520;
    config.height = 380;
    config.modal = true;
    config.iconCls = 'icon-categories-win';
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
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function(id) {
    var categoriesId = id || null;
    
    if (categoriesId > 0) {
      this.frmCategories.form.baseParams['categories_id'] = categoriesId;
       
      this.frmCategories.load({
        url: '<?php echo site_url('categories/load_category'); ?>',
        success: function (form, action) {
          // get the parent id of the loading category.
          var parentId = action.result.data.parent_category_id;
          
          // if the format of the parent id is looked like '4', '2', they should be converted to int.
          if (Ext.isString(parentId) && (parentId.indexOf('_') == -1)) {
            parentId = parseInt(parentId);
          }
          
          //the store of the combox should not be load automatically so that we could confirm that all the data is loaded as calling the setValue.
          this.pnlGeneral.dsParentCategories.on('load', function() {
            this.pnlGeneral.cboParentCategories.setValue(parentId);
          }, this);
          this.pnlGeneral.dsParentCategories.load();
          
          this.pnlGeneral.cboParentCategories.disable();
          
          var img = action.result.data.categories_image;
          
          if (img) {
            var html = '<img src ="../../../images/categories/' + img + '"  style = "margin-left: 170px; width: 70px; height:70px" />';
            this.pnlGeneral.getComponent('categories_image_panel').update(html);
          }
          
          Toc.categories.CategoriesDialog.superclass.show.call(this);
          
        },
        failure: function (form, action) {
          Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this
      });
      
    } else {
      this.pnlGeneral.dsParentCategories.on('load', function() {
        this.pnlGeneral.cboParentCategories.setValue(parseInt(this.categoriesId));
      }, this);
      this.pnlGeneral.dsParentCategories.load();
          
      Toc.categories.CategoriesDialog.superclass.show.call(this);
    }
    
  },
  
  buildForm: function(categoriesId) {
    this.pnlGeneral = Ext.create('Toc.categories.GeneralPanel');
    this.pnlMetaInfo = Ext.create('Toc.categories.MetaInfoPanel');
    
    var tabCategories = Ext.create('Ext.TabPanel', {
      activeTab: 0,
      border: false,
      deferredRender: false,
      items: [
        this.pnlGeneral,
        this.pnlMetaInfo
      ]
    });
    
    this.frmCategories = Ext.create('Ext.form.Panel', {
      border: false,
      fieldDefaults: {
        labelAlign: 'left',
        labelWidth: 120,
        labelSeparator: '',
        anchor: '98%'
      },
      url: '<?php echo site_url('categories/save_category'); ?>',
      baseParams: {},
      items: tabCategories
    });
    
    return this.frmCategories; 
  },
  
  submitForm: function () {
    //get the category status
    var status = this.pnlGeneral.query('#statusEnable')[0].getGroupValue();
    
    //if the category is disabled, need to confirm whether the correlative products should be disabled too.
    if (status == 0) {
      var params = {'product_flag': 1};
      
      Ext.MessageBox.confirm(
        TocLanguage.msgWarningTitle, 
        TocLanguage.msgDisableProducts, 
        function (btn) {
          if (btn == 'no') {
            params.product_flag = 0;

            this.frmCategories.form.submit({
              params: params,
              waitMsg: TocLanguage.formSubmitWaitMsg,
              success: function (form, action) {
                this.fireEvent('saveSuccess', action.result.feedback);
                this.close();
              },
              failure: function (form, action) {
                if (action.failureType != 'client') {
                  Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
                }
              },
              scope: this
            });
          } else{
            this.frmCategories.form.submit({
              params: params,
              waitMsg: TocLanguage.formSubmitWaitMsg,
              success: function (form, action) {
                this.fireEvent('saveSuccess', action.result.feedback, action.result.categories_id, action.result.text);
                this.close();
              },
              failure: function (form, action) {
                if (action.failureType != 'client') {
                  Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
                }
              },
              scope: this
            });
          }
        }, 
        this
      );       
    }else {
      this.frmCategories.form.submit({
        waitMsg: TocLanguage.formSubmitWaitMsg,
        success: function (form, action) {
          this.fireEvent('saveSuccess', action.result.feedback, action.result.categories_id, action.result.text);
          this.close();
        },
        failure: function (form, action) {
          if (action.failureType != 'client') {
            Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
          }
        },
        scope: this
      });
    }
  }
});

/* End of file categories_dialog.php */
/* Location: ./templates/base/web/views/categories/categories_dialog.php */