<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource products_dialog.php
 */
?>

Ext.define('Toc.products.ProductDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'products-dialog-win';
    config.title = '<?php echo lang('action_heading_new_product'); ?>';
    config.layout = 'fit';
    config.width = 870;
    config.height = 540;
    config.modal = true;
    config.border = false;
    config.iconCls = 'icon-products-win';
    config.productsId = config.products_id || null;
    config.flagContinueEdit = false;
    
    config.items = this.buildForm(config.productsId);
    config.buttons = [
      {
        text: TocLanguage.btnSubmit,
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
    if (this.productsId > 0) {
      this.frmProduct.load({
        url: Toc.CONF.CONN_URL,
        params:{
          action: 'load_product',
          products_id: this.productsId
        },
        success: function(form, action) {
          var cboData = {
            'products_tax_class_id': action.result.data.products_tax_class_id, 
            'manufacturers_id': action.result.data.manufacturers_id,
            'products_weight_class': action.result.data.products_weight_class,
            'quantity_discount_groups_id': action.result.data.products_weight_class,
            'quantity_unit_class': action.result.data.quantity_unit_class,
          };
          
          this.pnlData.on('activate', function() {
            this.pnlData.updateCbos(cboData);
          }, this);
          
          this.pnlData.onPriceNetChange(); 
          
          Toc.products.ProductDialog.superclass.show.call(this);
          
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
  
  buildForm: function(productsId) {
    this.pnlData = Ext.create('Toc.products.DataPanel');
    this.pnlVariants = Ext.create('Toc.products.VariantsPanel', {productsId: productsId, dlgProducts: this}); 
    this.pnlMeta = Ext.create('Toc.products.MetaPanel');
    this.pnlCategories = Ext.create('Toc.products.CategoriesPanel', {productsId: productsId});
    this.pnlXsellProducts = Ext.create('Toc.products.XsellProductsGrid', {productsId: productsId});
    this.pnlAccessories = Ext.create('Toc.products.AccessoriesPanel', {productsId: productsId});
    this.pnlImages = Ext.create('Toc.products.ImagesPanel', {productsId: productsId});
    
    this.pnlVariants.on('variantschange', this.pnlData.onVariantsChange, this.pnlData);
    
    tabProduct = new Ext.TabPanel({
      activeTab: 0,
      border: false,
      defaults:{
        hideMode:'offsets'
      },
      deferredRender: false,
      items: [
        Ext.create('Toc.products.GeneralPanel'),
        this.pnlMeta,
        this.pnlData,
        this.pnlCategories,
        this.pnlImages,
        this.pnlVariants,
        this.pnlXsellProducts,
        this.pnlAccessories
      ]
    });
    
    this.frmProduct = Ext.create('Ext.form.FormPanel', {
      layout: 'fit',
      border: false,
      fileUpload: true,
      url: Toc.CONF.CONN_URL,
      labelWidth: 120,
      baseParams: {  
        module: 'products',
        action: 'save_product'
      },
      items: tabProduct
    });
    
    return this.frmProduct;
  },
  
  submitForm: function() {
    var params = {
      action: 'save_product',
      accessories_ids: this.pnlAccessories.getAccessoriesIds(),
      xsell_ids: this.pnlXsellProducts.getXsellProductIds(),
      products_variants: this.pnlVariants.getVariants(), 
      products_id: this.productsId,
      categories_id: this.pnlCategories.getCategories()
    };
    
    if (this.productsId > 0) {
      params.products_type = this.pnlData.getProductsType();
    }
    
    var status = this.pnlVariants.checkStatus();
    
    if (status == true) { 
      this.frmProduct.form.submit({
        params: params,
        waitMsg: TocLanguage.formSubmitWaitMsg,
        success:function(form, action){
          this.fireEvent('saveSuccess', action.result.feedback);
          
          this.close();
        },    
        failure: function(form, action) {
          if(action.failureType != 'client') {
            Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
          }
        },
        scope: this
      });  
    } else {
      Ext.MessageBox.alert(TocLanguage.msgErrTitle, '<?php echo lang('msg_select_default_variants_records'); ?>');
    }
  }
});

/* End of file products_dialog.php */
/* Location: ./system/modules/products/views/products_dialog.php */