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

Ext.define('Toc.templates.ModulesGroupsPanel', {
    extend: 'Ext.panel.Panel',
    
    constructor: function(config) {
        config = config || {};
        
        config.title = '<?php echo lang('field_layout'); ?>' + config.medium;
        config.border = false;
        config.autoScroll = true;
        
        this.code = config.code;
        this.templatesId = config.templatesId;
        this.medium = config.medium;
        this.parent = config.parent;
        
        this.selectedMid = null;
        this.portals = [];
        
        this.addEvents({'modulechanged': true, 'moduleremoved': true, 'portletdropped': true});
        
        this.callParent([config]);
    },
  
    addGroupsPortals: function(groups) {
        if (!Ext.isEmpty(groups) && Ext.isArray(groups)) {
            Ext.each(groups, function(group) {
                var pnlColumn = null;
                if (group.modules.length > 0) {
                    pnlColumn = Ext.create('Ext.app.PortalColumn', {
                        id: 'content-group-' + this.medium + '-' + group.name,
                        html: '<p class="droppableMsg"></p>'
                    });
                } else {
                    pnlColumn = Ext.create('Ext.app.PortalColumn', {
                        id: 'content-group-' + this.medium + '-' + group.name,
                        html: '<p class="droppableMsg"><span><?php echo lang('introduction_drop_module'); ?></span></p>'
                    });
                }
                
                var pnlPortal = Ext.create('Ext.app.PortalPanel');
                this.portals.push(pnlPortal);
                
                pnlPortal.add(pnlColumn);
                pnlPortal.on('drop', function(dropEvent) {this.fireEvent('portletdropped', dropEvent, pnlPortal);}, this);
                
                var pnlGroup = Ext.create('Ext.Panel', {
                    title: '<?php echo lang('field_content_groups'); ?>' + group.name,
                    cls: 'x-portal-container',
                    items: pnlPortal
                });
                
                pnlColumn.on('render', function() {
					this.onPnlPortalRender(group, pnlPortal, pnlColumn);
                }, this);
                
                this.add(pnlGroup);
            }, this);
        }
    },
  
  onPnlPortalRender: function(group, pnlPortal, pnlColumn) {
    var scope = this;
    
    Ext.each(group.modules, function(module) {
        var portlet = Ext.create('Ext.app.Portlet', {
            id: 'portlet-' + scope.templatesId + '-' + module.id,
            title: module.title,
            collapsible: false,
            frame: false,
            animCollapse: false,
            cls: 'x-portlet x-portlet-draggable',
            tools: [{
                xtype: 'tool',
                type: 'gear',
                handler: function(e, target, panelHeader, tool){
                    Ext.select('.x-portlet-focus').removeCls('x-portlet-focus');
                    portlet.addCls('x-portlet-focus');
                    
                    if (scope.selectedMid != module.id) {
                        scope.selectedMid = module.id;
                        m = scope.parent.getModule(module.id);
                        
                        if (m != false) {
                        	scope.fireEvent('modulechanged', m);
                        }
                    }
                }
            }],
            listeners: {
                close: function() {scope.fireEvent('moduleremoved', module.id, pnlPortal);},
                render: function(c) {
                	portletEl = c.getEl();
                	
                	//add hover effect
                	portletEl.on('mouseenter', function() {
                		this.addCls('x-portlet-hover');
                  	});
                  	
                  	//delete hover effect
                	portletEl.on('mouseleave', function() {
                		this.removeCls('x-portlet-hover');
                  	});
                }
            }
        });
        
        module.column = pnlColumn;
        scope.parent.setModule(module);
        
        pnlColumn.add(portlet);
        pnlPortal.doLayout();
    }, this);
    
    var grdlDropTarget = Ext.create('Ext.dd.DropTarget', pnlColumn.body.dom, {
        ddGroup: 'templatesDD',
        notifyEnter: function(ddSource, e, data) {
            pnlColumn.body.stopAnimation();
            pnlColumn.body.addCls('notifyDrop');
        },
        notifyOut: function() {
        	pnlColumn.body.removeCls('notifyDrop');
        },
        notifyDrop: function(ddSource, e, data) {
            pnlColumn.body.removeCls('notifyDrop');
            
            //get module information
            var record = ddSource.dragData.records[0];
            var code = record.get('id');
            var text = record.get('text');
            
            //loading mask
            var mask = new Ext.LoadMask(this.el, {msg: 'Loading...'});
            mask.show();
            	
            //insert module to template layout
            Ext.Ajax.request({
                url: '<?php echo site_url('templates/add_template_module'); ?>',
                params: {
                    medium: scope.medium,
                    group: group.name,
                    code: code,
                    templates_id: scope.templatesId
                },
                callback: function(options, success, response) {
                    mask.hide();
                    var result = Ext.decode(response.responseText);
                    
                    //if add template module success
                    if (result.success == true) {
                        //set module
                        result.data.column = pnlColumn;
                        scope.parent.addModule(result.data);
                        
                        //remove drop message
                        var el = Ext.select('#content-group-' + scope.medium + '-' + group.name + ' p.droppableMsg span');
                        el.remove();
                        
                        //create portlet
                        var portlet = Ext.create('Ext.app.Portlet', {
                            id: 'portlet-' + result.data.templates_id + '-' + result.data.id,
                            title: result.data.title,
                            collapsible: false,
                            frame: false,
                            animCollapse: false,
                            cls: 'x-portlet x-portlet-draggable',
                            tools: [{
                                xtype: 'tool',
                                type: 'gear',
                                handler: function(e, target, panelHeader, tool){
                                    Ext.select('.x-portlet-focus').removeCls('x-portlet-focus');
                                    portlet.addCls('x-portlet-focus');
                                    
                                    if (scope.selectedMid != result.data.id) {
                                        scope.selectedMid = result.data.id;
                                        m = scope.parent.getModule(result.data.id);
                                        
                                        if (m != false) {
                                        	scope.fireEvent('modulechanged', m);
                                        }
                                    }
                                }
                            }],
                            listeners: {
                                close: function() {scope.fireEvent('moduleremoved', result.data.id, pnlPortal);},
                                render: function(c) {
                                	portletEl = c.getEl();
                                	
                                	//add hover effect
                                	portletEl.on('mouseenter', function() {
                                		this.addCls('x-portlet-hover');
                                  	});
                                  	
                                  	//delete hover effect
                                	portletEl.on('mouseleave', function() {
                                		this.removeCls('x-portlet-hover');
                                  	});
                                }
                            }
                        });
                        
                        //set current portlet focus
                        Ext.select('.x-portlet-focus').removeCls('x-portlet-focus');
                        portlet.addCls('x-portlet-focus');    
                        
                        pnlColumn.add(portlet);
                        pnlPortal.doLayout();
                        scope.fireEvent('modulechanged', result.data);
                    }
                },
                scope: this
            });
            
            return true;
        }
    });
  }
});