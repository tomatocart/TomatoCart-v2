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

Ext.define('Toc.images.ImagesGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.region = 'center';
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'module', 
        'run'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('images/list_images'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.columns =[
      {header: '<?php echo lang('table_heading_modules'); ?>', dataIndex: 'module', flex: 1},
      {
        xtype: 'actioncolumn', 
        width: 50,
        header: '<?php echo lang("table_heading_action"); ?>',
        items: [{
          tooltip: TocLanguage.tipExecute,
          iconCls: 'icon-action icon-execute-record',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent(rec.get('run'), rec);
          },
          scope: this
        }]
      }
    ];
    
    config.tbar = [
      {
        text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onRefresh,
        scope: this
      }
    ];
    
    this.addEvents({'checkimages': true, 'resizeimages': true});
    
    this.callParent([config]);
  },
  
  onRefresh: function() {
    this.getStore().load();
  }
});

/* End of file images_grid.php */
/* Location: ./templates/base/web/views/images/images_grid.php */