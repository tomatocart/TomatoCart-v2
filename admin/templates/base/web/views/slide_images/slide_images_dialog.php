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
 * @filesource modules/silde_images/views/slide_images_dialog.php
 */
?>

Ext.define('Toc.slideImages.SlideImagesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'slide_images_dialog-win';
    config.title = '<?php echo lang('heading_title_new_slide_image'); ?>';
    config.layout = 'fit';
    config.modal = true;
    config.width = 600;
    config.height = 500;
    config.iconCls = 'icon-slide_images-win';
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function() {
          this.submitForm();
        },
        scope:this
      },
      {
        text: TocLanguage.btnClose,
        handler: function() {
          this.close();
        },
        scope:this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function(id) {
    var imageId = id || null;
    
    this.pnlSlideImages.form.baseParams['image_id'] = imageId;
    
    if (imageId > 0)
    {
      this.pnlSlideImages.load({
        url: '<?php echo site_url('slide_images/load_slide_images'); ?>',
        params: {
          module: 'slide_images',
          action: 'load_slide_images'
        },
        success: function(form, action) {
          <?php 
            foreach (lang_get_all() as $l) {
              echo " 
                if (action.result.data.slide_image" . $l['id'] . ") {
                  var image = action.result.data.slide_image" . $l['id'] . ";
                  this." . $l['code'] . ".getComponent('uploaded_img" . $l['id'] . "').update(image);
                }";
             
            }
          ?>
          
          Toc.slideImages.SlideImagesDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData)
        },
        scope: this   
      }); 
    }else {
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.pnlSlideImages = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('slide_images/save_slide_images'); ?>',
      baseParams: {},
      layout: 'border',
      fieldDefaults: {
        labelWidth: 108,
        labelSeparator: '',
        anchor: '98%'
      },
      border: false,
      fileUpload: true,
      items: [this.getDataPanel(), this.getTabPanel()] 
    });
    
    return this.pnlSlideImages;
  },
  
  getDataPanel: function() {
	var dsGroups = Ext.create('Ext.data.Store', {
      fields: ['id', 'text'],
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('slide_images/get_image_groups'); ?>',
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.cboGroups = Ext.create('Ext.form.ComboBox', {
      fieldLabel: 'Image Group:',
      store: dsGroups,
      name: 'group', 
      queryMode: 'local',
      displayField: 'text',
      valueField: 'id',
      editable: false
    });
    
    var pnlData = Ext.create('Ext.Panel', {
      region: 'north',
      bodyPadding: 10,
      title: '<?php echo lang('heading_title_data'); ?>',
      height: 100,
      border: false,
      layout: 'fit',
      items: [
      	{
      		layout: 'column',
      		border: false,
      		items: [
      			{
      				border: false,
        			columnWidth: .5,
        			items: [
  							{
                  layout: 'column',
                  border: false,
                  items: [
                    {
                      width: 200,
                      border: false,
                      items:[
                        {xtype:'radio', name: 'status', fieldLabel: '<?php echo lang('field_publish'); ?>', boxLabel: '<?php echo lang('status_enabled'); ?>' , inputValue: '1', checked: true}
                      ]
                    },
                    {
                      width: 80,
                      border: false,
                      items: [
                        {xtype:'radio', name: 'status', hideLabel: true, boxLabel: '<?php echo lang('status_disabled'); ?>', inputValue: '0'}
                      ]
                    }
                  ]
                },
                {
                  xtype: 'numberfield',
                  name: 'sort_order',
                  border: false,
                  fieldLabel: '<?php echo lang('field_order'); ?>',
                  value: 0
                }
        			]
      			},
      			{
      				border: false,
        			columnWidth: .5,
        			items: [
								this.cboGroups,
                {
                  xtype: 'textfield',
                  name: 'new_image_group', 
                  border: false,
                  fieldLabel: 'New Image Group:'
                }
        			]
      			}
      		]
      	}
      ]
    });
    
    return pnlData;
  },
  
  getTabPanel: function() {
    var tabImages = Ext.create('Ext.TabPanel', {
      region: 'center',
      border: false,
      defaults:{
       hideMode: 'offsets'
      },
      activeTab: 0,
      deferredRender: false
    });
    
    <?php
      foreach(lang_get_all() as $l)
      {
        echo 'this.' . $l['code'] . ' = Ext.create("Ext.Panel", {
          title: "' . $l['name'] . '",
          border: false,
          iconCls: "icon-' . $l['country_iso'] . '-win",
          layout: "anchor",
          bodyPadding: 8,
          items: [
            {
              layout: "column",
              border: false,
              width: 500,
              items: [
                {
                  xtype: "fileuploadfield",
                  width: 400,
                  fieldLabel: "' . lang('field_slide_image') . '",
                  name: "image' . $l['id'] . '",
                  allowBlank: false
                },
                {
                  xtype: "panel",
                  border: false,
                  html: \'<span sytle = "padding: 5px 0 0 10px;display:block;"><b style="padding-left: 10px;">' . lang('maximum_file_upload_size') . '</b></span>\'
                }
              ]
            },
            {xtype: "panel", border: false, width: 400, id: "uploaded_img' . $l['id'] . '", html: ""},
            {xtype: "textarea", id: "' . $l['code'] . '", fieldLabel: "' . lang('field_description') . '", width: 400, height: 150, name: "description[' . $l['id'] . ']"},
            {xtype: "textfield", fieldLabel: "' . lang('field_image_url') . '", width: 400, name: "image_url[' . $l['id'] . ']"}
          ]
        });
        
        tabImages.add(this.' . $l['code'] . ');
        ';
      }
    ?>
    
    return tabImages;
  },
  
  submitForm : function() {
    this.pnlSlideImages.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
        this.fireEvent('savesuccess', action.result.feedback); 
        this.close();
      },
      failure: function(form, action) {
        if (action.failureType != 'client') {         
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);      
        }         
      },
      scope: this
    });   
  }
});

/* End of file slide_images_dialog.php */
/* Location: ./templates/base/web/views/slide_images/slide_images_dialog.php */