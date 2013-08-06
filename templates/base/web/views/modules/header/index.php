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

<!-- Begin: Header -->
<div id="header">
    <ul>
        <li><a href="<?php echo site_url('account/wishlist'); ?>"><?php echo lang('my_wishlist'); ?></a></li>
        
    <?php 
        if ($is_logged_on) : 
    ?>
        <li><a href="<?php echo site_url('account/logoff'); ?>"><?php echo lang('logoff'); ?></a></li>
    <?php 
        else : 
    ?>
        <li><a href="<?php echo site_url('account/login'); ?>"><?php echo lang('login'); ?></a></li>
        <li><a href="<?php echo site_url('account/create'); ?>"><?php echo lang('create_account'); ?></a></li>
    <?php 
        endif; 
    ?>
        
        <li id="bookmark"></li>    
        <li class="cart">
            <a href="<?php echo site_url('shopping_cart'); ?>">
                <span id="popupCart">
                    <img src="images/shopping_cart_icon.png" />
                    <span id="popupCartItems"><?php echo $items_num; ?></span><span><?php echo lang('text_items'); ?></span>
                </span>
            </a>
        </li>
    </ul>
    <a href="<?php echo base_url(); ?>" id="siteLogo">
    	<img src="<?php echo image_url('store_logo.png'); ?>" alt="<?php echo config('STORE_NAME'); ?>" title="<?php echo config('STORE_NAME'); ?>" />
    </a>  
</div>
<!-- End: Header -->

<!-- Begin: Navigation -->
<div id="nav">
    <div id="navigation">
        <ul>
            <li class="current"><a href="<?php echo base_url(); ?>"><?php echo lang('home'); ?></a></li>
            <li><a href="<?php echo site_url('latest'); ?>"><?php echo lang('new_products'); ?></a></li>
            <li><a href="<?php echo site_url('specials'); ?>"><?php echo lang('specials'); ?></a></li>
            <li><a href="<?php echo site_url('account'); ?>"><?php echo lang('my_account'); ?></a></li>
            <li><a href="<?php echo site_url('checkout'); ?>"><?php echo lang('checkout'); ?></a></li>
            <li><a href="<?php echo site_url('contact_us'); ?>"><?php echo lang('contact_us'); ?></a></li>      
        </ul>
        <div style="float: right; width: 206px">
            <form name="search_post" method="post" action="<?php echo site_url('search_post'); ?>">
                <p class="keywords">
                    <input type="text" name="keywords" id="keywords" />
                    <img id="quickSearch" class="search" src="images/button_quick_find.png" title="search" alt="search" />
                </p>
            </form>
            
        </div>
    </div>
</div>
<!-- End: Navigation -->

<!-- Begin: Breadcrumb -->
<div id="breadcrumb">
<?php 
    foreach($breadcrumbs as $breadcrumb) : 
?>
    <a href="<?php echo $breadcrumb['uri']; ?>"><?php echo $breadcrumb['name']; ?></a> &raquo; 
<?php 
    endforeach; 
?>        
    
    <div id="languages">
    <?php 
        foreach ($languages as $language) : 
    ?>
        <a href="<?php echo $language['url']; ?>">
        	<img src="<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" width="16" height="10" />
        </a>
    <?php 
        endforeach; 
    ?>
    </div>
</div>
<!-- End: Breadcrumb -->

<script type="text/javascript">
    $('#quickSearch').bind('click', function() {
        $('#navigation form').submit();
    });
</script>