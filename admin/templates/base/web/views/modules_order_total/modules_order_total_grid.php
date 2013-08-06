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
 * @filesource customers_grid.php
 */
?>

Ext.define('Toc.modules_order_total.ModulesOrderTotalGrid', {
    extend: 'Ext.grid.GridPanel',
    
    constructor: function(config) {
        config = config || {};
        
        config.border = false;
        config.viewConfig = {forceFit: true};
        
        config.store = Ext.create('Ext.data.Store', {
            fields:[
                'code',
                'title',
                'sort_order',
                'is_installed',
                'edit_cls',
                'install_cls'
            ],
            pageSize: Toc.CONF.GRID_PAGE_SIZE,
            proxy: {
                type: 'ajax',
                url : '<?php echo site_url('modules_order_total/list_order_totals'); ?>',
                reader: {
                    type: 'json',
                    root: Toc.CONF.JSON_READER_ROOT,
                    totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
                }
            },
            autoLoad: true
        });  
        
        config.columns =[
            {header: '<?php echo lang('table_heading_order_total_modules'); ?>', dataIndex: 'title', flex: 1},
            {header: '<?php echo lang('table_heading_sort_order'); ?>', dataIndex: 'sort_order', width: 85},
            {
                header: 'Actions',
                xtype: 'actioncolumn',
                items: [
                	{
                		tooltip: 'Edit',
                 		getClass: function(v, meta, rec, row, col, store) {
                   			return rec.get('edit_cls');
                 	 	},
                    	handler: this.onEdit,
                    	scope: this
                	},
                	{
                		tooltip: 'Install',
                 		getClass: function(v, meta, rec, row, col, store) {
                   			return rec.get('install_cls');
                 		},
                 		handler: this.onInstall,
                    	scope: this
                	}
                ],
                width: 54
            }
        ];
    
        config.tbar = [
        { 
            text: TocLanguage.btnRefresh,
            iconCls: 'refresh',
            handler: this.onRefresh,
            scope: this
        }];
        
        this.callParent([config]);
    },
  
    onRefresh: function() 
    {
    	this.getStore().load();
    },
    
    onEdit: function(grid, row, col) 
    {
    	var record = grid.getStore().getAt(row);

        if (record.get('install_cls') == 'icon-uninstall-record') 
        {
	        var dlg  = this.owner.createConfigurationDialog({code: record.get("code")});
            dlg.setTitle(record.get('title'));
            
            dlg.on('saveSuccess', function(){
    			this.onRefresh();
            }, this);
            
            dlg.show();
        }
    },
    
    onInstall: function(grid, row, col) {
    	var rec = grid.getStore().getAt(row);
    	var action = (rec.get('install_cls') == 'icon-install-record') ? 'install' : 'uninstall';
    	
        this.el.mask();
        Ext.Ajax.request({
            url: '<?php echo site_url('modules_order_total'); ?>' + '/' + action,
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
});