var WPJB = WPJB || {};

WPJB.clickandpledge = {
    
    loadOnce: function() {
        var $ = jQuery;
        
        if($(".wpjb-script-external-clickandpledge").length > 0) {
            return;
        }
   
       
    },
    
    error: function(error) {
        var field = null;
        
        switch(error.code) {
            case "invalid_cvc":
            case "incorrect_cvc":
                field = "cvc";
                break;
            case "invalid_expiry_month":
            case "invalid_expiry_year":
            case "expired_card":
                field = "expiration";
                break;
            default:
                field = "card_number";
        }
        
        return field;
    },
    
    response: function(status, response) {
        var $ = jQuery;
        var $form = $('.wpjb-payment-form');

        $form.find(".wpjb-flash-error").remove();
        $form.find(".wpjb-flash-info").remove();

        if (response.error) {
            
            var field = WPJB.clickandpledge.error(response.error);
            var form = new WPJB.form(".wpjb-payment-form");
            
            form.addError(wpjb_payment_lang.form_error);
            form.addFieldError(".wpjb-element-name-"+field, response.error.message);

            $(".wpjb-place-order-wrap .wpjb-place-order").show();
            $(".wpjb-place-order-wrap .wpjb-icon-spinner").css("visibility", "hidden");
            
            return;
        } 

       // $form.find("form").append($("<input />").attr("type", "hidden").attr("name", "stripe_token").val(response.id));
            
        WPJB.order.placeOrder(undefined, {context: WPJB.clickandpledge});
    },
    
    charge: function(response) {  
        var $ = jQuery;
        var data = {
            echo: "1",
            action: "wpjb_payment_accept",
            engine: "clickandpledge_payment",
            id: response.token_id,
            token_id: response.payment_id,
			token_amount: response.token_amount,
			token_msg: response.token_msg
        };

        $.ajax({
            url: wpjb_payment_lang.ajaxurl,
            cache: false,
            type: "POST",
            data: data,
            dataType: "json",
            success: function(response) {
                var result = $("#wpjb-checkout-success");
                
                result.find(".wpjb-clickandpledge-pending").hide();
                
                if(response.external_id) {
                    result.find(".wpjb-flash-info").removeClass("wpjb-none");
                    result.find(".wpjb-flash-info .wpjb-flash-body").html(response.message);
                } else {
                    result.find(".wpjb-flash-error").removeClass("wpjb-none");
                    result.find(".wpjb-flash-error .wpjb-flash-body").html(response.message);
                }

            }
        });
        
    },
    
    placeOrder: function(e) {
        
        e.preventDefault(); var $ = jQuery;
		var currentTime = new Date();
		var year = currentTime.getFullYear();
		var month = "0"+currentTime.getMonth() + 1;
	var vcvv = $("#clickandpledge_cvc").val();
		if($('input[name=clickandpledge_onetimeonly]:checked').val()== "clickandpledge_Recurring" ||($("#clickandpledge_onetimeonly").val() == "clickandpledge_Recurring" && $('input[name=clickandpledge_onetimeonly]:checked').val() == undefined) )
			{
				
			
			if($("#clickandpledge_nooftimes").val()=="")
			  {
				 alert("Please enter Number of installments");
				 $("#clickandpledge_nooftimes").focus();
				  return false;
			  }
				else if($("#clickandpledge_nooftimes").val()!= "" && $("#clickandpledge_nooftimes").val()<= 1){
					alert("Please enter number of installments value greater than 1");
					$("#clickandpledge_nooftimes").focus();
					return false;
				}
				else if($("#clickandpledge_nooftimes").val()!= "" && $("#clickandpledge_maxinst").val()!=""){
						if(parseInt($("#clickandpledge_nooftimes").val()) > parseInt($("#clickandpledge_maxinst").val()))
							{
								alert("Please enter number of installments between 2 to "+$("#clickandpledge_maxinst").val());
								 $("#clickandpledge_nooftimes").focus();
								 return false;
							} }
					   if($("#clickandpledge_nooftimes").val()!= "" && $("#clickandpledge_indefinite").val() != "on" && $("#recurring_select").val() == "Installment" && $("#clickandpledge_maxinst").val() == "" && $("#clickandpledge_nooftimes").val()  > 998)
							{
								alert("Please enter number of installments between 2 to 998");
								 $("#clickandpledge_nooftimes").focus();
								 return false;
							}
				else if($("#clickandpledge_nooftimes").val()!= "" && $("#clickandpledge_indefinite").val() == "on" && $("#recurring_select").val() == "Installment" && $("#clickandpledge_nooftimes").val()  > 998)
					{
						alert("Recurring type installment not allow indefinite number of payments");
								 $("#recurring_select").focus();
								 return false;
					}
					
				if($("input[name='cnp_payment_method_selection']:checked"). val()!= "CreditCard" &&
					$("input[name='cnp_payment_method_selection']:checked"). val() != "eCheck" &&
                   $('input[name=cnp_payment_method_selection]:checked').val()!="gpay" &&
			   $('input[name=cnp_payment_method_selection]:checked').val()!="paypal" &&
			   $('input[name=cnp_payment_method_selection]:checked').val()!="ba")
					{
						alert("Sorry but recurring payments are not supported with this payment method");
								 $("#clickandpledge_onetimeonly").focus();
								 return false;
					}
				   
			}
      if($('input[name=cnp_payment_method_selection]:checked').val()=="CreditCard"){
		var cardno = /^(?:3[47][0-9]{13})$/;
  
		  if($("#clickandpledge_nameOnCard").val()=="")
			  {
				  alert("Please enter Name on Card");
				  $("#clickandpledge_nameOnCard").focus();
				  return false;
			  }
		   if($("#clickandpledge_cardNumber").val()=="")
			  {
				  alert("Please enter Card Number");
				  $("#clickandpledge_cardNumber").focus();
				  return false;
			  }
		  
		  if($("#clickandpledge_cvc").val()=="")
			  {
				  alert("Please enter CVV");
				  $("#clickandpledge_cvc").focus();
				  return false;
			  }
		  
		  if($.isNumeric(vcvv) === false) {
       
           alert("Please enter Numbers only in card Verification(CVV)");
				  $("#clickandpledge_cvc").focus();
				  return false;
   			}
		   if($("#clickandpledge_cardNumber").val()!="" && $("#clickandpledge_cvc").val()!="")
			   {
				   if($("#clickandpledge_cardNumber").val().match(cardno))
					   {
						   if($("#clickandpledge_cvc").val().length <4)
							   {
								   alert("Not a valid CVV");
								    $("#clickandpledge_cvc").focus();
				 					 return false;
							   }
					   }
			  
			  }
		  
		  if($("#clickandpledge_cardExpYear").val() == year)
			  {
				 if($("#clickandpledge_cardExpMonth").val() < month)
					 {
						 alert("Please enter valid Expiration");
						 $("#clickandpledge_cardExpYear").focus(); 
						 return false;
					 }
			  }
	     }
		else if($('input[name=cnp_payment_method_selection]:checked').val()=="eCheck"){
			 if($("#clickandpledge_echeck_NameOnAccount").val()=="")
			  {
				  alert("Please enter name on account");
				  $("#clickandpledge_echeck_NameOnAccount").focus();
				  return false;
			  }
			 if($("#clickandpledge_echeck_CheckNumber").val()=="")
			  {
				  alert("Please enter check number");
				  $("#clickandpledge_echeck_CheckNumber").focus();
				  return false;
			  }
			if($("#clickandpledge_echeck_RoutingNumber").val()=="")
			  {
				  alert("Please enter routing number");
				  $("#clickandpledge_echeck_RoutingNumber").focus();
				  return false;
			  }
			if($("#clickandpledge_echeck_AccountNumber").val()=="")
			  {
				  alert("Please enter account number");
				  $("#clickandpledge_echeck_AccountNumber").focus();
				  return false;
			  }
			if($("#clickandpledge_echeck_retypeAccountNumber").val()=="")
			  {
				  alert("Please enter retype account number");
				  $("#clickandpledge_echeck_retypeAccountNumber").focus();
				  return false;
			  }
		}
       else if($('input[name=cnp_payment_method_selection]:checked').val()!="eCheck" &&
			   $('input[name=cnp_payment_method_selection]:checked').val()!="CreditCard" &&
			   $('input[name=cnp_payment_method_selection]:checked').val()!="gpay" &&
			   $('input[name=cnp_payment_method_selection]:checked').val()!="paypal" &&
			   $('input[name=cnp_payment_method_selection]:checked').val()!="ba"){
		   if($("#clickandpledge_reference_number").val()=="")
			  {
				  alert("Please enter reference number");
				  $("#clickandpledge_reference_number").focus();
				  return false;
			  }
	   }
       var $form = $('.wpjb-payment-form');
        
        $(".wpjb-place-order-wrap .wpjb-place-order").fadeOut("fast");
        $(".wpjb-place-order-wrap .wpjb-icon-spinner").css("visibility", "visible");
        
        var form = new WPJB.form(".wpjb-payment-form");
         form.clearErrors();
         WPJB.order.placeOrder(undefined, {context: WPJB.clickandpledge});
      
    },
    
    placeOrderSuccess: function(response) {
       
        var $ = jQuery;
        var charge = $.extend(response, {
            payment_id: $("#wpjb-clickandpledge-payment-id").val(),
            token_id: $("#wpjb-clickandpledge-id").val(),
			token_amount: $("#wpjb-clickandpledge-amount").val(),
			token_msg: $("#wpjb-clickandpledge-responsemsg").val()
        });
        
        WPJB.clickandpledge.charge(charge);
    }
}

