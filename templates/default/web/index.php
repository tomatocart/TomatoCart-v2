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

require_once 'helpers/general_helper.php';
?>

<!DOCTYPE html>
<html lang="<?php echo get_lang_code(); ?>">
<head>
	<meta charset="utf-8">
    <link rel="shortcut icon" href="<?php echo base_url('images/tomatocart.ico'); ?>" type="image/x-icon" />
    <title><?php echo $template['title'];?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <?php echo $template['meta_tags'];?>
    
    <base href="<?php echo base_url();?>" />
    <link rel="stylesheet" href="<?php echo base_url('templates/base/web/css/bootstrap.min.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('templates/base/web/css/select2.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('templates/default/web/css/stylesheet.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('templates/default/web/css/stylesheet.responsive.css');?>" />
    <?php echo $template['stylesheets'];?>
    
    <script type="text/javascript" src="<?php echo base_url('templates/base/web/javascript/jquery/jquery-1.8.2.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('templates/base/web/javascript/jquery/jquery.loadmask.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/toc.js"></script>
    <?php echo $template['javascripts'];?>
	<script type="text/javascript">
		var base_url = '<?php echo base_url(); ?>';
	</script>

    <!-- touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('images/apple-touch-icon-144-precomposed.png'); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('images/apple-touch-icon-114-precomposed.png'); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('images/apple-touch-icon-72-precomposed.png'); ?>">
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('images/apple-touch-icon-57-precomposed.png'); ?>">
	
	<meta name="Generator" content="TomatoCart" />
