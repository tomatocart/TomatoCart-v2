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

<h1><?php echo lang('wishlist_heading');?></h1>

<?php echo toc_validation_errors('wishlist'); ?>

<?php 
    if (is_array($products) && !is_null($products)):
?>

<form name="update_wishlist" method="post" action="<?php echo site_url('wishlist/update'); ?>">

	<table class="table table-hover table-gradient">
      	<thead>
            <tr>
              <th align="center"><?php echo lang('listing_products_heading'); ?></th>
              <th><?php echo lang('listing_comments_heading'); ?></th>
              <th width="100" class="visible-desktop"><?php echo lang('listing_date_added_heading'); ?></th>
              <th class="visible-desktop"></th>
            </tr>
      	</thead>
		<tbody>
			<?php 
			    foreach ($products as $product): 
			?>
            <tr>        
                <td class="center" width="150">
            		<a href="<?php echo site_url('product/' . $product['products_id']); ?>">
            			<img src="<?php echo product_image_url($product['image']); ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"> 
            		</a>
            		<p><?php echo $product['name']; ?></p>
            		<p><?php echo currencies_format($product['price']); ?> </p>
                <td>
                	<textarea id="comments[<?php echo $product['products_id']; ?>]" rows="5" cols="20" name="comments[<?php echo $product['products_id']; ?>]"><?php echo $product['comments']; ?></textarea>
                </td>
                <td width="96" class="visible-desktop"><?php echo get_date_short($product['date_added']); ?></td>
                <td class="center btn-toolbar visible-desktop">
        			<a href="<?php echo site_url('wishlist/delete/' . $product['products_id']); ?>" class="btn btn-mini pull-left"><?php echo lang('button_delete'); ?></a>&nbsp;
					<a href="<?php echo site_url('cart_add/' . $product['products_id']); ?>" class="btn btn-mini"><?php echo lang('button_buy_now'); ?></a>
                </td>
            </tr>   
			<?php endforeach;?>
		</tbody>
	</table>

	<div class="submitFormButtons right">
        <a href="javascript:void(0);" class="btn btn-info btn-small pull-left" onclick="javascript:window.history.go(-1);return false;"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_back'); ?></a>
        
        <button type="submit" class="btn btn-info btn-small pull-right"><i class="icon-ok-sign icon-white"></i> <?php echo lang('button_continue'); ?></button>
	</div>
</form>
<?php 
    else : 
?>
<div class="content btop">
	<p><?php echo lang('wishlist_empty'); ?> </p>
</div>

<div class="submitFormButtons right clearfix">
    <a href="javascript:void(0);" class="btn btn-info btn-small pull-left" onclick="javascript:window.history.go(-1);return false;"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_back'); ?></a>
</div>
<?php 
    endif;
?>

<?php 
    if (is_array($products) && !is_null($products)):
?>
<form name="share_wishlist" id="share_wishlist" method="post" action="<?php echo site_url('account/wishlist/share'); ?>" class="form-horizontal">     
	<div class="module-box">
		<h4><em class="pull-right"><?php echo lang('form_required_information'); ?></em><?php echo lang('share_your_wishlist_title'); ?></h4>

        <div class="content">   
            <div class="control-group">
                <label class="control-label" for="wishlist_customer"><?php echo lang('field_share_wishlist_customer_name'); ?><em>*</em></label>
                <div class="controls">
        			<input type="text" id="wishlist_customer" name="wishlist_customer" value="<?php echo $customers_name; ?>" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="wishlist_from_email"><?php echo lang('field_share_wishlist_customer_email'); ?><em>*</em></label>
                <div class="controls">
                	<input type="text" id="wishlist_from_email" name="wishlist_from_email" value="<?php echo $customers_email; ?>" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="wishlist_emails"><?php echo lang('field_share_wishlist_emails'); ?><em>*</em></label>
                <div class="controls">
					<textarea id="wishlist_emails" rows="5" cols="40" name="wishlist_emails"></textarea>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="wishlist_message"><?php echo lang('field_share_wishlist_message'); ?><em>*</em></label>
                <div class="controls">
                	<textarea id="wishlist_message" rows="5" cols="40" name="wishlist_message"></textarea>
                </div>
            </div>
        </div>   
	</div>
    <div class="submitFormButtons right">
        <button type="submit" class="btn btn-info btn-small pull-right"><i class="icon-ok-sign icon-white"></i> <?php echo lang('button_continue'); ?></button>
    </div>
</form>
<?php endif;?>