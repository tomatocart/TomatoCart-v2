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

$step = 1;
?>

<h1><?php echo lang('checkout')?></h1>

<?php echo toc_validation_errors('checkout'); ?>

<div class="accordion" id="checkoutForm"> 
    <div class="accordion-group" id="checkoutMethodForm">
        <div class="accordion-heading">
        	<a class="accordion-toggle" href="#checkoutMethodFormBody">
        		<?php echo $step++ . '. ' . lang('checkout_method'); ?>
        	</a>
        	<a class="modify" href="javascript:void(0);">Modify</a>
        </div>
        <div id="checkoutMethodFormBody" class="accordion-body collapse">
        	<div class="accordion-inner"></div>
        </div>
    </div>
  
    <div class="accordion-group" id="billingInformationForm">
        <div class="accordion-heading">
        	<a class="accordion-toggle" href="#billingInformationFormBody">
        		<?php echo $step++ . '. ' . lang('checkout_billing_information'); ?>
        	</a>
        	<a class="modify" href="javascript:void(0);">Modify</a>
        </div>
        <div id="billingInformationFormBody" class="accordion-body collapse">
        	<div class="accordion-inner"></div>
        </div>
    </div>  
  
    <div class="accordion-group" id="shippingInformationForm">
        <div class="accordion-heading">
        	<a class="accordion-toggle" href="#shippingInformationFormBody">
        		<?php echo $step++ . '. ' . lang('checkout_shipping_information'); ?>
        	</a>
        	<a class="modify" href="javascript:void(0);">Modify</a>
        </div>
        <div id="shippingInformationFormBody" class="accordion-body collapse">
        	<div class="accordion-inner"></div>
        </div>
    </div>
  
    <div class="accordion-group" id="shippingMethodForm">
        <div class="accordion-heading">
        	<a class="accordion-toggle" href="#shippingMethodFormBody">
        		<?php echo $step++ . '. ' . lang('checkout_shipping_method'); ?>
        	</a>
        	<a class="modify" href="javascript:void(0);">Modify</a>
        </div>
        <div id="shippingMethodFormBody" class="accordion-body collapse">
        	<div class="accordion-inner"></div>
        </div>
    </div>
  
    <div class="accordion-group" id="paymentInformationForm">
        <div class="accordion-heading">
        	<a class="accordion-toggle" href="#paymentInformationFormBody">
        		<?php echo $step++ . '. ' . lang('checkout_payment_information'); ?>
        	</a>
        	<a class="modify" href="javascript:void(0);">Modify</a>
        </div>
        <div id="paymentInformationFormBody" class="accordion-body collapse">
        	<div class="accordion-inner"></div>
        </div>
    </div>
  
    <div class="accordion-group" id="orderConfirmationForm">
        <div class="accordion-heading">
        	<a class="accordion-toggle" href="#orderConfirmationFormBody">
        		<?php echo $step++ . '. ' . lang('checkout_order_review'); ?>
        	</a>
        	<a class="modify" href="javascript:void(0);">Modify</a>
        </div>
        <div id="orderConfirmationFormBody" class="accordion-body collapse">
        	<div class="accordion-inner"></div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/checkout.js"></script>
<script>
<!--
var checkout = new jQuery.Toc.Checkout({
	logged_on: <?php echo is_logged_on() ? 'true' : 'false'; ?>
});
//-->
</script>