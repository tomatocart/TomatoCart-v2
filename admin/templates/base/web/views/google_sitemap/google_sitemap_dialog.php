<?php
/*
  $Id: google_sitemap_dialog.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com

  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
 */
?>
Ext.define('Toc.google_sitemap.GoogleSitemapDialog', {
  extend: 'Ext.Window', 
  constructor: function(config) {
    config = config || {};
      
    config.id = 'google_sitemap-win';
  	config.title = '<?php echo lang('heading_title'); ?>';
  	config.width = 600;
  	config.height = 440;
  	config.iconCls = 'icon-google_sitemap-win';
  	config.layout = 'fit';
  	config.items = this.buildForm();
    
  	config.buttons = [{
      text: TocLanguage.btnClose,
      handler: function() { 
        this.close();
      },
      scope: this
    }];
      
  this.callParent([config]);
  },
  
  buildForm: function() {
    var dataStore = Ext.create('Ext.data.Store', {
      fields: ['id', 'text'],
      data: [
         {id:"daily",text:"<?php echo lang('field_daily'); ?>"},
         {id:"weekly",text:"<?php echo lang('field_weekly'); ?>"},
         {id:"monthly",text:"<?php echo lang('field_month'); ?>"},
         {id:"yearly",text:"<?php echo lang('field_year'); ?>"}
       ]
    });
    
    this.fsCreateSitemap = Ext.create('Ext.form.FieldSet', {
      title: '<?php echo lang('button_create_sitemaps'); ?>',
      autoHeight: true,      
      items: [
        { 
          xtype: 'panel',
          layout: 'column',
          labelWidth: 130,
          border: false,
          items: [
            {  
              xtype: 'panel',
              columnWidth: 0.55,
              labelWidth: 130,
              border: false,
              labelSeparator: ' ',
              defaultType: 'combo',   
              defaults: {
              	xtype: 'combo', 
              	store: dataStore, 
            	  queryMode: 'local', 
              	valueField: 'id', 
              	displayField: 'text', 
              	value: 'daily', 
              	allowBlank: false, 
              	editable: false, 
              	triggerAction: 'all', 
              	anchor: '90%'
              },
              items: [ 
              	{            
                	fieldLabel: '<?php echo lang("field_categories"); ?>', 
                	hiddenName: 'categories_frequency' 
                }, 
                {fieldLabel: '<?php echo lang("field_products"); ?>', hiddenName: 'products_frequency'},
                {fieldLabel: '<?php echo lang("field_articles"); ?>', hiddenName: 'articles_frequency'}
              ] 
            },
            { 
              xtype: 'panel',
              columnWidth: 0.45,
              border: false,
              labelSeparator: ' ',
              defaultType: 'numberfield',
              labelWidth: 40,
              defaults: { 
            	fieldLabel: '<?php echo lang('field_priority'); ?>', 
            	decimalPrecision: 2, 
            	allowNegative: false, 
            	allowBlank: false, 
            	maxValue: 1, 
            	minValue: 0, 
            	anchor: '90%'
            	},
            	items: 
            	[
                  {name: 'categories_priority', value: 0.5}, 
                  {name: 'products_priority', value: 0.5}, 
                  {name: 'articles_priority', value: 0.25}
              ]
            }
          ] 
        },
        {
        	xtype: 'button',
      		text: '<?php echo lang('button_create_sitemaps'); ?>',
      		handler: function(){
        		this.createSitemap();
      		},
      		scope:this
      	}
      ]
    });
    
    this.fsSubmitSitemap = Ext.create('Ext.form.FieldSet',{
      title: '<?php echo lang('button_submit_sitemaps'); ?>',
      autoHeight: true,
      items: [
      { 
        xtype: 'displayfield', 
        hideLabel: true, 
        encodeHtml:false, 
        value: '<?php echo lang('introduction_google_sitemaps_submission'); ?>'
      },
      {
        xtype: 'button',
        text: '<?php echo lang('button_submit_sitemaps'); ?>',
    		handler: function(){
      		this.submitSitemap();
    		},
    		scope:this
      }]
    });
  
  	this.frmGoogleSitemap = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('google_sitemap/create_google_sitemap'); ?>',
      style: 'padding: 5px',
      border: false,
      items: [this.fsSubmitSitemap, this.fsCreateSitemap]
    });
    
    return this.frmGoogleSitemap;
  },
  
  createSitemap: function() {
		this.frmGoogleSitemap.form.submit({
      params: {
        module: 'google_sitemap',
        action: 'create_google_sitemap'
      },
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
        this.fireEvent('saveSuccess', action.result.feedback);
      },    
      failure: function(form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },  
      scope: this
    });  
	},
  
  submitSitemap: function() {
	  window.open("<?php echo 'http://www.google.com/webmasters/sitemaps/ping?sitemap=' . rtrim(rtrim(base_url(),'/'),'admin') . 'sitemapsIndex.xml'; ?>", "google","resizable=1,statusbar=5,width=400,height=200,top=0,left=50,scrollbars=yes");
	}
	
});
