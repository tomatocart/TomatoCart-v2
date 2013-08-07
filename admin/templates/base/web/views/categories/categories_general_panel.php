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

Ext.define('Toc.categories.GeneralPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_general'); ?>';
    config.border = false;
    config.bodyPadding = 8;
    config.layout = 'anchor';
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var items = [];
    
    this.dsParentCategories = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text',
        'margin'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('categories/list_parent_category'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    this.cboParentCategories = Ext.create('Ext.form.ComboBox', {
      listConfig: {
        getInnerTpl: function() {
          return '<div style="margin-left: {margin}px">{text}</div>';
        }
      },
      fieldLabel: '<?php echo lang("field_parent_category"); ?>',
      store: this.dsParentCategories,
      queryMode: 'local',
      valueField: 'id',
      displayField: 'text',
      name: 'parent_category_id',
      triggerAction: 'all'
    });
    
    items.push(this.cboParentCategories);
    
    <?php
      $i = 1; 
    
      foreach(lang_get_all() as $l)
      {
    ?>
        var lang<?php echo $l['id']; ?> = Ext.create('Ext.form.TextField', {
          name: 'categories_name[<?php echo $l['id']; ?>]',
          fieldLabel: '<?php echo $i != 1 ? '&nbsp;' : lang('field_name'); ?>',
          labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>',
          allowBlank: false
        });
        
        items.push(lang<?php echo $l['id'] ?>);
        
    <?php
        $i++;
      }
    ?>
    
    items.push({xtype: 'filefield', fieldLabel: '<?php echo lang("field_image"); ?>', name: 'image'});
    items.push({xtype: 'panel', name: 'categories_image', id: 'categories_image_panel', border: false});
    
    items.push({
      layout: 'column',
      border: false,
      items:[{
        id: 'status',
        border: false,
        style: "padding-right: 5px;",
        items:[{fieldLabel: '<?php echo lang('field_status'); ?>', xtype:'radio', id: 'statusEnable', name: 'categories_status', boxLabel: '<?php echo lang('status_enabled'); ?>', inputValue: '1', checked: true}]
      },{
        border: false,
        items: [{fieldLabel: '<?php echo lang('status_disabled'); ?>', boxLabel: '<?php echo lang('status_disabled'); ?>', xtype:'radio', name: 'categories_status', hideLabel: true, inputValue: '0'}]
      }]
    });
    
    items.push({xtype: 'numberfield', fieldLabel: '<?php echo lang("field_sort_order"); ?>', name: 'sort_order', value: 0});
    
    return items;
  }
});

/* End of file categories_general_panel.php */
/* Location: ./templates/base/web/views/categories/categories_general_panel.php */