Ext.define('Toc.desktop.Desktop', {
  extend: 'Ext.ux.desktop.Desktop',
  
  requires: [ 'Ext.ux.desktop.ShortcutModel'],
  
  shortcuts: Ext.create('Ext.data.Store', {
    model: 'Ext.ux.desktop.ShortcutModel',
    data: []
  }),
  
  addQuickStartButton: function(moduleId, updateConfig) {
  	var module = this.app.getModule(moduleId);
  	
  	if (module && !module.quickStartButton) {
  		var mLauncher = module.launcher;
  		
  	  module.quickStartButton = this.taskbar.quickStart.add({
        tooltip: { text: mLauncher.tooltip || mLauncher.text, align: 'bl-tl' },
        overflowText: mLauncher.text,
        iconCls: mLauncher.iconCls,
        handler: mLauncher.handler,
        scope: mLauncher.scope
      });
      
      if(updateConfig){
        this.app.launchers.quickstart.push(moduleId);
      }
  	}
  },
  
  removeQuickStartButton: function(moduleId, updateConfig) {
    var module = this.app.getModule(moduleId);
    
    if (module && module.quickStartButton) {
      this.taskbar.quickStart.remove(module.quickStartButton);
      
      module.quickStartButton = null;
    }
    
    if (updateConfig) {
      var qs = this.app.launchers.quickstart,
      
      i = 0;
      while(i < qs.length){
        if(qs[i] == moduleId){
          qs.splice(i, 1);
        }else{
          i++;
        }
      }
    }
  },
  
  addShortcut: function(moduleId, updateConfig){
    var module = this.app.getModule(moduleId);
    
    if(module && !module.shortcut){
      var mLauncher = module.launcher;
      
      module.shortcut = this.shortcuts.add({ name: mLauncher.text, iconCls: mLauncher.shortcutIconCls, module: module.getId()});
      
      if(updateConfig){
        this.app.launchers.shortcut.push(moduleId);
      }
    }
  },
  
  removeShortcut: function(moduleId, updateConfig) {
    var module = this.app.getModule(moduleId);
    
    if (module && module.shortcut) {
      this.shortcuts.remove(module.shortcut);
      
      module.shortcut = null;
      
      if(updateConfig){
        var sc = this.app.launchers.shortcut,
        i = 0;
        
        while(i < sc.length){
          if(sc[i] == moduleId){
            sc.splice(i, 1);
          }else{
            i++;
          }
        }
      }
    }
  },
  
  createDesktopMenu: function () {
    var me = this, ret = {
        items: me.contextMenuItems || []
    };

    if (ret.items.length) {
        ret.items.push('-');
    }

    return ret;
  },
  
  addContextMenu: function(moduleId, updateConfig) {
    var module = this.app.getModule(moduleId);
    
    if (module) {
      this.contextMenu.add(module.launcher);
      
      if(updateConfig){
        this.app.launchers.contextmenu.push(moduleId);
      }
    }
  },
  
  removeContextMenu: function(moduleId, updateConfig) {
    var module = this.app.getModule(moduleId);
    
    if (module) {
    	var items = this.contextMenu.items.items;
    	for(var i = 0; i< items.length; i++) {
        if(items[i].iconCls == module.launcher.iconCls) {
          this.contextMenu.remove(items[i]);
        }
      }
    }
    
    if (updateConfig) {
      var dc = this.app.launchers.contextmenu;
      var i = 0;
        
      while(i < dc.length){
        if(dc[i] == moduleId){
           dc.splice(i, 1);
        }else{
          i++;
        }
      }
    }
  },
  
  addAutoRun: function(moduleId) {
    var module = this.app.getModule(moduleId),
        autoruns = this.app.launchers.autorun;
      
    if(module && !module.autorun){
      module.autorun = true;
      autoruns.push(moduleId);
    }
  },
  
  removeAutoRun: function(moduleId) {
    var module = this.app.getModule(moduleId),
        autoruns = this.app.launchers.autorun;
    
    if (module && module.autorun) {
      var i = 0;
        
      while(i < autoruns.length){
        if(autoruns[i] == moduleId){
          autoruns.splice(i, 1);
        }else{
          i++;
        }
      }
      
      module.autorun = null;
    }
  },
  
  setBackgroundColor: function(hex){
    if(hex){
  	  Ext.getBody().down('.ux-wallpaper').setStyle('background-color', '#' + hex);
    	
      this.app.styles.backgroundcolor = hex;
    }
  },
  
  setFontColor: function(hex){
    if(hex){
      Ext.util.CSS.updateRule('.ux-desktop-shortcut-text', 'color', '#'+hex);
      this.app.styles.fontcolor = hex;
    }
  },
  
  setTransparency: function(v){
    if(v >= 0 && v <= 100){
    	var elTaskbar = this.taskbar.getEl();
    	
    	if (Ext.isIE) {
    	  elTaskbar.setStyle("filter", 'alpha(opacity='+v+')');
    	}else {
        elTaskbar.setStyle('opacity', v/100);
    	}
    	
      this.app.styles.transparency = v;
    }
  },
  
  setTheme: function(o){
    if (o && o.code && o.path) {
      Ext.util.CSS.swapStyleSheet('theme', o.path);
      
      this.app.styles.theme = o.code;
    }
  },
  
  setWallpaper: function (wallpaper, stretch) {
    this.wallpaper.setWallpaper(wallpaper.path, stretch);
    
    this.app.styles.wallpaper = wallpaper.code;
  },
  
  setWallpaperPosition: function(pos){
    if(pos){
      if(pos === "center"){
        var b = Ext.getBody().down('.ux-wallpaper');
        b.removeCls('wallpaper-tile');
        b.addCls('wallpaper-center');
      }else if(pos === "tile"){
        var b = Ext.getBody().down('.ux-wallpaper');
        b.removeCls('wallpaper-center');
        b.addCls('wallpaper-tile');
      }
      
      this.app.styles.wallpaperposition = pos;
    }
  }
});