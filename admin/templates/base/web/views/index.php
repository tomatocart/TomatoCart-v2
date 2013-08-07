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

  <div id="x-loading-mask" style="width:100%; height:100%; background:#000000; position:absolute; z-index:20000; left:0; top:0;">&#160;</div>
  <div id="x-loading-panel" style="position:absolute;left:40%;top:40%;border:1px solid #9c9f9d;padding:2px;background:#d1d8db;width:300px;text-align:center;z-index:20001;">
    <div class="x-loading-panel-mask-indicator" style="border:1px solid #c1d1d6;color:#666;background:white;padding:10px;margin:0;padding-left: 20px;height:130px;text-align:left;">
      <img class="x-loading-panel-logo" style="display:block;margin-bottom:15px;" src="<?php echo base_url();?>templates/base/web/images/tomatocart.jpg" />
      <img src="<?php echo base_url();?>templates/base/web/images/loading.gif" style="width:16px;height:16px;vertical-align:middle" />&#160;
      <span id="load-status"><?php echo lang('init_system'); ?></span>
      <div style="font-size:10px; font-weight:normal; margin-top:15px;">Copyright &copy; 2012 TomatoCart Shopping Cart Solution</div>
    </div>
  </div>

  <script type="text/javascript">
    Ext.namespace("Toc");
    
    Toc.CONF = {
      TEMPLATE: 'default',
      CONN_URL: '<?php echo site_url('index'); ?>',
      LOAD_URL: '<?php echo site_url('index/load_module_view'); ?>',
      PDF_URL: '<?php echo site_url('pdf'); ?>',
      GRID_PAGE_SIZE : <?php echo MAX_DISPLAY_SEARCH_RESULTS; ?>,
      GRID_STEPS : <?php echo EXT_GRID_STEPS; ?>,
      JSON_READER_ROOT: '<?php echo EXT_JSON_READER_ROOT; ?>',
      JSON_READER_TOTAL_PROPERTY: '<?php echo EXT_JSON_READER_TOTAL; ?>'
    };
    
    Toc.Languages = [];
    <?php 
      foreach (lang_get_all() as $l) {
        echo 'Toc.Languages.push(' . json_encode($l) . ');';
      }
    ?>
  
    var TocLanguage = {};
    TocLanguage = <?php echo json_encode($definitions); ?>;
    
    Ext.BLANK_IMAGE_URL = '<?php echo base_url(); ?>templates/base/web/images/s.gif';
  </script>

  <!-- TOC DESKTOP JS LIBRARY -->
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/core/classes.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/core/TocApp.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/core/TocModule.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/core/TocDesktop.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/settings/backgrounds.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/settings/modules.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/settings/settings.js"></script>
  
  <!-- TOC EXTENSION JS LIBRARY -->
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/Format.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/ColorPicker.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/CheckColumn.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/MultiSelect.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/layout/component/form/MultiSelect.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/portal/PortalPanel.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/portal/Portlet.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/portal/PortalDropZone.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/portal/PortalColumn.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/portal/GridPortlet.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/RowExpander.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>templates/base/web/javascript/desktop/ux/notification.js"></script>
  
  <!-- GNERATING TOC DESKTOP -->
  <script type="text/javascript" src="<?php echo site_url('index/desktop'); ?>"></script>