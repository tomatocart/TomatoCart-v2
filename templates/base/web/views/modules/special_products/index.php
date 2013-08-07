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

<div class="box">
    <h4><?php echo lang('box_specials_heading'); ?></h4>
    
    <ul class="products-list grid clearfix">
    <?php 
        foreach ($products as $product):
    ?>
        <li class="clearfix">
    		<div class="specials-banner"></div>
    		
            <div class="left">
                 <a href="<?php echo site_url('product/' . $product['products_id']); ?>">
                      <img alt="<?php echo $product['products_name']; ?>" title="<?php echo $product['products_name']; ?>" src="<?php echo product_image_url($product['products_image']); ?>" alt="default thumb" class="thumb" />
                 </a>
                <h3>
                	<a href="<?php echo site_url('product/' . $product['products_id']); ?>">
                        <?php echo $product['products_name']; ?>
                    </a>
                </h3>
                <p class="description">
                    <?php echo $product['short_description']; ?>
                </p>
            </div>
            <div class="right">
                <span class="price">
            		<s><?php echo currencies_display_price($product['products_price'], $product['tax_class_id']); ?></s>
            		<font class="special"><?php echo currencies_display_price($product['special_price'], $product['tax_class_id']); ?></font>
                </span>
                <span class="buttons">
                    <a class="btn btn-small btn-info" href="<?php echo site_url('cart_add/' . $product['products_id']); ?>">
                    	<i class="icon-shopping-cart icon-white "></i> 
                    	<?php echo lang('button_buy_now'); ?>
                    </a><br />
                    <a class="wishlist" href="<?php echo site_url('wishlist/add/' . $product['products_id']); ?>"><?php echo lang('add_to_wishlist'); ?></a><br />
                    <a class="compare" href="<?php echo site_url('compare/add/' . $product['products_id']); ?>"><?php echo lang('add_to_compare'); ?></a>
                </span>
            </div>
        </li>
    <?php 
        endforeach;
    ?>
    </ul>
</div>