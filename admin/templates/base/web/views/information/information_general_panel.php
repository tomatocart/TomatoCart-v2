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

Ext.define('Toc.information.GeneralPanel', {
  extend: 'Ext.tab.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_general'); ?>';
    config.activeTab = 0;
    config.border = false;
    config.deferredRender = false;
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var items = [];
    
    <?php
      foreach(lang_get_all() as $l)
      {
    ?>
        var pnlLang<?php echo $l['code']; ?> = Ext.create('Ext.Panel', {
          title: '<?php echo $l['name']; ?>',
          iconCls: 'icon-<?php echo $l['country_iso']; ?>-win',
          border: false,
          layout: 'anchor',
          bodyPadding: 6,
          items: [
            {
              xtype: 'textfield', 
              fieldLabel: '<?php echo lang('field_article_name'); ?>', 
              name: 'articles_name[<?php echo $l['id']; ?>]', 
              allowBlank: false
            },
            {
              xtype: 'htmleditor',
              fieldLabel: '<?php echo lang('filed_article_description'); ?>',
              name: 'articles_description[<?php echo $l['id']; ?>]',
              height: 230
            }
          ]
        });
        
        items.push(pnlLang<?php echo $l['code']; ?>);
    <?php
      }
    ?>
    
    return items;
  }
});

/* End of file information_general_panel.php */
/* Location: ./templates/base/web/views/information/information_general_panel.php */