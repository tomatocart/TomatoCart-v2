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

Ext.define('Toc.zone_groups.ZoneGroupsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'zone_groups-dialog-win';
    config.title = '<?php echo lang("action_heading_new_zone_group"); ?>';
    config.width = 440;
    config.modal = true;
    config.iconCls = 'icon-zone_groups-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function () {
          this.submitForm();
        },
        scope: this
      }, 
      {
        text: TocLanguage.btnClose,
        handler: function () {
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function (id) {
    var geoZoneId = id || null;
      
    if (geoZoneId > 0) {
      this.frmZoneGroup.form.baseParams['geo_zone_id'] = geoZoneId;
      
      this.frmZoneGroup.load({
        url: '<?php echo site_url('zone_groups/load_zone_group'); ?>',
        success: function (form, action) {
          Toc.zone_groups.ZoneGroupsDialog.superclass.show.call(this);
        },
        failure: function (form, action) {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this
      });
    } else {
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmZoneGroup = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('zone_groups/save_zone_group'); ?>',
      baseParams: {},
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      border: false,
      bodyPadding: 5,
      items: [
        {
          xtype: 'textfield',
          fieldLabel: '<?php echo lang("field_name"); ?>',
          name: 'geo_zone_name',
          allowBlank: false
        },
        {
          xtype: 'textfield',
          fieldLabel: '<?php echo lang("field_description"); ?>',
          name: 'geo_zone_description'
        }
      ]
    });
    
    return this.frmZoneGroup;
  },
  
  submitForm: function () {
    this.frmZoneGroup.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function (form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },
      failure: function (form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this
    });
  }
});

/* End of file zone_groups_dialog.php */
/* Location: ./templates/base/web/views/zone_groups/zone_groups_dialog.php */