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

Ext.define('Toc.manufacturers.ManufacturersDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'manufacturers_dialog-win';
    config.title = '<?php echo lang('action_heading_new_manufacturer'); ?>';
    config.width = 500;
    config.height = 380;
    config.modal = true;
    config.layout = 'fit';
    config.iconCls = 'icon-manufacturers-win';
    
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
  
  show: function(id) {
    var manufacturersId = id || null;
    
    if (manufacturersId > 0) {
      this.frmManufacturer.baseParams['manufacturers_id'] = manufacturersId;
      
      this.frmManufacturer.load({
        url : '<?php echo site_url('manufacturers/load_manufacturer'); ?>',
        success: function(form, action) {
          var img = action.result.data.manufacturers_image;
          
          if (img) {
            var html = '<img src ="<?php echo IMGHTTPPATH; ?>manufacturers/' + img + '"  style = "margin-left: 110px; width: 80px; height: 80px" /><br/><span style = "padding-left: 110px;">/images/manufacturers/' + img + '</span>';
            this.pnlGeneral.getComponent('manufactuerer_image_panel').update(html);
          }          
          
          Toc.manufacturers.ManufacturersDialog.superclass.show.call(this);
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
    this.pnlGeneral = Ext.create('Toc.manufacturers.GeneralPanel');
    this.pnlMetaInfo = Ext.create('Toc.manufacturers.MetaInfoPanel');
    
    var tabManufacturers = Ext.create('Ext.tab.Panel', {
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
    
    this.frmManufacturer = Ext.create('Ext.form.Panel', {
      id: 'form-manufacturers',
      layout: 'fit',
      border: false,
      fileUpload: true,
      fieldDefaults: {
        labelSeparator: '',
        labelWidth: 100,
        anchor: '97%'
      },
      url : '<?php echo site_url('manufacturers/save_manufacturer'); ?>',
      baseParams: {},
      items: tabManufacturers
    });
    
    return this.frmManufacturer;
  },
  
  submitForm : function() {
    this.frmManufacturer.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
         this.fireEvent('savesuccess', action.result.feedback);
         this.close();  
      },    
      failure: function(form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },  
      scope: this
    });   
  }
});

/* End of file manufacturers_dialog.php */
/* Location: ./templates/base/web/views/manufacturers/manufacturers_dialog.php */