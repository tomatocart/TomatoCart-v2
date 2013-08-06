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
  if ($has_quotes) :
?>

<div class="module-box form-horizontal">
    <div class="content">
    
    <?php
        if (count($quotes) > 1):
    ?>
        <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
			<b><?php echo lang('please_select'); ?></b>
        </div>
    
		<p><?php echo lang('choose_shipping_method'); ?></p>
    <?php
        else:
    ?>
		<p><?php echo lang('only_one_shipping_method_available'); ?></p>
    <?php
        endif;
    ?>
    
    	<table width="100%" cellspacing="0" cellpadding="2">
    
            <?php
                $radio_buttons = 0;
                foreach ($quotes as $quote) :
            ?>
            <tr>
                <td>
                	<table width="100%" cellspacing="0" cellpadding="2">
                        <tr>
                            <td width="10">&nbsp;</td>
                            <td colspan="3">
                            	<b><?php echo $quote['module']; ?></b>&nbsp;
                            	<?php 
                            	    if (isset($quote['icon']) && !empty($quote['icon'])) :
                                        //echo '<image src="' . $quote['icon'] . '" />'; 
                                    endif; 
                                ?>
                            </td>
                        	<td width="10">&nbsp;</td>
                    	</tr>
                    <?php
                        if (isset($quote['error'])) :
                    ?>
                        <tr>
                            <td width="10">&nbsp;</td>
                            <td colspan="3"><?php echo $quote['error']; ?></td>
                            <td width="10">&nbsp;</td>
                        </tr>
                    <?php
                        else :
                            foreach ($quote['methods'] as $methods) :
                                if ($quote['id'] . '-' . $methods['id'] == $selected_shipping_method_id) :
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
                			<td width="75%"><?php echo $methods['title']; ?></td>
                        <?php
                            if ( (count($quotes) > 1) || (sizeof($quote['methods']) > 1) ) :
                        ?>
                            <td><?php echo currencies_display_price($methods['cost'], $quote['tax_class_id']); ?></td>
                            <td align="right"><?php echo form_radio('shipping_mod_sel', $quote['id'] . '-' . $methods['id'], ($quote['id'] . '-' . $methods['id'] == $selected_shipping_method_id) ? TRUE : FALSE); ?></td>
                        <?php
                            else :
                        ?>
                			<td align="right" colspan="2"><?php echo currencies_display_price($methods['cost'], $quote['tax_class_id']) . '<input type="hidden" name="shipping_mod_sel" value="' . $quote['id'] . '-' . $methods['id'] . '">'; ?></td>
                        <?php
                            endif;
                        ?>
							<td width="10">&nbsp;</td>
                		</tr>
                    <?php
                                $radio_buttons++;
                            endforeach;
                      endif;
                    ?>
                	</table>
                </td>
            </tr>
            <?php
                endforeach;
            ?>
    	</table>
    </div>
    <div class="control-group">
        <div class="controls">
      		<button type="submit" class="btn btn-small btn-info pull-right" id="btn-save-shipping-method"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></button>
        </div>
    </div>
</div>
<?php

  endif;
?>