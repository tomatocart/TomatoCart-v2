<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
*/
?>

<h1><?php echo lang('shopping_cart_heading'); ?></h1>

<?php 
    if ($has_contents == TRUE) :
?>
<form name="shopping_cart" action="<?php echo site_url('cart_update'); ?>" method="post" class="clearfix">
    <div id="shopping-cart" class="module-box">
		<h6 class="title">
			<span class="icon"><i class="icon-shopping-cart"></i></span>		
            <span><?php echo lang('shopping_cart_heading'); ?></span>
        </h6>
    
        <div class="content clearfix">
        	<table class="table-gradient" width="100%">
        		<thead>
        			<tr>
        				<th class="product"><?php echo lang('table_heading_product'); ?></th>
        				<th colspan="2"><?php echo lang('table_heading_quantity'); ?></th>
        				<th class="price visible-desktop"><?php echo lang('table_heading_price'); ?></th>
        				<th class="total"><?php echo lang('table_heading_total'); ?></th>
        			</tr>
        		</thead>
        		<tbody>
                <?php
                    foreach ($products as $product_id => $product) :
                ?>
                    <tr>
                    	<td class="product">
                            <a class="image visible-desktop" href="<?php echo site_url('product/' . $product['id']); ?>">
    							<img alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" src="<?php echo product_image_url($product['image'], 'mini'); ?>" alt="default thumb" class="thumb" />
                            </a>
                            <div class="info">
                                <a class="name" href="<?php echo site_url('product/' . get_product_id($product['id']));?>"><?php echo $product['name']?></a>
                                <span class="sku"><?php echo lang('field_sku') . ' ' . $product['sku']; ?></span>
                                <?php 
                                    if ( (config('STOCK_CHECK') == '1') && ($product['in_stock'] === FALSE) ) :
                                ?>
                                <span class="markProductOutOfStock"><?php echo config('STOCK_MARK_PRODUCT_OUT_OF_STOCK') ; ?></span>
                                <?php 
                                    endif;
                                ?>
                                
                                <?php 
                                    if (isset($product['variants']) && !empty($product['variants'])) :
                                ?>
                                	<ul class="variants">
                                    <?php 
                                            foreach ($product['variants'] as $variants):  
                                    ?>
                            			<li>- <?php echo $variants['groups_name'];?> : <?php echo $variants['values_name'];?></li>
                                    <?php
                                            endforeach;
                                    ?>
        							</ul>                        
                                <?php 
                                    endif;
                                ?>
                                
                                <?php 
                                    if (isset($products['error'])) {
                                ?>
                                <br /><span class="markProductError"><?php echo $products['error']; ?></span>
                                <?php 
                                    }
                                ?>
                            </div>
                        </td>
                        <td class="quantity">
    						<input type="text" style="width: 20px" size="4" id="products[<?php echo $product_id; ?>]" value="<?php echo $product['quantity'];?>" name="products[<?php echo $product_id; ?>]" />
                        </td>
                        <td class="action">
                            <a class="btn btn-mini" href="<?php echo site_url('cart_delete/' . $product['id']); ?>"><i title="<?php echo lang('button_delete');?>" class="icon-trash"></i></a>
                        </td>
                        <td class="price visible-desktop"><?php echo currencies_display_price($product['final_price'], $product['tax_class_id']); ?></td>
                        <td class="total"><?php echo currencies_display_price($product['final_price'], $product['tax_class_id'], $product['quantity']); ?></td>
                    </tr>
                <?php
                    endforeach;
                ?>
            	</tbody>
            </table>
            <ul class="totals">
            <?php
                foreach ($order_totals as $module) :
            ?>
    			<li>
                	<label><?php echo $module['title']; ?></label>
    				<span><?php echo $module['text']; ?>&nbsp;</span>
    			</<li>
            <?php
                endforeach;
            ?>
            </ul>
        </div>
<?php
    if ( (config('STOCK_CHECK') == '1') && ($has_stock === FALSE) ) :
        if (config('STOCK_ALLOW_CHECKOUT') == '1') :
?>
          <p class="stockWarning" align="center"><?php echo sprintf(lang('products_out_of_stock_checkout_possible'), config('STOCK_MARK_PRODUCT_OUT_OF_STOCK')); ?></p>
<?php 
        else :
?>
          <p class="stockWarning" align="center"><?php echo sprintf(lang('products_out_of_stock_checkout_not_possible'), config('STOCK_MARK_PRODUCT_OUT_OF_STOCK')); ?></p>
<?php 
        endif;
    endif;
?>
    </div>
    
    <div class="row-fluid submitFormButtons">
        <div class="span4"><a href="<?php echo base_url(); ?>" class="btn btn-small btn-info"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue_shopping'); ?></a></div>
        
        <div class="span5"><button type="submit" class="btn btn-small btn-info"><i class="icon-refresh icon-white"></i> <?php echo lang('button_update_cart'); ?></button></div>

        <div class="span3"><a class="btn btn-small btn-small btn-info pull-right" href="<?php echo site_url('checkout'); ?>"><i class="icon-ok-sign icon-white"></i> <?php echo lang('button_checkout');?></a></div>
    </div>
</form>
<?php 
    else : 
?>

<p><?php echo lang('shopping_cart_empty'); ?></p>

<div class="submitFormButtons" style="text-align: right;">
	<a href="<?php echo base_url(); ?>" class="btn btn-small btn-info"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></a>
</div>

<?php 
    endif; 
?>