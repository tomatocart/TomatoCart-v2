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

Ext.define('Toc.articles.MetaInfoPanel', {
  extend: 'Ext.tab.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_meta'); ?>';
    config.activeTab = 0;
    config.border = false;
    config.deferredRender = false;
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var panels = [];
    
    <?php
      foreach(lang_get_all() as $l)
      {
    ?>
        var lang<?php echo $l['code']; ?> = Ext.create('Ext.Panel', {
          title: '<?php echo $l['name']; ?>',
          iconCls: 'icon-<?php echo $l['country_iso']; ?>-win',
          layout: 'anchor',
          border: false,
          bodyPadding: 6,
          items: [
            {xtype: 'textfield', fieldLabel: '<?php echo lang('field_page_title'); ?>', name: 'page_title[<?php echo $l['id']; ?>]'},
            {xtype: 'textarea', fieldLabel: '<?php echo lang('field_meta_keywords'); ?>', name: 'meta_keywords[<?php echo $l['id']; ?>]'},
            {xtype: 'textarea', fieldLabel: '<?php echo lang('field_meta_description'); ?>', name: 'meta_description[<?php echo $l['id']; ?>]'},
            {
              xtype: 'textfield',
              fieldLabel: '<?php echo lang('field_article_url'); ?>',
              name: 'articles_url[<?php echo $l['id']; ?>]',
              labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>'
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

/* End of file articles_meta_info_panel.php */
/* Location: ./templates/base/web/views/articles/articles_meta_info_panel.php */