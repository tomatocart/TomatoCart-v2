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

Ext.define('Toc.manufacturers.MetaInfoPanel', {
  extend: 'Ext.tab.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_meta'); ?>';
    config.activeTab = 0;
    config.deferredRender = false;
    config.border = false;
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var panels = [];
    
    <?php
      foreach(lang_get_all() as $l) {
    ?>
        var lang<?php echo $l['code']; ?> = Ext.create('Ext.Panel', {
          title: '<?php echo $l['name']; ?>',
          iconCls: 'icon-<?php echo $l['country_iso'] ?>-win',
          layout: 'anchor',
          border: false,
          bodyPadding: 8,
          items: [
            {xtype: 'textfield',fieldLabel: '<?php echo lang('field_page_title'); ?>' , name: 'page_title[<?php echo $l['id']; ?>]'},
            {xtype: 'textarea', fieldLabel: '<?php echo lang('field_meta_keywords') ?>', name: 'meta_keywords[<?php echo $l['id']; ?>]'},
            {xtype: 'textarea', fieldLabel: '<?php echo lang('field_meta_description') ?>', name: 'meta_description[<?php echo $l['id']; ?>]'},
            {
              xtype: 'textfield',
              fieldLabel: '<?php echo lang('field_manufacturer_url'); ?>',
              labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>',
              name: 'manufacturers_friendly_url[<?php echo $l['id']; ?>]'
            }
          ]
        });
        
        panels.push(lang<?php echo $l['code']; ?>);
    <?php
      }
    ?>
    
    return panels;
  }
});

/* End of file manufacturers_meta_info_panel.php */
/* Location: ./templates/base/web/views/manufacturers/manufacturers_meta_info_panel.php */