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

Ext.define('Toc.product_variants.ProductVariantsEntriesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'product_variants_entries-dialog-win';
    config.title = '<?php echo lang("action_heading_new_group_entry");?>';
    config.width = 440;
    config.modal = true;
    config.iconCls = 'icon-product_variants-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text:TocLanguage.btnSave,
        handler: function(){
          this.submitForm();
        }, 
        scope: this
      },
      {
        text: TocLanguage.btnClose,
        handler: function(){
          this.close();
        }, 
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function (id, valuesId) {
    this.variantsGroupsId = id || null;
    var variantsValuesId = valuesId || null;
    
    this.frmEntry.form.baseParams['products_variants_groups_id'] = this.variantsGroupsId;
    
    if (variantsValuesId > 0) {
      this.frmEntry.form.baseParams['products_variants_values_id'] = variantsValuesId;
      
      this.frmEntry.load({
        url : '<?php echo site_url('product_variants/load_product_variants_entry'); ?>',
        success: function (form, action) {
          Toc.product_variants.ProductVariantsEntriesDialog.superclass.show.call(this);
        },
        failure: function (form, action) {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this
      });
    } else {
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmEntry = Ext.create('Ext.form.Panel', {
      url : '<?php echo site_url('product_variants/save_product_variants_entry'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        anchor: '97%',
        labelSeparator: ''
      }
    });
    
    <?php
        $i = 1;
        foreach(lang_get_all() as $l)
        {
    ?>
          var lang<?php echo $l['id']; ?> = Ext.create('Ext.form.TextField', {
            name: 'products_variants_values_name[<?php echo $l['id']; ?>]',
            fieldLabel: '<?php echo $i != 1 ? '&nbsp;' : lang('field_group_entry_name'); ?>',
            allowBlank: false,
            labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>'
          });
          
          this.frmEntry.add(lang<?php echo $l['id']; ?>);
    <?php
          $i++;
        }
    ?>
    
    return this.frmEntry;
  },
  
  submitForm: function() {
    this.frmEntry.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success:function(form, action){
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },    
      failure: function(form, action) {
        if(action.failureType != 'client'){
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this
    });   
  }
});

/* End of file product_variants_entries_dialog.php */
/* Location: ./templates/base/web/views/product_variants/product_variants_entries_dialog.php */