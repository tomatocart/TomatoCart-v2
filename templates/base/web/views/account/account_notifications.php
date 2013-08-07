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

<h1><?php echo $title; ?></h1>

<form name="account_notifications" action="<?php echo $link_save; ?>" method="post">

<div class="box">
  <h6 class="title"><?php echo $text_product_notifications; ?></h6>

  <div class="content">
    <?php echo $text_product_description; ?>
  </div>
</div>

<div class="box">
  <h6 class="title"><?php echo $text_global; ?></h6>

  <div class="content">
    <table width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="30"><input type="checkbox" value="1"  id="<?php echo $value_global; ?>" name="<?php echo $value_global; ?>"></td>
        <td><b><label for="product_global"><?php echo $text_global_notifications; ?></label></b></td>
      </tr>
      <tr>
        <td width="30">&nbsp;</td>
        <td><?php echo $text_global_description; ?></td>
      </tr>
    </table>
  </div>
</div>

<?php
  if ($value_global_product_notifications != '1') {
?>

<div class="box">
  <h6 class="title"><?php echo $text_product_notifications_products; ?></h6>

  <div class="content">
  <?php
    if (is_array($notifications_products) && !empty($notifications_products)):
  ?>
    <table width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2"><?php echo $text_product_notifications_products_description; ?></td>
      </tr>
  <?php
    foreach ($notifications_products as $key=>$product):
  ?>
      <tr>
        <td width="30"><input type="checkbox" checked="checked" value="<?php echo $product['product_id']; ?>" id="products[<?php echo $key; ?>]" name="products[<?php echo $key; ?>]"></td>
        <td><b><label for="products[<?php echo $key; ?>]"><?php echo $product['product_name']; ?></label></b></td>      </tr>

  <?php
     endforeach;
  ?>
    </table>

  <?php
    else:
      echo $text_product_notifications_products_none;
    endif;
  ?>
  </div>
</div>
<?php
  }
?>

<div class="submitFormButtons" style="text-align: right;">
  <a class="button" href="<?php echo $link_continue?>"><?php echo $text_continue; ?></a>
</div>

</form>
