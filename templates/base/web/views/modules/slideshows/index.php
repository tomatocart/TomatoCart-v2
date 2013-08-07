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

<div class="box slideshows">
    <div id="<?php echo $mid; ?>" class="carousel slide">
        <div class="carousel-inner">
        <?php
            $active = 'active'; 
            foreach($images as $image):
        ?>
            <div class="<?php echo $active; ?> item">
            	<a href="<?php echo $image['image_link']; ?>"><img src="<?php echo image_url($image['image_src']); ?>" alt="<?php echo $image['image_info']; ?>" title="<?php echo $image['image_info']; ?>" /></a>
            	
                <?php 
                    if ($display_slide_info == 'true'):
                ?>
            	<div class="carousel-caption">
                  <p><?php echo $image['image_info']; ?></p>
                </div>
                <?php 
                    endif;
                ?>
            </div>
        <?php 
                $active = ''; 
            endforeach;
        ?>
        </div>
        <?php 
            if ($display_carousel_control == 'true'):
        ?>
        <!-- Carousel nav -->
        <a class="carousel-control left" href="#<?php echo $mid; ?>" data-slide="prev">&lsaquo;</a>
        <a class="carousel-control right" href="#<?php echo $mid; ?>" data-slide="next">&rsaquo;</a>
        <?php 
            endif;
        ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#<?php echo $mid; ?>').carousel({
            interval: <?php echo $play_interval; ?>,
			pause: 'hover'
        });
    });
</script>