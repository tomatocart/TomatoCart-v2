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

<h1><?php echo lang('success_heading'); ?></h1>

<form name="order" action="<?php echo base_url(); ?>" method="post">
    <div class="module-box">
        <div style="padding-top: 30px;">
          <p><?php echo lang('order_processed_successfully'); ?></p>
        
          <p>
            <?php
              echo sprintf(lang('view_order_history'), site_url('account'), site_url('account/orders')) . '<br /><br />' . sprintf(lang('contact_store_owner'), site_url('info/contact'));
            ?>
          </p>
        
          <h2 style="text-align: center;"><?php echo lang('thanks_for_shopping_with_us'); ?></h2>
        </div>
    </div>
    
    <div class="submitFormButtons" style="text-align: right;">
    	<button type="submit" class="btn btn-info btn-small"><i class="icon-chevron-right icon-white"></i><?php echo lang('button_continue'); ?></button>
    </div>
</form>
