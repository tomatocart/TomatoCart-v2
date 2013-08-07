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

Ext.define('Toc.categories.CategoriesMoveDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'categories-move-dialog-win';
    config.title = '<?php echo lang("action_heading_batch_move_categories"); ?>';
    config.layout = 'fit';
    config.width = 400;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-categories-win';
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function () {
          this.submitForm();
          
          this.disable();
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
  
  show: function (categoriesId) {
    var categoriesIds = [];
    
    if (Ext.isArray(categoriesId))
    {
      categoriesIds = categoriesId;
    }else {
      categoriesIds.push(categoriesId);
    }
    
    categoriesIds = Ext.JSON.encode(categoriesIds);
    
    this.frmCategories.form.baseParams['categories_ids'] = categoriesIds;

    this.frmCategories.load({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'categories',
        action: 'load_parent_category'
      },
      success: function (form, action) {
        // get the parent id of the loading category.
        var parentId = action.result.data.parent_id;
          
        // if the format of the parent id is looked like '4', '2', they should be converted to int.
        if (Ext.isString(parentId) && (parentId.indexOf('_') == -1)) {
          parentId = parseInt(parentId);
        }
          
        //the store of the combox should not be load automatically so that we could confirm that all the data is loaded as calling the setValue.
        this.dsParentCategories.on('load', function() {
          this.cboParentCategories.setValue(parentId);
        }, this);
        this.dsParentCategories.load();
      },
      failure: function (form, action) {
        Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
      },
      scope: this  
    });

    this.callParent();
  },
  
  buildForm: function() {
    this.dsParentCategories = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text',
        'margin'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('categories/list_parent_category'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    this.cboParentCategories = Ext.create('Ext.form.ComboBox', {
      listConfig: {
        getInnerTpl: function() {
          return '<div style="margin-left: {margin}px">{text}</div>';
        }
      },
      fieldLabel: '<?php echo lang("field_parent_category"); ?>',
      store: this.dsParentCategories,
      queryMode: 'local',
      valueField: 'id',
      displayField: 'text',
      name: 'parent_category_id',
      triggerAction: 'all'
    });
    
    this.frmCategories = Ext.create('Ext.form.Panel', {
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelAlign: 'top',
        labelWidth: 160,
        labelSeparator: '',
        anchor: '98%'
      },
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'categories',
        action: 'move_categories'
      },
      items: this.cboParentCategories
    });
    
    return this.frmCategories;
  },
  
  submitForm: function () {
    var parentId = this.cboParentCategories.getValue();
    
    this.frmCategories.form.submit({
      params: {'parent_category_id': parentId},
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function (form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
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
});


/* End of file categories_move_dialog.php */
/* Location: ./templates/base/web/views/categories/categories_move_dialog.php */