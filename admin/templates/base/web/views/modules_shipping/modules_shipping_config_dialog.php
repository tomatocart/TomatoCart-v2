<?php
/*
  $Id: modules_shipping_config_dialog.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com

  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

?>
Ext.define('Toc.modules_shipping.ShippingModuleConfigDialog', {
    extend: 'Ext.Window',
    
    constructor: function(config) {
        config = config || {};
        
        config.id = 'modules_shipping-dialog-win';
        config.width = 400;
        config.height = 400;
        config.modal = true;
        config.iconCls = 'icon-modules_shipping-win';
        config.layout = 'border';
        config.items = this.buildForm(config.code);
        
        config.buttons = [
            {
                text:TocLanguage.btnSave,
                handler: function()
                {
                	this.submitForm();
                },
                scope:this
            },
            {
                text: TocLanguage.btnClose,
                handler: function()
                {
                	this.close();
                },
    			scope:this
            }
        ];
        
        this.addEvents({'saveSuccess' : true});  
        
    	this.callParent([config]);
    },

    buildForm: function(code) {
        this.moduleForm = new Ext.form.FormPanel({ 
            url: '<?php echo site_url('modules_shipping/save'); ?>',
            baseParams: {  
                code: code
            }, 
            region: 'center',
            autoScroll: true,
            layout: 'anchor',
            defaults: {
            	anchor: '96%',
            	labelSeparator: ' ',
            	style: {
            		paddingTop: '8px',
                	paddingLeft: '8px'
                }
            },
            fieldDefaults: {labelAlign: 'top'}
        });  
        
        this.requestForm(code);
        
        return this.moduleForm;
    },
    
    requestForm: function(code) {
        Ext.Ajax.request({
            url: '<?php echo site_url('modules_shipping/get_configuration_options'); ?>',
            params: {
            	code: code
            },
            callback: function(options, success, response) {
                fields = Ext.decode(response.responseText);
                var combos = {};
                
                Ext.each(fields, function(field, i) {
                    if(field.type == 'numberfield')
                    {
                        this.moduleForm.add(
                            new Ext.form.TextField({
                                fieldLabel: '<b>' + field.title + '</b><br/>' + field.description,
                                name: field.name,
                                value: field.value,
                                width: 300
                            })
                        );
                    } 
                    else if(field.type == 'textfield')
                    {
                        this.moduleForm.add(
                            new Ext.form.TextField({
                                fieldLabel: '<b>' + field.title + '</b><br/>' + field.description,
                                name: field.name,
                                value: field.value,
                                width: 300
                            })
                        );
                    } 
                    else if(field.type == 'combobox')
                    {
                    	if (field.mode == 'local')
                    	{
                            ds = Ext.create('Ext.data.Store', {
                                fields: [
                                    'id',
                                    'text'
                                ],
                                data: field.values
                            });
                    	}
                    	else
                    	{
                            ds = Ext.create('Ext.data.Store', {
                                fields:[
                                    'id',
                                    'text'
                                ],
                                proxy: {
                                    type: 'ajax',
                                    url : field.action,
                                    reader: {
                                        type: 'json',
                                        root: Toc.CONF.JSON_READER_ROOT,
                                        totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
                                    }
                                },
                                autoLoad: true,
                                listeners: {
                                	load: function() {
                                		if (!Ext.isEmpty(field.value)) 
                                		{
                                			combos[i].setValue(field.value);
                                		}
                                	}
                                }
                            });
                    	}

                        combo = new Ext.form.ComboBox({
                            fieldLabel: '<b>' + field.title + '</b><br/>' + field.description,
                            name: field.name,
                            hiddenName: field.name,
                            store: ds,
                            displayField: 'text',
                            valueField: 'id',
                            queryMode: 'local',
                            triggerAction: 'all',
                            editable: false,
                            width: 300,
                            allowblank: false,
                            value: field.value,
                            forceSelection: true,
                            labelSeparator: ''
                        });   
                        
                        combos[i] = combo;
                        this.moduleForm.add(combo);
                    }
                },this);
                
                this.doLayout();
            },
            scope: this
        });
    
    },
    
    submitForm : function() {
        this.moduleForm.form.submit({
          waitMsg: TocLanguage.formSubmitWaitMsg,
          success: function(form, action){
            this.fireEvent('saveSuccess', action.result.feedback);
            this.close();
          },    
          failure: function(form, action) {
            if(action.failureType != 'client') {
              Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
            }
          },
          scope: this
        });   
    }
});