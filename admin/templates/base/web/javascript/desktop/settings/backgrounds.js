/*
 * $Id: backgrounds.js $
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
 
Ext.define('Toc.settings.BackgroundPanel', {
  extend: 'Ext.panel.Panel',
  
  uses: [
    'Ext.XTemplate',
    'Ext.view.View',
    'Ext.Button'
  ],
  
  constructor: function(app) {
    var view;
    var desktop;
    var config = {};
    
    desktop = app.getDesktop();
    
    this.store = new Ext.data.Store ({
      fields: ['code', 'name', 'thumbnail', 'path'],
      proxy: {
        type: 'ajax',
        url: Toc.CONF.CONN_URL,
        extraParams: {
          action: 'list_wallpapers'
        },
        reader: {
        	idProperty: 'code',
          type: 'json',
          root: 'wallpapers'
        }
      },
      listeners: {
        'load': function(store, records) {
          if (Ext.isArray(records) && !Ext.isEmpty(records)) {
          	Ext.Array.each(records, function(record, index) {
          		if (record.get('code') == app.styles.wallpaper.code) {
          		  view.select(index);
          		  
          		  return false;
          		}
          	});
          }
        }
      },
      autoLoad: true
    });
    
    var tpl = new Ext.XTemplate(
      '<tpl for=".">',
        '<div class="setting-view-thumb-wrap" id="{code}">',
          '<div class="setting-view-thumb"><img src="{thumbnail}" title="{name}" /></div>',
        '<span>{name}</span></div>',
      '</tpl>',
      '<div class="x-clear"></div>'
    );
    

    view = new Ext.view.View({
    	store: this.store,
      tpl: tpl,
      autoHeight:true,
      emptyText: TocLanguage.noWallpaperText,
      itemSelector:'div.setting-view-thumb-wrap',
      loadingText: TocLanguage.loadingText,
      singleSelect: true,
      trackOver: true,
      overItemCls:'x-view-over',
      prepareData: function(data){
        data.shortName = Ext.util.Format.ellipsis(data.name, 17);
        return data;
      },
      listeners: {
        selectionchange: function(view, sel) {
          if(sel.length > 0){
            var record = view.getLastSelected();
            
            if(record && record.data.code && record.data.path){
              var wallpaper = {
                code: record.data.code,
                path: record.data.path
              };
      
              if(app.styles.wallpaper != wallpaper.code){
                desktop.setWallpaper(wallpaper, false);
              }
            }
          }
        }
      }
    });
    
    var defaults = new Ext.Panel({
    	border: false,
      baseCls:'collapse-group',
      cls: 'setting-thumbnail-viewer',
      hideCollapseTool: true,
      id: 'setting-wallpaper-view',
      items: view,
      titleCollapse: true
    });  
    
    var wallpapers = new Ext.Panel({
      autoScroll: true,
      cls: 'setting-card-subpanel',
      id: 'wallpapers',
      items: defaults,
      region: 'center'
    });
    
    var position = new Ext.Panel({
      border: false,
      height: 140,
      id: 'position',
      items: [{
          border: false,
          items: {border: false, html:TocLanguage.wallpaperPositionTitle},
          x: 15,
          y: 15
        },{
          border: false,
          items: {border: false, html: '<img class="bg-pos-tile" src="'+Ext.BLANK_IMAGE_URL+'" width="64" height="44" border="0" alt="" />'},
          x: 15,
          y: 40
        },
        {
          xtype: 'radiofield',
          name: 'position',
          inputValue: 'tile',
          x: 90,
          y: 40,
          checked: app.styles.wallpaperposition == 'tile' ? true : false,
          listeners: {
            focus: function(field) {
              desktop.setWallpaperPosition(field.inputValue);
            }
          }
        },
        {
          border: false,
          items: {border: false, html: '<img class="bg-pos-center" src="'+Ext.BLANK_IMAGE_URL+'" width="64" height="44" border="0" alt="" />'},
          x: 125,
          y: 40
        },
        {
          xtype: 'radiofield',
          name: 'position',
          inputValue: 'center',
          checked: app.styles.wallpaperposition == 'center' ? true : false,
          x: 200,
          y: 40,
          listeners: {
            focus: function(field) {
              desktop.setWallpaperPosition(field.inputValue);
            }
          }
        },
        {
          border: false,
          items: {border: false, html:TocLanguage.desktopBackgroundTitle},
          x: 245,
          y: 15
        },{
          border: false,
          items: new Ext.Button({
            handler: function() {
              var cp = new Ext.picker.Color({
                  value: app.styles.backgroundcolor,
                  listeners: {
                    select: function(picker, selColor) {
                      desktop.setBackgroundColor(selColor);
                    }
                  }
              });
              
              var dialog = new Ext.Window({
                title: 'Color Picker',
                height: 200,
                width: 340,
                layout: 'fit',
                border: false,
                resizable: false, 
                items: cp
              });
            
              dialog.show();
            },
            text: TocLanguage.btnBackgroundColor
          }),
          x: 245,
          y: 40
        },{
          border: false,
          items: {border: false, html:TocLanguage.fontColorTitle},
          x: 425,
          y: 15
        },{
          border: false,
          items: new Ext.Button({
            handler: function() {
              var cp = new Ext.picker.Color({
                  value: app.styles.fontcolor,
                  listeners: {
                    select: function(picker, selColor) {
                      desktop.setFontColor(selColor);
                    }
                  }
              });
              
              var dialog = new Ext.Window({
                title: 'Color Picker',
                height: 200,
                width: 340,
                layout: 'fit',
                border: false,
                resizable: false, 
                items: cp
              });
            
              dialog.show();
            },
            text: TocLanguage.btnFontColor
          }),
          x: 425,
          y: 40
      }],
      layout: 'absolute',
      region: 'south',
      split: false
    });
    
    config.title = TocLanguage.WallpaperSetting;
    config.layout = 'border';
    config.border = false;
    config.items = [wallpapers, position];
    
    this.callParent([config]);
  }
});

