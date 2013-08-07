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
 * @filesource images_grid.php
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
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'products',
          action: 'get_images',
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
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'products',
        action: 'set_default',
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
            url: Toc.CONF.CONN_URL,
            params: {
              module: 'products',
              action: 'delete_image',
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
/* Location: ./system/modules/products/views/images_grid.php */
