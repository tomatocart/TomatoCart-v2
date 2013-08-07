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

Ext.define('Toc.templates.ModulesPanel', {
    extend: 'Ext.Panel',
    
    constructor: function(config) {
        config = config || {};
        
        config.title = '<?php echo lang('heading_title_available_modules'); ?>';
        config.layout = 'fit';
        config.width = 190;
        config.region = 'west';
        config.items = this.buildForm();
        
        config.tbar = [
          {
            text: TocLanguage.btnRefresh,
            iconCls: 'refresh',
            handler: function() {
            	this.pnlModulesTree.getStore().load();
            },
            scope: this
          }
        ];
        
        this.callParent([config]);
    },
    
    buildForm: function() {
        var store = Ext.create('Ext.data.TreeStore', {
            proxy: {
                type: 'ajax',
                url : '<?php echo site_url('templates/get_modules_tree'); ?>',
            },
            root: {
                id: '0',
                text: '',
                leaf: true,
                expandable: true,  
                expanded: true  
            },
            listeners: {
                'load': function() {
					this.pnlModulesTree.expandAll();
                },
                scope: this
            },
            autoLoad: true
        });
        
        this.pnlModulesTree = Ext.create('Ext.tree.Panel', {
            store: store,
            name: 'modules',
            viewConfig: {
                plugins: {
                    ptype: 'treeviewdragdrop',
                    ddGroup: 'templatesDD',
                    appendOnly: true
                }
            },
            border: false,
            rootVisible: false
        });
        
        return this.pnlModulesTree;
    }
});