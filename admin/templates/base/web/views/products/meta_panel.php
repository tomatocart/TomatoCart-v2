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

Ext.define('Toc.products.MetaPanel', {
  extend: 'Ext.TabPanel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_meta'); ?>';
    config.activeTab = 0;
    config.deferredRender = false;
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var panels = [];
    this.txtProductUrl = [];
    
    <?php
      foreach(lang_get_all() as $l)
      {
        echo 'var lang' . $l['code'] . ' = Ext.create(\'Ext.Panel\', {
          title:\'' . $l['name'] . '\',
          iconCls: \'icon-' . $l['country_iso'] . '-win\',
          layout: \'anchor\',
          bodyPadding: \'10\',
          defaults: {
            anchor: \'98%\'
          },
          items: [
            {xtype: \'textfield\', fieldLabel: \'' . lang('field_page_title') . '\', name: \'products_page_title[' . $l['id'] . ']\'},
            {xtype: \'textfield\', fieldLabel: \'' . lang('field_meta_keywords') . '\', name: \'products_meta_keywords[' . $l['id'] . ']\'},
            {xtype: \'textarea\', fieldLabel: \'' . lang('field_meta_description') . '\', name: \'products_meta_description[' . $l['id'] . ']\', height: 200},
            this.txtProductUrl[' . $l['id'] . '] = Ext.create(\'Ext.form.TextField\', {fieldLabel: \'' . lang('field_friendly_url') . '\', name: \'products_friendly_url[' . $l['id'] . ']\'})
          ]
        });
        
        panels.push(lang' . $l['code'] . ');
        ';
      }
    ?>
    
    return panels;
  }
});

/* End of file meta_panel.php */
/* Location: ./templates/base/web/views/products/meta_panel.php */