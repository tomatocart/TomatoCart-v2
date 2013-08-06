/*
 * Copyright(c) 2011 Sencha Inc. licensing@sencha.com
 */
/*
 * Ext JS Library 4.0 Copyright(c) 2006-2011 Sencha Inc. licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.define("Ext.ux.desktop.Module", {
			mixins : {
				observable : "Ext.util.Observable"
			},
			constructor : function(a) {
				this.mixins.observable.constructor.call(this, a);
				this.init()
			},
			init : Ext.emptyFn
		});

Ext.define("Ext.ux.desktop.ShortcutModel", {
  extend : "Ext.data.Model",
  fields : [{
        name : "name"
      }, {
        name : "iconCls"
      }, {
        name : "module"
      }]
});

Ext.define("Ext.ux.desktop.Desktop", {
  extend : "Ext.panel.Panel",
  alias : "widget.desktop",
  uses : ["Ext.util.MixedCollection", "Ext.menu.Menu", "Ext.view.View",
      "Ext.window.Window", "Ext.ux.desktop.TaskBar",
      "Ext.ux.desktop.Wallpaper", "Ext.ux.desktop.FitAllLayout"],
  activeWindowCls : "ux-desktop-active-win",
  inactiveWindowCls : "ux-desktop-inactive-win",
  lastActiveWindow : null,
  border : false,
  html : "&#160;",
  layout : "fitall",
  xTickSize : 1,
  yTickSize : 1,
  app : null,
  shortcuts : null,
  shortcutItemSelector : "div.ux-desktop-shortcut",
  shortcutTpl : ['<tpl for=".">',
      '<div class="ux-desktop-shortcut" id="{name}-shortcut">',
      '<div class="ux-desktop-shortcut-icon {iconCls}">', '<img src="',
      Ext.BLANK_IMAGE_URL, '" title="{name}">', "</div>",
      '<span class="ux-desktop-shortcut-text">{name}</span>', "</div>",
      "</tpl>", '<div class="x-clear"></div>'],
  taskbarConfig : null,
  windowMenu : null,
  initComponent : function() {
    var b = this;
    b.windowMenu = new Ext.menu.Menu(b.createWindowMenu());
    b.bbar = b.taskbar = new Ext.ux.desktop.TaskBar(b.taskbarConfig);
    b.taskbar.windowMenu = b.windowMenu;
    b.windows = new Ext.util.MixedCollection();
    b.contextMenu = new Ext.menu.Menu(b.createDesktopMenu());
    b.items = [{
          xtype : "wallpaper",
          id : b.id + "_wallpaper"
        }, b.createDataView()];
    b.callParent();
    b.shortcutsView = b.items.getAt(1);
    b.shortcutsView.on("itemclick", b.onShortcutItemClick, b);
    var a = b.wallpaper;
    b.wallpaper = b.items.getAt(0);
    if (a) {
      b.setWallpaper(a, b.wallpaperStretch)
    }
  },
  afterRender : function() {
    var a = this;
    a.callParent();
    a.el.on("contextmenu", a.onDesktopMenu, a)
  },
  createDataView : function() {
    var a = this;
    return {
      xtype : "dataview",
      overItemCls : "x-view-over",
      trackOver : true,
      itemSelector : a.shortcutItemSelector,
      store : a.shortcuts,
      tpl : new Ext.XTemplate(a.shortcutTpl)
    }
  },
  createDesktopMenu : function() {
    var b = this, a = {
      items : b.contextMenuItems || []
    };
    if (a.items.length) {
      a.items.push("-")
    }
    a.items.push({
          text : "Tile",
          handler : b.tileWindows,
          scope : b,
          minWindows : 1
        }, {
          text : "Cascade",
          handler : b.cascadeWindows,
          scope : b,
          minWindows : 1
        });
    return a
  },
  createWindowMenu : function() {
    var a = this;
    return {
      defaultAlign : "br-tr",
      items : [{
            text : "Restore",
            handler : a.onWindowMenuRestore,
            scope : a
          }, {
            text : "Minimize",
            handler : a.onWindowMenuMinimize,
            scope : a
          }, {
            text : "Maximize",
            handler : a.onWindowMenuMaximize,
            scope : a
          }, "-", {
            text : "Close",
            handler : a.onWindowMenuClose,
            scope : a
          }],
      listeners : {
        beforeshow : a.onWindowMenuBeforeShow,
        hide : a.onWindowMenuHide,
        scope : a
      }
    }
  },
  onDesktopMenu : function(b) {
    var a = this, c = a.contextMenu;
    b.stopEvent();
    if (!c.rendered) {
      c.on("beforeshow", a.onDesktopMenuBeforeShow, a)
    }
    c.showAt(b.getXY());
    c.doConstrain()
  },
  onDesktopMenuBeforeShow : function(c) {
    var b = this, a = b.windows.getCount();
    c.items.each(function(e) {
          var d = e.minWindows || 0;
          e.setDisabled(a < d)
        })
  },
  onShortcutItemClick : function(e, a) {
    var c = this, b = c.app.getModule(a.data.module), d = b
        && b.createWindow();
    if (d) {
      c.restoreWindow(d)
    }
  },
  onWindowClose : function(b) {
    var a = this;
    a.windows.remove(b);
    a.taskbar.removeTaskButton(b.taskButton);
    a.updateActiveWindow()
  },
  onWindowMenuBeforeShow : function(c) {
    var a = c.items.items, b = c.theWin;
    a[0].setDisabled(b.maximized !== true && b.hidden !== true);
    a[1].setDisabled(b.minimized === true);
    a[2].setDisabled(b.maximized === true || b.hidden === true)
  },
  onWindowMenuClose : function() {
    var a = this, b = a.windowMenu.theWin;
    b.close()
  },
  onWindowMenuHide : function(a) {
    a.theWin = null
  },
  onWindowMenuMaximize : function() {
    var a = this, b = a.windowMenu.theWin;
    b.maximize()
  },
  onWindowMenuMinimize : function() {
    var a = this, b = a.windowMenu.theWin;
    b.minimize()
  },
  onWindowMenuRestore : function() {
    var a = this, b = a.windowMenu.theWin;
    a.restoreWindow(b)
  },
  getWallpaper : function() {
    return this.wallpaper.wallpaper
  },
  setTickSize : function(b, c) {
    var e = this, a = e.xTickSize = b, d = e.yTickSize = (arguments.length > 1)
        ? c
        : a;
    e.windows.each(function(g) {
          var f = g.dd, h = g.resizer;
          f.xTickSize = a;
          f.yTickSize = d;
          h.widthIncrement = a;
          h.heightIncrement = d
        })
  },
  setWallpaper : function(b, a) {
    this.wallpaper.setWallpaper(b, a);
    return this
  },
  cascadeWindows : function() {
    var a = 0, c = 0, b = this.getDesktopZIndexManager();
    b.eachBottomUp(function(d) {
          if (d.isWindow && d.isVisible() && !d.maximized) {
            d.setPosition(a, c);
            a += 20;
            c += 20
          }
        })
  },
  createWindow : function(c, b) {
    var d = this, e, a = Ext.applyIf(c || {}, {
          stateful : false,
          isWindow : true,
          constrainHeader : true,
          minimizable : true,
          maximizable : true
        });
    b = b || Ext.window.Window;
    e = d.add(new b(a));
    d.windows.add(e);
    e.taskButton = d.taskbar.addTaskButton(e);
    e.animateTarget = e.taskButton.el;
    e.on({
          activate : d.updateActiveWindow,
          beforeshow : d.updateActiveWindow,
          deactivate : d.updateActiveWindow,
          minimize : d.minimizeWindow,
          destroy : d.onWindowClose,
          scope : d
        });
    e.on({
          afterrender : function() {
            e.dd.xTickSize = d.xTickSize;
            e.dd.yTickSize = d.yTickSize;
            if (e.resizer) {
              e.resizer.widthIncrement = d.xTickSize;
              e.resizer.heightIncrement = d.yTickSize
            }
          },
          single : true
        });
    e.doClose = function() {
      e.doClose = Ext.emptyFn;
      e.el.disableShadow();
      e.el.fadeOut({
            listeners : {
              afteranimate : function() {
                e.destroy()
              }
            }
          })
    };
    return e
  },
  getActiveWindow : function() {
    var b = null, a = this.getDesktopZIndexManager();
    if (a) {
      a.eachTopDown(function(c) {
            if (c.isWindow && !c.hidden) {
              b = c;
              return false
            }
            return true
          })
    }
    return b
  },
  getDesktopZIndexManager : function() {
    var a = this.windows;
    return (a.getCount() && a.getAt(0).zIndexManager) || null
  },
  getWindow : function(a) {
    return this.windows.get(a)
  },
  minimizeWindow : function(a) {
    a.minimized = true;
    a.hide()
  },
  restoreWindow : function(a) {
    if (a.isVisible()) {
      a.restore();
      a.toFront()
    } else {
      a.show()
    }
    return a
  },
  tileWindows : function() {
    var b = this, e = b.body.getWidth(true);
    var a = b.xTickSize, d = b.yTickSize, c = d;
    b.windows.each(function(g) {
          if (g.isVisible() && !g.maximized) {
            var f = g.el.getWidth();
            if (a > b.xTickSize && a + f > e) {
              a = b.xTickSize;
              d = c
            }
            g.setPosition(a, d);
            a += f + b.xTickSize;
            c = Math.max(c, d + g.el.getHeight() + b.yTickSize)
          }
        })
  },
  updateActiveWindow : function() {
    var b = this, c = b.getActiveWindow(), a = b.lastActiveWindow;
    if (c === a) {
      return
    }
    if (a) {
      if (a.el.dom) {
        a.addCls(b.inactiveWindowCls);
        a.removeCls(b.activeWindowCls)
      }
      a.active = false
    }
    b.lastActiveWindow = c;
    if (c) {
      c.addCls(b.activeWindowCls);
      c.removeCls(b.inactiveWindowCls);
      c.minimized = false;
      c.active = true
    }
    b.taskbar.setActiveButton(c && c.taskButton)
  }
});

Ext.define("Ext.ux.desktop.App", {
      mixins : {
        observable : "Ext.util.Observable"
      },
      requires : ["Ext.container.Viewport", "Ext.ux.desktop.Desktop"],
      isReady : false,
      modules : null,
      useQuickTips : true,
      constructor : function(a) {
        var b = this;
        b.addEvents("ready", "beforeunload");
        b.mixins.observable.constructor.call(this, a);
        if (Ext.isReady) {
          Ext.Function.defer(b.init, 10, b)
        } else {
          Ext.onReady(b.init, b)
        }
      },
      init : function() {
        var b = this, a;
        if (b.useQuickTips) {
          Ext.QuickTips.init()
        }
        b.modules = b.getModules();
        if (b.modules) {
          b.initModules(b.modules)
        }
        a = b.getDesktopConfig();
        b.desktop = new Ext.ux.desktop.Desktop(a);
        b.viewport = new Ext.container.Viewport({
              layout : "fit",
              items : [b.desktop]
            });
        Ext.EventManager.on(window, "beforeunload", b.onUnload, b);
        b.isReady = true;
        b.fireEvent("ready", b)
      },
      getDesktopConfig : function() {
        var b = this, a = {
          app : b,
          taskbarConfig : b.getTaskbarConfig()
        };
        Ext.apply(a, b.desktopConfig);
        return a
      },
      getModules : Ext.emptyFn,
      getStartConfig : function() {
        var b = this, a = {
          app : b,
          menu : []
        };
        Ext.apply(a, b.startConfig);
        Ext.each(b.modules, function(c) {
              if (c.launcher) {
                a.menu.push(c.launcher)
              }
            });
        return a
      },
      getTaskbarConfig : function() {
        var b = this, a = {
          app : b,
          startConfig : b.getStartConfig()
        };
        Ext.apply(a, b.taskbarConfig);
        return a
      },
      initModules : function(a) {
        var b = this;
        Ext.each(a, function(c) {
              c.app = b
            })
      },
      getModule : function(d) {
        var c = this.modules;
        for (var e = 0, b = c.length; e < b; e++) {
          var a = c[e];
          if (a.id == d || a.appType == d) {
            return a
          }
        }
        return null
      },
      onReady : function(b, a) {
        if (this.isReady) {
          b.call(a, this)
        } else {
          this.on({
                ready : b,
                scope : a,
                single : true
              })
        }
      },
      getDesktop : function() {
        return this.desktop
      },
      onUnload : function(a) {
        if (this.fireEvent("beforeunload", this) === false) {
          a.stopEvent()
        }
      }
    });

Ext.define("Ext.ux.desktop.Video", {
  extend : "Ext.panel.Panel",
  alias : "widget.video",
  width : "100%",
  height : "100%",
  autoplay : false,
  controls : true,
  bodyStyle : "background-color:#000;color:#fff",
  html : "",
  initComponent : function() {
    this.callParent()
  },
  afterRender : function() {
    var g;
    if (this.fallbackHTML) {
      g = this.fallbackHTML
    } else {
      g = "Your browser does not support HTML5 Video. ";
      if (Ext.isChrome) {
        g += "Upgrade Chrome."
      } else {
        if (Ext.isGecko) {
          g += "Upgrade to Firefox 3.5 or newer."
        } else {
          var b = '<a href="http://www.google.com/chrome">Chrome</a>';
          g += 'Please try <a href="http://www.mozilla.com">Firefox</a>';
          if (Ext.isIE) {
            g += ", "
                + b
                + ' or <a href="http://www.google.com/chromeframe">Chrome Frame for IE</a>.'
          } else {
            g += " or " + b + "."
          }
        }
      }
    }
    var e = this.getSize();
    var c = Ext.copyTo({
          tag : "video",
          width : e.width,
          height : e.height
        }, this,
        "poster,start,loopstart,loopend,playcount,autobuffer,loop");
    if (this.autoplay) {
      c.autoplay = 1
    }
    if (this.controls) {
      c.controls = 1
    }
    if (Ext.isArray(this.src)) {
      c.children = [];
      for (var d = 0, a = this.src.length; d < a; d++) {
        if (!Ext.isObject(this.src[d])) {
          Ext.Error
              .raise('The src list passed to "video" must be an array of objects')
        }
        c.children.push(Ext.applyIf({
              tag : "source"
            }, this.src[d]))
      }
      c.children.push({
            html : g
          })
    } else {
      c.src = this.src;
      c.html = g
    }
    this.video = this.body.createChild(c);
    var f = this.video.dom;
    this.supported = (f && f.tagName.toLowerCase() == "video")
  },
  doComponentLayout : function() {
    var a = this;
    a.callParent(arguments);
    if (a.video) {
      a.video.setSize(a.body.getSize())
    }
  },
  onDestroy : function() {
    var b = this.video;
    if (b) {
      var a = b.dom;
      if (a && a.pause) {
        a.pause()
      }
      b.remove();
      this.video = null
    }
    this.callParent()
  }
});

Ext.define("Ext.ux.desktop.Wallpaper", {
      extend : "Ext.Component",
      alias : "widget.wallpaper",
      cls : "ux-wallpaper",
      html : '<img src="' + Ext.BLANK_IMAGE_URL + '">',
      stretch : false,
      wallpaper : null,
      afterRender : function() {
        var a = this;
        a.callParent();
        a.setWallpaper(a.wallpaper, a.stretch)
      },
      applyState : function() {
        var b = this, a = b.wallpaper;
        b.callParent(arguments);
        if (a != b.wallpaper) {
          b.setWallpaper(b.wallpaper)
        }
      },
      getState : function() {
        return this.wallpaper && {
          wallpaper : this.wallpaper
        }
      },
      setWallpaper : function(b, a) {
        var c = this, e, d;
        c.stretch = (a !== false);
        c.wallpaper = b;
        if (c.rendered) {
          e = c.el.dom.firstChild;
          if (!b || b == Ext.BLANK_IMAGE_URL) {
            Ext.fly(e).hide()
          } else {
            if (c.stretch) {
              e.src = b;
              c.el.removeCls("ux-wallpaper-tiled");
              Ext.fly(e).setStyle({
                    width : "100%",
                    height : "100%"
                  }).show()
            } else {
              Ext.fly(e).hide();
              d = "url(" + b + ")";
              c.el.addCls("ux-wallpaper-tiled")
            }
          }
          c.el.setStyle({
                backgroundImage : d || ""
              })
        }
        return c
      }
    });
/*
 * Ext JS Library 4.0 Copyright(c) 2006-2011 Sencha Inc. licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.define("Ext.ux.desktop.FitAllLayout", {
      extend : "Ext.layout.container.AbstractFit",
      alias : "layout.fitall",
      onLayout : function() {
        var b = this;
        b.callParent();
        var a = b.getLayoutTargetSize();
        b.owner.items.each(function(c) {
              b.setItemBox(c, a)
            })
      },
      getTargetBox : function() {
        return this.getLayoutTargetSize()
      },
      setItemBox : function(c, b) {
        var a = this;
        if (c && b.height > 0) {
          if (c.layoutManagedWidth == 2) {
            b.width = undefined
          }
          if (c.layoutManagedHeight == 2) {
            b.height = undefined
          }
          c.getEl().position("absolute", null, 0, 0);
          a.setItemSize(c, b.width, b.height)
        }
      }
    });
    
    Ext.define("Ext.ux.desktop.StartMenu", {
      extend : "Ext.panel.Panel",
      requires : ["Ext.menu.Menu", "Ext.toolbar.Toolbar"],
      ariaRole : "menu",
      cls : "x-menu ux-start-menu",
      defaultAlign : "bl-tl",
      iconCls : "user",
      floating : true,
      shadow : true,
      width : 300,
      initComponent : function() {
        var a = this, b = a.menu;
        a.menu = new Ext.menu.Menu({
              cls : "ux-start-menu-body",
              border : false,
              floating : false,
              items : b
            });
        a.menu.layout.align = "stretch";
        a.items = [a.menu];
        a.layout = "fit";
        Ext.menu.Manager.register(a);
        a.callParent();
        a.toolbar = new Ext.toolbar.Toolbar(Ext.apply({
              dock : "right",
              cls : "ux-start-menu-toolbar",
              vertical : true,
              width : 100
            }, a.toolConfig));
        a.toolbar.layout.align = "stretch";
        a.addDocked(a.toolbar);
        delete a.toolItems;
        a.on("deactivate", function() {
              a.hide()
            })
      },
      addMenuItem : function() {
        var a = this.menu;
        a.add.apply(a, arguments)
      },
      addToolItem : function() {
        var a = this.toolbar;
        a.add.apply(a, arguments)
      },
      showBy : function(c, f, e) {
        var b = this;
        if (b.floating && c) {
          b.layout.autoSize = true;
          b.show();
          c = c.el || c;
          var d = b.el.getAlignToXY(c, f || b.defaultAlign, e);
          if (b.floatParent) {
            var a = b.floatParent.getTargetEl().getViewRegion();
            d[0] -= a.x;
            d[1] -= a.y
          }
          b.showAt(d);
          b.doConstrain()
        }
        return b
      }
    });
/*
 * Ext JS Library 4.0 Copyright(c) 2006-2011 Sencha Inc. licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.define("Ext.ux.desktop.TaskBar", {
      extend : "Ext.toolbar.Toolbar",
      requires : ["Ext.button.Button", "Ext.resizer.Splitter",
          "Ext.menu.Menu", "Ext.ux.desktop.StartMenu"],
      alias : "widget.taskbar",
      cls : "ux-taskbar",
      startBtnText : "Start",
      initComponent : function() {
        var a = this;
        a.startMenu = new Ext.ux.desktop.StartMenu(a.startConfig);
        a.quickStart = new Ext.toolbar.Toolbar(a.getQuickStart());
        a.windowBar = new Ext.toolbar.Toolbar(a.getWindowBarConfig());
        a.tray = new Ext.toolbar.Toolbar(a.getTrayConfig());
        a.items = [{
              xtype : "button",
              cls : "ux-start-button",
              iconCls : "ux-start-button-icon",
              menu : a.startMenu,
              menuAlign : "bl-tl",
              text : a.startBtnText
            }, a.quickStart, {
              xtype : "splitter",
              html : "&#160;",
              height : 14,
              width : 2,
              cls : "x-toolbar-separator x-toolbar-separator-horizontal"
            }, a.windowBar, "-", a.tray];
        a.callParent()
      },
      afterLayout : function() {
        var a = this;
        a.callParent();
        a.windowBar.el.on("contextmenu", a.onButtonContextMenu, a)
      },
      getQuickStart : function() {
        var b = this, a = {
          minWidth : 20,
          width : 60,
          items : [],
          enableOverflow : true
        };
        Ext.each(this.quickStart, function(c) {
              a.items.push({
                    tooltip : {
                      text : c.name,
                      align : "bl-tl"
                    },
                    overflowText : c.name,
                    iconCls : c.iconCls,
                    module : c.module,
                    handler : b.onQuickStartClick,
                    scope : b
                  })
            });
        return a
      },
      getTrayConfig : function() {
        var a = {
          width : 80,
          items : this.trayItems
        };
        delete this.trayItems;
        return a
      },
      getWindowBarConfig : function() {
        return {
          flex : 1,
          cls : "ux-desktop-windowbar",
          items : ["&#160;"],
          layout : {
            overflowHandler : "Scroller"
          }
        }
      },
      getWindowBtnFromEl : function(a) {
        var b = this.windowBar.getChildByElement(a);
        return b || null
      },
      onQuickStartClick : function(b) {
        var a = this.app.getModule(b.module);
        if (a) {
          a.createWindow()
        }
      },
      onButtonContextMenu : function(d) {
        var c = this, b = d.getTarget(), a = c.getWindowBtnFromEl(b);
        if (a) {
          d.stopEvent();
          c.windowMenu.theWin = a.win;
          c.windowMenu.showBy(b)
        }
      },
      onWindowBtnClick : function(a) {
        var b = a.win;
        if (b.minimized || b.hidden) {
          b.show()
        } else {
          if (b.active) {
            b.minimize()
          } else {
            b.toFront()
          }
        }
      },
      addTaskButton : function(c) {
        var a = {
          iconCls : c.iconCls,
          enableToggle : true,
          toggleGroup : "all",
          width : 140,
          text : Ext.util.Format.ellipsis(c.title, 20),
          listeners : {
            click : this.onWindowBtnClick,
            scope : this
          },
          win : c
        };
        var b = this.windowBar.add(a);
        b.toggle(true);
        return b
      },
      removeTaskButton : function(a) {
        var c, b = this;
        b.windowBar.items.each(function(d) {
              if (d === a) {
                c = d
              }
              return !c
            });
        if (c) {
          b.windowBar.remove(c)
        }
        return c
      },
      setActiveButton : function(a) {
        if (a) {
          a.toggle(true)
        } else {
          this.windowBar.items.each(function(b) {
                if (b.isButton) {
                  b.toggle(false)
                }
              })
        }
      }
    });
Ext.define("Ext.ux.desktop.TrayClock", {
      extend : "Ext.toolbar.TextItem",
      alias : "widget.trayclock",
      cls : "ux-desktop-trayclock",
      html : "&#160;",
      timeFormat : "g:i A",
      tpl : "{time}",
      initComponent : function() {
        var a = this;
        a.callParent();
        if (typeof(a.tpl) == "string") {
          a.tpl = new Ext.XTemplate(a.tpl)
        }
      },
      afterRender : function() {
        var a = this;
        Ext.Function.defer(a.updateTime, 100, a);
        a.callParent()
      },
      onDestroy : function() {
        var a = this;
        if (a.timer) {
          window.clearTimeout(a.timer);
          a.timer = null
        }
        a.callParent()
      },
      updateTime : function() {
        var a = this, b = Ext.Date.format(new Date(), a.timeFormat), c = a.tpl
            .apply({
                  time : b
                });
        if (a.lastText != c) {
          a.setText(c);
          a.lastText = c
        }
        a.timer = Ext.Function.defer(a.updateTime, 10000, a)
      }
    });

