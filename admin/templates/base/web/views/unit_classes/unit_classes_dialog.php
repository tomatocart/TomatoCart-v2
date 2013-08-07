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

Ext.define('Toc.unit_classes.UnitClassesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'unit_classes-dialog-win';
    config.title = '<?php echo lang('action_heading_new_quantity_unit_class'); ?>';
    config.layout = 'fit';
    config.width = 400;
    config.height = 200;
    config.modal = true;
    config.iconCls = 'icon-unit_classes-win';
    
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
  
  show: function (unit_class_id) {
    var unit_class_id = unit_class_id || null;

    if (unit_class_id > 0) {
      this.frmUnitClasses.form.baseParams['unit_class_id'] = unit_class_id;
      
      this.frmUnitClasses.load({
        url: '<?php echo site_url('unit_classes/load_unit_class'); ?>',
        success: function(form, action) {
          if (!action.result.data.is_default) {    
            this.frmUnitClasses.add({xtype: 'checkbox', name: 'default', id:'default_unit_classess', fieldLabel: '<?php echo lang('field_is_default_unit'); ?>', anchor:''});
          }
          
          Toc.unit_classes.UnitClassesDialog.superclass.show.call(this);
        },
        failure: function(form, action) {
          Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this       
      });
    } else {   
      this.frmUnitClasses.add({xtype: 'checkbox', name: 'default', id:'default_unit_classess', fieldLabel: '<?php echo lang('field_is_default_unit'); ?>', anchor:''});    
      
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmUnitClasses = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('unit_classes/save_unit_class'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 5,
      fieldDefaults: {
        anchor: '97%',
        labelSeparator: '',
        labelWidth: 150
      }
    });
    
    <?php
      $i = 1;
      foreach(lang_get_all() as $l)
      {
    ?>
        var txtLang<?php echo $l['id']; ?> = Ext.create('Ext.form.TextField', {
          name: 'unit_class_title[<?php echo $l['id']; ?>]',
          fieldLabel: '<?php echo $i != 1 ? '&nbsp;' : lang('field_unit_class_name'); ?>',
          allowBlank: false,
          labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>'
        });
        
        this.frmUnitClasses.add(txtLang<?php echo $l['id']; ?>);
    <?php
        $i++;
      }
    ?>
    
    return this.frmUnitClasses;
  },
  
  submitForm: function () {
    this.frmUnitClasses.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function (form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },
      failure: function (form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback)
        }
      },
      scope: this
    });
  }
});

/* End of file unit_classes_dialog.php */
/* Location: ./templates/base/web/views/unit_classes/unit_classes_dialog.php */