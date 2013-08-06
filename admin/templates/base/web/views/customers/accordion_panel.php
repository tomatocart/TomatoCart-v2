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

Ext.define('Toc.customers.AccordionPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.region = 'east';
    config.border = false;
    config.split = true;
    config.minWidth = 240;
    config.maxWidth = 350;
    config.width = 300;
    config.layout = 'accordion';
    
    config.grdAddressBook = Ext.create('Toc.customers.AddressBookGrid');
    
    config.items = [config.grdAddressBook];
    
    this.callParent([config]);
  }
});

/* End of file accordion_panel.php */
/* Location: ./templates/base/web/views/customers/accordion_panel.php */