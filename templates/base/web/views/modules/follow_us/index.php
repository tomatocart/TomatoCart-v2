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

<div class="box box-follow-us">
    <h4><?php echo lang('box_follow_us_heading'); ?></h4>
    
    <div class="contents">
        <li class="facebook"><a href="<?php echo $facebook_link;?>"><?php echo lang('follow-us_facebook-title');?></a></li>
        <li class="twitter"><a href="<?php echo $twitter_link;?>"><?php echo lang('follow-us_twitter-title');?></a></li>
        <li class="google-plus"><a href="<?php echo $google_plus_link;?>"><?php echo lang('follow-us_google-plus');?></a></li>
        <li class="rss"><a href="<?php echo site_url('rss');?>"><?php echo lang('follow-us_rss');?></a></li>
    </div>
</div>