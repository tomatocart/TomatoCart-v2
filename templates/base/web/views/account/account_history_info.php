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


<h1><?php echo lang('orders_heading'); ?></h1>

<h6><span class="pull-right"><?php echo lang('order_total_heading') . ' ' . $info['total']; ?></span><?php echo  lang('order_date_heading') . ' ' . get_date_short($date_purchased) . ' <small>(' . $orders_status_name . ')</small>'; ?></h6>

<div class="module-box">
	<div class="row-fluid">
        <div class="span6">
            <h6><?php echo lang('order_billing_address_title'); ?></h6>
            
            <p><?php echo address_format($billing, '<br />'); ?></p>
            
            <h6><?php echo lang('order_payment_method_title'); ?></h6>
            
            <p><?php echo $payment_method; ?></p>
        </div>
        <div class="span6">
            <?php
              if ($delivery != FALSE):
            ?>
            <h6><?php echo lang('order_delivery_address_title'); ?></h6>
            
            <p><?php echo address_format($delivery, '<br />'); ?></p>
        
                <?php
                    if (!empty($info['shipping_method'])) :
                ?>
            
            <h6><?php echo lang('order_shipping_method_title'); ?></h6>
            
            <p><?php echo $info['shipping_method']; ?></p>
            <?php
                endif;
            
                if (!empty($info['tracking_no'])) :
            ?>    
            <h6><?php echo lang('order_shipping_tracking_no_title'); ?></h6>
            
            <p><?php echo $info['tracking_no']; ?></p>
            <?php
                endif;
            endif;
            ?>
        </div>
	</div>
</div>

<h6><?php echo lang('order_products_title'); ?></h6>

<div class="module-box">
    <table width="100%" cellspacing="0" cellpadding="2">

    <?php
        if (isset($info['tax_groups']) && sizeof($info['tax_groups']) > 1) :
    ?>

      <tr>
        <td colspan="2"><h6><?php echo lang('order_products_title'); ?></h6></td>
        <td align="right"><h6><?php echo lang('order_tax_title'); ?></h6></td>
        <td align="right"><h6><?php echo lang('order_total_title'); ?></h6></td>
      </tr>
      
    <?php
        else :
    ?>

      <tr>
        <td colspan="3"></td>
      </tr>

    <?php
        endif;
    
        if (isset($products)) :
            foreach ($products as $product) :
    ?>
		<tr>
			<td align="right" valign="top" width="30"><?php echo $product['qty'] . '&nbsp;x'; ?></td>
			<td valign="top">
				<?php echo $product['name']; ?>

                <?php 
                    if (isset($product['variants']) && (sizeof($product['variants']) > 0)) :
                        foreach ($product['variants'] as $variant) :
                ?>
				<br /><nobr><small>&nbsp;<i> - <?php echo $variant['groups_name'] . ': ' . $variant['values_name']; ?></i></small></nobr>
                <?php
                        endforeach;
                    endif;
                ?>
            </td>
            <?php 
                if (isset($info['tax_groups']) && sizeof($info['tax_groups']) > 1) :
            ?>
            <td valign="top" align="right"><?php echo display_tax_rate_value($product['tax']); ?></td>
            <?php 
                endif;
            ?>
            
            <td align="right" valign="top"><?php echo currencies_display_price_with_tax_rate($product['final_price'], $product['tax'], $product['qty'], $info['currency'], $info['currency_value']); ?></td>
        </tr>
    <?php 
            endforeach;
        endif;
    ?>

    </table>

    <p>&nbsp;</p>

    <table width="100%" cellspacing="0" cellpadding="2">
        <?php
            foreach ($totals as $total) :
        ?>
    	<tr>
        	<td align="right"><?php echo $total['title']; ?></td>
         	<td align="right" width="100"><?php echo $total['text']; ?></td>
		</tr>
        <?php
            endforeach;
        ?>
    </table>
</div>

<?php
    if ( !empty($order->info['wrapping_message']) ) :
?>
<h6><?php echo lang('gift_wrapping_message_heading'); ?></h6>

<div class="module-box">
	<?php echo $order->info['wrapping_message']; ?>
</div>
<?php
    endif;
?>

<?php
  if (isset($status_history)) :
?>
<h6><?php echo lang('order_history_heading'); ?></h6>

<div class="module-box">
	<table width="100%" cellspacing="0" cellpadding="2">
        <?php
            foreach($status_history as $status):
        ?>
		<tr>
			<td valign="top" width="70"><?php echo get_date_short($status['date_added']); ?></td>
			<td valign="top" width="70"><?php echo $status['orders_status_name']; ?></td>
			<td valign="top" width="70"><?php echo (!empty($status['comments']) ? nl2br($status['comments']) : '&nbsp;'); ?></td>
		</tr>
        <?php
            endforeach;
        ?>
    </table>
</div>

<?php
  endif;
?>

<div class="control-group clearfix">
	<a href="<?php echo site_url('account/orders'); ?>" class="btn btn-info btn-small pull-left"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_back'); ?></a>
</div>