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

Ext.define('Toc.homepage_info.HomepageInfoPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_homepage_text_title'); ?>';
    config.border = false;
    config.layout = 'fit';
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var tabHomepageInfo = Ext.create('Ext.tab.Panel', {
      activeTab: 0,
      border: false,
      deferredRender: false
    });
    
    <?php
      foreach(lang_get_all() as $l)
      {
    ?>
        var lang<?php echo $l['code']; ?> = Ext.create('Ext.Panel', {
          title: '<?php echo $l['name']; ?>',
          iconCls: 'icon-<?php echo $l['country_iso']; ?>-win',
          layout: 'anchor',
          bodyPadding: 6,
          border: false,
          items: [
            {xtype: 'htmleditor', height: 300, fieldLabel: '<?php echo lang('field_homepage_text'); ?>', name: 'index_text[<?php echo $l['id']; ?>]'}
          ]
        });
        
        tabHomepageInfo.add(lang<?php echo $l['code']; ?>);
    <?php
      }
    ?>
    
    return tabHomepageInfo;
  }
});

/* End of file homepage_info_panel.php */
/* Location: ./templates/base/web/views/homepage_info/homepage_info_panel.php */