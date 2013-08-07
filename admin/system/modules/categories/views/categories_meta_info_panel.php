<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource modules/categories/views/categories_meta_info.php
 */
?>

Ext.define('Toc.categories.MetaInfoPanel', {
  extend: 'Ext.TabPanel',
  
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
      foreach (lang_get_all() as $l) {
        echo 'var lang' . $l['code'] . ' = Ext.create("Ext.Panel", {
          title:\'' . $l['name'] . '\',
          iconCls: \'icon-' . $l['country_iso'] . '-win\',
          layout: \'anchor\',
          border: false,
          bodyPadding: 8,
          items: [
            {xtype: \'textfield\', fieldLabel: \'' . lang('field_page_title') . '\', name: \'page_title[' . $l['id'] . ']\'},
            {xtype: \'textarea\', fieldLabel: \'' . lang('field_meta_keywords') . '\', name: \'meta_keywords[' . $l['id'] . ']\', height: 60},
            {xtype: \'textarea\', fieldLabel: \'' . lang('field_meta_description') . '\', name: \'meta_description[' . $l['id'] . ']\', height: 80},
            {xtype: \'textfield\', fieldLabel: \'' . lang('field_url') . '\', name: \'categories_url[' . $l['id'] . ']\'}
          ]
        });
        
        panels.push(lang' . $l['code'] . ');
        ';
      }
    ?>
    
    return panels;
  }
});

/* End of file categories_meta_info.php */
/* Location: ./system/modules/categories/views/categories_meta_info.php */

