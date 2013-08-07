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
 * @filesource
 */
?>

Ext.define('Toc.configurations.ConfigurationGrid', {
  extend: 'Ext.grid.property.Grid',
  
  constructor: function(config) {
    config = config || {};
    
    config.source = {};
    
    config.border = false;
    config.nameColumnWidth = 500;
    
    config.listeners = {
      propertychange: this.onPropertyChange,
      scope: this
    };
    
    this.getSource(config.gID);
    
    this.addEvents({'savepropery': true});
    
    this.callParent([config]);
  },
  
  getSource: function(gID){
    this.comboProperties = {};
    
    Ext.Ajax.request({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'configurations',
        action: 'list_configurations',
        gID: gID
      },
      callback: function(options, success, response) {
        var fields = Ext.decode(response.responseText);
        var customEditors = {};
        var propertyNames = {};
        var source = {};
        
        this.fields = fields;
        
        Ext.each(fields, function(field, i) {
          if (field.type == 'combobox') {
            if (field.mode == 'local') {
              this['ds' + field.title] = Ext.create('Ext.data.Store', {
                fields: [
                  'id',
                  'text'
                ],
                data: field.values
              });
            }else if (field.mode == 'remote') {
              this['ds' + field.title] = Ext.create('Ext.data.Store', {
                fields:[
                  'id',
                  'text'
                ],
                proxy: {
                  type: 'ajax',
                  url : Toc.CONF.CONN_URL,
                  extraParams: {
                    module: field.module,
                    action: field.action
                  },
                  reader: {
                    type: 'json',
                    root: Toc.CONF.JSON_READER_ROOT,
                    totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
                  }
                }
              });
            }
            
            this.comboProperties[field.title] =  Ext.create('Ext.form.ComboBox', {
              name: field.name,
              store: this['ds' + field.title],
              displayField: 'text',
              valueField: 'id',
              queryMode: field.mode,
              editable: false
            });
            
            customEditors[field.title] = this.comboProperties[field.title];
          }else if (field.type == 'textarea') {
            customEditors[field.title] = Ext.create('Ext.form.TextArea', {name: field.name});
          }
          
          source[field.title] = field.value
          
          propertyNames[field.title] = '<strong>' + field.title + '</strong><br />Description: ' + field.description;
         
        }, this);
        
        this.setSource(source);
        this.customEditors = customEditors;
        this.propertyNames = propertyNames;
      },
      scope: this
    });
  },
  
  onPropertyChange: function(source, recordId, value, oldValue) {
    Ext.each(this.fields, function(field, i) {
      if (field.title == recordId) {
        if (field.type == 'combobox') {
          source[field.title] = this.comboProperties[field.title].getRawValue();
          
          this.setSource(source);
        }
          
        Ext.Ajax.request({
          url: Toc.CONF.CONN_URL,
          params: {
            module: 'configurations',
            action: 'save_configurations',
            cID: field.id,
            configuration_value: value
          },
          callback: function(options, success, response) {
            result = Ext.decode(response.responseText);
            
            if (result.success == true) {
              if (recordId == 'Country') {
                this.dsZone.load();
              }
              
              this.fireEvent('saveproperty', result.feedback);
            } else {
              Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
            }
          },
          scope: this
        });
      }
    }, this);
  }
});