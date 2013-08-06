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
 * @filesource system/modules/reports_customers/views/orders_total_panel.php
 */
?>

Ext.define('Toc.reports_customers.OrdersTotalPanel', {
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
      { 
        text: '',
        iconCls: 'search',
        handler: this.onSearch,
        scope: this
      }
    ];
    
    config.dsChart = Ext.create('Ext.data.Store', {
      fields:[
        'orders_id', 
        'customers_id',
        'customers_name',
        'value'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'reports_customers',
          action: 'list_orders_total'
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
          fields: ['value'],
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
          fields: ['customers_name'],
          title: '<?php echo lang('table_heading_customers'); ?>'
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
              var customers_name = storeItem.get('customers_name');
              
              if (customers_name.length > 25)
              {
                customers_name = customers_name.substr(0, 25) + '... ';
              }
              
              this.setTitle(customers_name + ':  ' + storeItem.get('value'));
            }
          },
          label: {
            display: 'insideEnd',
            field: 'value',
            renderer: statics.formatCurrency,
            orientation: 'horizontal',
            color: '#333',
            'text-anchor': 'middle'
          },
          xField: 'customers_name',
          yField: ['value']
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
    var startDate = this.dateStart.getValue() || null;
    var endDate = this.dateEnd.getValue() || null;
    var dsChartProxy = this.dsChart.getProxy();
    
    dsChartProxy.extraParams['start_date'] = startDate;
    dsChartProxy.extraParams['end_date'] = endDate;
    
    this.dsChart.load();
  }
});

/* End of file orders_total_panel.php */
/* Location: ./system/modules/reports_products/views/orders_total_panel.php */
