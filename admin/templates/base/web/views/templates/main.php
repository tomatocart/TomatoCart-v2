<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource
 */

    echo 'Ext.namespace("Toc.templates");';
  
    require_once 'templates_grid.php';
    require_once 'templates_upload_dialog.php';
    require_once 'templates_settings_dialog.php';
    require_once 'templates_layouts_dialog.php';
    require_once 'modules_panel.php';
    require_once 'modules_groups_panel.php';
    require_once 'modules_settings_panel.php';
?>

Ext.override(Toc.desktop.TemplatesWindow, {
    createWindow : function(){
        win = this.createTemplatesWindow();
        
        win.show();
    },
    
    createTemplatesWindow: function() {
        var desktop = this.app.getDesktop();
        win = desktop.getWindow('templates-win');
        
        if (!win) {
          var grd = Ext.create('Toc.templates.TemplatesGrid');
          
          grd.on('add', this.createTemplatesUploadDialog, this);
          grd.on('editLayouts', this.createLayoutsDialog, this);
          grd.on('editTemplates', this.createSettingsDialog, this);
          
          win = desktop.createWindow({
            id: 'templates-win',
            title: 'Templates',
            width: 870,
            height: 400,
            iconCls: 'icon-templates-win',
            layout: 'fit',
            items: grd
          });
        }
        
        return win;
    },
    
    createLayoutsDialog: function(rec) {
        var desktop = this.app.getDesktop();
        var dlg = desktop.getWindow('templates-layouts-dialog-win');
        
        if (!dlg) {
          dlg = desktop.createWindow({templatesId: rec.get('id'), code: rec.get('code')}, Toc.templates.TemplatesLayoutsDialog);
        }
        
        dlg.setTitle(rec.get('title'));
        dlg.show();
    },
    
    createSettingsDialog: function(rec) {
        var desktop = this.app.getDesktop();
        var dlg = desktop.getWindow('templates-settings-dialog-win');
        
        if (!dlg) {
          dlg = desktop.createWindow({templatesId: rec.get('id'), code: rec.get('code')}, Toc.templates.TemplatesSettingsDialog);
        }
        
        dlg.setTitle(rec.get('title'));
        dlg.show();
    },
    
    createTemplatesUploadDialog: function() {
        var desktop = this.app.getDesktop();
        var dlg = desktop.getWindow('templates-upload-dialog-win');
        
        if (!dlg) {
            dlg = desktop.createWindow(null, Toc.templates.TemplatesUplaodDialog);
            
            dlg.on('saveSuccess', function(feedback) {
            	this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
            }, this);
        }
        
        dlg.show();
    }
});