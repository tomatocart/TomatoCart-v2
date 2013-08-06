Ext.define("Toc.desktop.Module", {
  extend : "Ext.ux.desktop.Module",
  /**
   * Read only. {string}
   * Override this with the unique id of your module.
   */
  id : null,
   
  /**
   * Read only. {object}
   * Override this with the launcher for your module.
   * 
   * Example:
   * 
   * {
   *    iconCls: 'pref-icon',
   *    handler: this.createWindow,
   *    scope: this,
   *    shortcutIconCls: 'pref-shortcut-icon',
   *    text: 'Preferences'
   * }
   */
  launcher : null,
  
  /**
   * Read only. {boolean}
   * Ext.app.App uses this property to determine if the module has been loaded.
   */
  loaded : false,
  
  /**
   * Override this to initialize your module.
   */
  init : Ext.emptyFn,
  
  /**
   * Override this function to create your module's window.
   */
  createWindow : function() {
    this.app.createWindow(this.id)
  },
  
  /**
   * @param {array} An array of request objects
   *
   * Override this function to handle requests from other modules.
   * Expect the passed in param to look like the following.
   * 
   * {
   *    requests: [
   *       {
   *          action: 'createWindow',
   *          params: '',
   *          callback: this.myCallbackFunction,
   *          scope: this
   *       },
   *       { ... }
   *    ]
   * }
   *
   * View makeRequest() in App.js for more details.
   */
  handleRequest : Ext.emptyFn,
  
  getId: function() {
    return this.id;
  }
});