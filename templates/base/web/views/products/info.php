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

<h1><?php echo $name; ?></h1>

<div id="product-info" class="module-box" style="position: relative">
	<?php 
	    if ($is_specials === TRUE):
	?>
		<div class="specials-banner"></div>
	<?php   
	    elseif ($is_featured === TRUE):  
	?>
		<div class="featured-banner"></div>
	<?php   
	    endif;
	?>
    	
    <div class="content row-fluid clearfix">
        
        <!--Product Images-->
        <div class="span5 clearfix">
            <div id="product-images">
                <a id="default-image" href="<?php echo product_image_url($default_image, 'large'); ?>" rel="showTitle:false, smoothMove:15,zoomWidth:400, zoomHeight:300, adjustY:0, adjustX:10" class="cloud-zoom">
                	<img title="<?php echo $name; ?>" alt="<?php echo $name; ?>" src="<?php echo product_image_url($default_image, 'product_info'); ?>">
                </a>
            <?php 
                foreach ($images as $image): 
            ?>
              	<a rel="useZoom: 'default-image', smallImage: '<?php echo product_image_url($image['image'], 'product_info'); ?>' " class="cloud-zoom-gallery" href="<?php echo product_image_url($image['image'], 'large'); ?>">
    				<img title="<?php echo $name; ?>" alt="<?php echo $name; ?>" src="<?php echo product_image_url($image['image'], 'mini'); ?>" />
              	</a>
    	    <?php 
    	        endforeach; 
    	    ?>
    	    </div>
        </div>
        <!--END: Product Images-->
        
        <!--Add to Cart Form-->
        <form class="form-inline span7 clearfix" id="cart-quantity" name="cart-quantity" action="<?php echo site_url('cart_add/' . $products_id); ?>" method="post">
        	<div id="product-info">
        		<div class="price-info">
        			<?php 
        			    if ($is_specials === TRUE):
        			?>
        			<span class="price"><s><?php echo currencies_display_price($price, $tax_class_id); ?></s>&nbsp;&nbsp;<font class="special"><?php echo currencies_display_price($specials_price); ?></font></span>
        			<?php 
        			    else: 
        			?>
        			<span class="price"><?php echo currencies_display_price($price, $tax_class_id); ?></span>
        			<?php 
        			    endif;
        			?>
        			<span class="tax"><?php echo ( (config('DISPLAY_PRICE_WITH_TAX') == '1') ? lang('including_tax') : '' ); ?></span>
        			<img src="<?php echo image_url('stars_' . $rating . '.png'); ?>" alt="<?php echo sprintf(lang('rating_of_5_stars'), $rating); ?>" title="<?php echo sprintf(lang('rating_of_5_stars'), $rating); ?>" />
        		</div>
        		<div class="divider"></div>
            <?php 
                if (!empty($manufacturers_name)):
            ?>
                <div>
                  	<label><?php echo lang('field_products_manufacturer'); ?></label>
                  	<span><?php echo $manufacturers_name; ?></span>
                </div>
            <?php 
                endif;
            ?>
                <div>
                  	<label><?php echo lang('field_sku'); ?></label>
                  	<span><?php echo $sku; ?></span>
                </div>
                <div>
                  	<label><?php echo lang('field_availability'); ?></label>
                  	<span><?php echo ($quantity > 0) ? lang('in_stock') : lang('out_of_stock'); ?></span>
                </div>
            <?php 
                if (config('PRODUCT_INFO_QUANTITY') == '1') :
            ?>
                <div>
                  	<label><?php echo lang('field_quantity'); ?></label>
                	<span id="product-info-qty"><?php echo $quantity . '&nbsp;' . $unit_class; ?></span>
                </div>
            <?php 
                endif; 
            ?>
            
            <?php 
                if (config('PRODUCT_INFO_MOQ') == '1') :
            ?>
                <div>
                  	<label><?php echo lang('field_moq'); ?></label>
                	<span><?php echo $product['moq'] . '&nbsp;' . $unit_class; ?></span>
                </div>
            <?php 
                endif; 
            ?>
            
            <?php 
                if (config('PRODUCT_INFO_ORDER_INCREMENT') == '1') :
            ?>
                <div>
                  	<label><?php echo lang('field_order_increment'); ?></label>
                	<span><?php echo $product['orderincrement'] . '&nbsp;' . $unit_class; ?></span>
                </div>
            <?php 
                endif; 
            ?>
            
            <?php 
                if (isset($url) && !empty($url)) :
            ?>
                <div>
                  <span><?php echo sprintf(lang('go_to_external_products_webpage'), site_url('products/info/goto/' . urlencode($url))); ?></span>
                </div>
            <?php 
                endif; 
            ?>
            
            <?php 
                if (isset($date_available) && !empty($date_available)) :
                    if (strtotime($date_available) > now()) :
            ?>
                <div>
                  <span><?php echo sprintf(lang('date_availability'), get_date_long($date_available)); ?></span>
                </div>
            <?php 
                    endif;
                endif; 
            ?>
            
            <?php
                if (isset($attributes) && is_array($attribute)):
                    foreach($attributes as $attribute):
            ?>
                <div>          
                  	<label><?php echo $attribute['name']; ?>:</label>
                	<span><?php echo $attribute['value']; ?></span>
                </div>
            <?php
                    endforeach;
                endif;
            ?>  
                <div class="divider"></div>
            <!--Variants-->
            <?php
                if (isset($combobox_array) && is_array($combobox_array)):
                    foreach($combobox_array as $groups_name => $combobox):
            ?>
           		<div class="variant-comb">
                    <label><?php echo $groups_name; ?> :</label>
                    <span><?php echo $combobox; ?></span>
                 </div>
            <?php
                endforeach;
              endif;
            ?>  
            <!--Variants-->
                <div class="center">
                	<b><?php echo lang('field_short_quantity'); ?></b>&nbsp;
                	<input type="text" size="3" value="<?php echo $moq?>" id="quantity" name="quantity">&nbsp;
                	<button class="btn btn-info" title="<?php echo lang('button_add_to_cart'); ?>"><i class="icon-shopping-cart icon-white "></i> <?php echo lang('button_add_to_cart'); ?></button>
                </div>
                <div class="center">
                	<a href="<?php echo site_url('compare/add/' . $products_id); ?>"><?php echo lang('add_to_compare'); ?></a>
            		&nbsp;<span>|</span>&nbsp;
            		<a href="<?php echo site_url('wishlist/add/' . $products_id); ?>"><?php echo lang('add_to_wishlist'); ?></a>
                </div>
                <div class="divider"></div>
                <div class="description">
                    <p><?php echo $short_description; ?></p>
                </div>
        	</div>
        </form>
        
        <!--END: Add to Cart Form-->
    </div>
