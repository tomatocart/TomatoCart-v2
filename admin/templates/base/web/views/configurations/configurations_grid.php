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

//prevent the js error as creating more than two configurations windows
if (!Ext.ClassManager.isCreated('Toc.configurations.ConfigurationGrid')) {
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
        url: '<?php echo site_url('configurations/list_configurations'); ?>',
        params: {
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
                    url : '<?php echo site_url('configurations'); ?>/' + field.action,
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
          //flag to check whether it is necessary to send the ajax request to update the value
          var sendRequest = true;
          
          if (field.type == 'combobox') {
            //get old value for the combobox so that we could check whether the value is really changed
            var originalValue;
            switch(oldValue) {
              case 'True':
                originalValue = 1;
                break;
              case 'False':
                originalValue = -1;
                break;
              case 'Optional':
                originalValue = 0;
                break;
              case 'null':
              case 'undefined':
                originalValue = -2;
                break;
              default:
                originalValue = oldValue;
            }
            
            //the value isn't changed, don't send the ajax request
            if ((!value && value !== 0) || value == originalValue || originalValue == -2) {
              sendRequest = false;
            } 
          
            if (!this.comboProperties[field.title].getRawValue()) {
              source[field.title] = oldValue;
            }else {
              source[field.title] = this.comboProperties[field.title].getRawValue();
            }
            
            this.setSource(source);
            
          //for the textfield  
          }else if (oldValue == value) {
            sendRequest = false;
          }
          
          //send the ajax request to update the configuration value
          if (sendRequest) {
            Ext.Ajax.request({
              url: '<?php echo site_url('configurations/save_configurations'); ?>',
              params: {
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
        }
      }, this);
    }
  });
}

/* End of file configurations_grid.php */
/* Location: ./templates/base/web/views/configurations/configurations_grid.php */