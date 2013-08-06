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
 * @filesource ./system/modules/newsletters/views/send_newsletters_dialog.php
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
    config.height = 350;
    
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
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'newsletters',
        action: 'get_newsletters_confirmation',
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
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'newsletters',
        action: 'send_newsletters',
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
/* Location: ./system/modules/newsletters/views/send_newsletters_dialog.php */
