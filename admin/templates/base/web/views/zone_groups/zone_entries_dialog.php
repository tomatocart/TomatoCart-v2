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

Ext.define('Toc.zone_groups.ZoneEntriesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'zone_entries-dialog-win';
    config.title = '<?php echo lang("action_heading_new_zone_group"); ?>';
    config.width = 440;
    config.modal = true;
    config.iconCls = 'icon-zone_groups-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text:TocLanguage.btnSave,
        handler: function(){
          this.submitForm();
        },
        scope:this
      },
      {
        text: TocLanguage.btnClose,
        handler: function(){
          this.close();
        },
        scope:this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);  
  },
  
  show: function (geoZoneId, entryId) {
    this.geoZoneId = geoZoneId || null;
    var geoZoneEntryId = entryId || null;  
    
    this.frmZoneEntry.form.baseParams['geo_zone_id'] = this.geoZoneId;
        
    if (geoZoneEntryId > 0) {
      this.frmZoneEntry.form.baseParams['geo_zone_entry_id'] = geoZoneEntryId;
    
      this.frmZoneEntry.load({
        url: '<?php echo site_url('zone_groups/load_zone_entry'); ?>',
        success: function (form, action) {
          this.cboCountries.setValue(action.result.data.zone_country_id);
          this.cboCountries.setRawValue(action.result.data.countries_name);
          this.updateCboZones(action.result.data.zone_id);
          
          Toc.zone_groups.ZoneEntriesDialog.superclass.show.call(this);
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
    var dsCountries = Ext.create('Ext.data.Store', {
      fields: ['countries_id', 'countries_name'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('zone_groups/get_countries'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.cboCountries = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_country'); ?>',
      store: dsCountries, 
      name: 'countries_id', 
      queryMode: 'local',
      displayField: 'countries_name', 
      valueField: 'countries_id', 
      editable: false,
      forceSelection: true,
      listeners :{
        select: this.onCboCountriesSelect,
        scope: this
      } 
    });
    
    this.dsZones = Ext.create('Ext.data.Store', {
      fields:[
        'zone_id',
        'zone_name'
      ],
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('zone_groups/get_zones'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    this.cboZones = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_zone'); ?>',
      disabled: true, 
      store: this.dsZones, 
      name: 'zone_id', 
      queryMode: 'local',
      displayField: 'zone_name', 
      valueField: 'zone_id', 
      editable: false,
      forceSelection: true
    });
    
    this.frmZoneEntry = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('zone_groups/save_zone_entry'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 5,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      items: [this.cboCountries, this.cboZones]
    });
    
    return this.frmZoneEntry;
  },
  
  onCboCountriesSelect: function() {
    this.updateCboZones();
  },
  
  updateCboZones: function(zoneId) {
    this.cboZones.enable();  
    this.dsZones.getProxy().extraParams['countries_id'] = this.cboCountries.getValue();  
    
    if(zoneId) {
      this.dsZones.on('load', function(){
        this.cboZones.setValue(zoneId);
      }, this);
    }
    
    this.dsZones.load();
  },
  
  submitForm: function() {
    this.frmZoneEntry.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success:function(form, action){
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },    
      failure: function(form, action) {
        if(action.failureType != 'client'){
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this
    });   
  }
});

/* End of file zone_entries_dialog.php */
/* Location: ./templates/base/web/zone_groups/zone_entries_dialog.php */