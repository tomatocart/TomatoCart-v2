<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */
?>

Ext.define('Toc.reports_products.LowStockPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    var dsCategories = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text',
        'margin'
      ],
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('reports_products/get_categories'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.cboCategories = Ext.create('Ext.form.ComboBox', {
      listConfig: {
        getInnerTpl: function() {
          return '<div style="margin-left: {margin}px">{text}</div>';
        }
      },
      emptyText: '<?php echo lang("top_category"); ?>',
      name: 'categories',
      store: dsCategories,
      queryMode: 'local',
      displayField: 'text',
      valueField: 'id',
      triggerAction: 'all',
      listeners: {
        select: this.onSearch,
        scope: this
      }
    });
    
    config.tbar = [
      { 
        text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onRefresh,
        scope: this
      },
      '->',
      config.cboCategories
    ];
    
    config.dsChart = Ext.create('Ext.data.Store', {
      fields:[
        'products_id', 
        'products_name',
        'products_quantity'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('reports_products/list_low_stock'); ?>',
        extraParams: {},
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    var chart = Ext.create('Ext.chart.Chart', {
      width: 720,
      height: 300,
      animate: true,
      store: config.dsChart,
      axes: [
        {
          type: 'Numeric',
          position: 'bottom',
          fields: ['products_quantity'],
          title: '<?php echo lang('table_heading_quantity'); ?>',
          grid: true,
          minimum: 0
        }, 
        {
          type: 'Category',
          position: 'left',
          fields: ['products_name'],
          title: '<?php echo lang('table_heading_products'); ?>'
        }
      ],
      series: [
        {
          type: 'bar',
          axis: 'bottom',
          highlight: true,
          tips: {
            trackMouse: true,
            width: 600,
            height: 28,
            renderer: function(storeItem, item) {
              var products_name = storeItem.get('products_name');
              
              if (products_name.length > 25)
              {
                products_name = products_name.substr(0, 25) + '... ';
              }
              
              this.setTitle(products_name + ':  ' + storeItem.get('products_quantity') + ' <?php echo lang('low_stock_tip'); ?>');
            }
          },
          label: {
            display: 'insideEnd',
            field: 'products_quantity',
            orientation: 'horizontal',
            color: '#333',
            'text-anchor': 'middle'
          },
          xField: 'products_name',
          yField: ['products_quantity']
        }
      ]
    });
    
    config.items = chart;
    
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.dsChart,
      dock: 'bottom',
      displayInfo: true
    }];
    
    this.callParent([config]);
  },
  
  onRefresh: function() {
    this.dsChart.load();
  },
  
  onSearch: function() {
    var categoriesId = this.cboCategories.getValue() || null;
    var dsChartProxy = this.dsChart.getProxy();
    
    dsChartProxy.extraParams['categories_id'] = categoriesId;
    
    this.dsChart.load();
  }
});


/* End of file low_stock_panel.php */
/* Location: ./templates/base/web/views/reports_products/low_stock_panel.php */