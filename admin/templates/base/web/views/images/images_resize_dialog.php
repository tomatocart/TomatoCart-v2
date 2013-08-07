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

Ext.define('Toc.images.ImagesResizeDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'images-resize-dialog-win';
    config.layout = 'fit';
    config.width = 480;
    config.height = 300;
    config.modal = true;
    config.iconCls = 'icon-images-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        id: 'btn-execute-resize-images',
        text: TocLanguage.tipExecute,
        handler: function () {
          Ext.getCmp('btn-execute-resize-images').hide();
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
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var dsImageGroups = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('images/get_image_groups'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.lstImage = Ext.create('Ext.ux.form.MultiSelect', {
      fieldLabel: '<?php echo lang('images_resize_field_groups'); ?>',
      store: dsImageGroups,
      name: 'groups[]',
      width: 400,
      height: 150,
      legend: '<?php echo lang('images_resize_table_heading_groups'); ?>',
      displayField: 'text',
      valueField: 'id'
    });
    
    this.chkImage = Ext.create('Ext.form.Checkbox', {
      fieldLabel: '<?php echo lang('images_resize_field_overwrite_images'); ?>',
      name: 'overwrite',
      inputValue: '1'
    });
    
    this.frmImage = Ext.create('Ext.form.Panel', {
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelSeparator: '',
        labelWidth: 150
      },
      items:[this.lstImage, this.chkImage]
    });
    
    return this.frmImage;
  },
  
  submitForm: function() {
    var groups = Ext.JSON.encode(this.lstImage.getValue()) || '';
    var overwrite = this.chkImage.getValue() ? 1 : '';
    
    this.removeAll();
    
    this.grdImages = Ext.create('Ext.grid.Panel', {
      border: false,
      store: Ext.create('Ext.data.Store', {
        fields:[
          'group', 
          'count'
        ],
        pageSize: Toc.CONF.GRID_PAGE_SIZE,
        proxy: {
          type: 'ajax',
          url : '<?php echo site_url('images/list_images_resize_result'); ?>',
          extraParams: {
            overwrite: overwrite,
            groups: groups
          },
          reader: {
            type: 'json',
            root: Toc.CONF.JSON_READER_ROOT,
            totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
          }
        },
        autoLoad: true
      }),
      
      columns: [
        {header: '<?php echo lang('images_resize_table_heading_groups'); ?>', dataIndex: 'group', flex: 1},
        {header: '<?php echo lang('images_resize_table_heading_total_resized'); ?>', dataIndex: 'count'},
      ]
    });
    
    this.add(this.grdImages);
    this.doLayout();
  }
});

/* End of file images_resize_dialog.php */
/* Location: ./templates/base/web/views/images/images_resize_dialog.php */