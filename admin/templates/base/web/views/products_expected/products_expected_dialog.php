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

Ext.define('Toc.products_expected.ProductsExpectedDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'products_expected-dialog-win';
    config.title = '<?php echo lang("table_heading_date_expected"); ?>';
    config.layout = 'fit';
    config.width = 400;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-products_expected-win';
    
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
  
  show: function (id) {
    var productsId = id || null;
    
    this.frmProductsExpected.form.baseParams['products_id'] = productsId;
    
    this.frmProductsExpected.load({
      url : '<?php echo site_url('products_expected/load_products_expected'); ?>',
      success: function (form, action) {
        Toc.products_expected.ProductsExpectedDialog.superclass.show.call(this);
      },
      failure: function (form, action) {
        Ext.MessageBox.alert( TocLanguage.msgErrTitle, action.result.feedback );
      },
      scope: this
    });
  },
  
  buildForm: function() {
    this.frmProductsExpected = Ext.create('Ext.form.Panel', {
      url : '<?php echo site_url('products_expected/save_products_expected'); ?>',
      baseParams: {},
      fieldDefaults: {
        anchor: '97%',
        labelSeparator: ''
      },
      border: false,
      bodyPadding: 10,
      items: [
        {
          xtype: 'datefield',
          fieldLabel: '<?php echo lang("table_heading_date_expected"); ?>',
          name: 'products_date_available',
          format: 'Y-m-d'
        }
      ]
    });
    
    return this.frmProductsExpected;
  },
  
  submitForm: function () {
    this.frmProductsExpected.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function (form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },
      failure: function (form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert( TocLanguage.msgErrTitle, action.result.feedback );
        }
      },
      scope: this
    });
  }
});

/* End of file products_expected_dialog.php */
/* Location: ./templates/base/web/views/products_expected/products_expected_dialog.php */