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

    $pagesize_array = array(12 => 12, 24 => 24, 36 => 36, 48 => 48);
    $sort_array = array('name|asc' => lang('Name (A ~ Z)'), 
                        'name|desc' => lang('Name (Z ~ A)'), 
                        'price|asc' => lang('Price (L ~ H)'), 
                        'price|desc' => lang('Price (H ~ L)'), 
                        'sku|asc' => lang('SKU (L ~ H)'), 
                        'sku|desc' => lang('SKU (H ~ L)'));
?>

<h1><?php echo lang('search_results_heading'); ?></h1>

<?php 
    if (count($products) > 0) : 
?>
	<div class="products-listing-action">
		<form id="products-filter-top" class="form-inline" action="<?php echo $filter_form_action; ?>" method="get">
			<div class="row-fluid">
				<div class="span4">
            	    <label><?php echo lang('label_products_per_page'); ?></label>&nbsp;
            	    <?php echo form_dropdown('pagesize', $pagesize_array, $pagesize, 'id="pagesize-top"'); ?>
				</div>
				<div class="span4" align="center">
            	    <label><?php echo lang('label_select_view'); ?></label>&nbsp;
                    <div class="btn-group">
                    	<a class="btn btn-small view-type <?php echo (($view == 'list') ? 'active' : ''); ?>" href="javascript:;"><i class="icon-th-list"></i></a> 
                    	/ 
                    	<a class="btn btn-small view-type <?php echo (($view == 'grid') ? 'active' : ''); ?>" href="javascript:;"><i class="icon-th"></i></a>
						<input type="hidden" id="view-top" name="view" value="<?php echo $view; ?>" />
                    </div>
				</div>
				<div class="span4">
					<div class="pull-right">
                	    <label><?php echo lang('label_sorting'); ?></label>&nbsp;
                        <?php echo form_dropdown('sort', $sort_array, $sort, 'id="sort-top"'); ?>
					</div>
				</div>
			</div>
			<?php 
			    if (isset($search_filters) && is_array($search_filters)):
			        foreach ($search_filters as $name => $value):
			            echo form_hidden($name, $value);
			        endforeach;
			    endif;
			?>
        </form>
        <?php 
            if (!empty($links)):
        ?>
        <div class="seperator"></div>
        <div class="row-fluid">
        	<div class="span6 total-pages">
        		<?php  echo $total_pages; ?>
        	</div>
        	<div class="span6">
                <div class="pagination clearfix">
                	<?php  echo $links; ?>
                </div>        	
        	</div>
        </div>
        <?php 
            endif;
        ?>
	</div>
	
    <ul class="products-list <?php echo $view; ?> clearfix">
        <?php 
            foreach($products as $product):
        ?>
        <li class="clearfix">
    	<?php 
    	    if ($product['is_specials'] === TRUE):
    	?>
    		<div class="specials-banner"></div>
    	<?php   
    	    elseif ($product['is_featured'] === TRUE):  
    	?>
    		<div class="featured-banner"></div>
    	<?php   
    	    endif;
    	?>
            <div class="left">
                 <a href="<?php echo site_url('product/' . $product['products_id']); ?>">
                      <img alt="<?php echo $product['product_name']; ?>" title="<?php echo $product['product_name']; ?>" src="<?php echo product_image_url($product['product_image']); ?>" alt="default thumb" class="thumb" />
                 </a>
                <h3>
                	<a href="<?php echo site_url('product/' . $product['products_id']); ?>">
                        <?php echo $product['product_name']; ?>
                    </a>
                </h3>
                <p class="description">
                    <?php echo $product['short_description']; ?>
                </p>
            </div>
            <div class="right">
                <span class="price">
                	<?php 
                	    if ($product['is_specials'] === TRUE):
                	?>
                		<s><?php echo currencies_format($product['product_price']); ?></s>
                		<font class="special"><?php echo currencies_format($product['specials_price']); ?></font>
                	<?php 
                	    else:
                	?>
                    <?php echo currencies_format($product['product_price']); ?>
                	<?php     
                	    endif;
                	?>
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
	<div class="products-listing-action">
		<form id="products-filter-bottom" class="form-inline" action="<?php echo $filter_form_action; ?>" method="get">
			<div class="row-fluid">
				<div class="span4">
            	    <label><?php echo lang('label_products_per_page'); ?></label>&nbsp;
            	    <?php echo form_dropdown('pagesize', $pagesize_array, $pagesize, 'id="pagesize-bottom"'); ?>
				</div>
				<div class="span4" align="center">
            	    <label><?php echo lang('label_select_view'); ?></label>&nbsp;
                    <div class="btn-group">
                    	<a class="btn btn-small view-type <?php echo (($view == 'list') ? 'active' : ''); ?>" href="javascript:;"><i class="icon-th-list"></i></a> 
                    	/ 
                    	<a class="btn btn-small view-type <?php echo (($view == 'grid') ? 'active' : ''); ?>" href="javascript:;"><i class="icon-th"></i></a>
						<input type="hidden" id="view-bottom" name="view" value="<?php echo $view; ?>" />
                    </div>							
				</div>
				<div class="span4">
					<div class="pull-right">
                	    <label><?php echo lang('label_sorting'); ?></label>&nbsp;
                        <?php echo form_dropdown('sort', $sort_array, $sort, 'id="sort-bottom"'); ?>
					</div>
				</div>
			</div>
			<?php 
			    if (isset($search_filters) && is_array($search_filters)):
			        foreach ($search_filters as $name => $value):
			            echo form_hidden($name, $value);
			        endforeach;
			    endif;
			?>
        </form>
        <?php 
            if (!empty($links)):
        ?>
        <div class="seperator"></div>
        <div class="row-fluid">
        	<div class="span6 total-pages">
        		<?php  echo $total_pages; ?>
        	</div>
        	<div class="span6">
                <div class="pagination clearfix">
                	<?php  echo $links; ?>
                </div>        	
        	</div>
        </div>
        <?php 
            endif;
        ?>
	</div>
	<script type="text/javascript">
    <!--
    $(function() {
		$('#pagesize-top, #pagesize-bottom, #sort-top, #sort-bottom').select2().on('change', function(o) {
		    var pos = '-top';
			if (o.currentTarget.id.indexOf('-bottom') > 0) {
			    pos = '-bottom';
			}
			$('#products-filter' + pos).submit();
		});

    	$('.view-type').on('click', function(object) {
        	var $this = $(this);
        	
        	if (!$this.hasClass('active')) {
            	var view = 'grid';
				if ($this.find('.icon-th-list').length > 0) {
					var view = 'list';
				}

				$this.parent().find('input').val(view);
				$this.parents('form').submit();
            }
    	});
    });
    //-->
    </script>
<?php 
    endif; 
?>
<div class="controls clearfix">
    <a class="btn btn-small btn-info pull-right" href="<?php echo site_url('search'); ?>"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_back'); ?></a>
</div>
