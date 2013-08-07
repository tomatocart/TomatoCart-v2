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
    <h4 class="title"><?php echo lang('box_manufacturers_heading'); ?></h4>
    
    <div class="contents">
	<?php 
	    if ($list_type == 'Image List'):
	?>
        <ul>
        <?php 
            foreach ($manufacturers as $manufacturer):
        ?>
            <li>
            	<a href="<?php echo site_url('search?manufacturers=' . $manufacturer['manufacturers_id']); ?>">
            		<img src="<?php echo image_url('manufacturers/' .  $manufacturer['manufacturers_image']); ?>" title="<?php echo $manufacturer['manufacturers_name']; ?>" />
            	</a>
            </li>
	    <?php 
            endforeach;
        ?>
        </ul>
	<?php 
        else:
    ?>
        <form name="manufacturers" action="<?php echo site_url('search/manufacturers'); ?>" method="post">
    	<?php 
            $options = array();
            foreach ($manufacturers as $manufacturer):
                $options[$manufacturer['manufacturers_id']] = $manufacturer['manufacturers_name'];
            endforeach;
        ?>
				<?php echo form_dropdown('manufacturers', $options, NULL, 'size="' . $list_size . '" onchange="this.form.submit();" style="width: 99%"'); ?>
        </form>
    <?php 
        endif;
    ?>
    </div>
</div>