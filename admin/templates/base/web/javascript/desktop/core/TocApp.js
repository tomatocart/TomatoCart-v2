/**
  $Id: TocApp.js $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

/**
 * Extend the app.js of the Ext Destktop JS Library 4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */

Ext.namespace("Toc.desktop");

Ext.define("Toc.desktop.App", {
  extend : "Ext.ux.desktop.App",
  
  requestQueue : [],
  styles : null,
  modules: null,
  launchers : null,
  
  getStyles : Ext.emptyFn,
  getLaunchers : Ext.emptyFn,
  
  getTaskbarConfig: function () {
	  var me = this;
	  
    var ret = this.callParent();

    return Ext.apply(ret, {
      trayItems: [
        { xtype: 'button', id: 'btnSetting', iconCls:'settings', text: TocLanguage.Settings, handler: function() {me.onSettings(); this.disable();} }
      ]
    });
  },
  
  getStartConfig: function () {
    var me = this, cfg = {
      app: me,
      menu: []
    };
    
    cfg = this.addModuesMenus(cfg);
    
    Ext.apply(cfg,  {
      iconCls: 'user',
      height: 300,
      toolConfig: {
        width: 100,
        items: [
          {
            text: TocLanguage.Settings,
            iconCls:'settings',
            handler: me.onSettings,
            scope: me
          },
          '-',
          {
            text: TocLanguage.Logout,
            iconCls:'logout',
            handler: me.onLogout,
            scope: me
          }
        ]
      }
    }, me.startConfig);

    return cfg;
  },
  
  init: function() {
    var me = this, desktopCfg;

    if (me.useQuickTips) {
      Ext.QuickTips.init();
    }

    me.modules = me.modules || me.getModules();
    me.launchers = me.launchers || me.getLaunchers();
    me.styles = me.styles || me.getStyles();
    
    desktopCfg = me.getDesktopConfig();
    me.desktop = new Toc.desktop.Desktop(desktopCfg);
    
    me.viewport = new Ext.container.Viewport({
      layout: 'fit',
      items: [ me.desktop ]
    });
    
    if (!Ext.isEmpty(me.modules)) {
      me.initModules(me.modules);
    }
    
    if (!Ext.isEmpty(me.launchers)) {
      me.initLaunchers();
    }
    
    if (!Ext.isEmpty(me.styles)) {
      me.initStyles();
    }
    
    Ext.EventManager.on(window, 'beforeunload', me.onUnload, me);

    me.isReady = true;
    me.fireEvent('ready', me);

    this.onReady(function(){
      Ext.get('x-loading-mask').hide();
      Ext.get('x-loading-panel').hide();
    },this);
  },
  
  initLaunchers : function(){
    var l = this.launchers;
    
    if(!l){
      return false;
    }
    
    if (l.contextmenu) {
      this.initContextMenu(l.contextmenu);
    }
    
    if (l.quickstart) {
      this.initQuickStart(l.quickstart);
    }

    if (l.shortcut) {
      this.initShortcut(l.shortcut);
    }
    
    if(l.autorun) {
      this.onReady(Ext.Function.bind(this.initAutoRun, this, [l.autorun]));      
    }
    
    return true;
  },
  
  initStyles : function(){
    var s = this.styles;
    if(Ext.isEmpty(s)){
      return false;
    }

    this.desktop.setBackgroundColor(s.backgroundcolor);
    this.desktop.setFontColor(s.fontcolor);
    this.desktop.setTheme(s.theme);
    this.desktop.setTransparency(s.transparency);
    this.desktop.setWallpaper(s.wallpaper);
    this.desktop.setWallpaperPosition(s.wallpaperposition);
    
    return true;
  },
  
  /**
   * @param {array} mIds An array of the modulId's to add to the Quick Start panel
   */
  initQuickStart : function(mIds){
    if(mIds) {
      for(var i = 0, len = mIds.length; i < len; i++){
        this.desktop.addQuickStartButton(mIds[i], false);
      }
    }
  },
  
  /**
   * @param {array} mIds An array of the module ids to add to the Desktop Shortcuts
   */
  initShortcut : function(mIds){
    if(mIds){
      for(var i = 0, len = mIds.length; i < len; i++){
        this.desktop.addShortcut(mIds[i], false);
      }
    }
  },
  
  /**
   * @param {array} mIds An array of the module ids to add to the Desktop Context Menu
   */
  initContextMenu : function(mIds){
    if(mIds){
      for(var i = 0, len = mIds.length; i < len; i++){
        var m = this.getModule(mIds[i]);
        if(m){
          this.desktop.contextMenu.add(m.launcher);
        }
      }
    }
  },
  
  /**
   * @param {array} mIds An array of the module ids to run when this app is ready
   */
  initAutoRun : function(mIds){
    if(mIds){
      for(var i = 0, len = mIds.length; i < len; i++){
        var m = this.getModule(mIds[i]);
        if(m){
          m.autorun = true;
          this.createWindow(mIds[i]);
        }
      }
    }
  },
  
  /**
   * Read-only. The queue of requests to run once a module is loaded
   */
  addModuesMenus : function(cfg){
    var ms = this.modules;
    if(!ms){ return false; }
      
    for (var i = 0, len = ms.length; i < len; i++) {
      var m = ms[i];
      m.app = this;
      
      if (m.appType == 'group') {
        if(m.loaded === false && Ext.isEmpty(m.launcher.handler)) {
          m.launcher.handler = Ext.Function.bind(this.createWindow, this, [m.id]);
        }
        
        var items = m.items;        
        for(var j = 0; j < items.length; j++){
          var item = this.getModule(items[j]);
          
          if (item) {
            item.app = this;
            
            if (item.loaded === false && Ext.isEmpty(item.launcher.handler)) {
              item.launcher.handler = Ext.Function.bind(this.createWindow, this, [item.id]);
            }
            
            if(item.appType == 'subgroup') {
              var items2 = item.items;
              
              for(var k = 0; k < items2.length; k++){
                var item2 = this.getModule(items2[k]);
                
                item2.app = this;
                
                if(item2.loaded === false && Ext.isEmpty(item2.launcher.handler)) {
                  item2.launcher.handler = Ext.Function.bind(this.createWindow, this, [item2.id]);
                }
                
                item.menu.add(item2.launcher);
              }
            }
            
            m.menu.add(item.launcher);
          }
        }
        
        cfg.menu.push(m.launcher);
      }
    }
    
    return cfg;
  },
  
  /**
   * @param {string} moduleId
   * 
   * Provides the handler to the placeholder's launcher until the module it is loaded.
   * Requests the module.  Passes in the callback and scope as params.
   */
  createWindow : function(moduleId){
    if ((moduleId.indexOf('grp') == -1) && (moduleId.indexOf('subgroup') == -1)) {
      var m = this.requestModule(moduleId, function(m){
        if (m) {
          m.createWindow();
        }
      }, this);
    } else {
      return false;
    }
  },
  
  /** 
   * @param {string} v The moduleId or moduleType you want returned
   * @param {Function} cb The Function to call when the module is ready/loaded
   * @param {object} scope The scope in which to execute the function
   */
  requestModule : function(v, cb, scope){
    var m = this.getModule(v);

    if(m){
      if(m.loaded === true){
        cb.call(scope, m);
      }else{
        if(cb && scope){
          this.requestQueue.push({
            id: m.id,
            callback: cb,
            scope: scope
          });
          
          this.loadModule(m.id, m.launcher.text);
        }
      }
    }
  },
  
  loadModule : function(moduleId, moduleName) {
    /*
    var notifyWin = this.desktop.showNotification({
      html: 'Loading ' + moduleName + '...'
      , title: 'Please wait'
    });
    */
    Ext.Ajax.request({
      url: Toc.CONF.LOAD_URL + '/' + moduleId,
      success: function(o){
        /*
        notifyWin.setIconClass('x-icon-done');
        notifyWin.setTitle('Finished');
        notifyWin.setMessage(moduleName + ' loaded.');
        this.desktop.hideNotification(notifyWin);       
        notifyWin = null;
        */
        
        if(o.responseText !== ''){
          eval(o.responseText);
          this.loadModuleComplete(true, moduleId);
        }else{
          alert('An error occured on the server.');
        }
      },
      failure: function(){
        alert('Connection to the server failed!');
      },
      scope: this
    });
  },
  
  /**
   * @param {boolean} success
   * @param {string} moduleId
   * 
   * Will be called when a module is loaded.
   * If a request for this module is waiting in the
   * queue, it as executed and removed from the queue.
   */
  loadModuleComplete : function(success, moduleId){   
    if (success === true && moduleId){
      var m = this.getModule(moduleId);
      m.loaded = true;
      m.init();
      
      var q = this.requestQueue;
      var nq = [];
      for(var i = 0, len = q.length; i < len; i++){
          if(q[i].id === moduleId){
             var dlg = q[i].callback.call(q[i].scope, m);
          }else{
              nq.push(q[i]);
          }
      }
      this.requestQueue = nq;
    }
    
    return dlg;
  },
  
  /**
   * @param {string} v The moduleId or moduleType you want returned
   */
  getModule : function(v){
    var ms = this.modules;
    
    for(var i = 0, len = ms.length; i < len; i++){
      if(ms[i].id == v || ms[i].moduleType == v){
        return ms[i];
      }
    }
    
    return null;
  },
  
  getDesktopSettingWindow: function() {
    var desktopSettingWindow = new Toc.settings.SettingsDialog({app: this});
    
    return desktopSettingWindow;
  },
  
  showNotification :function(config){
  	var me = this;
  	
    var win = new Ext.ux.Notification(Ext.apply({
    	animateTarget: me.desktop.taskbar,
      autoDestroy: true,
      hideDelay: 3000,
      iconCls: 'x-icon-waiting'
    }, config));
    
    win.animShow();
  }
});