<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
  $Id: guest_book.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class TOC_Access_Guest_book extends TOC_Access {
    public function __construct()
    { 
      parent::__construct();
      
      $this->_module = 'guest_book';
      $this->_group = 'articles';
      $this->_icon = 'guest_book.png';
      $this->_sort_order = 500;
      
      $this->_title = lang('access_guest_book_title');
    }
  }
