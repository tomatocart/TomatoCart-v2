/*
 * $Id: themes.js $
 * TomatoCart Open Source Shopping Cart Solutions
 * http://www.tomatocart.com
 *
 * Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd
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
 
Toc.settings.ThemePanel = function(app) {

  var desktop,
      view;
  
  desktop = app.getDesktop();
  this.app = app;

  function onSelectionChange(view, sel){
    if(sel.length > 0){
      var record = view.getRecord(sel[0]);
      
      if(record && record.data.code && record.data.path){
        var theme = {
          code: record.data.code,
          name: record.data.name,
          path: record.data.path
        };
      
        if(app.styles.theme != theme.code){
          desktop.setTheme(theme);
        }
      }
    }
  };

  this.store = new Ext.data.JsonStore({
    baseParams: {
      module: 'desktop_settings',
      action: 'list_themes'
    },
    fields: ['code', 'name', 'thumbnail', 'path'],
    id: 'code',
    root: 'themes',
    url: Toc.CONF.CONN_URL,
    autoLoad: true
  });
      
  this.store.on('load', function(store, records){
    if(records){
      defaults.setTitle(TocLanguage.availableTheme + ' (' + records.length + ')');
      
      var t = app.styles.theme;
      if(t){
        view.select(t);
      }
    }        
  }, this);
  
  var tpl = new Ext.XTemplate(
    '<tpl for=".">',
      '<div class="setting-view-thumb-wrap" id="{code}">',
        '<div class="setting-view-thumb"><img src="{thumbnail}" title="{code}" /></div>',
      '<span>{shortName}</span></div>',
    '</tpl>',
    '<div class="x-clear"></div>'
  );
  
  view = new Ext.DataView({
    autoHeight:true,
    emptyText: TocLanguage.noThemeText,
    itemSelector:'div.setting-view-thumb-wrap',
    loadingText: TocLanguage.loadingText,
    singleSelect: true,
    overClass:'x-view-over',
    prepareData: function(data){
      data.shortName = Ext.util.Format.ellipsis(data.name, 17);
      return data;
    },
    store: this.store,
    tpl: tpl
  });
  view.on('selectionchange', onSelectionChange, this);
    
  var defaults = new Ext.Panel({
    border: false,
    cls: 'setting-thumbnail-viewer',
    id: 'setting-theme-view',
    items: view,
    title: 'Default Themes',
    height:270,
    autoScroll: true
  });
  
  var themes = new Ext.Panel({
    autoScroll: true,
    bodyStyle: 'padding:10px',
    border: true,
    cls: 'setting-card-subpanel',
    id: 'themes',
    items: defaults,
    margins: '10 10 10 10',
    region: 'center'
  });
  

  this.slider = createSlider({
    handler: new Ext.util.DelayedTask(updateTransparency, this)
    , min: 0
    , max: 100
    , x: 15
    , y: 35
    , width: 100
  });
  
  var transparency = new Ext.Panel({
    border: false,
    height: 70,
    items: [
      {x: 15, y: 15, xtype: 'label', text: TocLanguage.taskbarTransparency},
      this.slider.slider,
      this.slider.display
    ],
    layout: 'absolute',
    split: false,
    region: 'south'
  });
  
  Toc.settings.ThemePanel.superclass.constructor.call(this, {
    title: TocLanguage.ThemeSetting,
    layout: 'border',
    labelAlign:'left',
    border: false,
    items:[themes, transparency]
  });
  
  // private functions
  function createSlider(config){
    var handler = config.handler, min = config.min, max = config.max
      , width = config.width || 100, x = config.x, y = config.y;

    var slider = new Ext.Slider({
      minValue: min
      , maxValue: max
      , width: width
      , x: x
      , y: y
    });
    
    var display =  new Ext.form.NumberField({
      cls: 'setting-percent-field', 
      enableKeyEvents: true, 
      maxValue: max, 
      minValue: min
      , width: 45
      , x: x + width + 15
      , y: y - 1
    });
      
    function sliderHandler(slider){
      var v = slider.getValue();
      display.setValue(v);
      handler.delay(100, null, null, [v]); // delayed task prevents IE bog
    }
    
    slider.on({
      'change': { fn: sliderHandler, scope: this }
      , 'drag': { fn: sliderHandler, scope: this }
    });
    
    display.on({
      'keyup': {
        fn: function(field){
          var v = field.getValue();
          if(v !== '' && !isNaN(v) && v >= field.minValue && v <= field.maxValue){
            slider.setValue(v);
          }
        }
        , buffer: 350
        , scope: this
      }
    });

    return { slider: slider, display: display }
  }
  
  function updateTransparency(v){
    desktop.setTransparency(v);
  }
};

Ext.extend(Toc.settings.ThemePanel, Ext.Panel, {
  onShow: function() {
    Toc.settings.ThemePanel.superclass.onShow.call(this);
    
    this.slider.slider.setValue(this.app.styles.transparency);
  }
});