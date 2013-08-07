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
 * @filesource ./system/modules/newsletters/views/log_dialog.php
 */
?>

Ext.define('Toc.newsletters.LogDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'log-dialog-win';
    config.title = '<?php echo lang('heading_newsletters_title'); ?>';
    config.layout = 'fit';
    config.width = 600;
    config.height = 350;
    
    config.items = this.buildGrid();
    
    config.buttons = [
      {
        text: TocLanguage.btnClose,
        handler: function() { 
          this.close();
        },
        scope: this
      }
    ];
    
    this.callParent([config]);
  },
  
  show: function(newslettersId) {
    this.grdLog.getStore().getProxy().extraParams['newsletters_id'] = newslettersId;
    this.grdLog.getStore().load();
     
    this.callParent();
  },
  
  buildGrid: function() {
    var dsLog = Ext.create('Ext.data.Store', {
      fields:[
        'email_address', 
        'sent',
        'date_sent'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'newsletters',
          action: 'list_log'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    this.grdLog = Ext.create('Ext.grid.Panel', {
      store: dsLog,
      border: false,
      columns: [
        {header: '<?php echo lang('table_heading_email_addresses'); ?>', dataIndex: 'email_address', flex: 1},
        {header: '<?php echo lang('table_heading_sent'); ?>', width: 100, align: 'center', dataIndex: 'sent'},
        {header: '<?php echo lang('table_heading_date_sent'); ?>', width: 150, align: 'center', dataIndex: 'date_sent'},
      ],
      dockedItems: [{
        xtype: 'pagingtoolbar',
        store: dsLog,
        dock: 'bottom',
        displayInfo: true
      }]
    });
    
    return this.grdLog;
  }
});

/* End of file log_dialog.php */
/* Location: ./system/modules/newsletters/views/log_dialog.php */
