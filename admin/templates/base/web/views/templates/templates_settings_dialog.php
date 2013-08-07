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

Ext.define('Toc.templates.TemplatesSettingsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'templates-settings-dialog-win';
    config.title = 'Templates Settings';
    config.layout = 'fit';
    config.width = 600;
    config.height = 480;
    config.modal = true;
    config.border = false;
    config.iconCls = 'icon-templates-win';
    
    this.templatesId = config.templatesId || null;
    this.code = config.code || null;
    
    config.items = this.buildForm(this.templatesId, this.code);
    
    config.buttons = [
      {
        text: TocLanguage.btnSubmit,
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
    
    this.callParent([config]);
    
    this.keys = [];
    this.moduleTexts = [];
    this.moduleSortOrders = {};
  },
  
  buildForm: function(templatesId, code) {
    this.frmSettings = Ext.create('Ext.form.Panel', { 
      url: '<?php echo site_url('templates/save_template_params'); ?>',
      baseParams: {  
        templates_id: templatesId
      }, 
      autoScroll: true,
      fieldDefaults: {
        labelAlign: 'top'
      },
      defaults: {
        anchor: '98%'
      },
      bodyPadding: 10
    });  
    
    this.requestForm(templatesId, code);
    
    return this.frmSettings;
  },

  requestForm: function(templatesId, code) {
    Ext.Ajax.request({
      url: '<?php echo site_url('templates/get_template_params'); ?>',
      params: {
        templates_id: templatesId,
        code: code
      },
      callback: function(options, success, response) {
        fields = Ext.decode(response.responseText);
        
        Ext.each(fields, function(field, i) {
          if(field.type == 'textfield'){
            this.frmSettings.add(
              new Ext.form.TextField({
                fieldLabel: '<b>' + field.title + '</b><br/>' + field.description,
                name: field.name,
                value: field.value
              })
            );
          } else if(field.type == 'textarea'){
            this.frmSettings.add(
              new Ext.form.TextArea({
                fieldLabel: '<b>' + field.title + '</b><br/>' + field.description,
                name: field.name,
                value: field.value
              })
            );
          } else if(field.type == 'combobox') {
            combo = new Ext.form.ComboBox({
              fieldLabel: '<b>' + field.title + '</b><br/>' + field.description,
              name: field.name,
              hiddenName: field.name,
              store: new Ext.data.SimpleStore({
                  fields: [{name: 'id', mapping: 'id'}, {name: 'text', mapping: 'text'}],
                  data : field.values
              }),
              displayField: 'text',
              valueField: 'id',
              mode: 'local',
              triggerAction: 'all',
              editable: false,
              allowblank: false,
              value: field.value
            });
                  
            this.frmSettings.add(combo);
          } else if(field.type == 'credit_cards_checkbox') {
            selected = field.value.split(',');
            Ext.each(field.values, function(value, i){
              var hideLabel = (i != 0) ? true : false;
              
              checked = false;
              for (var j = 0; j < selected.length; j++) {
                if (selected[j] == value.id) {
                  checked = true;
                }
              }  
              
              checkBox = new Ext.form.Checkbox({
                fieldLabel: '<b>' + field.title + '</b><br />' + field.description,
                name: field.name,
                boxLabel: value.text,
                inputValue: value.id,
                hideLabel: hideLabel,
                checked : checked
              });
              
              this.frmSettings.add(checkBox);
            }, this);

          }
        },this);
        
        this.doLayout();
      },
      scope: this
    });  
  },
  
  submitForm : function() {
    this.frmSettings.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
         this.fireEvent('savesuccess', action.result.feedback);
         this.close();  
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