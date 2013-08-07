/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @packageTomatoCart
 * @authorTomatoCart Dev Team
 * @copyrightCopyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @licensehttp://www.gnu.org/licenses/gpl.html
 * @linkhttp://tomatocart.com
 * @sinceVersion 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Checkout Class
 *
 * @package TomatoCart
 * @subpackagetomatocart
 * @categorytemplate-module-controller
 * @authorTomatoCart Dev Team
 * @linkhttp://tomatocart.com/wiki/
 */
jQuery.Toc.Checkout = function(config) {
    config = config || {};
    
    //all the checkout steps
    config.steps = {
        checkoutMethodForm : 1,
        billingInformationForm : 2,
        shippingInformationForm : 3,
        shippingMethodForm : 4,
        paymentInformationForm : 5,
        orderConfirmationForm : 6
    };
    
    //current step
    config.currentStep = 0;
    
    config.checkoutMethodBody = $("#checkoutMethodForm .collapse");
    config.billingInformationBody = $("#billingInformationForm .collapse");
    config.shippingInformationBody = $("#shippingInformationForm .collapse");
    config.shippingMethodBody = $("#shippingMethodForm .collapse");
    config.paymentInformationBody = $("#paymentInformationForm .collapse");
    config.orderConfirmationBody = $("#orderConfirmationForm .collapse");

    this.initialize(config);
};

/*
 * 
 */
