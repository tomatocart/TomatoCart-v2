/*
 * $Id: sidebar.js $
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
 
Toc.settings.SidebarPanel = function(app) {
  config = {};
  
  config.title = TocLanguage.sidebarConfigTitle;
  config.app = app;
  config.desktop = app.getDesktop();
  config.layout = 'border';
  config.border = false;
  config.items = [this.buildGadgetsPanel(), this.buildDisplaySettingPanel(app)];
  
  Toc.settings.SidebarPanel.superclass.constructor.call(this, config);
};

Ext.extend(Toc.settings.SidebarPanel, Ext.Panel, {

  buildDisplaySettingPanel: function(app) {
  
	  var sidebarSetting = new Ext.Panel({
	    border: false,
	    height: 140,
	    layout: 'absolute',
	    region: 'south',
	    items: [
		    {
		      border: false,
		      items: {border: false, html:TocLanguage.sidebarStateSettingTitle},
		      x: 15,
		      y: 15
		    },
		    {
		      border: false,
		      items: {
		        xtype: 'checkbox',
		        name: 'sidebar_opened',
		        hideLabel: true,
		        inputValue: true,
		        checked: (app.sidebaropened && !app.sidebarcollapsed),
		        listeners: {
		          check: this.onSidebarOpenedCheck,
		          scope: this
		        }
		      },
		      x: 15,
		      y: 37
		    },
		    {
		      border: false,
		      items: {border: false, html:TocLanguage.sidebarTransparencySettingTitle},
		      x: 170,
		      y: 16
		    },
		    {
		      border: false,
		      items:  this.buildTransparencySlider(),
		      x: 170,
		      y: 40
		    },
		    {
		      border: false,
		      items: {border: false, html: TocLanguage.sidebarChoseGroundColor},
		      x: 385,
		      y: 15
		    },
		    {
		      border: false,
		      items: this.btnBackgroundColor = new Ext.Button({text: TocLanguage.btnBackgroundColor, handler: this.onChangeBgColor,scope: this}),
		      x: 390,
		      y: 40
		    }
	    ]
	  });

	  var pnlDisplaySetting = new Ext.Panel({
	    border: false,
	    style: 'padding:0 10px;',
	    region: 'south',
	    items: {
	      xtype: 'fieldset',
	      title: TocLanguage.sidebarSettingTitle,
	      id: 'sidebar-display-setting',
	      style: 'margin:0 0 10px 0;padding:0;height:110px;',
	      items: [sidebarSetting]
	    }
	  });

	  return pnlDisplaySetting;
  },
  
  onSidebarOpenedCheck: function(checkbox, checked) {
    if (checked) {
      this.desktop.showSidebar();
      
      this.btnBackgroundColor.enable();
      this.sidebarSlider.slider.enable();
      this.sidebarSlider.displayPercent.enable();
    } else {
      this.desktop.hideSidebar();
      
      this.btnBackgroundColor.disable();
      this.sidebarSlider.slider.disable();
      this.sidebarSlider.displayPercent.disable();
    } 
  },

	onShow: function() {
    Toc.settings.SidebarPanel.superclass.onShow.call(this);

    if (!this.app.sidebaropened) {
      this.btnBackgroundColor.disable();
      this.sidebarSlider.slider.disable();
      this.sidebarSlider.displayPercent.disable();
    }
    this.sidebarSlider.slider.setValue(this.app.styles.sidebartransparency);
  },
  
	buildGadgetsPanel: function() {
    this.pnlGadgets = new Ext.Panel({  
      id: 'gadgets-view',      
      region: 'center',
      style: 'padding:10px 10px;',
      border: false,
      items: {
        xtype: 'fieldset',
	      title: TocLanguage.sidebarGadgetsTitle,
	      height: 250,
	      items: [this.buildGadgetsView()]
	    }
    });
    
    return this.pnlGadgets;
	},
	
	buildGadgetsView: function() {
    this.gadgetsView = new Ext.DataView({
      store: new Ext.data.Store({
	      url: Toc.CONF.CONN_URL,
	      baseParams: {
	        module: 'desktop_settings',
	        action: 'get_gadgets'        
	      },
	      reader: new Ext.data.JsonReader({
	        root: Toc.CONF.JSON_READER_ROOT,
	        id: 'code'
	      },[
	        'code',
	        'type',
	        'icon',
	        'title',
	        'description',
	        'file'
	      ]),
	      autoLoad: true
	    }),
      tpl: new Ext.XTemplate(
	      '<tpl for=".">',  
	         '<div class="thumb-wrap" id="{title}">',  
	           '<div class="thumb"><img src="templates/default/desktop/gadgets/{code}/{icon}" title="{title}"></div>',  
	           '<span>{description}</span>',
	         '</div>',
	      '</tpl>',
	      '<div class="x-clear"></div>'  
	    ),
      autoWidth: true,
      multiSelect: true,
      overClass: 'x-view-over',
      selectedClass: 'x-view-selected',
      itemSelector: 'div.thumb-wrap',
      plugins: new Ext.DataView.DragSelector({dragSafe: true}),
      emptyText: TocLanguage.sidebarNoGadgets,
      listeners: {
        render: function() {
          var dragZone = new ImageDragZone(this.gadgetsView, {containerScroll: true, ddGroup: 'GadgetsDD', scroll: false});
        },
        dblclick: function(view, index, node) {
          this.app.desktop.sidebar.addGadget(view.getRecord(node).get('code'), true);
        },
        scope: this
      }
    });
    
    return this.gadgetsView;
	},
	
	buildTransparencySlider: function() {
    this.sidebarSlider = this.createSlider({
      handler: new Ext.util.DelayedTask(this.updateSidebarTransparency, this), 
      min: 0, 
      max: 100, 
      x: 15, 
      y: 10, 
      width: 100
    });
      
    var sidebarTransparency =  new Ext.Panel({
      border: false,
      width: 200,
      region: 'south',
      height: 30,
      items: [
        this.sidebarSlider.slider,
        this.sidebarSlider.displayPercent
      ]
    });
    
    return sidebarTransparency;
	},
	
	createSlider: function(config) {
    var handler = config.handler, min = config.min, max = config.max
      , width = config.width || 100, x = config.x, y = config.y;

    var slider = new Ext.Slider({
      minValue: min, 
      maxValue: max, 
      width: width, 
      x: x, 
      y: y
    });
    
    var displayPercent =  new Ext.form.NumberField({
      enableKeyEvents: true, 
      cls: 'setting-percent-field',
      maxValue: max, 
      minValue: min,
      width: 45,
      style: 'position:absolute;left:118px;top:1px;'
    });
    
    var sliderHandler = function(slider) {
      var v = slider.getValue();
      
      displayPercent.setValue(v);
      handler.delay(100, null, null, [v]); // delayed task prevents IE bug
    };
    
    slider.on({
      'change': { fn: sliderHandler, scope: this }, 
      'drag': { fn: sliderHandler, scope: this }
    });
    
    displayPercent.on({
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
    
    return { slider: slider, displayPercent: displayPercent };
  },
  
  onChangeBgColor: function() {
    var dialog = new Ext.ux.ColorDialog({
      border: false, 
      closeAction: 'close',
      listeners: {
        'select': { fn: this.onColorSelect, scope: this, buffer: 350 }
      }, 
      manager: this.desktop.getManager(), 
      resizable: false, 
      title: 'Color Picker'
    });
    
    dialog.show(this.app.styles.sidebarbackgroundcolor);
  },  
    
  onColorSelect: function(p, hex) {
    if (this.app.sidebaropened) {
      this.desktop.sidebar.setBackgroundColor(hex);
    }
  },
  
  updateSidebarTransparency: function(v) {
    if (this.app.sidebaropened) {
      this.desktop.sidebar.setBackgroundTransparency(v);
    }
  }
});