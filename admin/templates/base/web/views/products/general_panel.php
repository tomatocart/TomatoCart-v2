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

Ext.define('Toc.products.GeneralPanel', {
  extend: 'Ext.TabPanel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_general'); ?>';
    config.activeTab = 0;
    config.deferredRender = false;
    config.border = false;
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var panels = [];
    
    <?php
      list($defaultLanguageCode) = explode("_", lang_get_code());
      
      foreach(lang_get_all() as $l)
      {
        echo 'var lang' . $l['code'] . ' = Ext.create("Ext.Panel", {
          title:\'' . $l['name'] . '\',
          iconCls:\'icon-' . $l['country_iso'] . '-win\',
          layout: \'anchor\',
          border: false,
          frame: false,
          bodyPadding: \'10\',
          defaults: {
            anchor: \'98%\'
          },
          items: [
            {xtype: \'textfield\', fieldLabel: \'' . lang('field_name') . '\', name: \'products_name[' . $l['id'] . ']\' , allowBlank: false},
            {xtype: \'textfield\', fieldLabel: \'' . lang('field_tags') . '\', name: \'products_tags[' . $l['id'] . ']\'},
            {xtype: \'textarea\', fieldLabel: \'' . lang('field_short_description') . '\', name: \'products_short_description[' . $l['id'] . ']\' , height: \'50\'},
            {xtype: \'htmleditor\', fieldLabel: \'' . lang('field_description') . '\', name: \'products_description[' . $l['id'] . ']\', height: 230},
            {xtype: \'textfield\', fieldLabel: \'' . lang('field_url') . '\', name: \'products_url[' . $l['id'] . ']\'}
          ]
        });
        
        panels.push(lang' . $l['code'] . ');
        ';
      }
    ?>
    
    return panels;
  }
});

/* End of file general_panel.php */
/* Location: ./templates/base/web/views/products/general_panel.php */