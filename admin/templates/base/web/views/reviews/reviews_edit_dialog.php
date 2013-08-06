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

Ext.define('Toc.reviews.ReviewsEditDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'reviews-dialog-win';
    config.title = '<?php echo lang("action_heading_new_special"); ?>';
    config.layout = 'fit';
    config.width = 525;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-reviews-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function () {
          this.submitForm();
          this.disable();
        }, 
        scope: this
      }, 
      {
        text: TocLanguage.btnClose,
        handler: function () {
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function (id) {
    var reviewsId = id || null;
    
    if (reviewsId > 0) {
      this.frmReviews.form.baseParams['reviews_id'] = reviewsId;
      
      this.frmReviews.load({
        url: '<?php echo site_url('reviews/load_reviews'); ?>',
        success: function (form, action) {
          if ( Ext.isEmpty(action.result.data.ratings) ) {
            this.pnlAverageRating = Ext.create('Ext.Panel', {
              layout: {
                type: 'table',
                columns: 8
              },
              border: false,
              items: [
                {xtype: 'displayfield', fieldLabel: '<?php echo lang("field_detailed_rating"); ?>'},
                {xtype: 'label', text: '<?php echo lang("rating_bad"); ?>', width: 30}, 
                {xtype: 'radio', name: 'detailed_rating', inputValue: '1', checked: action.result.data.detailed_rating == 1, width: 20, style: 'text-align:center;'},
                {xtype: 'radio', name: 'detailed_rating', inputValue: '2', checked: action.result.data.detailed_rating == 2, width: 20, style: 'text-align:center;'},
                {xtype: 'radio', name: 'detailed_rating', inputValue: '3', checked: action.result.data.detailed_rating == 3, width: 20, style: 'text-align:center;'},
                {xtype: 'radio', name: 'detailed_rating', inputValue: '4', checked: action.result.data.detailed_rating == 4, width: 20, style: 'text-align:center;'},
                {xtype: 'radio', name: 'detailed_rating', inputValue: '5', checked: action.result.data.detailed_rating == 5, width: 20, style: 'text-align:center;'},
                {xtype: 'label', text: '<?php echo lang("rating_good"); ?>', width: 30}
              ]
            });
            
            this.frmReviews.add(this.pnlAverageRating);   
          } else {
            var items = [];
            for (var i = 0; i < action.result.data.ratings.length; i++){
              var n = action.result.data.ratings[i].customers_ratings_id;
              var name = "ratings_value" + n;
              
              items.push({xtype: 'displayfield', fieldLabel: action.result.data.ratings[i].name + ':'});
              items.push({xtype: 'label', text: '<?php echo lang("rating_bad"); ?>', width: 30}); 
              items.push({xtype: 'radio', name: name, inputValue: '1', checked: action.result.data.ratings[i].value == 1, width: 20, style: 'text-align:center;'});
              items.push({xtype: 'radio', name: name, inputValue: '2', checked: action.result.data.ratings[i].value == 2, width: 20, style: 'text-align:center;'});
              items.push({xtype: 'radio', name: name, inputValue: '3', checked: action.result.data.ratings[i].value == 3, width: 20, style: 'text-align:center;'});
              items.push({xtype: 'radio', name: name, inputValue: '4', checked: action.result.data.ratings[i].value == 4, width: 20, style: 'text-align:center;'});
              items.push({xtype: 'radio', name: name, inputValue: '5', checked: action.result.data.ratings[i].value == 5, width: 20, style: 'text-align:center;'});
              items.push({xtype: 'label', text: '<?php echo lang("rating_good"); ?>', width: 30});
            }
            
            var pnlDetailedRatings = Ext.create('Ext.Panel', {
              layout: {
                type: 'table',
                columns: 8
              },
              border: false,
              defaultType: 'radio',
              items: items
            });
            
            this.frmReviews.add(pnlDetailedRatings);
          }
          
          this.frmReviews.add(this.getPnlStatus(action.result.data.reviews_status));
          this.frmReviews.add(this.txtRating);
          this.frmReviews.form.setValues(action.result.data);
          
          Toc.reviews.ReviewsEditDialog.superclass.show.call(this);
        },
        failure: function (form, action) {
          Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this
      });
    } else {
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmReviews = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('reviews/save_reviews'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelWidth: 150,
        anchor: '97%',
        labelSeparator: '' 
      },
      items: [
        {xtype: 'displayfield', fieldLabel: '<?php echo lang("field_product"); ?>', name: 'products_name'},
        {xtype: 'displayfield', fieldLabel: '<?php echo lang("field_author"); ?>', name: 'customers_name'},
        {xtype: 'displayfield', fieldLabel: '<?php echo lang("field_summary_rating"); ?>', name: 'reviews_rating'}
      ]
    });
    
    this.txtRating = {xtype: 'textarea', fieldLabel: '<?php echo lang("field_review"); ?>', name: 'reviews_text', height: 150, allowBlank: false};
    
    return this.frmReviews;
  },
  
  getPnlStatus: function(status) {
    return Ext.create('Ext.Panel', {
      layout: 'column',
      border: false,
      items: [
        {
          layout: 'anchor',
          style: 'padding-right: 10px;',
          border: false,
          items: [
            {
              xtype: 'radio', 
              name: 'reviews_status', 
              fieldLabel: '<?php echo lang('field_review_status'); ?>', 
              inputValue: '1', 
              boxLabel: '<?php echo lang('field_status_enabled'); ?>', 
              checked: true,
              anchor: '',
              checked: status == 1
            } 
          ] 
        },
        {
          layout: 'anchor',
          border: false,
          items: [
            {
              xtype: 'radio', 
              hideLabel: true, 
              name: 'reviews_status', 
              inputValue: '0', 
              boxLabel: '<?php echo lang('field_status_disabled'); ?>', 
              width: 150,
              checked: status == 0
            }
          ]
        }
      ]
    });
  },
  
  submitForm: function () {
    var fields = this.frmReviews.form.getFieldValues();
    
    this.frmReviews.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      params: fields,
      success: function (form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },
      failure: function (form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this
    });
  }
});

/* End of file reviews_edit_dialog.php */
/* Location: ./templates/base/web/views/reviews/reviews_edit_dialog.php */