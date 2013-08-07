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

Ext.define('Toc.newsletters.SendEmailsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
  
    config.id = 'send-emails-dialog-win';
    config.title = '<?php echo lang('heading_newsletters_title'); ?>';
    config.width = 600;
    config.layout = 'fit';
    config.modal = true;
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: '<?php echo lang('button_ok') ?>',
        id: 'btn-send-emails',
        handler: this.onAction,
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
    
    this.addEvents({'sendsuccess' : true});
    
    this.callParent([config]);
  },
  
  show: function (newslettersId) {
    this.newslettersId = newslettersId || null;
    
    this.callParent();
  },
  
  onAction: function() {
    text = Ext.getCmp('btn-send-emails').getText();
    
    if (text == '<?php echo lang('button_ok') ?>') {
      this.showConfirmation();
    } else {
      this.sendEmails();
    }
  },
  
  sendEmails: function() {
    var batch = Ext.JSON.encode(this.selAudience.getValue());
  
    this.pnlSendEmail.el.mask('<?php echo lang('sending_please_wait') ?>', 'x-mask-loading');
    
    Ext.Ajax.request({
      url: '<?php echo site_url('newsletters/send_emails'); ?>',
      params: {
        newsletters_id: this.newslettersId,
        batch: batch
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
         this.fireEvent('sendsuccess', result.feedback);
         this.close();        
        } else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
        }
        
        this.pnlSendEmail.el.unmask();
      },
      scope: this
    }); 
  },
  
  showConfirmation: function() {
    var batch = Ext.JSON.encode(this.selAudience.getValue());
    
    if ( Ext.isEmpty(batch) ) {
      Ext.MessageBox.alert(TocLanguage.msgInfoTitle, TocLanguage.msgMustSelectOne);
      return;
    }  
  
    this.pnlSendEmail.el.mask(TocLanguage.formSubmitWaitMsg, 'x-mask-loading');
    
    Ext.Ajax.request({
      url: '<?php echo site_url('newsletters/get_emails_confirmation'); ?>',
      params: {
        newsletters_id: this.newslettersId,
        batch: batch
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          this.pnlSendEmail.removeAll();
          
          this.pnlSendEmail.update(result.confirmation);
          Ext.getCmp('btn-send-emails').setText('<?php echo lang('button_send') ?>');
        } else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
        }
        
        this.pnlSendEmail.el.unmask();
      },
      scope: this
    }); 
  },
  
  getAudienceSelectionForm: function() {
    var dsAudience = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('newsletters/get_emails_audience'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    var selAudience = Ext.create('Ext.ux.form.MultiSelect', {
      store: dsAudience,
      style: 'padding: 15px;',
      border: false,
      name: 'customers',
      width: 550,
      height: 250,
      legend: '<?php echo lang('newsletter_customer'); ?>',
      displayField: 'text',
      valueField: 'id'
    });
    
    return selAudience;
  },
  
  buildForm: function() {
    this.selAudience = this.getAudienceSelectionForm();
    
    this.pnlSendEmail = Ext.create('Ext.Panel', {
      border: false,
      bodyPadding: 10,
      items: this.selAudience
    });
    
    return this.pnlSendEmail;
  }
});

/* End of file send_emails_dialog.php */
/* Location: ./templates/base/web/views/newsletters/send_emails_dialog.php */