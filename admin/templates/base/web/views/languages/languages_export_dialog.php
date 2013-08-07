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

Ext.define('Toc.languages.LanguagesExportDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'languages-export-dialog-win';
    config.width = 640;
    config.modal = true;
    config.iconCls = 'icon-languages-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: '<?php echo lang('button_export') ?>',
        handler: function() {
          this.exportLanguage();
        },
        scope: this
      },
      {
        text: TocLanguage.btnClose,
        handler: function() { 
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);
  },
  
  show: function (languagesId) {
    this.languagesId = languagesId || null;
    
    if (this.languagesId > 0) {
      this.frmExport.form.baseParams['languages_id'] = languagesId;
      this.dsGroups.getProxy().extraParams['languages_id'] = languagesId;
      this.dsGroups.load();
    }
    
    this.callParent();
  },
  
  buildForm: function() {
    this.dsGroups = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text'
      ],
      proxy: {
        type: 'ajax',
        url: '<?php echo site_url('languages/get_groups'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    this.lstGroups = Ext.create('Ext.ux.form.MultiSelect', {
      fieldLabel: '<?php echo lang('table_heading_definition_groups'); ?>',
      store: this.dsGroups,
      name: 'export_id',
      width: 580,
      height: 250,
      displayField: 'text',
      valueField: 'id'
    });
    
    this.chkData = Ext.create('Ext.form.Checkbox', {
      name: 'include_data', 
      fieldLabel: '<?php echo lang('field_export_with_data'); ?>', 
      inputValue: 'on', 
      checked: true
    });
    
    this.frmExport = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('languages/export'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 20,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%',
        labelWidth: 200
      },
      items: [ 
        {html: '<?php echo lang('introduction_export_language'); ?>', border: false, style: 'padding: 10px 0;'},
        this.lstGroups,
        this.chkData
      ]
    });
    
    return this.frmExport;
  },
  
  exportLanguage: function() {
    var languagesId = this.languagesId;
    var groups = this.lstGroups.getValue();
    var data = this.chkData.getRawValue();
    var params = "height=600px, width=640px, top= 50px, left=165px, staus=yes, toolbar=no, menubar=no, location=no, scrollbars=yes";
    
    window.open('<?php echo site_url('languages/export'); ?>' + '?languages_id=' + languagesId + '&export=' + groups + '&include_data=' + data, '<?php echo lang('button_export'); ?>', params);
  }
});

/* End of file languages_export_dialog.php */
/* Location: ./templates/base/web/views/languages/languages_export_dialog.php */