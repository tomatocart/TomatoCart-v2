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

Ext.define('Toc.products.ImagesGrid', {
  extend: 'Ext.grid.GridPanel',
  
  statics: {
    renderAction: function(value) {
      if(value == 1) {
        return '<img class="img-button btn-default" style="cursor: pointer" src="<?php echo icon_url('default.png'); ?>" />&nbsp;<img class="img-button btn-delete" style="cursor: pointer" src="<?php echo icon_url('delete.png'); ?>" />';
      }else {
        return '<img class="img-button btn-set-default" style="cursor: pointer" src="<?php echo icon_url('default_grey.png'); ?>" />&nbsp;<img class="img-button btn-delete" style="cursor: pointer" src="<?php echo icon_url('delete.png'); ?>" />';
      }
    }
  }, 
  
  constructor: function(config) {
    var statics = this.statics();
    
    config = config || {};
    
    config.productsId = config.productsId || null;
    config.border = false;
    config.region = 'center';
    
    config.store = Ext.create('Ext.data.Store', {
      fields: ['id', 'image', 'name', 'size', 'default'],
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('products/get_images'); ?>',
        extraParams: {
          products_id: config.productsId
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.columns =[
      { header: '&nbsp;', dataIndex: 'image', align: 'center'},
      { header: '<?php echo lang('subsection_images'); ?>', dataIndex: 'name', flex: 1},
      { header: '&nbsp;', dataIndex: 'size'},
      { header: '&nbsp;', dataIndex: 'default', renderer: statics.renderAction, align: 'center', width:50}
    ];
    
    config.tbar = [
      { 
        text: TocLanguage.btnRefresh,
        iconCls:'refresh',
        handler: this.onRefresh,
        scope: this
      }
    ];
    
    config.listeners = {
      itemclick: this.onClick
    };
    
    this.callParent([config]);
  },
  
  onClick: function(view, record, item, index, e) {
    var action = false;

    if (index !== false) {
      var btn = e.getTarget(".img-button");
      
      if (btn) {
        action = btn.className.replace(/img-button btn-/, '').trim();
        var code = this.getStore().getAt(index).get('code');
        var title = this.getStore().getAt(index).get('title');
        
        switch(action) {
          case 'set-default':
            this.onSetDefault(index);
            break;
          case 'delete':
            this.onDelete(index);
            break;
        }
      }
    }
  },
  
  onSetDefault: function(index) {
    var record = this.getStore().getAt(index);
    var image  = Ext.isEmpty(record.get('id')) ? record.get('name') : record.get('id');   
    
    Ext.Ajax.request({
      url: '<?php echo site_url('products/set_default'); ?>',
      params: {
        image: image
      },
      callback: function(options, success, response){
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          this.onRefresh();
        } else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
        }
      },
      scope: this
    }); 
  },
  
  onDelete: function(index) {
    var record = this.getStore().getAt(index);
    var image = Ext.isEmpty(record.get('id')) ? record.get('name') : record.get('id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url: '<?php echo site_url('products/delete_image'); ?>',
            params: {
              image: image
            },
            callback: function(options, success, response){
              var result = Ext.decode(response.responseText);
              
              if (result.success == true) {
                this.onRefresh();
              } else {
                Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
              }
            },
            scope: this
          });   
        }
    }, this);
  },
  
  onRefresh: function() {
    this.getStore().load();
  }
});

/* End of file images_grid.php */
/* Location: ./templates/base/web/views/products/images_grid.php */