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

Ext.define('Toc.newsletters.SendNewslettersDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'send-newsletters-dialog-win';
    config.title = '<?php echo lang('heading_newsletters_title'); ?>';
    config.layout = 'fit';
    config.modal = true;
    config.width = 600;
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        id: 'btn-send-newsletters',
        text: '<?php echo lang('button_send') ?>',
        handler: function() { 
          this.sendEmails();
        },
        scope: this
      },
      {
        text: TocLanguage.btnClose,
        handler: function() { 
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'sendsuccess': true});
    
    this.callParent([config]);
  },
  
  show: function (newslettersId) {
    this.newslettersId = newslettersId;
    
    Ext.Ajax.request({
      url: '<?php echo site_url('newsletters/get_newsletters_confirmation'); ?>',
      params: {
        newsletters_id: this.newslettersId
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          this.frmNewsletter.update(result.confirmation);
          
          if (result.execute == true) {
            Ext.getCmp('btn-send-newsletters').setText('<?php echo lang('button_send') ?>');
          } else {
            Ext.getCmp('btn-send-newsletters').hide();
          }
        } else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
        }
        
        this.frmNewsletter.el.unmask();
      },
      scope: this
    }); 
        
    Toc.newsletters.SendNewslettersDialog.superclass.show.call(this);
  },
  
  sendEmails: function() {
    this.frmNewsletter.el.mask('<?php echo lang('sending_please_wait') ?>', 'x-mask-loading');
    
    Ext.Ajax.request({
      url: '<?php echo site_url('newsletters/send_newsletters'); ?>',
      params: {
        newsletters_id: this.newslettersId
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
         this.fireEvent('sendsuccess', result.feedback);
         this.close();        
        } else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
        }
        
        this.frmNewsletter.el.unmask();
      },
      scope: this
    }); 
  },
  
  buildForm: function() {
    this.frmNewsletter = Ext.create('Ext.Panel', {
      border: false,
      bodyPadding: 10
    });
    
    return this.frmNewsletter;
  }
});

/* End of file send_newsletters_dialog.php */
/* Location: ./templates/base/web/views/newsletters/send_newsletters_dialog.php */