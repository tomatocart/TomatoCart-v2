<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */
?>

Ext.define('Toc.languages.TranslationsEditDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('heading_translations_title'); ?>';
    config.id = 'translations-win';
    config.layout = 'border';
    config.border = false;
    config.height = 400;
    config.width = 850;
    config.modal = true;
    config.iconCls = 'icon-languages-win';
    
    config.grdTranslations = Ext.create('Toc.languages.TranslationsEditGrid', {languagesId: config.languagesId});
    config.pnlModulesTree = Ext.create('Toc.languages.ModulesTreePanel', {languagesId: config.languagesId});
    
    config.pnlModulesTree.on('selectchange', this.onTreeSelect, this);

    config.items = [config.grdTranslations, config.pnlModulesTree];
    
    this.callParent([config]);
  },
  
  onTreeSelect: function(group) {
    this.grdTranslations.refreshGrid(group);
  }
});

/* End of file translations_dialog.php */
/* Location: ./templates/base/web/views/languages/translations_dialog.php */