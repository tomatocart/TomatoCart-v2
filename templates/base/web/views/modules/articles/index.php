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
    <h4><?php echo $title; ?></h4>
    
    <div class="contents">
        <ul>
        <?php 
            foreach($articles as $article) :
        ?>
            <li><a href="<?php echo site_url('info/' . $article['articles_id']); ?>"><?php echo $article['articles_name']; ?></a></li>
        <?php 
            endforeach;
        ?>
        </ul>
    </div>
</div>