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

Ext.define('Toc.articles_categories.GeneralPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_general'); ?>';
    config.bodyPadding = 8;
    config.border = false;
    config.layout = 'anchor';
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var items = [];
    
    <?php
      $i = 1;
      
      foreach(lang_get_all() as $l)
      {
    ?>
        var txtLang<?php echo $l['id'] ?> = Ext.create('Ext.form.TextField', {
          name: 'articles_categories_name[<?php echo $l['id']; ?>]',
          fieldLabel: '<?php echo $i != 1 ? '&nbsp;' : lang('field_name') ?>',
          allowBlank: false,
          labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>'
        });
        
        items.push(txtLang<?php echo $l['id']; ?>);
    <?php
        $i++;
      }
    ?>
    
    var pnlPublish = {
      layout: 'column',
      border: false,
      items: [
        {
          border: false,
          items: [
            {
              xtype: 'radio', 
              name: 'articles_categories_status', 
              fieldLabel: '<?php echo lang('field_publish'); ?>', 
              inputValue: '1', 
              boxLabel: '<?php echo lang('field_publish_yes'); ?>', 
              checked: true
            }
          ]
        },
        {
          border: false,
          style: 'padding-left: 5px;',
          items: [
            {
              xtype: 'radio', 
              hideLabel: true, 
              name: 'articles_categories_status', 
              inputValue: '0',
              boxLabel: '<?php echo lang('field_publish_no'); ?>'
            }
          ]
        }
      ]
    };
    
    items.push(pnlPublish);
    
    items.push({xtype: 'numberfield', id: 'articles_categories_order', name: 'articles_categories_order', fieldLabel: '<?php echo lang('field_articles_order'); ?>', allowBlank: false, value: 0});
    
    return items;
  }
});

/* End of file articles_categories_general_panel.php */
/* Location: ./templates/base/web/views/articles_categories/articles_categories_general_panel.php */