jQuery.Toc.override(jQuery.Toc.Checkout, {

    /**
     * Send Ajax Request to Server
     * 
     * @param data
     */
    sendRequest : function(data) {
        data = data || {};
        
        $.extend(data, {
            type : 'post',
            dataType : 'json'
        });
        
        $.ajax(data);
    },

    /**
     * Set options to properties
     * 
     * @param options
     */
    setOptions : function(options) {
        for ( var m in options) {
            this[m] = options[m];
        }
    },

    /**
     * Initialize the Checkout Forms.
     */
    initialize : function(options) {
        // initialize options
        this.setOptions(options);
        
        // initialize checkout navigation
        this.iniCheckoutForms();
        
        //if the customer is logged on, load checkout method form
        if (this.logged_on == false) {
            this.loadCheckoutMethodForm();
        } else {
            this.loadBillingInformationForm();
        }
    },
    
    /**
     * Load Checkout Method Form
     */
    loadCheckoutMethodForm : function() {
        var $this = this;
        
        this.sendRequest({
            url : base_url + 'checkout/checkout/load_checkout_method_form',
            success : function(response) {
                //Set checkout method form content
                $('#checkoutMethodForm .accordion-body .accordion-inner').html(response.form);
                
                
                //show checkout method form
                $this.checkoutMethodBody.collapse('show');
                
                //update the current step
                $this.currentStep = $this.steps.checkoutMethodForm;
            }
        });
    },
    
    /**
     * Initialize Checkout Forms.
     */
    iniCheckoutForms : function() {
        var $this = this;

        // process the modify button
        $('#checkoutForm .accordion-heading a.modify').live('click', function(e) {
            e.preventDefault();
            
            var $me = $(this);
            var form = $me.parent().parent().attr('id');
            
            console.log($this.currentStep);
            console.log($this.steps[form]);
            
            //if the current step is large than the clicked page
            if ($this.currentStep > $this.steps[form]) {
                //hide the active panel
                $me.parent().parent().parent().find('> .accordion-group > .in').collapse('hide');

                //active the current pagel
                $me.parent().next().collapse('show');
                
                //set the current step
                $this.currentStep = $this.steps[form];
            }
        });
        
//-------------------------------------Checkout Method Form-----------------------------------------------

        // process btn new customer
        $('#btn-new-customer').live('click', function() {
            $this.loadBillingInformationForm();
        });

        // process btn login
        $('#btn-login').live('click', function(e) {
            e.preventDefault();
            
            $this.sendRequest({
                url : base_url + 'account/login/ajax_process',
                data : $('#email_address, #password'),
                success : function(response) {
                    if (response.success === true) {
                        window.location.reload();
                    } else {
                        alert(response.errors);
                    }
                },
                beforeSend : function() {
                    $("#checkoutMethodForm .accordion-body").mask('Loading...');
                },
                complete : function() {
                    $("#checkoutMethodForm .accordion-body").unmask();
                }
            });
        });

//-------------------------------------Billing Infomration Form-----------------------------------------------

        //create_billing_address
        $('#create_billing_address').live('click', function(e) {
            var $this = $(this);
            
            if ($('#sel_billing_address').length > 0) {
                if ($this.attr('checked') == 'checked') {
                    $('#billingAddressDetails').show();
                } else {
                    $('#billingAddressDetails').hide();
                }                
            } else {
                $this.attr('checked', true);
            }
        });

        // billing country change
        $('#billing_country').live('change', function() {
            $this.sendRequest({
                url : base_url + 'checkout/checkout/get_country_states',
                data : {
                    countries_id : $('#billing_country option:selected').val()
                },
                success : function(response) {
                    if (response.success == true) {
                        $('#li-billing-state select').html(response.options);
                    }
                },
                beforeSend : function() {
                    $("#billingInformationForm .accordion-body").mask('Loading...');
                },
                complete : function() {
                    $("#billingInformationForm .accordion-body").unmask();
                }
            });
        });

        //save billing form
        $('#btn-save-billing-form').live('click', function(e) {
            e.preventDefault();

            $this.saveBillingInformationForm();
        });

      //-------------------------------------Shipping Infomration Form-----------------------------------------------

        //create_shipping_address
        $('#create_shipping_address').live('click', function(e) {
            var $this = $(this);
            
            if ($('#sel_shipping_address').length > 0) {
                if ($this.attr('checked') == 'checked') {
                    $('#shippingAddressDetails').show();
                } else {
                    $('#shippingAddressDetails').hide();
                }                
            } else {
                $this.attr('checked', true);
            }
        });


        // shipping country change
        $('#shipping_country').live('change', function() {
            $this.sendRequest({
                url : base_url + 'checkout/checkout/get_country_states',
                data : {
                    countries_id : $('#shipping_country option:selected').val()
                },
                success : function(response) {
                    if (response.success == true) {
                        $('#li-shipping-state select').html(response.options);
                    }
                },
                beforeSend : function() {
                    $("#shippingInformationForm .accordion-body").mask('Loading...');
                },
                complete : function() {
                    $("#shippingInformationForm .accordion-body").unmask();
                }
            });
        });


    //save shipping form
    $('#btn-save-shipping-form').live('click', function(e) {
        e.preventDefault();
        
        $this.saveShippingInformationForm();
    });

    //-------------------------------------Shipping Method Form-----------------------------------------------
    
    //save shipping form
    $('#btn-save-shipping-method').live('click', function(e) {
        e.preventDefault();
        
        var shipping_methods = document.getElementsByName("shipping_mod_sel"); 
        var shipping_method = null;
        
        $.each(shipping_methods, function(index, method) {
            if (method.type == 'radio') {
                if (method.checked) {
                    shipping_method = method.value;
                }
            } else if (method.type == 'hidden') {
                shipping_method = method.value;
            }
        });
        
        if (shipping_method != null) {
            $this.sendRequest({
                url : base_url + 'checkout/checkout/save_shipping_method',
                data : {
                    shipping_mod_sel: shipping_method,
                    shipping_comments: $('#shipping_comments').val()
                },
                success : function(response) {
                    if (response.success == true)  {
                        // open billing information form
                        $this.loadPaymentInformationForm();
                    } 
                },
                beforeSend : function() {
                    $("#shippingMethodForm .accordion-body").mask('Loading...');
                },
                complete : function() {
                    $("#shippingMethodForm .accordion-body").unmask();
                }
            });
        } else {
            alert('Please select a shipping method!');
        }
    });

    //-------------------------------------Shipping Method Form-----------------------------------------------
    
    //save payment form
    $('#btn-save-payment-form').live('click', function(e) {
        e.preventDefault();
    
        var data = {
            payment_comments: $('#payment_comments').val()
        };
     
        if ($('#conditions').length > 0) {
            data.conditions = (($('#conditions').attr('checked') == 'checked') ? 1 : 0);
        } 
    
        //if (this.isTotalZero == false) {
        var payment_methods = document.getElementsByName("payment_method"); 
        var payment_method = null;
    
        $.each(payment_methods, function(index, method) {
            if (method.type == 'radio') {
                if (method.checked) {
                    payment_method = method.value;
                }
            } else if (method.type == 'hidden') {
                payment_method = method.value;
            }
        });
    
        if (payment_method != null) {
            data['payment_method'] = payment_method;
            
            //get all the inputs
            var div_payment = $('#payment_method_' + payment_method);
            var inputs = div_payment.find('input');
            if (inputs.length > 0) {
                $.each(inputs, function(index, input){
                    if (input.type == 'text') {
                        data[input.name] = input.value;
                    } else if ((input.type == 'checkbox') || (input.type == 'radio')) {
                        if (input.checked == true) {
                            data[input.name] = input.value;
                        }
                    }
                });
            }
            
            var selects = div_payment.find('select');
            $.each(selects, function(index, select) {
                data[select.name] = select.options[select.selectedIndex].value;
            });
        } else {
            alert('Please select a payment method!');
        }
        //}
    
        $this.sendRequest({
            url : base_url + 'checkout/checkout/save_payment_method',
            data : data,
            success : function(response) {
                // open billing information form
                $this.loadOrderConfirmationForm();
            },
            beforeSend : function() {
                $("#paymentMethodForm .accordion-body").mask('Loading...');
            },
            complete : function() {
                $("#paymentMethodForm .accordion-body").unmask();
            }
        });
    });
    },

    /**
     * Load Billing Information Form
     */
    loadBillingInformationForm : function() {
        var $this = this;
        
        this.sendRequest({
            url : base_url + 'checkout/checkout/load_billing_form',
            data: $('input[name=checkout_method]:checked'),
            success : function(response) {
                if (response.success == true) {
                    //set billing information content 
                    $('#billingInformationForm .accordion-inner').html(response.form);
                    
                    //if the customer is logged on, load checkout method form
                    if ($this.logged_on == false) {
                        // close checkout method form
                        $this.checkoutMethodBody.collapse('hide');
                    }
                    
                    // open billing information form
                    $this.billingInformationBody.collapse('show');
                    
                    //update the current step
                    $this.currentStep = $this.steps.billingInformationForm;
                }
            },
            beforeSend : function() {
                $("#checkoutMethodForm .accordion-body").mask('Loading...');
            },
            complete : function() {
                $("#checkoutMethodForm .accordion-body").unmask();
            }
        });
    },

    /**
     * Save Billing Information Form
     */
    saveBillingInformationForm: function() {
        var $this = this;
        
        this.sendRequest({
            url : base_url + 'checkout/checkout/save_billing_form',
            data: $('input[name=checkout_method]:checked, input[name=billing_email_address], input[name=billing_password], input[name=confirmation], input[name=billing_gender]:checked, input[name=billing_firstname], input[name=billing_company], input[name=billing_lastname], input[name=billing_street_address], input[name=billing_suburb], input[name=billing_postcode], input[name=billing_city], select[name=billing_country], select[name=billing_state], input[name=billing_telephone], input[name=billing_fax], input[name=create_billing_address]:checked, input[name=ship_to_this_address]:checked, #sel_billing_address'),
            success : function(response) {
                if (response.success == true) {
                    if ($('#ship_to_this_address').attr('checked') == 'checked') {
                        $this.loadShippingMethodForm();
                    } else {
                        $this.loadShippingInformationForm();
                    }
                } else {
                    alert(response.errors.join("\n"));
                }
            },
            beforeSend : function() {
                $("#billingInformationForm .accordion-body").mask('Loading...');
            },
            complete : function() {
                $("#billingInformationForm .accordion-body").unmask();
            }
        });
    },

    /**
     * Load Shipping Information Form
     */
    loadShippingInformationForm : function() {
        var $this = this;
        
        this.sendRequest({
            url : base_url + 'checkout/checkout/load_shipping_form',
            success : function(response) {
                // open billing information form
                $('#shippingInformationForm .accordion-inner').html(response.form);
                
                // open billing information form
                $this.billingInformationBody.collapse('hide');
                
                // open billing information form
                $this.shippingInformationBody.collapse('show');
                
                //update the current step
                $this.currentStep = $this.steps.shippingInformationForm;
            },
            beforeSend : function() {
                $("#billingInformationForm .accordion-body").mask('Loading...');
            },
            complete : function() {
                $("#billingInformationForm .accordion-body").unmask();
            }
        });
    },

    /**
     * Save Billing Information Form
     */
    saveShippingInformationForm: function() {
        var $this = this;
        
        this.sendRequest({
            url : base_url + 'checkout/checkout/save_shipping_form',
            data: $('input[name=shipping_gender]:checked, input[name=shipping_firstname], input[name=shipping_lastname], input[name=shipping_company], input[name=shipping_street_address], input[name=shipping_suburb], input[name=shipping_postcode], input[name=shipping_city], select[name=shipping_country], select[name=shipping_state], input[name=shipping_telephone], input[name=shipping_fax], input[name=create_shipping_address]:checked, #sel_shipping_address'),
            success : function(response) {
                if (response.success == true) {
                    $this.loadShippingMethodForm();
                } else {
                    alert(response.errors.join("\n"));
                }
            },
            beforeSend : function() {
                $("#billingInformationForm .accordion-body").mask('Loading...');
            },
            complete : function() {
                $("#billingInformationForm .accordion-body").unmask();
            }
        });
    },

    loadShippingMethodForm: function() {
        var $this = this;
        
        this.sendRequest({
            url : base_url + 'checkout/checkout/load_shipping_method_form',
            success : function(response) {
                if (response.success == true) {
                    //set content
                    $('#shippingMethodForm .accordion-inner').html(response.form);
                    
                    if ($('#ship_to_this_address').attr('checked') == 'checked') {
                        // open billing information form
                        $this.billingInformationBody.collapse('hide');
                    } else {
                        // open billing information form
                        $this.shippingInformationBody.collapse('hide');
                    }
                    
                    // open billing information form
                    $this.shippingMethodBody.collapse('show');

                    //update the current step
                    $this.currentStep = $this.steps.shippingMethodForm;
                } else {
                    alert(response.errors.join("\n"));
                }
            },
            beforeSend : function() {
                $("#billingInformationForm .accordion-body").mask('Loading...');
            },
            complete : function() {
                $("#billingInformationForm .accordion-body").unmask();
            }
        });
    },

    loadPaymentInformationForm : function() {
        var $this = this;
        
        this.sendRequest({
            url : base_url + 'checkout/checkout/load_payment_information_form',
            success : function(response) {
                $('#paymentInformationForm .accordion-inner').html(response.form);
                
                $this.shippingMethodBody.collapse('hide');

                $this.paymentInformationBody.collapse('show');

                //update the current step
                $this.currentStep = $this.steps.paymentInformationForm;
            }
        });
    },

    loadOrderConfirmationForm: function() {
        var $this = this;
        
        this.sendRequest({
            url : base_url + 'checkout/checkout/load_order_confirmation_form',
            success : function(response) {
                if (response.success == true) {
                    $('#orderConfirmationForm .accordion-inner').html(response.form);

                    // close checkout method form
                    $this.paymentInformationBody.collapse('hide');
                    
                    // open billing information form
                    $this.orderConfirmationBody.collapse('show');

                    //update the current step
                    $this.currentStep = $this.steps.orderConfirmationForm;
                } else {
                    alert(response.errors.join("\n"));
                }
            },
            beforeSend : function() {
                $("#orderConfirmationForm .accordion-body").mask('Loading...');
            },
            complete : function() {
                $("#orderConfirmationForm .accordion-body").unmask();
            }
        });
    }
});