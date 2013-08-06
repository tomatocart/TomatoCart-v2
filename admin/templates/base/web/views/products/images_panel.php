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

Ext.define('Toc.products.ImagesPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_images'); ?>';
    config.layout = 'fit';
    
    config.productsId = config.productsId || null;
    config.items = this.buildForm(config.productsId);
    
    this.callParent([config]);
  },
  
  buildForm: function(productsId) {
    this.grdImages = Ext.create('Toc.products.ImagesGrid', {productsId: productsId});
    
    var pnlImages = Ext.create('Ext.Panel', {
      layout: 'border',
      border: false,
      items: [
        this.grdImages,
        {
          region:'east',
          xtype: 'panel',
          layout: 'accordion',
          split: true,
          width: 250,
          minSize: 175,
          maxSize: 400,
          border: false,
          items: [
            this.getImageUploadPanel(productsId),
            this.getLocalImagesPanel(productsId)
          ]
        }
      ]
    });
    
    return pnlImages;
  },
  
  getImageUploadPanel: function(productsId) {
    var productsId = productsId || null;
    var scope = this;
    var grdImages = this.grdImages;
    var imgIdentity = 0;
    
    var pnlUploadGuid = Ext.create('Ext.panel.Panel', {
        border: false,
        html: '<p style="margin:10px 5px;"><strong><?php echo lang('introduction_multiple_upload'); ?></strong></p>'
    });
    
    this.btnUpload = Ext.create('Ext.Button', {
      text: TocLanguage.btnUpload,
      iconCls: 'icon-upload',
      handler: function(){
        var form = this.up('form').getForm();
        
        if (form.isValid()) {
          form.submit({
            url: '<?php echo site_url('products/upload_image'); ?>',
            params: {
              products_id: productsId
            },
            waitMsg: 'Uploading your photo...',
            success: function(fp, o) {
              imgIdentity = 0;
              
              scope.btnUpload.disable();
              
              scope.grdImages.onRefresh();
              scope.pnlImagesUpload.removeAll();
            }
          });
        }
      }
    });
    
    this.pnlImagesUpload = Ext.create('Ext.form.Panel', {
      title: '<?php echo lang('image_remote_upload'); ?>',
      bodyPadding: '5',
      border: false,
      items: [
        pnlUploadGuid
      ],
      tbar: [
        { 
          xtype: 'button', 
          text: TocLanguage.btnAdd, 
          iconCls:'add',
          handler: function() {
            this.pnlImagesUpload.add({
              xtype: 'filefield',
              width: 230,
              buttonText: '',
              buttonConfig: {
                iconCls: 'image-add'
              },
              name: 'images_' + imgIdentity
            });
            
            imgIdentity++;
            
            this.btnUpload.enable();
          },
          scope: this
        },
        this.btnUpload,
        { 
          xtype: 'button', 
          text: TocLanguage.btnDelete, 
          iconCls:'remove',
          handler: function() {
            this.pnlImagesUpload.removeAll();
            
            var pnlUploadGuid = Ext.create('Ext.panel.Panel', {
              border: false,
              html: '<p style="margin:10px 5px;"><strong><?php echo lang('introduction_multiple_upload'); ?></strong></p>'
            });
            
            this.pnlImagesUpload.add(pnlUploadGuid);
            this.btnUpload.disable();
          },
          scope: this
        },
      ]
    }); 
    
    return this.pnlImagesUpload;
  },
  
  getLocalImagesPanel: function(productsId) {
    var dsLocalImages = Ext.create('Ext.data.Store', {
      fields:['id', 'text'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('products/get_local_images'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.selLocalImages = Ext.create('Ext.ux.form.MultiSelect', {
      width: 220,
      store: dsLocalImages,
      name: 'multiselect'
    });
    
    var pnlLocalImages = Ext.create('Ext.Panel', {
      title: '<?php echo lang('image_local_files'); ?>',
      layout: 'border',
      border: false,
      items:[
        {
          region: 'north',
          border: false,
          html: '<p class="form-info"><?php echo lang('introduction_select_local_images'); ?></p>'
        },  
        {
          xtype:'fieldset',
          title: '<?php echo lang('section_images'); ?>',
          region: 'center',
          border: false,
          items: this.selLocalImages
        }
      ],
      tbar: [{
        text: TocLanguage.btnAdd,
        iconCls: 'add',
        handler: this.onLocalImageAdd,
        scope:this
      }]   
    });
    
    return pnlLocalImages;
  },
  
  onLocalImageAdd: function() {
    var images = this.selLocalImages.getValue();
    
    if (Ext.isEmpty(images))
    {
      return;
    }
    else
    {
      images = images.join(',');
    }
    
    Ext.Ajax.request({
      url: '<?php echo site_url('products/assign_local_images'); ?>', 
      params: {
        products_id: this.productsId,
        localimages: images
      },
      callback: function(options, success, response) {
        if (success == true) {
          var result = Ext.decode(response.responseText);
          
          if (result.success == true) {
            this.grdImages.onRefresh();
            this.selLocalImages.store.load();
          }
        } else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrTitle);
        }
      },
      scope: this
    });
  }
});

/* End of file images_panel.php */
/* Location: ./templates/base/web/views/products/images_panel.php */