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
      
<div style="float: right;"><a href="<?php echo $product_link;?>"><img src="<?php echo $product_img_src; ?>" alt="<?php echo $product_name; ?>" title="<?php echo $product_name; ?>" class="productImage" hspace="5" vspace="5"></a></div>

<h1><?php echo $title;?></h1>

<div style="clear: both"></div>

<form name="tell_a_friend" action="<?php echo $action; ?>" method="post">

  <div class="moduleBox">
    <h6><em class="fl"><?php echo $required_information_text; ?></em><?php echo $your_details_text; ?></h6>
  
    <fieldset>
      <label accesskey="from_name"><?php echo $from_name_text; ?><em>*</em></label><input name="from_name" id="from_name" value="<?php echo $from_name; ?>" type="text" /><br />
      <label accesskey="from_email_address"><?php echo $from_email_text; ?><em>*</em></label><input name="from_email_address" id="from_email_address" value="<?php echo $from_email; ?>" type="text" />
    </fieldset>
  </div>

  <div class="moduleBox">
    <h6>Your Friends Details</h6>
  
      <fieldset>
        <label accesskey="to_name"><?php echo $to_name_text; ?><em>*</em></label><input name="to_name" id="to_name" type="text" /><br />
        <label accesskey="to_email_address"><?php echo $to_email_text; ?><em>*</em></label><input name="to_email_address" id="to_email_address" type="text" />
      </fieldset>
  </div>

  <div class="moduleBox">
    <h6><?php echo $message_text; ?></h6>
    <fieldset>
      <textarea name="message" cols="40" rows="8" id="message"></textarea>
    </fieldset>
  </div>

  <div class="buttons">
    <div style="float: right;"><button class="button small" type="submit"><?php echo $continue_text;?></button></div>
    <a href="<?php echo $product_link;?>" class="button small"><?php echo $back_text;?></a>
  </div>
  <div class="clear"></div>
</form>