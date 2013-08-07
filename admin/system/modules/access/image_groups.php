<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
  $Id: guest_book.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class TOC_Access_Image_groups extends TOC_Access {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'image_groups';
      $this->_group = 'definitions';
      $this->_icon = 'status.png';
      $this->_sort_order = 900;
      
      $this->_title = lang('access_image_groups_title');
    }
  }
