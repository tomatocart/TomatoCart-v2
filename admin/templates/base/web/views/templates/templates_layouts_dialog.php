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

Ext.define('Toc.templates.TemplatesLayoutsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'templates-layouts-dialog-win';
    config.title = 'Templates Layouts';
    config.layout = 'fit';
    config.width = 890;
    config.height = 540;
    config.modal = true;
    config.border = false;
    config.iconCls = 'icon-templates-win';
    
    this.code = config.code || null;
    this.templatesId = config.templatesId || null;
    
    config.items = this.buildForm(this.templatesId, this.code);
    
    config.listeners = {
      render: this.getLayoutData
    };
    
    this.modules = [];
    this.moduleTexts = [];
    this.moduleSortOrders = {};
    
    this.web = [];
    this.mobile = [];
    
    this.callParent([config]);
  },
  
  getLayoutData: function() {
    var scope = this;
    var mask = new Ext.LoadMask(this.el, {msg: 'Loading...'});
    mask.show();
    
    Ext.Ajax.request({
      url: '<?php echo site_url('templates/get_layout_data'); ?>',
      params: {
        code: this.code,
        templates_id: this.templatesId
      },
      callback: function(options, success, response) {
        mask.hide();
        
        var result = Ext.decode(response.responseText);
        if (result.success == true) {
          //initialize the web layout content groups
          //
          if (!Ext.isEmpty(result.layout.web)) {
            Ext.each(result.layout.web, function(module_group) {
            	if (!Ext.isEmpty(module_group.modules)) {
            		Ext.each(module_group.modules, function(module){
						scope.modules.push(module);            		
            		});
            	}
            });
            
	          this.pnlWebContentGroups.addGroupsPortals(result.layout.web);
          }

    	  //initialize the mobile layout content groups
    	  //
          if (!Ext.isEmpty(result.layout.mobile)) {
            Ext.each(result.layout.mobile, function(module_group) {
            	if (!Ext.isEmpty(module_group.modules)) {
            		Ext.each(module_group.modules, function(module){
						scope.modules.push(module);            		
            		});
            	}
            });
            
	        this.pnlMobileContentGroups.addGroupsPortals(result.layout.mobile);
          }

		  //initialize the mobile layout content groups
		  //
          if (!Ext.isEmpty(result.layout.pad)) {
            Ext.each(result.layout.pad, function(module_group) {
            	if (!Ext.isEmpty(module_group.modules)) {
            		Ext.each(module_group.modules, function(module){
					  	scope.modules.push(module);            		
            		});
            	}
            });
            
	          this.pnlPadContentGroups.addGroupsPortals(result.layout.pad);
          }
        }
      },
      scope: this
    });
  },
  
  addModule: function(module) {
    this.modules.push(module);
  },
  
  getModule: function(moduleId) {
    module = false;
    
    if (!Ext.isEmpty(this.modules)) {
      Ext.each(this.modules, function(m) {
      	if (m.id == moduleId) {
      		module = m;
      	}
      });
    }
    
    return module;
  },
  
  buildForm: function(templatesId, code) {
    this.pnlModules = Ext.create('Toc.templates.ModulesPanel');
    this.pnlWebContentGroups = Ext.create('Toc.templates.ModulesGroupsPanel', {templatesId: templatesId, code: code, medium: 'web', parent: this});
    this.pnlModuleSettings = Ext.create('Toc.templates.ModulesSettingsPanel');
    
    //register module event for web layout
    this.pnlWebContentGroups.on('modulechanged', this.onModuleChanged, this);
    this.pnlWebContentGroups.on('moduleremoved', this.onModulesRemoved, this);
    this.pnlWebContentGroups.on('portletdropped', this.onPortletsDropped, this);
    
    this.pnlModuleSettings.on('savesuccess', this.onSettingsSaveSuccess, this);
    
    this.pnlTemplatesModules = Ext.create('Ext.Panel', {
      layout: 'border',
      padding: 5,
      defaults: {
        split: true
      },
      border: false,
      items: [
      	this.pnlModules, 
        {
        	xtype: 'tabpanel',
        	region: 'center',
        	items: [
        		this.pnlWebContentGroups
        	]
        },
        this.pnlModuleSettings
      ]
    });
    
    return this.pnlTemplatesModules;
  },
  
  setModule: function(module) {
    scope = this;
    
    if (!Ext.isEmpty(this.modules)) {
      Ext.each(this.modules, function(m, i) {
      	if (m.id == module.id) {
      		if (Ext.isEmpty(module.column)) {
      			module.column = m.column;
      		}
      		
      		scope.modules[i] = module;
      	}
      });
    }
  },
  
  onSettingsSaveSuccess: function(module) {
    this.setModule(module);
  },
  
  onModuleChanged: function(module) {
  	this.pnlModuleSettings.buildForm(module);
  },
  
  onModulesRemoved: function(moduleId, pnlPortal) {
  	var module = this.getModule(moduleId);
    var scope = this;
    var mask = new Ext.LoadMask(this.el, {msg: 'Loading...'});
    mask.show();
    
    Ext.Ajax.request({
      url: '<?php echo site_url('templates/delete_template_module'); ?>',
      params: {
        mid: moduleId
      },
      callback: function(options, success, response) {
        mask.hide();
        
        var result = Ext.decode(response.responseText);
        if (result.success == true) {
        	//clean up module settings panel
        	this.pnlModuleSettings.removeAll();
        	
        	//remove portlet
            var el = Ext.select('#portlet-' + module.templates_id + '-' + module.id);
            if (!Ext.isEmpty(el)) {
                el.remove();
            }
            
            setTimeout(function() {
    			var portlets = pnlPortal.getEl().select('.x-portlet');
                if (portlets.getCount() == 0) {
    				var el = pnlPortal.getEl().select('p.droppableMsg');
                    el.insertHtml('beforeEnd', '<span><?php echo lang('introduction_drop_module'); ?></span>');
                }
            	
            	//remove the module from the array
            	Ext.Array.remove(scope.modules, module);
            	
            	pnlPortal.doLayout();
            }, 100);
            
            
        }
      },
      scope: this
    });
  },
  
  onPortletsDropped: function(dropEvent) {
    var mId = dropEvent.panel.getId().split('-')[2];
    var module = this.getModule(mId);
    
    var prefix = 'content-group-' + module.medium + '-';
    var panelId = dropEvent.column.getId();
    
    var group = panelId.substr(prefix.length);
    
    var scope = this;
    var mask = new Ext.LoadMask(this.el, {msg: 'Loading...'});
    mask.show();
    
    Ext.Ajax.request({
      url: '<?php echo site_url('templates/update_template_module_group'); ?>',
      params: {
        mid: mId,
        group: group
      },
      callback: function(options, success, response) {
        mask.hide();
        
        var result = Ext.decode(response.responseText);
        if (result.success == true) {
          //get old column
        	oldColumn = module.column;
        
          //save module
          module.column = dropEvent.column;
          module.content_group = group;
        	scope.setModule(module);
        	
        	//remove drop message
          var el = Ext.select('#content-group-' + module.medium + '-' + group + ' p.droppableMsg span');
          el.remove();
        	
        	//do layout
        	oldColumn.doLayout();
        	dropEvent.column.doLayout();
        }
      },
      scope: this
    });
  }
});