</head>
<body>
  <!--  page header  -->
    <!-- BEGIN: Header -->
    <div id="header">
        <div class="container">
        	<div class="row-fluid">
        		<div class="span4 logo">
                    <a href="<?php echo base_url(); ?>">
                    	<img src="<?php echo get_logo(); ?>" alt="<?php echo config('STORE_NAME'); ?>" title="<?php echo config('STORE_NAME'); ?>" />
                    </a>  
        		</div>
        		<div class="span8">
                    <div class="top-nav row-fluid clearfix">
                    	<div class="pull-right">
                            <a class="popup-cart" href="javascript:void(0);">
                                <i class="icon-shopping-cart"></i>
                                <span id="popup-cart-items"><?php echo cart_item_count(); ?></span>&nbsp;<span><?php echo lang('text_items'); ?></span>
                            </a>
                        </div>
                        <div class="dropdown pull-right">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="javascrip:void(0);"><?php echo currency_title(); ?> (<?php echo currency_symbol_left(); ?>)<b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu">
                            <?php 
                                foreach (get_currencies() as $code => $currency) : 
                            ?>
                                <li role="menuitem">
                                	<a href="<?php echo current_url() . '?currency=' . $code; ?>"><?php echo $currency['title']; ?> (<?php echo $currency['symbol_left']; ?>)</a>
                                </li>
                            <?php 
                                endforeach; 
                            ?>
                            </ul>
                        </div>
                        <div class="dropdown pull-right">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="javascrip:void(0);">
                            	<img src="<?php echo lang_image(); ?>" alt="<?php echo lang_name(); ?>" title="<?php echo lang_name(); ?>" width="16" height="10" /> <?php echo lang_name(); ?><b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                            <?php 
                                foreach (get_languages() as $lang) : 
                            ?>
                                <li role="menuitem">
                                	<a href="<?php echo current_url() . '?language=' . $lang['code']; ?>"><img src="<?php echo image_url('worldflags/' . strtolower(substr($lang['code'], 3)) . '.png'); ?>" alt="<?php echo $lang['name']; ?>" title="<?php echo $lang['name']; ?>" width="16" height="10" /> <?php echo $lang['name']; ?></a>
                                </li>
                            <?php 
                                endforeach; 
                            ?>
                            </ul>
                        </div>
                    </div>
                    <div class="main-nav">
                    	<ul>
                            <li class="visible-desktop"><a href="<?php echo base_url(); ?>"><?php echo lang('home'); ?></a></li>
                            <li><a href="<?php echo base_url('wishlist'); ?>"><?php echo lang('my_wishlist'); ?></a></li>
                            <li><a href="<?php echo site_url('account'); ?>"><?php echo lang('my_account'); ?></a></li>
                        <?php 
                            if (is_logged_on()) : 
                        ?>
                            <li><a href="<?php echo site_url('account/logoff'); ?>"><?php echo lang('logoff'); ?></a></li>
                        <?php 
                            else : 
                        ?>
                            <li><a href="<?php echo site_url('account/login'); ?>"><?php echo lang('login'); ?></a></li>
                        <?php 
                            endif; 
                        ?>
                            <li><a href="<?php echo site_url('shopping_cart'); ?>"><?php echo lang('checkout'); ?></a></li>
                            <li><a href="<?php echo site_url('contact_us'); ?>"><?php echo lang('contact_us'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Header -->
    
    <!-- BEGIN: Navigation -->
    <div class="container">
    	<div class="navbar navbar-inverse">
    		<div class="navbar-inner">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
                <form name="search_post" method="post" action="<?php echo site_url('search'); ?>" class="navbar-search pull-right">
                    <input type="text" name="keywords" class="search-query" placeholder="Search" />
                    <div class="icon-search"></div>
                </form>
    			<div class="nav-collapse collapse">
        			<?php echo build_categories_dropdown_menu(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Navigation -->
    
    <!-- BEGIN: Breadcrumb -->
    <div class="container">
        <ul class="breadcrumb hidden-phone">
        <?php 
            foreach($template['breadcrumbs'] as $breadcrumb) : 
        ?>
            <li><a href="<?php echo $breadcrumb['uri']; ?>"><?php echo $breadcrumb['name']; ?></a><span class="divider">/</span></li>
        <?php 
            endforeach; 
        ?> 
        </ul>       
    </div>
    <!-- END: Breadcrumb -->
<!--  END: page header  -->
  
    <!--  slideshow  -->
    <?php 
        if (isset($template['module_groups']['slideshow'])) {
    ?>
        <div id="slideshows" class="container">
        <?php echo $template['module_groups']['slideshow']; ?>
        </div>
    <?php 
        }
    ?>
    <!--  END: slideshow  -->
    <div class="container">
    	<div class="row-fluid">
        <!--  left module group  -->
        <?php 
            $has_left = isset($template['module_groups']['left']) && !empty($template['module_groups']['left']);
            $has_right = isset($template['module_groups']['right']) && !empty($template['module_groups']['right']);
            if ($has_left) 
            {
        ?>
            <div id="content-left" class="span3  hidden-phone"><?php echo $template['module_groups']['left']; ?></div> 
        <?php 
            }  
        ?>
        <!--  END: left module group  -->
    
        <div  id="content-center" class="span<?php echo 12 - ($has_left ? 3 : 0) - ($has_right ? 3 : 0); ?>">
            <!--  before module group  -->
            <?php 
              if (isset($template['module_groups']['before'])) {
                echo $template['module_groups']['before']; 
              }  
            ?>
            <!--  END: before module group  -->
            
            <!--  page body  -->
            <?php 
              if (isset($template['body'])) {
                echo $template['body']; 
              }  
            ?>
            <!--  END: page body  -->
            
            <!--  after module group  -->
            <?php 
              if (isset($template['module_groups']['after'])) {
                echo $template['module_groups']['after']; 
              }  
            ?>
            <!--  END: after module group  -->
        </div>
    
        <!--  right module group  -->
        <?php 
            if ($has_right) 
            {
        ?>
            <div id="content-right" class="span3 hidden-phone"><?php echo $template['module_groups']['right']; ?></div> 
        <?php 
            }  
        ?>
        <!--  END: right module group  -->
    	</div>
    </div>
  
<!--  BEGIN: Page Footer -->
<div class="container">
	<div id="footer" class="row-fluid clearfix">
    	<div class="span3">
            <?php 
                if (isset($template['module_groups']['footer-col-1'])):
                    echo $template['module_groups']['footer-col-1']; 
                endif;
            ?>
    	</div>
    	<div class="span3">
            <?php 
                if (isset($template['module_groups']['footer-col-2'])):
                    echo $template['module_groups']['footer-col-2']; 
                endif;
            ?>
    	</div>
    	<div class="span3">
            <?php 
                if (isset($template['module_groups']['footer-col-3'])):
                    echo $template['module_groups']['footer-col-3']; 
                endif;
            ?>
    	</div>
    	<div class="span3">
            <?php 
                if (isset($template['module_groups']['footer-col-4'])):
                    echo $template['module_groups']['footer-col-4']; 
                endif;
            ?>
    	</div>
    </div>
    <p class="copyright pull-right">
		<?php 
            echo sprintf(lang('footer'), date('Y'), site_url(), config('STORE_NAME'));
		?>    
    </p>
</div>
<!--  END: Page Footer -->

<!--  BEGIN: Run Service -->
<?php 
    run_service('google_analytics'); 
?>

<?php 
    run_service('debug'); 
?>
<!--  END: Run Service -->
  
<script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/bootstrap/select2.js"></script>

<script type="text/javascript">
	$('.popup-cart').popover({
	    animation: true,
	    trigger: 'hover',
	    placement: 'bottom',
	    html: true,
	    title: '<b><?php echo lang('cart_contents'); ?></b>',
	    content: '<?php echo get_shopping_cart_contents(); ?>'
	});
</script>
<!--  END: page footer -->
</body>
</html>