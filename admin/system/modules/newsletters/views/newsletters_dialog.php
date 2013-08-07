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
 * @filesource ./system/modules/newsletters/views/newsletters_dialog.php
 */
?>

Ext.define('Toc.newsletters.NewslettersDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'newsletters-dialog-win';
    config.title = '<?php echo lang('action_heading_new_newsletter'); ?>';
    config.width = 700;
    config.height = 400;
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function() {
          this.submitForm();
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
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);
  },
  
  show: function (newslettersId) {
    this.newslettersId = newslettersId || null;
    
    if (this.newslettersId > 0) {
      this.frmNewsletter.form.baseParams['newsletters_id'] = this.newslettersId;
      
      this.frmNewsletter.load({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'newsletters',
          action: 'load_newsletter'
        },
        success: function(form, action) {
          Toc.newsletters.NewslettersDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.dsModules = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'newsletters',
          action: 'get_modules'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.frmNewsletter = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'newsletters',
        action: 'save_newsletter'
      },
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%',
        labelWidth: 100
      },
      items: [ 
        {
          xtype: 'combo',
          name: 'newsletter_module',
          fieldLabel: '<?php echo lang('field_module'); ?>', 
          store: this.dsModules,
          queryMode: 'local',
          valueField: 'id',
          editable: false,
          displayField: 'text',
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          name: 'title', 
          fieldLabel: '<?php echo lang('field_title'); ?>', 
          allowBlank: false
        },
        {
          xtype: 'htmleditor',
          name: 'content', 
          fieldLabel: '<?php echo lang('field_content'); ?>', 
          height: 250
        }
      ]
    });
    
    return this.frmNewsletter;
  },
  
  submitForm : function() {
    this.frmNewsletter.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action){
         this.fireEvent('savesuccess', action.result.feedback);
         this.close();  
      },    
      failure: function(form, action) {
        if (action.failureType != 'client') {
          Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },  
      scope: this
    });   
  }
});


/* End of file newsletters_dialog.php */
/* Location: ./system/modules/newsletters/views/newsletters_dialog.php */
