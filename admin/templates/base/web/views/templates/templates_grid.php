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

Ext.define('Toc.templates.TemplatesGrid', {
    extend: 'Ext.grid.GridPanel',
    
    constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
        fields:[
            {name: 'id'},
            {name: 'code'},
            {name: 'title'},
            {name: 'author'},
            {name: 'url'},
            {name: 'default_cls'},
            {name: 'install_cls'}
        ],
        proxy: {
            type: 'ajax',
            url : '<?php echo site_url('templates/list_templates'); ?>',
            extraParams: {
                module: 'templates',
                action: 'list_templates'
            },
            reader: {
                type: 'json',
                root: Toc.CONF.JSON_READER_ROOT,
                totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
            }
        },
        autoLoad: true
    });
    
    config.columns = [
        { header: '<?php echo lang('table_heading_templates'); ?>', dataIndex: 'title', sortable: true, flex: 1},
        { header: '<?php echo lang('table_heading_author'); ?>', align: 'center', dataIndex: 'author', width: 200},
        { header: '<?php echo lang('table_heading_url'); ?>', align: 'center', dataIndex: 'url', width: 200},
        {
            header: '<?php echo lang('table_heading_action'); ?>',
            xtype: 'actioncolumn',
            items: [
            	{
            		tooltip: 'Default',
             		getClass: function(v, meta, rec, row, col, store) {
               			return rec.get('default_cls');
             	 	},
                	handler: this.onSetDefault,
                	scope: this
            	},
            	{
            		tooltip: 'Install',
             		getClass: function(v, meta, rec, row, col, store) {
               			return rec.get('install_cls');
             		},
                    handler: this.onInstall,
                    scope: this
            	},
            	{
            		tooltip: 'Edit',
             		getClass: function(v, meta, rec, row, col, store) {
               			return 'icon-edit-record';
             		},
             		handler: this.onEdit,
                	scope: this
            	},
            	{
            		tooltip: 'Layout',
             		getClass: function(v, meta, rec, row, col, store) {
               			return 'icon-templates-grp';
             		},
             		handler: this.onLayout,
                	scope: this
            	}
            ]
        }
    ];
    
    config.tbar = [
        {
            text: TocLanguage.btnAdd,
            iconCls: 'add',
            handler: this.onAdd,
            scope: this
        },
        '-', 
        { 
            text: TocLanguage.btnRefresh,
            iconCls: 'refresh',
            handler: this.onRefresh,
            scope: this
        }
    ];
    
    this.addEvents({'add': true});
    this.addEvents({'editLayouts': true});
    this.addEvents({'editTemplates': true});
    
    this.callParent([config]);
    },
    
    onAdd: function() {
    	this.fireEvent('add');
    },
    
    onRefresh: function() {
    	this.getStore().load();
    },
    
    onSetDefault: function(grid, row, col) {
    	var rec = grid.getStore().getAt(row);
    	var cls = rec.get('default_cls');
    
    	if (cls == 'icon-default-gray-record') {
            this.el.mask();
            Ext.Ajax.request({
            url: '<?php echo site_url('templates/set_default'); ?>',
            params: {
              code: rec.get('code')
            },
            callback: function(options, success, response){
            	this.el.unmask();
            
              result = Ext.decode(response.responseText);
              
              if (result.success == true) {
                this.onRefresh();
              } else {
                Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
              }
            },
            scope: this
            }); 
    	}
    },
    
    onInstall: function(grid, row, col) {
    	var rec = grid.getStore().getAt(row);
    	var action = (rec.get('install_cls') == 'icon-install-record') ? 'install' : 'uninstall';
    	
        this.el.mask();
        Ext.Ajax.request({
          url: '<?php echo site_url('templates'); ?>' + '/' + action,
          params: {
            code: rec.get('code')
          },
          callback: function(options, success, response){
          	this.el.unmask();
          
            result = Ext.decode(response.responseText);
            
            if (result.success == true) {
              this.onRefresh();
            } else {
              Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
            }
          },
          scope: this
        }); 
    },
    
    onEdit: function (grid, row, col) {
    	var rec = grid.getStore().getAt(row);
    	var cls = rec.get('install_cls');
    	
    	if (rec.get('install_cls') == 'icon-uninstall-record') {
        	this.fireEvent('editTemplates', rec);
    	}
    },
    
    onLayout: function (grid, row, col) {
    	var rec = grid.getStore().getAt(row);
    	var cls = rec.get('install_cls');
    	
    	if (rec.get('install_cls') == 'icon-uninstall-record') {
        	this.fireEvent('editLayouts', rec);
    	}
    }
});