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

// ------------------------------------------------------------------------

    echo 'Ext.namespace("Toc.customers");';  
    
    require_once('customers_grid.php');
    require_once('customers_dialog.php');
    require_once('accordion_panel.php');
    require_once('address_book_grid.php');
    require_once('address_book_dialog.php');
    require_once('customers_main_panel.php');
?>

Ext.override(Toc.desktop.CustomersWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('customers-win');

    if (!win) {                               
      this.pnl = Ext.create('Toc.customers.mainPanel');
      
      this.pnl.on('createcustomer', this.onCreateCustomer, this);
      this.pnl.on('editcustomer', this.onEditCustomer, this);
      this.pnl.on('createaddress', this.onCreateAddress, this);
      this.pnl.on('editaddress', this.onEditAddress, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'customers-win',
        title: '<?php echo lang('heading_customers_title'); ?>',
        width: 850,
        height: 400,
        iconCls: 'icon-customers-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateCustomer: function() {
    var dlg = this.createCustomerDialog();
    
    dlg.setTitle('<?php echo lang('action_heading_new_customer'); ?>');
    dlg.show();
  },
  
  onEditCustomer: function(rec) {
    var dlg = this.createCustomerDialog();
    
    dlg.setTitle(rec.get('customers_lastname'));
    dlg.show(rec.get('customers_id'));
  },
  
  onCreateAddress: function(customersId) {
    var dlg = this.createAddressBookDialog();
    
    dlg.show(customersId);
  },
  
  onEditAddress: function(customersId, addressId, customer) {
    var dlg = this.createAddressBookDialog();
    
    dlg.setTitle(customer);
    
    dlg.show(customersId, addressId); 
  },
  
  createCustomerDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('customers-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.customers.CustomersDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdCustomers.onRefresh();
        this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
      }, this);
    }
    
    return dlg;    
  },
  
  createAddressBookDialog : function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('address-book-dialog-win');

    if (!dlg) {
      dlg = desktop.createWindow({},Toc.customers.AddressBookDialog);

      dlg.on('savesuccess', function(feedback) {
        this.pnl.pnlAccordion.grdAddressBook.onRefresh();
        this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
      }, this);
    }
    
    return dlg;
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification( {title: TocLanguage.msgSuccessTitle, html: feedback} );
  }
});

/* End of file main.php */
/* Location: ./templates/base/web/views/customers/main.php */