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

<h1><?php echo lang('info_sitemap_heading'); ?></h1>

<div class="module-box sitemapBox">
    <div class="row-fluid clearfix">
        <ul class="span6 sitemapLinks">
            <li>
                <a href="<?php echo site_url('account'); ?>"><?php echo lang('sitemap_account'); ?></a>
                <ul>
                    <li><a href="<?php echo site_url('account/edit'); ?>"><?php echo lang('sitemap_account_edit'); ?></a></li>
                    <li><a href="<?php echo site_url('account/address_book'); ?>"><?php echo lang('sitemap_address_book'); ?></a></li>
                    <li><a href="<?php echo site_url('account/orders'); ?>"><?php echo lang('sitemap_account_history'); ?></a></li>
                    <li><a href="<?php echo site_url('account/newsletters'); ?>"><?php echo lang('sitemap_account_notifications'); ?></a></li>
                </ul>
            </li>
            <li><a href="<?php echo site_url('checkout'); ?>"><?php echo lang('sitemap_shopping_cart'); ?></a></li>
            <li><a href="<?php echo site_url('latest'); ?>"><?php echo lang('sitemap_products_new'); ?></a></li>
            <li><a href="<?php echo site_url('specials'); ?>"><?php echo lang('sitemap_specials'); ?></a></li>
            <li>
                <a href="<?php echo site_url('info'); ?>"><?php echo lang('box_information_heading'); ?></a>
                <ul>
                    <li><a href="<?php echo site_url('info/condition'); ?>"><?php echo lang('box_information_conditions'); ?></a></li>
                    <li><a href="<?php echo site_url('contact_us'); ?>"><?php echo lang('box_information_contact'); ?></a></li>
                </ul>
            </li>
        </ul>
        
        <div class="span6 sitemapCategoryTree">
            <?php echo $category_tree; ?>
        </div>
    </div>
</div>

<div class="controls clearfix">
	<a href="<?php echo site_url(); ?>" class="btn btn-small btn-info pull-right"><i class="icon-chevron-right icon-white"></i><?php echo lang('button_continue'); ?></a>
</div>