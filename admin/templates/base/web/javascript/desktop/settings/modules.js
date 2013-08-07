/*
 * $Id: modules.js $
 * TomatoCart Open Source Shopping Cart Solutions
 * http://www.tomatocart.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v2 (1991)
 * as published by the Free Software Foundation.
 * 
 * NOTE:
 * This code is based on code from qWikiOffice Desktop 0.8.1
 * http://www.qwikioffice.com
 *
 * Ext JS Library 2.0 Beta 2
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
  
 * http://extjs.com/license
 */
Ext.define('Toc.settings.ModulePanel', {
  extend: 'Ext.panel.Panel',
  
  constructor: function(app) {
    var moduleStore = Ext.create('Ext.data.Store', {
    	pageSize: 300,
      fields: ['parent', 'id', 'text', 'autorun', 'contextmenu', 'quickstart', 'shortcut'],
      proxy: {
        type: 'ajax',
        method: 'post',
        url: Toc.CONF.CONN_URL,
        extraParams: {
          action: 'load_modules'
        },
        reader: {
          type: 'json'
        }
      },
      groupField: 'parent',
      autoLoad: true
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
      iconCls: 'icon-grid',
      store: moduleStore,
      height: 405,
      features: [{ftype:'grouping'}],
      columns: [{
        header: 'module',
        flex: 1,
        hidden: true,
        dataIndex: 'parent'
      },
    	{
        header: TocLanguage.colModule,
        flex: 1,
        dataIndex: 'text'
      },
      {
        xtype: 'checkcolumn',
        header: TocLanguage.colAutorun,
        dataIndex: 'autorun',
        listeners: {
      	  checkchange: function(column, recordIndex, checked) {
      	  	var moduleId = moduleStore.getAt(recordIndex).get('id');
      	  	
      	    if (checked) {
      	      app.getDesktop().addAutoRun(moduleId, true);
      	    }else {
      	      app.getDesktop().removeAutoRun(moduleId, true);
      	    }
          }
        }
      },
      {
        xtype: 'checkcolumn',
        header: TocLanguage.colQuickstart,
        dataIndex: 'quickstart',
        listeners: {
          checkchange: function(column, recordIndex, checked) {
            var moduleId = moduleStore.getAt(recordIndex).get('id');
            
            if (checked) {
              app.getDesktop().addQuickStartButton(moduleId, true);
            }else {
              app.getDesktop().removeQuickStartButton(moduleId, true);
            }
          }
        }
      },
      {
        xtype: 'checkcolumn',
        header: TocLanguage.colShortcut,
        dataIndex: 'shortcut',
        listeners: {
          checkchange: function(column, recordIndex, checked) {
            var moduleId = moduleStore.getAt(recordIndex).get('id');
            
            if (checked) {
              app.getDesktop().addShortcut(moduleId, true);
            }else {
              app.getDesktop().removeShortcut(moduleId, true);
            }
          }
        }
      },
      {
        xtype: 'checkcolumn',
        header: TocLanguage.colContextmenu,
        dataIndex: 'contextmenu',
        listeners: {
          checkchange: function(column, recordIndex, checked) {
            var moduleId = moduleStore.getAt(recordIndex).get('id');
            
            if (checked) {
              app.getDesktop().addContextMenu(moduleId, true);
            }else {
              app.getDesktop().removeContextMenu(moduleId, true);
            }
          }
        }
      }]
    });
    
    var config = {
      title: TocLanguage.ModulesSetting,
      border: false,
      items: grid
    };
    
    this.callParent([config]);
  }
});