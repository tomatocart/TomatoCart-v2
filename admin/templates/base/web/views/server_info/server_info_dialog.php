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

Ext.define('Toc.server_info.ServerInfoDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'server_info-win';
    config.title = '<?php echo lang('heading_server_info_title'); ?>';
    config.width = 800;
    config.height = 400;
    config.iconCls = 'icon-server_info-win';
    config.layout = 'fit';
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  show: function() {
    this.frmServerInfo.load({
        url: '<?php echo site_url('server_info/get_system_info'); ?>',
        success: function(form, action) {
          Toc.server_info.ServerInfoDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this       
      });
  },
  
  buildForm: function() {
    this.frmServerInfo = Ext.create('Ext.form.Panel', {
      border: false,
      layout: 'anchor',
      bodyPadding: 10,
      fieldDefaults: {
        anchor: '98%',
        labelSeparator: '',
        labelWidth: 150
      },
      items: [
        {
          name: 'project_version',
          xtype: 'displayfield'
        },
        {
          layout: 'column',
          border: false,
          items: [
            {
              layout: 'anchor',
              border: false,
              columnWidth: .49,
              items: [
                {
                  name: 'server_host',
                  xtype: 'displayfield',
                  fieldLabel: '<?php echo lang('field_server_host'); ?>'
                },
                {
                  name: 'server_operating_system',
                  xtype: 'displayfield',
                  fieldLabel: '<?php echo lang('field_server_operating_system'); ?>'
                },
                {
                  name: 'server_date',
                  xtype: 'displayfield',
                  fieldLabel: '<?php echo lang('field_server_date'); ?>'
                }
              ]
            },
            {
              border: false,
              columnWidth: .49,
              items: [
                {
                  name: 'database_host',
                  xtype: 'displayfield',
                  fieldLabel: '<?php echo lang('field_database_host'); ?>'
                },
                {
                  name: 'database_version',
                  xtype: 'displayfield',
                  fieldLabel: '<?php echo lang('field_database_version'); ?>'
                }
              ]
            }
          ]
        },
        {
          name: 'http_server',
          xtype: 'displayfield',
          fieldLabel: '<?php echo lang('field_http_server'); ?>',
          group: true
        },
        {
          name: 'php_version',
          xtype: 'displayfield',
          fieldLabel: '<?php echo lang('field_php_version'); ?>'
        }
      ]
    });
    
    return this.frmServerInfo;
  }
})

/* End of file server_info_dialog.php */
/* Location: ./templates/base/web/views/server_info/server_info_dialog.php */