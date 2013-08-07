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

<?php
  if (config('DISPLAY_CONDITIONS_ON_CHECKOUT') == '1') :
?>

<div class="module-box clearfix">
    <h6><?php echo lang('order_conditions_title'); ?></h6>
    
    <div class="content">
        <?php echo sprintf(lang('order_conditions_description'), site_url('articles/4')) . '<br /><br />' . form_checkbox('conditions', '1', $order_conditions); ?>
    <label for="conditions"><?php echo lang('order_conditions_acknowledge'); ?></label>
    </div>
</div>
<?php
  endif;
?>

<div class="module-box clearfix">
	<div class="content">
    <?php
        if (sizeof($selection) > 0) :
    ?>
        <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
          <b><?php echo lang('please_select'); ?></b>
        </div>

    	<p><?php echo lang('choose_payment_method'); ?></p>
    <?php
        else :
    ?>
    	<p><?php echo lang('only_one_payment_method_available'); ?></p>
    <?php
        endif;
    ?>
    
    	<table id="payment_methods" width="100%" cellspacing="0" cellpadding="2">
        <?php
            $radio_buttons = 0;
            for ($i = 0, $n = sizeof($selection); ($i < $n); $i++) :
        ?>

			<tr id="payment_method_<?php echo $selection[$i]['id']; ?>">
				<td colspan="2">
					<table width="100%" cellspacing="0" cellpadding="2">
                    <?php
                        if ( ($n == 1) || ($has_billing_method && ($selection[$i]['id'] == $selected_billing_method_id)) ) :
                    ?>
                        <tr id="defaultSelected" class="moduleRowSelected">
                    <?php 
                        else :
                    ?>
                        <tr class="moduleRow">
                    <?php
                        endif;
                    ?>
							<td width="10">&nbsp;</td>
                        <?php
                            if ($n > 1) :
                        ?>
                            <td colspan="3"><?php echo '<b>' . $selection[$i]['module'] . '</b>'; ?></td>
                            <td align="right"><?php echo form_radio('payment_method', $selection[$i]['id'], ($has_billing_method ? $selected_billing_method_id : null)); ?></td>
                        <?php
                            else :
                        ?>
        					<td colspan="4"><?php echo '<b>' . $selection[$i]['module'] . '</b><input type="hidden" name="payment_method" value="'. $selection[$i]['id'] . '"'; ?></td>
                        <?php
                          endif;
                        ?>
            				<td width="10">&nbsp;</td>
          				</tr>
                    <?php
                        if (isset($selection[$i]['error'])) :
                    ?>
                        <tr>
                            <td width="10">&nbsp;</td>
                            <td colspan="4"><?php echo $selection[$i]['error']; ?></td>
                            <td width="10">&nbsp;</td>
                        </tr>

                    <?php
                        elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) :
                    ?>
                        <tr>
                        	<td width="10">&nbsp;</td>
                        	<td colspan="4">
                        		<table cellspacing="0" cellpadding="2">
                                <?php
                                    for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) :
                                ?>
                        			<tr>
                                        <td width="10">&nbsp;</td>
                                        <td><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                                        <td width="10">&nbsp;</td>
                                        <td><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                                        <td width="10">&nbsp;</td>
                                    </tr>
                                <?php
                                      endfor;
                                ?>
                        		</table>
                        	</td>
                        	<td width="10">&nbsp;</td>
                        </tr>
                    <?php
                        endif;
                    ?>
        			</table>
        		</td>
      		</tr>
        <?php
                $radio_buttons++;
            endfor;
        ?>
    	</table>
	</div>
    <div class="control-group">
        <h6><?php echo lang('add_comment_to_order_title'); ?></h6>
        
        <div class="controls">
            <?php echo form_textarea(array('id' => 'payment_comments', 'name' => 'payment_comments', 'value' => $payment_comments, 'rows' => '3', 'style' => 'width: 98%')); ?>
        </div>
    </div>
    
    <div class="control-group">
        <div class="controls">
      		<button type="submit" class="btn btn-small btn-info pull-right" id="btn-save-payment-form"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></button>
        </div>
    </div>
</div>