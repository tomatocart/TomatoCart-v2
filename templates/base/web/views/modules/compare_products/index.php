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

<div class="box">
    <h4 class="title"><?php echo lang('box_compare_products_heading'); ?></h4>
    
    <div class="contents">
        <ul class="clearfix">
        	<?php 
        	    foreach ($products as $product) : 
        	?>
          	<li>
          		<span class="pull-right" style="margin: 0 3px 1px 3px; width: 16px"><a style="padding-left: 0;" href="<?php echo site_url('compare/delete/' . $product['products_id']); ?>"> <i class="icon-trash"></i> </a></span>
          		<a href="<?php echo site_url('product/' . $product['products_id']); ?>" style="width: 160px; padding-left: 0;"><?php echo $product['products_name']; ?></a>	
          	</li>
        	<?php 
        	    endforeach;
        	?>
        </ul>
        <p>
            <span style="float: right"><a class="btn btn-mini multibox" data-toggle="modal" href="<?php echo site_url('compare'); ?>" data-target="#myModal"><?php echo lang('button_compare_now'); ?></a></span>
            <a  class="btn btn-mini" href="<?php echo site_url('compare/clear'); ?>"><?php echo lang('button_clear'); ?></a>
        </p>
    </div>
</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 900px; margin-left: -450px">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?php echo lang('box_compare_products_heading'); ?></h3>
    </div>
    <div class="modal-body">
    	<p></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>