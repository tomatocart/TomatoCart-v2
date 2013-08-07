/**
  $Id: notification.js $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

Ext.ux.NotificationMgr = {
    positions: []
};

Ext.define('Ext.ux.Notification', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.iconCls = config.iconCls || 'x-icon-information';
    config.width = 200;
    config.autoHeight = true;
    config.closable = true;
    config.plain = false;
    config.draggable = false;
    config.bodyStyle = 'text-align:left;padding:10px;';
    config.resizable = false;
    
    this.callParent([config]);
  },
  
  animShow: function() {
  	this.pos = 0;
  	
    while(Ext.Array.indexOf(Ext.ux.NotificationMgr.positions, this.pos) > -1)
    {
      this.pos++;
    }
    
    Ext.ux.NotificationMgr.positions.push(this.pos);
    
    this.setSize(200, 100);
    
    this.show();
  },
  
  afterShow: function() {
  	var me = this;
  	
  	this.alignTo(this.animateTarget || document, 'br-tr', [ -5, -1-((this.getSize().height+10)*this.pos) ]);
  	
    this.callParent([this]);
    
    if (this.autoDestroy) {
      setTimeout( function() {
        me.destroy();
      }, this.hideDelay || 3000);
    }
  },
  
  onDestroy: function() {
    Ext.Array.remove(Ext.ux.NotificationMgr.positions, this.pos);
    
    this.callParent([this]);
  }
});