</div>

<div class=" clearfix">
	<ul id="product-info-tab" class="nav nav-tabs">
        <li class="<?php echo ($tab == 'description') ? 'active' : ''; ?>"><a href="#tab-description" data-toggle="tab" class="active"><?php echo lang('section_heading_products_description'); ?></a></li>
        <li class="<?php echo ($tab == 'reviews') ? 'active' : ''; ?>"><a href="#tab-reviews" data-toggle="tab" data-target="#tab-reviews"><?php echo lang('section_heading_reviews') . ' (' . $review_count . ')'; ?></a></li>
        <li class="<?php echo ($tab == 'accessories') ? 'active' : ''; ?>"><a href="#tab-accessories" data-toggle="tab" data-target="#tab-accessories"><?php echo lang('section_heading_products_accessories'); ?></a></li>
	</ul>

	<div id="product-info-tab-content" class="tab-content">
        <div class="tab-pane <?php echo ($tab == 'description') ? 'active' : ''; ?>" id="tab-description">
        	<?php echo $description; ?>
        </div>

        <div class="tab-pane <?php echo ($tab == 'reviews') ? 'active' : ''; ?>" id="tab-reviews">
            <p><?php echo toc_validation_errors('reviews'); ?></p>
            
            <?php 
                if (isset($reviews) && (count($reviews) > 0)) :
                    foreach ($reviews as $review): 
            ?>
            <dl class="review">
    			<dt>
    				<img title="<?php echo sprintf(lang('rating_of_5_stars'), $review['reviews_rating']); ?>" alt="<?php echo sprintf(lang('rating_of_5_stars'), $review['reviews_rating']); ?>" src="<?php echo image_url('stars_' . $review['reviews_rating'] . '.png'); ?>">
                	&nbsp;&nbsp;&nbsp;&nbsp;<?php echo sprintf(lang('reviewed_by'), $review['customers_name']);?>&nbsp;&nbsp;<?php echo lang('field_posted_on');?>&nbsp;(<?php echo get_date_short($review['date_added']);?>)
              	</dt>
              	<dd>
              		<?php 
              		    if (isset($review['ratings']) && (count($review['ratings']) > 0)):
              		        $customers_ratings = $review['ratings'];
              		?>
              		<table class="customers-ratings">
              			<?php 
              			    foreach($customers_ratings as $rating):
              			?>
              			<tr>
              				<td class="name"><?php echo $rating['name']; ?></td>
              				<td><img src="<?php echo image_url('stars_' . $rating['value'] . '.png'); ?>" alt="<?php echo sprintf(lang('rating_of_5_stars'), $rating['value']); ?>" title="<?php echo sprintf(lang('rating_of_5_stars'), $rating['value']); ?>" /></td>
              			</tr>
              			<?php 
              			    endforeach;
              			?>
              		</table>
                    <?php 
                        endif;
                    ?>
                    <p><?php echo $review['reviews_text'];?></p>
            	</dd>              
            </dl>
            <?php 
                    endforeach;
                else :
                    echo '<p>' . lang('no_review') . '</p>';
                endif;
            ?>
        
            <hr class="divider" />
            
            <h3><?php echo lang('heading_write_review'); ?></h3>
            
            <?php 
                if(is_logged_on() === FALSE):
            ?>
                <p><?php echo sprintf(lang('login_to_write_review'), site_url('account/login')); ?></p>
            <?php 
                else:
            ?>        
            <p><?php echo lang('introduction_rating');?></p>
            
            <form method="post" action="<?php echo site_url('products/info/save_review/' . $products_id); ?>" name="newReview" id="frmReviews">
              	<?php
              	    if (isset($ratings) && (count($ratings) == 0)) :
              	?>
                <p>
                	<b><?php echo lang('field_review_rating'); ?></b>&nbsp;&nbsp;&nbsp;
                    
              	    <?php echo lang('review_lowest_rating_title');?> 
                    <input type="radio" value="1" id="rating1" name="rating" <?php echo set_radio('rating', '1'); ?> />&nbsp;&nbsp;
                    <input type="radio" value="2" id="rating2" name="rating" <?php echo set_radio('rating', '2'); ?> />&nbsp;&nbsp;
                    <input type="radio" value="3" id="rating3" name="rating" <?php echo set_radio('rating', '3', TRUE); ?> />&nbsp;&nbsp;
                    <input type="radio" value="4" id="rating4" name="rating" <?php echo set_radio('rating', '4'); ?> />&nbsp;&nbsp;
                    <input type="radio" value="5" id="rating5" name="rating" <?php echo set_radio('rating', '5'); ?> /> 
                    <?php echo lang('review_highest_rating_title');?>
                </p>
    				<input type="hidden" id="rat_flag" name="rat_flag" value="0" />
              	<?php 
              	    else :
              	?>
                    <table class="ratings" border="1" cellspacing="0" cellpadding="0">
                      <thead>
                        <tr>
                          <td width="45%">&nbsp;</td>
                          <td><?php echo lang('1_star'); ?></td>
                          <td><?php echo lang('2_stars'); ?></td>
                          <td><?php echo lang('3_stars'); ?></td>
                          <td><?php echo lang('4_stars'); ?></td>
                          <td><?php echo lang('5_stars'); ?></td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                            $i = 0;
                            foreach ( $ratings as $key => $value ):
                        ?>
                          <tr>
                            <td><?php echo $value;?></td>
                            <td><input type="radio" value="1" name="rating_<?php echo $key; ?>" title="<?php echo lang('1_star'); ?>" <?php echo set_radio('rating_' . $key, '1'); ?> /></td>
                            <td><input type="radio" value="2" name="rating_<?php echo $key; ?>" title="<?php echo lang('2_stars'); ?>" <?php echo set_radio('rating_' . $key, '2'); ?> /></td>
                            <td><input type="radio" value="3" name="rating_<?php echo $key; ?>" title="<?php echo lang('2_stars'); ?>" <?php echo set_radio('rating_' . $key, '3', TRUE); ?> /></td>
                            <td><input type="radio" value="4" name="rating_<?php echo $key; ?>" title="<?php echo lang('2_stars'); ?>" <?php echo set_radio('rating_' . $key, '4'); ?> /></td>
                            <td><input type="radio" value="5" name="rating_<?php echo $key; ?>" title="<?php echo lang('2_stars'); ?>" <?php echo set_radio('rating_' . $key, '5'); ?> /></td>
                          </tr>
                        <?php 
                                $i++;
                            endforeach;
                        ?>
    	              </tbody>
                    </table>
                    <input type="hidden" value="<?php echo $i; ?>" name="radio_count" id="radio_count">
                <?php 
              	    endif;
              	?>
              
              	<h6><?php echo lang('field_review');?></h6>
              
              	<textarea id="review" rows="5" cols="45" name="review"><?php echo set_value('review'); ?></textarea>            
                
                <div class="submitFormButtons">
                    <button type="submit" class="btn small" name="<?php echo lang('submit_reviews');?>"><?php echo lang('submit_reviews');?></button>
                </div>
            </form>
            <?php 
                endif;
            ?>
        </div>
        
        <div class="tab-pane <?php echo ($tab == 'accessories') ? 'active' : ''; ?>" id="tab-accessories">
        	<?php 
        	    if (is_array($accessories)):
        	?>
            <ul class="products-list grid clearfix">
                <?php 
                    foreach ($accessories as $accessory):
                        $product = load_product_library($accessory['accessories_id']);
                ?>
                <li class="clearfix">
                    <div class="left">
                         <a href="<?php echo site_url('product/' . $product->get_id()); ?>">
                              <img alt="<?php echo $product->get_title(); ?>" title="<?php echo $product->get_title(); ?>" src="<?php echo product_image_url($product->get_image()); ?>" alt="default thumb" class="thumb" />
                         </a>
                        <h3>
                        	<a href="<?php echo site_url('product/' . $product->get_id()); ?>">
                                <?php echo $product->get_title(); ?>
                            </a>
                        </h3>
                        <p class="description">
                            <?php echo $product->get_short_description(); ?>
                        </p>
                    	<?php 
                    	    if ($product->is_specials()):
                    	?>
                    		<div class="specials-banner"></div>
                    	<?php   
                    	    elseif ($product->is_featured()):  
                    	?>
                    		<div class="featured-banner"></div>
                    	<?php   
                    	    endif;
                    	?>
                    </div>
                    <div class="right">
                        <span class="price">
                        	<?php 
                        	    if ($product->is_specials()):
                        	?>
                        		<s><?php echo currencies_display_price($product->get_specials_price()); ?></s>
                        	<?php     
                        	    endif;
                        	?>
                            <?php echo currencies_display_price($product->get_price(), $product->get_tax_class_id()); ?></span>
                        <span class="buttons">
                            <a class="btn btn-small btn-info" href="<?php echo site_url('cart_add/' . $product->get_id()); ?>">
                            	<i class="icon-shopping-cart icon-white "></i> 
                            	<?php echo lang('button_buy_now'); ?>
                            </a><br />
                            <a class="wishlist" href="<?php echo site_url('wishlist/add/' . $product->get_id()); ?>"><?php echo lang('add_to_wishlist'); ?></a><br />
                            <a class="compare" href="<?php echo site_url('compare/add/' . $product->get_id()); ?>"><?php echo lang('add_to_compare'); ?></a>
                        </span>
                    </div>
                </li>
                <?php 
                    endforeach;
                ?>
            </ul>
        	<?php 
        	    endif;
        	?>
        </div>

    </div>
</div> 

<script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/cloud-zoom/cloud-zoom.1.0.2.js"></script>