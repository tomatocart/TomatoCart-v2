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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html dir="<?php echo lang_get_text_direction(); ?>" xml:lang="<?php echo lang_get_code(); ?>" lang="<?php echo lang_get_code(); ?>">
  <head>
    <title><?php echo lang('administration_title'); ?></title>
    <link rel="shortcut icon" href="<?php echo base_url();?>templates/base/web/images/favicon.ico" type="image/x-icon" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="-1" />
    <?php echo $template['meta_tags'];?>
    
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>templates/base/web/javascript/extjs/resources/css/ext-all.css" />
    <?php echo $template['stylesheets'];?>

		<script src="<?php echo base_url();?>templates/base/web/javascript/extjs/ext-all-debug.js"></script> 
    <?php echo $template['javascripts'];?>
    
    <style type="text/css">
    <?php 
      foreach (lang_get_all() as $l) {
        echo ".icon-" . $l['country_iso'] . "-win {background-image: url(/images/worldflags/" . $l['country_iso'] . ".png) !important;}";
      }
    ?>
    </style>
  </head>

  <body scroll="no">
    <?php 
      echo $template['body'];
    ?>
  </body>
</html>