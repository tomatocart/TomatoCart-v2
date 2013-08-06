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
  
  <div id="x-login-panel">
    <img src="<?php echo base_url();?>templates/base/web/images/s.gif" class="login-logo abs-position" />
    
    <div class="login-features abs-position">
      <p>The professional and innovative open source online shopping cart solution</p>
      <p align="justify">Equipped with modern technology AJAX and Rich Internet Applications (RIA) Framework ExtJS, TomatoCart offer significant usability improvements and make interacting with the web interfaces faster and more efficient.</p>
    </div>
    
    <img src="<?php echo base_url();?>templates/base/web/images/s.gif" class="login-screenshot abs-position" />
    
    <span class="login-supported abs-position">
      <b>Supported Browsers</b><br />
      <a href="http://www.mozilla.org/download.html" target="_blank">Firefox 2+</a><br />
      <a href="http://www.microsoft.com/windows/downloads/ie/getitnow.mspx" target="_blank">Internet Explorer 7+</a><br />
      <a href="http://www.opera.com/download/" target="_blank">Opera 9+</a>
    </span>
  
    <div id="x-login-form" class="x-login-form abs-position"><a id="forget-password"><?php echo lang("label_forget_password"); ?></a></div>
  </div>

  <script type="text/javascript">
    Ext.onReady(function(){
      Ext.BLANK_IMAGE_URL = '<?php echo base_url(); ?>templates/base/web/images/s.gif';
      Ext.EventManager.onWindowResize(centerPanel);
      
      var loginPanel = Ext.get("x-login-panel");
      
      centerPanel();
      
      Ext.namespace("Toc");
      Toc.Languages = [];
      <?php 
        foreach (lang_get_all() as $l) {
          echo 'Toc.Languages.push({"id" : "' . $l['code'] . '", "text" : "' . $l['name'] . '"});';
        }
      ?>
      
      var cboLanguage = new Ext.form.ComboBox({
        store: new Ext.data.Store({
          fields: ['id', 'text'],
          data : Toc.Languages
        }),
        fieldLabel: '<?php echo lang("field_language"); ?>',
        name: 'language',
        hiddenName: 'language',
        displayField: 'text',
        valueField: 'id',
        labelSeparator: ' ',
        queryMode: 'local',
        displayField: 'text',
        valueField: 'id',
        triggerAction:'all',
        forceSelection: true,
        editable: false,
        value: '<?php echo $language; ?>',
        style: 'background-color: transparent;',
        listeners: {
          select: function() {
            document.location = '<?php echo site_url('index'); ?>?admin_language=' + cboLanguage.getValue();
          }
        }
      });

      var txtUserName = null;
      var frmlogin = Ext.create('Ext.form.Panel', {
        bodyPadding: 5,
        width: 335,
        url: '<?php echo site_url('login/process'); ?>',
        layout: 'anchor',
        defaults: {
          anchor: '100%'
        },
        defaultType: 'textfield',
        items: [
          cboLanguage,
          txtUserName = new Ext.form.TextField({
            name: 'user_name', 
            fieldLabel: '<?php echo lang("field_username"); ?>', 
            labelSeparator: ' ', 
            allowBlank:false,
            listeners: {
              specialkey: function(field, e) {
                if (e.getKey() == e.ENTER) {
                  login();
                }
              }
            }
          }),
          {
            name: 'user_password', 
            fieldLabel: '<?php echo lang("field_password"); ?>', 
            inputType: 'password', 
            labelSeparator: ' ', 
            allowBlank:false, 
            listeners: {
              specialkey: function(field, e) {
                if (e.getKey() == e.ENTER) {
                  login();
                }
              }
            }
          }
        ],
        buttons: [{
          text: '<?php echo lang("button_login"); ?>',
          handler: login, 
          scope: this
        }],
        listeners : {
          render: function() {txtUserName.focus(false, true);}
        },
        renderTo: 'x-login-form'
      });
      
      Ext.get('forget-password').on('click', function() {
        forgetPassword();
      });
      
      function centerPanel(){
        var xy = loginPanel.getAlignToXY(document, 'c-c');
        positionPanel(loginPanel, xy[0], xy[1]);
      }
      
      function login() {
        frmlogin.form.submit({
          success: function (form, action) {
            window.location = '<?php echo site_url('index'); ?>';
          },
          failure: function (form, action) {
            if (action.failureType != 'client') {
              Ext.Msg.alert('<?php echo lang('ms_error'); ?>', action.result.error);
            }
          },
          scope: this
        });
      }
      
      function positionPanel(el, x, y){
        if(x && typeof x[1] == 'number') {
          y = x[1];
          x = x[0];
        }
        
        el.pageX = x;
        el.pageY = y;
        
        if(x === undefined || y === undefined){ // cannot translate undefined points
          return;
        }
        
        if(y < 0) { 
          y = 10;
        }
        
        var p = el.translatePoints(x, y);
        el.setLocation(p.left, p.top);
        
        return el;
      }
      
      function removeLoadMask() {
        var loading = Ext.get('x-loading-panel');
        var mask = Ext.get('x-loading-mask');
        loading.hide();
        mask.hide();
      }
      
      removeLoadMask(); 
    });  
    
    function forgetPassword() {
      Ext.Msg.prompt('<?php echo lang('label_forget_password'); ?>', '<?php echo lang("ms_forget_password_text"); ?>', function(btn, email){
        if (btn = 'ok' && !Ext.isEmpty(email)) {
          Ext.get('x-login-panel').mask('<?php echo lang("ms_sending_email"); ?>'); 
          
          Ext.Ajax.request({
            url: '<?php echo site_url('login/get_password'); ?>',
            params: {
              email_address: email
            },
            callback: function(options, success, response) {
              Ext.get('x-login-panel').unmask();
            
              result = Ext.decode(response.responseText);
              
              Ext.Msg.alert('<?php echo lang('ms_feedback'); ?>', result.feedback);
            },
            scope: this
          }); 
        }
      });
    }
  </script>