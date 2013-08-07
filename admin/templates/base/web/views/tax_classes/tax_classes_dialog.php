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

Ext.define('Toc.tax_classes.TaxClassesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'tax-class-dialog-win';
    config.title = '<?php echo lang('action_heading_new_tax_class'); ?>';
    config.width = 500;
    config.modal = true;
    config.iconCls = 'icon-tax_classes-win';
    
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
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);  
  },
  
  show: function (id) {
    var taxClassesId = id || null;
    
    if (taxClassesId > 0) {
      this.frmTaxClass.form.baseParams['tax_class_id'] = taxClassesId;
      
      this.frmTaxClass.load({
        url: '<?php echo site_url('tax_classes/load_tax_class'); ?>',
        success: function() {
          Toc.tax_classes.TaxClassesDialog.superclass.show.call(this);
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
    this.frmTaxClass = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('tax_classes/save_tax_class'); ?>',
      baseParams: {},
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      border: false,
      bodyPadding: 10,
      items: [                           
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_title'); ?>', 
          name: 'tax_class_title', 
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_description'); ?>', 
          name: 'tax_class_description'
        }
      ]
    });
    
    return this.frmTaxClass;
  },
  
  submitForm: function() {
    this.frmTaxClass.form.submit({
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

/* End of file tax_classes_dialog.php */
/* Location: ./templates/base/web/views/tax_classes/tax_classes_dialog.php */