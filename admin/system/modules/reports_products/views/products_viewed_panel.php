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
 * @filesource system/modules/reports_products/views/products_viewed_panel.php
 */
?>

Ext.define('Toc.reports_products.ProductsViewedPanel', {
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
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'reports_products',
          action: 'get_categories'
        },
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
    
    var dsLanguages = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text'
      ],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'reports_products',
          action: 'get_languages'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.cboLanguages = Ext.create('Ext.form.ComboBox', {
      emptyText: '<?php echo lang("languages"); ?>',
      name: 'languages',
      store: dsLanguages,
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
      config.cboCategories,
      ' ',
      config.cboLanguages,
      ' ',
      { 
        text: '',
        iconCls: 'search',
        handler: this.onSearch,
        scope: this
      }
    ];
    
    config.dsChart = Ext.create('Ext.data.Store', {
      fields:[
        'products_id', 
        'products_name',
        'products_viewed',
        'language'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'reports_products',
          action: 'list_products_viewed'
        },
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
          fields: ['products_viewed'],
          title: '<?php echo lang('table_heading_total'); ?>',
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
              
              this.setTitle(products_name + ':  ' + storeItem.get('products_viewed') + ' <?php echo lang('products_viewed_tip'); ?>');
            }
          },
          label: {
            display: 'insideEnd',
            field: 'products_viewed',
            orientation: 'horizontal',
            color: '#333',
            'text-anchor': 'middle'
          },
          xField: 'products_name',
          yField: ['products_viewed']
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
    var languagesId = this.cboLanguages.getValue() || null;
    var dsChartProxy = this.dsChart.getProxy();
    
    dsChartProxy.extraParams['categories_id'] = categoriesId;
    dsChartProxy.extraParams['language_id'] = languagesId;
    
    this.dsChart.load();
  }
});

/* End of file products_viewed_panel.php */
/* Location: ./system/modules/reports_products/views/products_viewed_panel.php */