jQuery(function($) {     
    $(".wpjb-place-order").unbind("click").bind("click", WPJB.clickandpledge.placeOrder);
    WPJB.clickandpledge.loadOnce();
    	jQuery('#clickandpledge_nooftimes').keypress(function(e) {
						var a = [];
						var k = e.which;

						for (i = 48; i < 58; i++)
							a.push(i);

						if (!(a.indexOf(k)>=0))
							e.preventDefault();
					});
});    
jQuery("#submitDonation_stripePay_link").click(processClick1_Stripe);
jQuery("#submitDonation_stripePay").click(processClick1_Stripe);
function processClick1_Stripe() {
  var cnpdonationamt = getcnpjbtotalamount();

  var finalamtt = parseInt(cnpdonationamt);

     Show_StripePaymentInterface(finalamtt);
}

function Show_StripePaymentInterface(cnpdonationamt) {
   
 
    var finalamt = cnpdonationamt;
//console.log("finalamount"+finalamt);
    // var finalamt = (finalamt * 1000) / 100;
//console.log(finalamt);

   finalamt = parseInt(finalamt.toFixed());
    var currencycodeiso = "usd";
    var pk = "pk_test_51JkXsiH8mU0lDjNKzvzyu2nqrhY34ZCg6FOd2N2ZNgnO50YnMbKSQE69d2aCVzs8fQ48w0Ez81YCWUnp0ingT10T00ncFMd3PN";
    var stripe = Stripe(pk, {
        apiVersion: "2020-08-27",
    });       

    paymentRequest = stripe.paymentRequest({
        country: "US",
        currency: currencycodeiso,
        total: {
            label: "Total Charge",
            amount: finalamt,
        },
        requestPayerName: true,
        requestPayerEmail: true,
    });

    var elements = stripe.elements();
    var prButton = elements.create("paymentRequestButton", {
        paymentRequest: paymentRequest,
    });
    paymentRequest.canMakePayment().then(function (result) {
        if (result) {
            var apay = result["applePay"];
            var gpay = result["googlePay"];
            var link = result["link"];
            if (apay == true || gpay == true || link == true) {
                paymentRequest.show();
            }
        }
    });

    paymentRequest.on("paymentmethod", function (ev) {
 //console.log("Completed-paymentMethod.id:" + ev.paymentMethod.id);
 var cnppaymntintent =  CreateaPaymentIntent();
   console.log("cnppaymntintentvalue:" + cnppaymntintent);
        stripe.confirmCardPayment(
            cnppaymntintent,
            { payment_method: ev.paymentMethod.id },
            { handleActions: false }
        ).then(function (confirmResult) {
            if (confirmResult.error) {
                console.log("confirmResult.error:" + JSON.stringify(confirmResult.error));
                // Report to the browser that the payment failed, prompting it to
                // re-show the payment interface, or show an error message and close
                // the payment interface.
                ev.complete("fail");
            } else {
               console.log("success");
                // Report to the browser that the confirmation was successful, prompting
                // it to close the browser payment method collection interface.
    jQuery("#wpjbgpaypymntintent").val(confirmResult.paymentIntent.id);
                          var sbmtid ="wpjb-place-order";
                          if(window[sbmtid]){return false;} 
                          window[sbmtid]=true; 
                          jQuery(".wpjb-place-order").trigger("click",[true]);
                ev.complete("success");
              
				//finally call your api function	
            }
        });
    });

}
function CreateaPaymentIntent(){ 
 
 var datastring = jQuery(".wpjb-form").serialize();

        var retVal="";
          jQuery.ajax({
         
            type: "POST",    //request type,
            url: wpjb_payment_lang.ajaxurl,
			 async: false,
             
			data: {
			   "action":"cnp_jbcnppaymentintent",
			   "cnpfrmid" : datastring
			 },
            cache: false,
          error: function (error) {
              console.log(error); 
                },
			success:function(msg){
   //  console.log(msg);
            retVal = msg;
       
            },

        });
     
      return retVal;
      }
      
      
      function getcnpjbtotalamount(){ 
    
     
        var datastring = jQuery(".wpjb-form").serialize();
		
        var retVal="";
          jQuery.ajax({
            type: "POST",    //request type,
            url: wpjb_payment_lang.ajaxurl,
			 async: false,
             
			 data: {
			   "action":"cnp_jbcnpgettotal",
			   "cnpfrmid" : datastring
			 },
           cache: false,
          error: function (error) {
			//console.log(error);
 			
},
			success:function(msg){
  //  console.log(msg+"tot");
            retVal = msg;
       
            },

        });
     
      return retVal;
      }
