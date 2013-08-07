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

Ext.define('Toc.image_groups.ImageGroupsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'image_groups-dialog-win';
    config.title = '<?php echo lang('action_heading_new_image_group'); ?>';
    config.layout = 'fit';
    config.width = 450;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-image_groups-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text:TocLanguage.btnSave,
        handler: function() {
          this.submitForm();
        },
        scope:this
      },
      {
        text: TocLanguage.btnClose,
        handler: function() {
          this.close();
        },
        scope:this
      }
    ];
    
    this.callParent([config]);
  },
  
  show: function(id) {
    imageGroupsId = id || null;      
    
    if (imageGroupsId > 0) {
      this.frmImageGroup.form.baseParams['image_groups_id'] = imageGroupsId;
      
      this.frmImageGroup.load({
        url : '<?php echo site_url('image_groups/load_image_group'); ?>',
        success: function(form, action) {
          if(action.result.data.is_default) {
            Ext.getCmp('default_image_group').disable();
          }
            
          Toc.image_groups.ImageGroupsDialog.superclass.show.call(this);
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
    this.frmImageGroup = Ext.create('Ext.form.Panel', {
      url : '<?php echo site_url('image_groups/save_image_group'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        anchor: '97%',
        labelSeparator: '',
        labelWidth: 150
      },
      items: [
        <?php
          $i = 1;
          foreach(lang_get_all() as $l)
          {
        ?>
            {
              xtype: 'textfield', 
              name: 'title[<?php echo $l['id']; ?>]', 
              fieldLabel: '<?php echo ($i != 1) ? '&nbsp;' : lang('field_title') ?>',
              labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>'
            },
        <?php
            $i++;
          }
        ?>
        {xtype: 'textfield', name: 'code', allowBlank: false, fieldLabel: '<?php echo lang('field_code'); ?>'},
        {xtype: 'numberfield', name: 'size_width', allowBlank: false, fieldLabel: '<?php echo lang('field_width'); ?>'},
        {xtype: 'numberfield', name: 'size_height', allowBlank: false, fieldLabel: '<?php echo lang('field_height'); ?>'},
        {xtype: 'checkbox', name: 'force_size', fieldLabel: '<?php echo lang('field_force_size'); ?>', anchor: ''},
        {xtype: 'checkbox', name: 'is_default', id: 'default_image_group', fieldLabel: '<?php echo lang('field_set_as_default'); ?>', anchor: ''}
      ]
    });
    
    return this.frmImageGroup;
  },
  
  submitForm: function() {
    this.frmImageGroup.form.submit({
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

/* End of file image_groups_dialog.php */
/* Location: ./templates/base/web/views/image_groups/image_groups_dialog.php */