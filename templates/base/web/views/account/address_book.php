<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package      TomatoCart
 * @author       TomatoCart Dev Team
 * @copyright    Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html
 * @link         http://tomatocart.com
 * @since        Version 2.0
 * @filesource
*/
?>

<h1><?php echo lang('address_book_heading'); ?></h1>

<?php echo toc_validation_errors('address_book'); ?>

<div class="module-box">
    <h6><?php echo lang('primary_address_title'); ?></h6>
    
    <div class="row-fluid">
        <div class="span5"><?php echo lang('primary_address_description') ?></div>
        <div class="span3"><strong><?php echo lang('primary_address_title'); ?></strong><br /><img src="<?php echo image_url('arrow_south_east.gif'); ?>" /></div>
        <div class="span4"><?php echo $primary_address_format; ?></div>
    </div>
</div>

<div class="module-box">
    <h6><?php echo lang('address_book_title'); ?></h6>
    
    <?php
        if (!empty($address_books)) :
            foreach($address_books as $address_book) :
    ?>
    <div class="row-fluid">
    	<div class="span8">
            <strong><?php echo $address_book['firstname'] . ' ' . $address_book['lastname']; ?></strong>
            <?php
                if ($address_book['address_book_id'] == $default_address_id) :
            ?>
            <small><i><?php echo lang('primary_address_marker'); ?></i></small>
            <?php
                endif;
            ?>
            <p><?php echo $address_book['format']; ?></p>
    	</div>
    	<div class="span4">
            <a class="btn btn-small btn-mini btn-inverse" href="<?php echo site_url('account/address_book/edit/' . $address_book['address_book_id']); ?>"><i class="icon-edit icon-white"></i> <?php echo lang('button_edit'); ?></a>
            <a class="btn btn-small btn-mini btn-inverse" href="<?php echo site_url('account/address_book/delete/' . $address_book['address_book_id']); ?>"><i class="icon-trash icon-white"></i> <?php echo lang('button_delete'); ?></a>
    	</div>
    </div>
    <?php
            endforeach;
        endif;
    ?>
    
    <?php
        if (count($address_books) > config('MAX_ADDRESS_BOOK_ENTRIES')) :
    ?>
    	<p><?php echo sprintf(lang('address_book_maximum_entries'), config('MAX_ADDRESS_BOOK_ENTRIES')); ?></p>
    <?php
        endif;
    ?>
</div>

<div class="control-group clearfix">
    <?php
        if (count($address_books) < config('MAX_ADDRESS_BOOK_ENTRIES')) :
    ?>
	<a href="<?php echo site_url('account/address_book/add'); ?>" class="btn btn-small btn-success pull-right"><i class=" icon-plus icon-white"></i> <?php echo lang('button_add_address'); ?></a>
    <?php
        endif;
    ?>
    <a href="<?php echo site_url('account'); ?>" class="btn btn-small btn-info pull-left"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_back'); ?></a>
</div>