//ba
jQuery('#chkBankAccount').click(function() {
var cnpcheckbox =jQuery('[id="chkBankAccount"]');

if (cnpcheckbox.is(':checked')){
  //  if ( jQuery('#chkBankAccount').attr('checked')) 
        jQuery('#submitDonation_ach_link_account').attr('disabled', false);
    } else {
        jQuery('#submitDonation_ach_link_account').attr('disabled', true);
    }
});

function processClick1_StripeFinancialConnections() {
  
var datastring = jQuery(".wpjb-form").serialize();

   
        var retVal="";
          jQuery.ajax({
            type: "POST",    //request type,
            url: wpjb_payment_lang.ajaxurl ,
			 async: false,
                
			 data: {
			   "action":"cnp_jbcnpbapaymentintent",
			   "cnpfrmid" : datastring
			 },
        
        cache: false,
        error:  function (error) {
			console.log(error);
 		
},
        success: function (msg) {
           console.log(msg);
           // console.log(msg.d);
            Show_StripeFinancialConnections(msg);
        }
           

        });
     
    

}

function Show_StripeFinancialConnections(_clientsecretvalue) {
    const confirmationForm = document.getElementById('confirmation-form');

    const stripe = Stripe('pk_test_51JkXsiH8mU0lDjNKzvzyu2nqrhY34ZCg6FOd2N2ZNgnO50YnMbKSQE69d2aCVzs8fQ48w0Ez81YCWUnp0ingT10T00ncFMd3PN');

    stripe.collectBankAccountForPayment({
        clientSecret: _clientsecretvalue,
        params: {
            payment_method_type: 'us_bank_account',
            payment_method_data: {
                billing_details: {
                    name: 'lakshmi',
                    email: 'lakshmi@clickandpledge.com',
                    phone: '0123456789',
                },
            },
        },
        expand: ['payment_method'],
    })
        .then(({ paymentIntent, error }) => {
            console.log(paymentIntent);
            if (error) {
                console.error(error.message);
                console.error(error);
                // PaymentMethod collection failed for some reason.
            } else if (paymentIntent.status === 'requires_payment_method') {
                // Customer canceled the hosted verification modal. Present them with other
                // payment method type options.
            } else if (paymentIntent.status === 'requires_confirmation') {
                // We collected an account - possibly instantly verified, but possibly
                // manually-entered. Display payment method details and mandate text
                // to the customer and confirm the intent once they accept
                // the mandate.
                jQuery("#lblpaymentstatus").text(paymentIntent.status);
                jQuery("#lblpaymentstatus").val(paymentIntent.status);
                //confirmationForm.show();

                stripe.confirmUsBankAccountPayment(_clientsecretvalue)
                    .then(({ paymentIntent, error }) => {
                        if (error) {
                            console.error(error.message);
                            // The payment failed for some reason.
                        } else if (paymentIntent.status === "requires_payment_method") {
                            // Confirmation failed. Attempt again with a different payment method.
                        } else if (paymentIntent.status === "processing") {
                            // Confirmation succeeded! The account will be debited.
                            // Display a message to customer.
                            jQuery("#wpjbbapymntintent").val(paymentIntent.id);
                          var sbmtid ="wpjb-place-order";
                          if(window[sbmtid]){return false;} 
                          window[sbmtid]=true; 
                          jQuery(".wpjb-place-order").trigger("click",[true]);
                        
                        } else if (paymentIntent.next_action ?.type === "verify_with_microdeposits") {
                            // The account needs to be verified via microdeposits.
                            // Display a message to consumer with next steps (consumer waits for
                            // microdeposits, then enters a statement descriptor code on a page sent to them via email).
                        }
                    });
            }
        });

   
}
//ba

