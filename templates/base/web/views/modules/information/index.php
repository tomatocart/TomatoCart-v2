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
  <h4 class="title"><?php echo lang('box_information_heading'); ?></h4>
  
  <div class="contents">
    <ul>
      <?php 
        foreach ($information as $info):
      ?>
        <li><a href="<?php echo $info['link']; ?>"><?php echo $info['title']; ?></a></li>
      <?php 
        endforeach;
      ?>
    </ul>
  </div>
</div>