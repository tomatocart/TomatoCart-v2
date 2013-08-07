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

Ext.define('Toc.manufacturers.GeneralPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_general'); ?>';
    config.border = false;
    config.layout = 'anchor';
    config.bodyPadding = 8;
    config.items = this.buildForm();
      
    this.callParent([config]);
  },
  
  buildForm: function() {
    var items = [];
    
    items.push({xtype: 'textfield', fieldLabel: '<?php echo lang('field_name'); ?>', name: 'manufacturers_name', allowBlank: false});
    items.push({xtype: 'panel', id: 'manufactuerer_image_panel', border: false, html: ''});
    items.push({xtype: 'fileuploadfield', fieldLabel: '<?php echo lang('field_image'); ?>', name: 'manufacturers_image'});
    
    <?php
      $i = 1;
      foreach(lang_get_all() as $l)
      {
    ?>
        this.lang<?php echo $l['id']; ?> = Ext.create('Ext.form.TextField', {
          name: 'manufacturers_url[' + '<?php echo $l['id']; ?>' + ']',
          fieldLabel: '<?php echo $i == 1 ? lang('field_url') : '&nbsp;'; ?>',
          labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>',
          value: 'http://'
        });
        
        items.push(this.lang<?php echo $l['id']; ?>);
    <?php
        $i++;
      }  
    ?>
    
    return items;
  }
});

/* End of file manufacturers_general_panel.php */
/* Location: ./templates/base/web/views/manufacturers/manufacturers_general_panel.php */