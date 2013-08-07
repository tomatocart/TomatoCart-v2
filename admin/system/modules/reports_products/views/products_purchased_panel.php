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
 * @filesource system/modules/reports_products/views/products_purchases_grid.php
 */
?>

Ext.define('Toc.reports_products.ProductsPurchasedPanel', {
  extend: 'Ext.Panel',
  
  statics: {
    formatCurrency : function(price) {
      return Ext.util.Format.currency(price, '<?php echo $this->currencies->get_symbol_left(); ?>', parseInt('<?php echo $this->currencies->get_decimal_places(); ?>'), '<?php echo $this->currencies->get_symbol_right(); ?>');
    }
  },
  
  constructor: function(config) {
    var statics = this.statics();
     
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
    
    config.dateStart = Ext.create('Ext.form.DateField', {
      width: 150, 
      format: 'Y-m-d', 
      emptyText: '<?php echo lang("field_start_date"); ?>'
    });
    
    config.dateEnd = Ext.create('Ext.form.DateField', {
      width: 150, 
      format: 'Y-m-d', 
      emptyText: '<?php echo lang("field_end_date"); ?>'
    });
    
    config.tbar = [
      { 
        text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onRefresh,
        scope: this
      },
      '->',
      config.dateStart,
      ' ',
      config.dateEnd,
      ' ',
      config.cboCategories,
      ' ',
      { 
        iconCls: 'search',
        handler: this.onSearch,
        scope: this
      }
    ];
    
    config.dsChart = Ext.create('Ext.data.Store', {
      fields:[
        'products_id', 
        'products_name',
        'quantity',
        'final_price',
        'total',
        'average_price'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'reports_products',
          action: 'list_products_purchased'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    chart = Ext.create('Ext.chart.Chart', {
      width: 720,
      height: 300,
      animate: true,
      store: config.dsChart,
      axes: [
        {
          type: 'Numeric',
          position: 'bottom',
          fields: ['total'],
          label: {
            renderer: statics.formatCurrency
          },
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
              
              this.setTitle(products_name + ':  ' + storeItem.get('quantity') + ' <?php echo lang('products_purchased_tip'); ?>');
            }
          },
          label: {
            display: 'insideEnd',
            field: 'total',
            renderer: statics.formatCurrency,
            orientation: 'horizontal',
            color: '#333',
            'text-anchor': 'middle'
          },
          xField: 'products_name',
          yField: ['total']
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
  
  onSearch: function() {
    var startDate = this.dateStart.getValue() || null;
    var endDate = this.dateEnd.getValue() || null;
    var categoriesId = this.cboCategories.getValue() || null;
    var dsChartProxy = this.dsChart.getProxy();
    
    dsChartProxy.extraParams['start_date'] = startDate;
    dsChartProxy.extraParams['end_date'] = endDate;
    dsChartProxy.extraParams['categories_id'] = categoriesId;
    
    this.dsChart.load();
  },
  
  onRefresh: function() {
    this.dsChart.load();
  }
});

/* End of file products_purchases_panel.php */
/* Location: ./system/modules/reports_products/views/products_purchases_panel.php */
