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

<h1><?php echo lang('search_heading'); ?></h1>

<?php echo toc_validation_errors('search'); ?>

<form name="search" action="<?php echo site_url('search'); ?>" method="post" class="form-inline">
    <div class="module-box">
        <h6><?php echo lang('search_criteria_title'); ?></h6>
        
        <div class="control-group">
            <div class="controls">
            	<input type="text" name="keywords" class="keywords" value="<?php set_value('keywords'); ?>" />
            	<button class="btn btn-small btn-info"><i class="icon-search icon-white"></i> <?php echo lang('button_search'); ?></button>
            </div>
        </div>
    </div>
    
    <div class="module-box">
        <h6><?php echo lang('advanced_search_heading'); ?></h6>
        
        <div class="row-fluid">
			<div class="span6">
                <div class="control-group">
                    <label class="control-label" for="cPath"><?php echo lang('field_search_categories'); ?><em>*</em></label>
                    <div class="controls">
                        <select id="cPath" name="cPath">
                        
                            <?php
                                foreach($categories as $categories_id => $categories_name) :
                            ?>
                            <option value="<?php echo $categories_id; ?>"><?php echo $categories_name; ?></option>
                            <?php
                                endforeach;
                            ?>
                        
                        </select>
                    </div>
                    <div class="control-group">
                        <label class="control-label checkbox" for="recursive"><input type="checkbox" name="recursive" id="recursive" value="1" <?php echo set_checkbox('recursive', '1', TRUE);?> /> <?php echo lang('field_search_recursive'); ?></label>
                    </div>
                </div>
			</div>
			<div class="span6">
                <div class="control-group">
                    <label class="control-label" for="manufacturers"><?php echo lang('field_search_manufacturers'); ?><em>*</em></label>
                    <div class="controls">
                        <select id="manufacturers" name="manufacturers">
                        
                            <?php
                                foreach($manufacturers as $manufacturer) :
                            ?>
                            <option value="<?php echo $manufacturer['id']; ?>" <?php echo set_select('manufacturers', $manufacturer['id']); ?>><?php echo $manufacturer['text']; ?></option>
                            <?php
                                endforeach;
                            ?>
                        
                        </select>
                    </div>
                </div>
			</div>
		</div>
		
        <div class="row-fluid">
			<div class="span6">
                <div class="control-group">
                    <label class="control-label" for="pfrom"><?php echo lang('field_search_price_from'); ?><em>*</em></label>
                    <div class="controls">
                    	<input type="text" name="pfrom" id="pfrom" value="<?php echo set_value('pfrom'); ?>" />
                    </div>
                </div>
			</div>
			<div class="span6">
                <div class="control-group">
                    <label class="control-label" for="pto"><?php echo lang('field_search_price_to'); ?><em>*</em></label>
                    <div class="controls">
                    	<input type="text" name="pto" id="pto" value="<?php echo set_value('pto'); ?>" />
                    </div>
                </div>
			</div>
		</div>
    </div>
</form>