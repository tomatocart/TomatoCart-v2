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

Ext.define('Toc.orders.OrdersEditPanel', {
	extend: 'Ext.Panel',
	
	constructor: function(config) {
		config = config || {};
		  
		config.title = '<?php echo lang('section_address'); ?>';
		config.layout = 'border';
		config.border = false;
		config.items = this.buildForm(config);
		
		config.defaults = {autoScroll: true};
		
		this.addEvents({'updateSuccess': true, 'editProductsSuccess': true});
		
		this.callParent([config]);
	},
	
	buildForm: function(config) {
		this.cboCurrencies = Ext.create('Ext.form.ComboBox', {
			fieldLabel: '<?php echo lang('field_order_currencies'); ?>',
			store: this.buildJsonStore('<?php echo site_url('orders/list_currencies'); ?>', ['id', 'text', 'symbol_left', 'symbol_right', 'decimal_places']),
			queryMode: 'local', 
			name: 'currency_id', 
			displayField: 'text', 
			valueField: 'id', 
			editable: false,
			allowBlank: false,
			listeners: {
				select: this.oncboCurrenciesSelect,
				scope: this
			}
		});
    
		this.fsOrderInfo = Ext.create('Ext.form.FieldSet', {
			title: '<?php echo lang('subsection_order_information'); ?>',
			layout: 'column',
			autoScroll: true,
			items: [
				{
					columnWidth: 0.63,
					border: false,
					items: [
						{xtype: 'displayfield', fieldLabel: '<?php echo lang("field_customers_name"); ?>', name: 'customers_name'},
						{xtype: 'displayfield', fieldLabel: '<?php echo lang("field_customers_email_address"); ?>', name: 'email_address'}
					]
				},
				{
					columnWidth: 0.36,
					border: false,
					items: this.cboCurrencies
				}
			]
		});
		
		this.cboBillingAddresses = this.buildCboAddresses(config, this.onCboBillingAddressesSelect);
		this.cboShippingAddresses = this.buildCboAddresses(config, this.onCboShippingAddressesSelect);
		
		this.cboBillingCountries = this.buildCboCountries('billing_countries_id', this.onCboBillingCountriesSelect);
		this.cboShippingCountries = this.buildCboCountries('shipping_countries_id', this.onCboShippingCountriesSelect);
		
		this.cboBillingZones = this.buildCboZones('billing_zone_id');
		this.cboShippingZones = this.buildCboZones('shipping_zone_id');
		
		this.fsBillingAddress = Ext.create('Ext.form.FieldSet', {
			title: '<?php echo lang('subsection_billing_address'); ?>',
			autoHeight: true,
			defaults: {xtype: 'textfield', anchor: '97%'},
			items: [
				this.cboBillingAddresses,
				this.txtBillingCustomerName = Ext.create('Ext.form.TextField', {fieldLabel: '<?php echo lang('field_customers_name'); ?>', name: 'billing_name'}),
				this.txtBillingCompany = Ext.create('Ext.form.TextField', {fieldLabel: '<?php echo lang('field_customers_company'); ?>', name: 'billing_company'}),
				this.txtBillingStreet = Ext.create('Ext.form.TextField', {fieldLabel: '<?php echo lang('field_customers_street_address'); ?>', name: 'billing_street_address'}),
				this.txtBillingSuburb = Ext.create('Ext.form.TextField', {fieldLabel: '<?php echo lang('field_customers_suburb'); ?>', name: 'billing_suburb'}),
				this.txtBillingCity = Ext.create('Ext.form.TextField', {fieldLabel: '<?php echo lang('field_customers_city'); ?>', name: 'billing_city'}),
				this.txtBillingPostcode = Ext.create('Ext.form.TextField', {fieldLabel: '<?php echo lang('field_customers_postcode'); ?>', name: 'billing_postcode'}),
				this.cboBillingCountries,
				this.cboBillingZones,
				Ext.create('Ext.Button', {text: '<?php echo lang('button_update');?>', iconCls:'refresh', handler: this.submitForm, scope: this})
			]
		});
		
		this.fsShippingAddress = Ext.create('Ext.form.FieldSet', {
			title: '<?php echo lang('subsection_shipping_address'); ?>',
			autoHeight: true,
			defaults: {xtype: 'textfield', anchor: '97%'},
			items: [
				this.cboShippingAddresses,
				this.txtShippingCustomerName = Ext.create('Ext.form.TextField', {id: 'fsShippingAddress-name', fieldLabel: '<?php echo lang('field_customers_name'); ?>', name: 'shipping_name'}),
				this.txtShippingCompany = Ext.create('Ext.form.TextField', {id: 'fsShippingAddress-company', fieldLabel: '<?php echo lang('field_customers_company'); ?>', name: 'shipping_company'}),
				this.txtShippingStreet = Ext.create('Ext.form.TextField', {id: 'fsShippingAddress-street_adress', fieldLabel: '<?php echo lang('field_customers_street_address'); ?>', name: 'shipping_street_address'}),
				this.txtShippingSuburb = Ext.create('Ext.form.TextField', {id: 'fsShippingAddress-suburb', fieldLabel: '<?php echo lang('field_customers_suburb'); ?>', name: 'shipping_suburb'}),
				this.txtShippingCity = Ext.create('Ext.form.TextField', {id: 'fsShippingAddress-city', fieldLabel: '<?php echo lang('field_customers_city'); ?>', name: 'shipping_city'}),
				this.txtShippingPostcode = Ext.create('Ext.form.TextField', {id: 'fsShippingAddress-postcode', fieldLabel: '<?php echo lang('field_customers_postcode'); ?>', name: 'shipping_postcode'}),
				this.cboShippingCountries,
				this.cboShippingZones,
				Ext.create('Ext.Button', {text: '<?php echo lang('button_update');?>', iconCls:'refresh', handler: this.submitForm, scope: this})
			]
		});
		
		this.cboPaymentMethods = Ext.create('Ext.form.ComboBox', {
			fieldLabel: '<?php echo lang('field_payment_method'); ?>',
			store: this.buildJsonStore('<?php echo site_url('orders/list_payment_methods'); ?>', ['id', 'text']),
			queryMode: 'local', 
			name: 'method_id', 
			displayField: 'text', 
			valueField: 'id', 
			editable: false,
			allowBlank: false
		});
		
		this.frmOrder = Ext.create('Ext.form.Panel', {
			url: '<?php echo site_url('orders/save_address'); ?>',
			baseParams: {
				orders_id: config.ordersId
			},      
			border: false,
			bodyStyle: 'padding:10px',
			autoHeight: true,
			region: 'center',
			fieldDefaults: {
				labelSeparator: ''
			},
			items: [
				this.fsOrderInfo,
				{
		          	xtype : 'panel', 
		          	layout: 'column',
		          	border: false,
		          	items: [
			            {
			              columnWidth: 0.5,
			              border: false,
			              items: [
			                this.fsBillingAddress,
			                {
			                  xtype: 'fieldset', 
			                  title: '<?php echo lang('subsection_payment_method'); ?>',
			                  height: 110,
			                  items: [
			                    this.cboPaymentMethods
			                  ]
			                }
			              ]
			            },
			            {
			              columnWidth: 0.5,
			              border: false,
			              style: 'padding-left: 18px',
			              items: [
			                this.fsShippingAddress,
		               		{
			                  xtype: 'fieldset', 
			                  title: '<?php echo lang('subsection_delivery_method'); ?>',
			                  layout: 'column',
			                  height: 110,
			                  labelSeparator: ' ',
			                  defaults: {anchor: '97%'},
			                  items:[
			                    {
			                      columnWidth: 0.5,
			                      border: false,
			                      items: [this.stxShippingMethod = Ext.create('Ext.form.DisplayField', {hideLabel: true, width: 200})]
			                    },
			                    {
			                      columnWidth: 0.49,
			                      border: false,
			                      items:[this.btnEditShippingMethod = Ext.create('Ext.Button', {'text': '<?php echo lang('button_change_delivery_method'); ?>', iconCls: 'add', handler: this.onEditShippingMethod, scope: this})]
			                    }
			                  ]
			                }
			              ]
			            }
		          	]
		        },
		        this.grdProducts = Ext.create('Toc.orders.OrdersEditProductsGrid', {ordersId: config.ordersId, cboCurrencies: this.cboCurrencies, outStockProduct: config.outStockProduct}),
		        {
		        	layout: 'fit',
		        	border: false,
		        	style: 'padding: 0 24px 8px 420px;',
		        	items: [
		        		this.fsOrderTotals = Ext.create('Ext.form.FieldSet', {
		        			title: '<?php echo lang('subsection_order_totals'); ?>',
		        			labelSeparator: ' ',
		        			width: 250
		        		})
		        	]
		        }
			]
		});
		
		this.grdProducts.getStore().on('load', function() {
      		this.unmask();
      
      		if (this.grdProducts.getStore().getCount() > 0) {
        		this.stxShippingMethod.setValue(this.grdProducts.store.getProxy().getReader().rawData.shipping_method);
        		this.fsOrderTotals.body.update(this.grdProducts.store.getProxy().getReader().rawData.totals);
        		
        		this.doLayout();

		        this.cboPaymentMethods.enable();
		        this.btnEditShippingMethod.enable();
	      	} else {
		        this.cboPaymentMethods.disable();
		        this.btnEditShippingMethod.disable();
	      	}
    	}, this);
    	
    	this.grdProducts.on('editProductsSuccess', function(feedback) {
    		this.fireEvent('editProductsSuccess', feedback);
    	}, this);
		
    
    	this.loadForm(config);
    	
		return this.frmOrder;
	},
	
	loadForm: function (config) {
		this.frmOrder.load({
			url: '<?php echo site_url('orders/load_order'); ?>',
			params: {
				orders_id: config.ordersId
			},
			success: function (form, action) {
        		//currency
				this.cboCurrencies.store.on('load', function(){
					this.cboCurrencies.setValue(action.result.data.currency);
				}, this);
				this.cboCurrencies.store.load();
        
				//payment method
				this.cboPaymentMethods.store.on('load', function(){
					this.cboPaymentMethods.setValue(action.result.data.payment_method);
          
					this.cboPaymentMethods.on('select', this.onPaymentMethodChange, this);
				}, this);
				this.cboPaymentMethods.store.load();
        
				if (action.result.data.has_payment_method == false) {
					this.cboPaymentMethods.disable();
				}
        
				//billing address
				this.cboBillingCountries.store.on('load', function(){
					this.setBillingAddress(action.result.data.billing_address);
				}, this);
				this.cboBillingCountries.store.load();
	        
				//shipping address
				this.cboShippingCountries.store.on('load', function(){
					this.setShippingAddress(action.result.data.shipping_address);
				}, this);
				this.cboShippingCountries.store.load();
			},
			failure: function (form, action) {
				Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
			},
			scope: this
		});
	},
	
	setBillingAddress: function(address) {
		var data = address.split(',');
    
		if (data.length > 1){
			this.txtBillingCustomerName.setValue(data[0]);
			this.txtBillingCompany.setValue(data[1]);
			this.txtBillingStreet.setValue(data[2]);
			this.txtBillingSuburb.setValue(data[3]);
			this.txtBillingCity.setValue(data[4]);
			this.txtBillingPostcode.setValue(data[5]);
       
			if(!Ext.isEmpty(data[7])) {
				var countries_id = this.cboBillingCountries.store.getAt(this.cboBillingCountries.store.find('countries_name', data[7])).get('countries_id');
				this.cboBillingCountries.setValue(countries_id);
                    
				this.cboBillingZones.store.on('load', function() {
					var index = this.cboBillingZones.store.find('zone_name', data[6]);
					
					if (index != -1) {
						this.cboBillingZones.setValue(this.cboBillingZones.store.getAt(index).get('zone_id'));
					} else {
						this.cboBillingZones.setRawValue(data[6]);
						this.cboBillingZones.setEditable(true);
					}
				}, this);
         
				this.cboBillingZones.store.getProxy().extraParams['countries_id'] = countries_id;
				this.cboBillingZones.store.load();
				this.cboBillingZones.enable();
			}
		} else {
			this.resetBillingAddress();
		}
	},
	
	setShippingAddress: function(address) {
		var data = address.split(',');
    
		if (data.length > 1){
			this.txtShippingCustomerName.setValue(data[0]);
			this.txtShippingCompany.setValue(data[1]);
			this.txtShippingStreet.setValue(data[2]);
			this.txtShippingSuburb.setValue(data[3]);
			this.txtShippingCity.setValue(data[4]);
			this.txtShippingPostcode.setValue(data[5]);
       
			if(!Ext.isEmpty(data[7])) {
				var countries_id = this.cboShippingCountries.store.getAt(this.cboShippingCountries.store.find('countries_name', data[7])).get('countries_id');
				this.cboShippingCountries.setValue(countries_id);
                    
				this.cboShippingZones.store.on('load', function() {
					var index = this.cboShippingZones.store.find('zone_name', data[6]);
           
					if (index != -1) {
						this.cboShippingZones.setValue(this.cboShippingZones.store.getAt(index).get('zone_id'));
					} else {
						this.cboShippingZones.setRawValue(data[6]);
						this.cboShippingZones.setEditable(true);
					}
				}, this);
         
				this.cboShippingZones.store.getProxy().extraParams['countries_id'] = countries_id;
				this.cboShippingZones.store.load();
				this.cboShippingZones.enable();
			}
		} else {
			this.resetBillingAddress();
		}
	},
	
	resetBillingAddress: function () {
		this.txtBillingCustomerName.setValue('');
		this.txtBillingCompany.setValue('');
		this.txtBillingStreet.setValue('');
		this.txtBillingSuburb.setValue('');
		this.txtBillingCity.setValue('');
		this.txtBillingPostcode.setValue('');
		this.cboBillingZones.reset();
		this.cboBillingZones.disable();
		this.cboBillingCountries.reset();
	},
	
	resetShippingAddress: function () {
		this.txtShippingCustomerName.setValue('');
		this.txtShippingCompany.setValue('');
		this.txtShippingStreet.setValue('');
		this.txtShippingSuburb.setValue('');
		this.txtShippingCity.setValue('');
		this.txtShippingPostcode.setValue('');
		this.cboShippingZones.reset();
		this.cboShippingZones.disable();
		this.cboShippingCountries.reset();
	},
	
	buildCboAddresses: function(config, handler) {
		var cbo = Ext.create('Ext.form.ComboBox', {
			store: this.buildJsonStore('<?php echo site_url('orders/get_customer_addresses'); ?>', ['id', 'text'], {orders_id: config.ordersId}),
			hideLabel: true,
			queryMode: 'remote', 
			displayField: 'text', 
			valueField: 'text', 
			editable: false,
			allowBlank: true,
			emptyText: '<?php echo lang('choose_from_addess_book'); ?>',
			listeners: {
				select: handler,
				scope: this
			}
		});
		
		return cbo
	},
	
	buildCboCountries: function(name, handler) {
		var cbo = Ext.create('Ext.form.ComboBox', {
			store: this.buildJsonStore('<?php echo site_url('orders/list_countries'); ?>', ['countries_id', 'countries_name']),
			fieldLabel: '<?php echo lang("field_customers_country"); ?>',
			name: name,
			queryMode: 'local', 
			displayField: 'countries_name', 
			valueField: 'countries_id', 
			editable: false,
			allowBlank: false,
			listeners: {
				change: handler,
				scope: this
			}
		});
		
		return cbo;
	},
	
	buildCboZones: function(name) {
		var cbo = Ext.create('Ext.form.ComboBox', {
			store: this.buildJsonStore('<?php echo site_url('orders/list_zones'); ?>', ['zone_id', 'zone_code', 'zone_name']),
			fieldLabel: '<?php echo lang("field_customers_state"); ?>',
			name: name,
			queryMode: 'local', 
			displayField: 'zone_name', 
			valueField: 'zone_id',
			disabled: true, 
			editable: true,
			allowBlank: false,
			emptyText: ''
		});
		
		return cbo;
	},
	
	buildJsonStore: function(url, fields, params) {
		params = params || {};
		
		var store = Ext.create('Ext.data.Store', {
			fields: fields,
			proxy: {
				type: 'ajax',
				url : url,
				extraParams: params,
				reader: {
					type: 'json',
					root: Toc.CONF.JSON_READER_ROOT,
					totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
				}
			},
      		autoLoad: false
		});
		
		return store;
	},
	
	submitForm : function() {
	    this.frmOrder.baseParams['billing_countries'] = this.cboBillingCountries.getRawValue();
	    this.frmOrder.baseParams['shipping_countries'] = this.cboShippingCountries.getRawValue();
	    this.frmOrder.baseParams['billing_state'] = this.cboBillingZones.getRawValue();
	    this.frmOrder.baseParams['shipping_state'] = this.cboShippingZones.getRawValue();
	    
	    var index = this.cboBillingZones.store.find('zone_name', this.cboBillingZones.getRawValue());
	    if (index != -1) {
	     this.frmOrder.baseParams['billing_state_code'] = this.cboBillingZones.store.getAt(index).get('zone_code');
	    } else {
	     this.frmOrder.baseParams['billing_state_code'] = '';
	    }
	    
	    var index = this.cboShippingZones.store.find('zone_name', this.cboShippingZones.getRawValue());
	    if (index != -1) {
	     this.frmOrder.baseParams['shipping_state_code'] = this.cboShippingZones.store.getAt(index).get('zone_code');
	    } else {
	     this.frmOrder.baseParams['shipping_state_code'] = '';
	    }
	    
	    this.cboBillingCountries.disable();
	    this.cboShippingCountries.disable();
	    this.cboBillingAddresses.disable();
	    this.cboShippingAddresses.disable();
	
	    this.frmOrder.form.submit({
	      waitMsg: TocLanguage.formSubmitWaitMsg,
	      success: function(form, action) {
	        this.grdProducts.getStore().load();
	        
	        this.fireEvent('updateSuccess', action.result.feedback);
	      },    
	      failure: function(form, action) {
	        if (action.failureType != 'client') {
	          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
	        }
	      },  
	      scope: this
	    });
	    
	    this.cboBillingCountries.enable();
	    this.cboShippingCountries.enable();
	    this.cboBillingAddresses.enable();
	    this.cboShippingAddresses.enable();   
  	},
  	
  	oncboCurrenciesSelect: function() {
  		this.mask();
  		
  		Ext.Ajax.request({
      		waitMsg: TocLanguage.formSubmitWaitMsg,
      		url: '<?php echo site_url('orders/change_currency'); ?>',
      		params: {
        		orders_id: this.ordersId,
        		currency: this.cboCurrencies.getValue()
      		},
      		callback: function (options, success, response) {
        		var result = Ext.decode(response.responseText);
        
				if (result.success == true) {
          			this.grdProducts.getStore().load();
        		} else {
          			Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
				}
      		},
      		scope: this
    	});
	},
	
	onCboBillingAddressesSelect: function() {
		var address = this.cboBillingAddresses.getValue().toString();
		
		if (address == '<?php echo lang('add_new_address'); ?>')
		{
			this.resetBillingAddress();
		}else {
			this.setBillingAddress(address);
		}
	},
	
	onCboShippingAddressesSelect: function() {
		var address = this.cboShippingAddresses.getValue().toString();
		
		if (address == '<?php echo lang('add_new_address'); ?>') {
			this.resetShippingAddress();
		}else {
			this.setShippingAddress(address);
		}
	},
	
	onCboBillingCountriesSelect: function() {
		this.cboBillingZones.store.on('load', function() {
			this.cboBillingZones.setValue(this.cboBillingZones.store.getAt(0).get('zone_id'));
		}, this);
		
	    this.cboBillingZones.store.getProxy().extraParams['countries_id'] = this.cboBillingCountries.getValue();  
	    this.cboBillingZones.store.load();
	    this.cboBillingZones.enable();
	},
	
	onCboShippingCountriesSelect: function() {
		this.cboShippingZones.store.on('load', function() {
			this.cboShippingZones.setValue(this.cboShippingZones.store.getAt(0).get('zone_id'));
		}, this);
		
	    this.cboShippingZones.store.getProxy().extraParams['countries_id'] = this.cboShippingCountries.getValue();
	    this.cboShippingZones.store.load();
	    this.cboShippingZones.enable();
	},
	
	onPaymentMethodChange: function() {
		this.mask();
		
		Ext.Ajax.request({
			waitMsg: TocLanguage.formSubmitWaitMsg,
			url: '<?php echo site_url('orders/update_payment_method'); ?>',
			params: {
				payment_method: this.cboPaymentMethods.getValue(),
				orders_id: this.ordersId
			},
			callback: function (options, success, response) {
				this.unmask();
	        
				var result = Ext.decode(response.responseText);
				if (result.success == true) {
					this.grdProducts.getStore().load();
	          
					if (result.disable_cbo_payment == true) {
						this.cboPaymentMethods.allowBlank = true;
						this.cboPaymentMethods.setValue('');
						this.cboPaymentMethods.setRawValue('');
						this.cboPaymentMethods.disable();
					} else {
						this.cboPaymentMethods.allowBlank = false;
						this.cboPaymentMethods.enable();
					}
				} else {
					Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
				}
			},
			scope: this
		});
	},
	
	onEditShippingMethod: function() {
		this.fireEvent('editShippingMethod', this.ordersId, this.grdProducts);
	},
	
	mask: function() {
    	this.el.mask(TocLanguage.loadingText, 'x-mask-loading');
	},
  
	unmask: function() {
		this.el.unmask();
	},
});

/* End of file orders_edit_panel.php */
/* Location: ./system/modules/orders/views/orders_edit_panel.php */