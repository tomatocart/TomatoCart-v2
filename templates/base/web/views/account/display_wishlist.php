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

<h1>
<?php echo lang('wishlist_heading');?>
</h1>

<div class="module-box">
	<?php 
	    if (is_array($products) && !empty($products)):
	?>
	<table table class="table table-hover table-striped">
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
                <td width="96" class="visible-desktop"><?php echo $product['date_added']; ?></td>
                <td class="center btn-toolbar visible-desktop">
					<a href="<?php echo site_url('cart_add/' . $product['products_id']); ?>" class="btn btn-mini"><?php echo lang('button_buy_now'); ?></a>
                </td>
            </tr>   
			<?php 
			    endforeach;
			?>
		</tbody>
	</table>

    <div class="submitFormButtons right clearfix">
	    <a href="<?php echo site_url(); ?>" class="btn btn-info pull-left"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_continue'); ?></a>
    </div>
	<?php 
	    else : 
	?>
	<div class="content btop">
		<p><?php echo lang('wishlist_empty'); ?> </p>
	</div>

    <div class="submitFormButtons right clearfix">
	    <a href="<?php echo site_url(); ?>" class="btn btn-info pull-left"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_continue'); ?></a>
    </div>
	<?php 
	    endif;
	?>
</div>
