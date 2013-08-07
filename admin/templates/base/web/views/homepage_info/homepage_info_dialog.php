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

Ext.define('Toc.homepage_info.HomepageInfoDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('heading_homepage_info_title'); ?>';
    config.layout = 'fit';
    config.width = 870;
    config.height = 450;
    config.iconCls = 'icon-homepage_info-win';
    config.border = false;
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
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
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function() {
    this.frmPagehomeInfo.load({
      url: '<?php echo site_url('homepage_info/load_info') ?>'
    }, this);
    
    this.callParent();
  },
  
  buildForm: function() {
    var pnlMetaInfo = Ext.create('Toc.homepage_info.MetaInfoPanel');
    var pnlHomepageInfo = Ext.create('Toc.homepage_info.HomepageInfoPanel');
    
    var tabProduct = Ext.create('Ext.tab.Panel', {
      activeTab: 0,
      defaults:{
        hideMode:'offsets'
      },
      deferredRender: false,
      items: [
        pnlHomepageInfo,
        pnlMetaInfo
      ]
    });
    
    this.frmPagehomeInfo = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('homepage_info/save_info'); ?>',
      layout: 'fit',
      labelWidth: 120,
      border: false,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '98%'
      },
      items: tabProduct
    });
    
    return this.frmPagehomeInfo;
  },
  
  submitForm: function() {
    this.frmPagehomeInfo.form.submit({
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

/* End of file homepage_info_dialog.php */
/* Location: ./templates/base/web/views/homepage_info/homepage_info_dialog.php */