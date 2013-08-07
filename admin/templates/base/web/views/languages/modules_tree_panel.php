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

Ext.define('Toc.languages.ModulesTreePanel', {
  extend: 'Ext.tree.TreePanel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('tree_head_title'); ?>';
    config.region = 'west';
    config.autoScroll = true;
    config.width = 170;
    config.rootVisible = false;
    config.currentGroup = 'general';
    
    config.store = Ext.create('Ext.data.TreeStore', {
      proxy: {
        type: 'ajax',
        url: '<?php echo site_url('languages/list_translation_groups'); ?>',
        extraParams: {
          languages_id: config.languagesId
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT
        }
      },
      root: {
        id: '0',
        text: '<?php echo lang('heading_languages_title'); ?>',
        leaf: false,
        expandable: true,  
        expanded: true  
      },
      listeners: {
        'load': function() {
          this.setContentGroup('general');
        },
        scope: this
      }
    });
    
    config.listeners = {
      "itemclick": this.onTreeNodeClick
    };
    
    this.addEvents({'selectchange': true});
    
    this.callParent([config]);
  },
  
  onTreeNodeClick: function(view, record) {
    var group = record.get('id');
    
    this.setContentGroup(group);
  },
  
  setContentGroup: function(group) {
    this.currentGroup = group;
    
    this.fireEvent('selectchange', group);
  }
});

/* End of file modules_tree_panel.php */
/* Location: ./templates/base/web/views/languages/modules_tree_panel.php */