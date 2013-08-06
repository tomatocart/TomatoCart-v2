<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="images/tomatocart.ico" type="image/x-icon" />
    <title>TomatoCart, Open Source Shopping Cart Solutions</title>
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/stylesheet.css">
    <script src="<?php echo base_url()?>assets/js/jquery-1.8.2.min.js" type="text/javascript"></script>
</head>
<body>

<div id="header">
    <div class="container">
    	<div class="row">
        	<div class="span4">
    			<a href="index.php"><img src="<?php echo base_url(); ?>assets/img/logo.png" border="0" title="TomatoCart, Open Source Shopping Cart Solutions" style="margin: 10px 10px 0px 10px;" /></a>
        	</div>
        	<div class="span8">
            	<div class="row clearfix">
                    <ul class="languages pull-right">
                        <li><b><?php echo lang('title_language'); ?></b></li>
                        <?php
                            foreach (get_languages() as $language) :
                        ?>
                        <li><?php echo '<a href="index.php?language=' . $language['code'] . '"><img src="' . get_language_flag($language['code']) . '" title="' . $language['title'] . '" alt="' . $language['title'] . '" /></a>'; ?></li>
                        <?php
                            endforeach;
                        ?>
                    </ul>
            	</div>
        		<div class="links pull-right">
                	<a href="http://www.tomatocart.com" target="_blank"><?php echo lang('head_tomatocart_support_title'); ?></a> &nbsp;|&nbsp;
                	<a href="http://www.tomatocart.com/index.php/community/forum" target="_blank"><?php echo lang('head_tomatocart_support_forum_title'); ?></a>
            	</div>
        	</div>
        </div>
    </div>
</div>