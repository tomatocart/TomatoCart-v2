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

Ext.define('Toc.customers.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    this.pnlAccordion = Ext.create('Toc.customers.AccordionPanel');
    this.grdCustomers = Ext.create('Toc.customers.CustomersGrid');
    
    this.grdCustomers.on('selectchange', this.onGrdCustomersSelectChange, this);
    this.grdCustomers.on('create', function() {this.fireEvent('createcustomer');}, this);
    this.grdCustomers.on('edit', function(rec) {this.fireEvent('editcustomer', rec);}, this);
    this.grdCustomers.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    this.grdCustomers.getStore().on('load', this.onGrdCustomersLoad, this);
    
    this.pnlAccordion.grdAddressBook.on('create', function(customersId) {this.fireEvent('createaddress', customersId)}, this);
    this.pnlAccordion.grdAddressBook.on('edit', function(customersId, addressId, customer) {this.fireEvent('editaddress', customersId, addressId, customer);}, this);
    this.pnlAccordion.grdAddressBook.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback)}, this);
    
    config.items = [this.grdCustomers, this.pnlAccordion];
    
    this.addEvents({'createcustomer': true, 'editcustomer': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdCustomersLoad: function() {
    if (this.grdCustomers.getStore().getCount() > 0) {
      this.grdCustomers.getSelectionModel().select(0);
      var record = this.grdCustomers.getStore().getAt(0);
      
      this.onGrdCustomersSelectChange(record);
    } else {
      this.pnlAccordion.grdAddressBook.reset();
    }
  },

  onGrdCustomersSelectChange: function(record) {
    this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file customers_main_panel.php */
/* Location: ./templates/base/web/views/customers/customers_main_panel.php */