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

Ext.define('Toc.products.VariantDataPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = config.valuesId;
    config.border = false;
    config.bodyPadding = 3;
    config.autoScroll = true;
    config.split = true;
    
    this.dlgProducts = config.dlgProducts;
    
    config.items = this.buildForm(config.valuesId, config.data, config.downloadable);
    
    this.callParent([config]);
  },
  
  buildForm: function(valuesId, data, downloadable) {
     var items = [
      {
        xtype: 'fieldset',
        title: '<?php echo lang('fieldset_lengend_data_title'); ?>',
        labelWidth: 80,
        labelSeparator: ' ',
        autoHeight: true,
        defaults: {
          anchor: '94%'
        },
        items: [
          {
            fieldLabel: '<?php echo lang('field_quantity'); ?>',
            name: 'variants_quantity[' + valuesId + ']',
            xtype: 'numberfield',
            allowDecimals: false,
            allowNegative: false,
            value: data.variants_quantity
          },
          {
            fieldLabel: '<?php echo lang('field_price_net'); ?>',
            xtype: 'textfield',
            name: 'variants_net_price[' + valuesId + ']',
            value: data.variants_net_price
          },
          {
            fieldLabel: '<?php echo lang('field_sku'); ?>',
            xtype: 'textfield',
            name: 'variants_sku[' + valuesId + ']',
            value: data.variants_sku
          },
          {
            fieldLabel: '<?php echo lang('field_model'); ?>',
            xtype: 'textfield',
            name: 'variants_model[' + valuesId + ']',
            value: data.variants_model
          },
          {
            fieldLabel: '<?php echo lang('field_weight'); ?>',
            xtype: 'textfield',
            name: 'variants_weight[' + valuesId + ']',
            value: data.variants_weight
          }, 
          {
            layout: 'column',
            border: false,
            items:[
              {
                labelSeparator: ' ',
                labelWidth: 80,
                border: false,
                items: [{
                  fieldLabel: '<?php echo lang('field_status'); ?>', 
                  xtype:'radio', 
                  name: 'variants_status_' + valuesId, 
                  boxLabel: '<?php echo lang('status_enabled'); ?>', 
                  xtype:'radio', 
                  inputValue: '1', 
                  checked: (data.variants_status == 1 ? true: false) 
                }]
              }, 
              {
                border: false,
                items: [{
                  boxLabel: '<?php echo lang('status_disabled'); ?>', 
                  xtype:'radio',
                  style: 'margin:0 8px;', 
                  name: 'variants_status_' + valuesId, 
                  hideLabel: true, 
                  inputValue: '0', 
                  checked: (data.variants_status == 1 ? false: true) 
                }]
              }
            ]
          }
        ]
      },
      
      this.fsImages = Ext.create('Ext.form.FieldSet', {
        title: '<?php echo lang("fieldset_lengend_image_title"); ?>',
        labelWidth: 80, 
        labelSeparator: ' ', 
        defaults: {anchor: '94%'}, 
        autoHeight: true, 
        items: this.buildImagesPanel(valuesId, data.variants_image)
      })
    ];
    
     //register a listener to images grid to update images panel
    this.dlgProducts.pnlImages.grdImages.getStore().on('load', this.onImagesChange, this);

    return items;
  },
  
  buildImagesPanel: function(valuesId, selectedImage) {
    var dsImages = this.dlgProducts.pnlImages.grdImages.getStore();
    var pnlImages = {
      layout: 'column',
      border: false,
      items: []
    };
    
    if ((count = dsImages.getCount()) > 0) {
      for (var i = 0; i < count; i++ ) {
        var imageID = dsImages.getAt(i).get('id');
        var imageName = dsImages.getAt(i).get('name');
        var imagePath = dsImages.getAt(i).get('image');
        var inputValue = imageID || imageName;
        
        var pnlImage = {
          layout: 'column',
          columnWidth: .33,
          border: false,
          items: [
            {
              labelSeparator: ' ',
              border: false,
              items: [
                {
                  xtype: 'radio', 
                  name: 'variants_image_' + valuesId, 
                  hideLabel: true,
                  inputValue: inputValue,
                  checked: ((inputValue == selectedImage) ? true : false)
                }
              ]
            },
            {
              xtype: 'panel',
              border: false,
              html: '<div style="margin: 3px; cursor:pointer;">' + imagePath + '</div>'
            }
          ]
        };
        
        pnlImages.items.push(pnlImage); 
      }
    } else {
      pnlImages.items.push({
        xtype: 'displayfield', 
        hideLabel: true, 
        value: '<?php echo lang('ms_notice_no_products_image');?>', 
        style: 'margin-bottom: 10px'
      });
    }
    
    return pnlImages;
  },
  
  onImagesChange: function() {
    this.fsImages.removeAll();
    this.fsImages.add(this.buildImagesPanel(this.valuesId, this.data.variants_image));
  }, 
});

/* End of file variants_data_panel.php */
/* Location: ./templates/base/web/views/products/variants_data_panel.php */