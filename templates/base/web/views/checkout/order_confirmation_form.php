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

<div class="moduleBox">
	<div class="content">
    	<table width="100%" cellspacing="0" cellpadding="2">
			<tr>
				<td width="50%" valign="top">
					<p><b><?php echo lang('order_delivery_address_title'); ?></b></p>
                <?php
                    if ($has_shipping_address) :
                ?>
					<p><?php echo address_format($shipping_address, '<br />'); ?></p>
          
          			<p><b><?php echo lang('order_shipping_method_title'); ?></b></p>

                    <?php
                        if ($has_shipping_method) :
                    ?>
					<p><?php echo $shipping_method; ?></p>
                    <?php
                        endif;
                    ?>
                <?php 
                    endif;
                ?>
				</td>
				<td valign="top">
                    <p><b><?php echo lang('order_billing_address_title'); ?></b></p>
                    <p><?php echo address_format($billing_address, '<br />'); ?></p>
                    
                    <p><b><?php echo lang('order_payment_method_title'); ?></b></p>
                    <p><?php echo $billing_method; ?></p>
    			</td>
            </tr>
            <tr>
            	<td width="100%" colspan="2" valign="top">
					<div style="border: 1px; border-style: solid; border-color: #CCCCCC; background-color: #FBFBFB; padding: 5px;">
						<table width="100%" cellspacing="0" cellpadding="2">
                        <?php
                            if ($number_of_tax_groups > 1) :
                        ?>
                            <tr>
                                <td colspan="2"><b><?php echo lang('order_products_title'); ?></b></td>
                                <td align="right"><b><?php echo lang('order_tax_title'); ?></b></td>
                                <td align="right"><b><?php echo lang('order_total_title'); ?></b></td>
                            </tr>
                        <?php
                            else :
                        ?>
                            <tr>
                            	<td colspan="3"><?php echo '<b>' . lang('order_products_title') . '</b> '; ?></td>
                            </tr>

                        <?php
                            endif;
                            
                            foreach ($products as $product) :
                        ?>
							<tr>
								<td align="right" valign="top" width="30"><?php echo $product['quantity']; ?>&nbsp;x&nbsp;</td>
                                <td valign="top"><?php echo $product['name']; ?>
						    <?php 
                                //if ( (STOCK_CHECK == '1') && !$osC_ShoppingCart->isInStock($product['id']) ) {
                                  //echo '<span class="markProductOutOfStock">' . config('STOCK_MARK_PRODUCT_OUT_OF_STOCK') . '</span>';
                                //}
                                
                                if ( (isset($product['variants'])) && (sizeof($product['variants']) > 0) ) :
                                    foreach ($product['variants'] as $variants) :
                                        echo '<br /><nobr><small>&nbsp;<i> - ' . $variants['groups_name'] . ': ' . $variants['values_name'] . '</i></small></nobr>';
                                    endforeach;
                                endif;
                            ?>
                            	</td>
                            <?php     
                                if ($number_of_tax_groups > 1) :
                            ?>
								<td valign="top" align="right">
									<?php 
									    $shopping_cart = get_shopping_cart();
									    echo display_tax_rate_value(get_tax_rate($product['tax_class_id'], $shopping_cart->get_taxing_address('country_id'), $shopping_cart->get_taxing_address('zone_id')));
									?>
                            <?php 
                                endif;
                            ?>
                            	<td align="right" valign="top"><?php echo currencies_display_price($product['final_price'], $product['tax_class_id'], $product['quantity']); ?></td>
                            </tr>
                            <?php     
                                endforeach;
                            ?>
                        </table>
            
                        <table width="100%" cellspacing="0" cellpadding="2">
                        <?php
                            foreach ($order_totals as $module) :
                        ?>
							<tr>
                            	<td align="right"><?php echo $module['title']; ?></td>
                                <td align="right"><?php echo $module['text']; ?></td>
                            </tr>
                        <?php
                            endforeach;
                        ?>
            			</table>
					</div>
				</td>      
			</tr>
		</table>
	</div>
</div>

<?php
    if ($payment_comments !== FALSE) :
?>

<div class="moduleBox">
    <h6><?php echo lang('order_comments_title'); ?></h6>
    
    <div class="content">
        <?php echo nl2br($payment_comments) . form_hidden(array('name' => 'payment_comments', 'value' => $payment_comments)); ?>
    </div>
</div>
<?php
    endif;
?>

<div class="submitFormButtons clearfix">
	<form name="checkout_confirmation" action="<?php echo $form_action_url; ?>" method="post">
	
        <?php
            if ($has_active_payment) :
                if ($confirmation) :
        ?>
        <div class="moduleBox">
    		<h6><?php echo lang('order_payment_information_title'); ?></h6>
        
    		<div class="content">
    			<p><?php echo empty($confirmation['title']) ? '' : $confirmation['title']; ?></p>
        
                <?php
                    if (isset($confirmation['fields'])) :
                ?>
            	<table cellspacing="3" cellpadding="2">
                    <?php
                        for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) :
                    ?>
                    <tr>
                        <td width="10">&nbsp;</td>
                        <td><?php echo $confirmation['fields'][$i]['title']; ?></td>
                        <td width="10">&nbsp;</td>
                        <td><?php echo $confirmation['fields'][$i]['field']; ?></td>
                    </tr>
                    <?php
                        endfor;
                    ?>
    			</table>
                <?php
                    endif;
                ?>
                
                <?php 
                    if (isset($confirmation['text'])) :
                ?>
                <p><?php echo $confirmation['text']; ?></p>
                <?php
                    endif;
                ?>
            </div>
    	</div>
    
        <?php
                endif;
            endif;
          
            if ($has_active_payment) :
                echo $process_button;
            endif;
        ?>
        <div class="control-group">
            <div class="controls">
          		<button type="submit" class="btn btn-small btn-info pull-right" id="btn-confirm-order"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></button>
            </div>
        </div>
	</form>
</div>