//paypal
 
     function PayPalJBCreateOrder(){ 
  
       var datastring = jQuery(".wpjb-form").serialize();
        var retVal="";
          jQuery.ajax({
            type: "POST",    //request type,
            url:  wpjb_payment_lang.ajaxurl ,
			 async: false,
             
			 data: {
			   "action":"cnp_jbcnpcreateorder",
			   "cnpfrmid" : datastring
			 },
           cache: false,
          error: function (error) {
			console.log(error);
 			///   console('error; ' + eval(error));
},
			success:function(msg){
          console.log(msg);
            retVal = msg;
       
            },

        });
     
      return retVal;
      }

 function PayPalRecurring(){ 
  
        var datastring = jQuery(".wpjb-form").serialize();
  		var frmurl = window.location.href;
        var retVal="";
          jQuery.ajax({
            type: "POST",    //request type,
            url:  wpjb_payment_lang.ajaxurl ,
			 async: false,
             
			 data: {
			   "action":"cnp_jbCreateBillingAgreement",
			   "cnpfrmid" : datastring
			 },
           cache: false,
          error: function (error) {
			console.log(error);
 			///   console('error; ' + eval(error));
},
			success:function(msg){
            console.log(msg);
          
          
          
           var retVal =  msg;
            payPallLink = retVal
         
            jQuery(".paypal-checkout-sandbox").show();
            window['paypaltoken'] = document.getElementById('paypaltoken');
            var width = 600;
            var height = 600;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;
            paypalwindow = window.open(payPallLink, 'PayPalModalPopUp', 'width=' + width + ', height=' + height + ', top=' + top + ', left=' + left);
            var timer = setInterval(function () {
                if (paypalwindow != null && paypalwindow.closed) {
                    clearInterval(timer);
                   // closePaypalWindow(); 
             
                }
            }, 1000);
            
            },

        });
     
     
      }

//paypal