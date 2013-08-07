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

Ext.define('Toc.templates.ModulesSettingsPanel', {
  extend: 'Ext.form.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('heading_title_module_settings'); ?>';
    config.width = 280;
    config.bodyPadding = 10;
    config.region = 'east';
    config.autoScroll = true;
    
    config.layout = 'anchor';
    config.defaults = {anchor: '98%'};
    config.fieldDefaults = {labelAlign: 'top'};
    
    config.url = '<?php echo site_url('templates/save_module_settings'); ?>';
    
    config.module = null;

    config.buttons = [
      {
        text: TocLanguage.btnSave,
        iconCls: 'save',
        handler: function() {
        	this.submitForm();
        },
        scope: this
      }
    ];
    
    this.callParent([config]);
  },
  
  buildForm: function(module) {
  	this.module = module;
  	
  	this.setTitle('<?php echo lang('heading_title_module_settings'); ?>' + ': ' + module.title);
  
  	//remove all components
  	this.removeAll();
  	
  	this.buildLayoutSettingsFieldset(module);
  	this.buildModuleSettingsFieldset(module);
  },
  
  buildLayoutSettingsFieldset: function(module) {
    var dsPages = Ext.create('Ext.data.Store', {
      fields: ['id', 'text'],
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('templates/get_pages'); ?>',
        extraParams: {
          module: 'templates',
          action: 'get_pages'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true,
      listeners: {
        load: function() {this.cboPages.setValue(module.content_page);},
        scope: this
      }
    });
    
    this.cboPages = Ext.create('Ext.form.ComboBox', {
      fieldLabel: 'Pages',
      store: dsPages, 
      name: 'pages', 
      queryMode: 'local',
      displayField: 'text', 
      valueField: 'id', 
      editable: false,
      forceSelection: true,
      allowBlank: false,
      value: module.content_page
    });
    
    var pnlLayoutSettings = Ext.create('Ext.form.FieldSet', {
      title: 'Layout Settings',
      border: false,
      bodyPadding: 10,
      layout: 'anchor',
      defaults: {anchor: '98%'},
      items: [
        this.cboPages,
        {
        	fieldLabel: 'Page Specific?', 
        	labelSeparator: '', 
        	xtype: 'checkbox', 
        	name: 'page_specific', 
        	labelAlign: 'left',
					checked: (module.page_specific == 1) ? true : false 
				},
        {
          layout: 'column',
          border: false,
          items:[
            {
              border: false,
          		labelAlign: 'left',
              items: [{
                fieldLabel: 'Status', 
                labelWidth: 70,
                xtype:'radio', 
                name: 'status', 
                boxLabel: 'Enable', 
                inputValue: '1',
          			labelAlign: 'left',
          			checked: (module.status == 1) ? true : false 
              }]
            }, 
            {
              border: false,
              items: [{
                boxLabel: 'Disable', 
                xtype:'radio',
                style: 'margin:0 10px;', 
                name: 'status', 
                hideLabel: true, 
                inputValue: '0',
          			checked: (module.status == 0) ? true : false
              }]
            }
          ]
        },
        {fieldLabel: 'Sort Order', xtype: 'textfield', name: 'sort_order', value: module.sort_order}
      ]
    });
    
    this.insert(0, pnlLayoutSettings);
  },
  
  	buildModuleSettingsFieldset: function(module) {
        if (!Ext.isEmpty(module.params)) {
            var pnlModuleSettings = Ext.create('Ext.form.FieldSet', {
                title: 'Module Settings',
                border: false,
                bodyPadding: 10,
                layout: 'anchor',
                defaults: {anchor: '98%'}
            });
  
			Ext.each(module.params, function(field, i) {
                if(field.type == 'textfield'){
                    pnlModuleSettings.add(
                        new Ext.form.TextField({
                            fieldLabel: field.title + '<br/><span class="desc">' + field.description + '</span>',
                            name: 'params[' + field.name + ']',
                            value: field.value,
                            labelSeparator: ''
                        })
                    );
                } if(field.type == 'numberfield'){
                    pnlModuleSettings.add(
                        new Ext.form.TextField({
                            fieldLabel: field.title + '<br/><span class="desc">' + field.description + '</span>',
                            name: 'params[' + field.name + ']',
                            value: field.value,
							labelSeparator: ''
                        })
                    );
                } else if(field.type == 'textarea'){
                    pnlModuleSettings.add(
                        new Ext.form.TextArea({
                            fieldLabel: field.title + '<br/><span class="desc">' + field.description + '</span>',
                            name: 'params[' + field.name + ']',
                            value: field.value,
                            labelSeparator: ''
                        })
                    );
                } else if(field.type == 'combobox') {
                	var combo = null;
                    if (field.mode == 'local') {
                        ds = Ext.create('Ext.data.Store', {
                            fields: [
                                'id',
                                'text'
                            ],
                            data: field.values
                        });
                    }else if (field.mode == 'remote') {
                        ds = Ext.create('Ext.data.Store', {
                            fields:[
                                'id',
                                'text'
                            ],
                            proxy: {
                                type: 'ajax',
                                url : '<?php echo site_url(); ?>/' + field.url,
                                reader: {
                                    type: 'json',
                                    root: Toc.CONF.JSON_READER_ROOT,
                                    totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
                                }
                            },
                            autoLoad: true,
                            listeners: {
                            	load: function() {combo.setValue(field.value); }
                            }
                        });
                    }
                    
                    combo = new Ext.form.ComboBox({
                        fieldLabel: field.title + '<br/><span class="desc">' + field.description + '</span>',
                        name: 'params[' + field.name + ']',
                        hiddenName: field.name,
                        store: ds,
                        displayField: 'text',
                        valueField: 'id',
                        mode: 'local',
                        triggerAction: 'all',
                        editable: false,
                        allowBlank: false,
                        value: field.value,
                        forceSelection: true,
                        labelSeparator: ''
                    });
                        
                  	pnlModuleSettings.add(combo);
                }
			},this);
      
			this.insert(1, pnlModuleSettings);
		}
  	},
  
  	submitForm : function() {
        this.form.submit({
            params: {
                id: this.module.id,
                medium: this.module.medium,
                code: this.module.module,
                content_group: this.module.content_group,
                templates_id: this.module.templates_id,
                params: this.module.params
            },
            waitMsg: TocLanguage.formSubmitWaitMsg,
            success: function(form, action) {
                if (action.result.success == true) {
                    //Ext.Msg.alert(TocLanguage.msgSuccessTitle, TocLanguage.msgSuccessTitle);
                    
                    this.module.page_specific = action.result.data.page_specific;
                    this.module.status = action.result.data.status;
                    this.module.content_page = action.result.data.content_page;
                    this.module.sort_order = action.result.data.sort_order;
                    
                    if (!Ext.isEmpty(this.module.params)) {
                    	for(i = 0; i < this.module.params.length; i++) {
                    		var field = this.module.params[i];
                    		
                    		this.module.params[i].value = action.result.data.params[field.name];
                    	}
                    }
                    
                    this.fireEvent('savesuccess', this.module);
                }
            },    
            failure: function(form, action) {
                if (action.failureType != 'client') {
					Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
                }
            }, 
            scope: this
        });   
  	}
});