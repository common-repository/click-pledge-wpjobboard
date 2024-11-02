var limitField;
jQuery(document).ready(function($) {
 if($('#wpjobboard_clickandpledge_AccountID').is(':visible')){
campaignlimitText(jQuery('#wpjobboard_clickandpledge_receiptsettings'),jQuery('#OrganizationInformation_countdown'),1500);
campaignlimitText(jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin'),jQuery('#TermsCondition_countdown'),1500);
 }
	displaycheck();
	recurringdisplay();getdefaultpaymentlist();
	if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-OneTimeOnly').is(':checked')== true &&          jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').is(':checked')== true)
		{
			
			jQuery("#wpjobboard_clickandpledge_DefaultpaymentOptions").parent().closest('tr').show();
		}
	else
		{
			jQuery("#wpjobboard_clickandpledge_DefaultpaymentOptions").parent().closest('tr').hide();
		}
			
	jQuery('#cnp_btncode').on('click', function() 
		 {  
		 	 if(jQuery('#cnp_btncode').val() == "Get the code")
			 {
			 var cnpemailid = jQuery('#cnp_emailid').val();
			//	var ajaxurl = "admin-ajax.php" 
			 if(jQuery('#cnp_emailid').val() != "" && validateEmail(cnpemailid))
			 {
				 jQuery.ajax({
				  type: "POST", 
				  url: ajaxurl ,
				  data: {
						'action':'cnp_WPJBgetcode',
						'cnpemailid' : cnpemailid
					  },
					cache: false,
					beforeSend: function() {
					jQuery('.cnploaderimage').show();
					jQuery(".cnperror").hide();
					},
					complete: function() {
					jQuery('.cnploaderimage').hide();
						
					},	
				  success: function(htmlText) { 
					if(htmlText !=""){
				  var obj = jQuery.parseJSON(htmlText);}
					
				 
				  if(obj == "Code has been sent successfully")
				  {
					  jQuery(".cnpcode").show();
					  jQuery("#cnp_btncode").prop('value', 'Login');
					  jQuery(".text-danger").html("");
					  jQuery(".text-success").html("");
					  jQuery(".cnperror").show();
					  jQuery(".text-success").html("Please enter the code sent to your email");
				  }
				  else
				  {
				   	jQuery(".cnperror").show();
				  }
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
			  }
			  else{
			  alert("Please enter valid connect user name");
			  jQuery('#cnp_emailid').focus();
			  return false;
			  }
			 }
			 if(jQuery('#cnp_btncode').val() == "Login")
			 {
			 	 var cnpemailid = jQuery('#cnp_emailid').val().trim();
				  var cnpcode = jQuery('#cnp_code').val().trim();
				 if(cnpemailid != "" && cnpcode != "")
				 {
				 jQuery.ajax({
				  type: "POST", 
				  url: ajaxurl ,
				  data: {
						'action':'cnp_WPJBgetAccounts',
						'wpjbcnpemailid' : cnpemailid,
					  	'wpjbcnpcode' : cnpcode
					  },
				  cache: false,
				  beforeSend: function() {
					jQuery("#cnp_btncode").prop('value', 'Loading....');
					jQuery("#cnp_btncode").prop('disabled', 'disabled');
					},
					complete: function() {
					
					},	
				  success: function(htmlText) {
				
				  if(htmlText.trim() != "error")
				  {
				      jQuery('#cnp_emailid').val("");
					  jQuery('#cnp_code').val("");
  				  	  jQuery(".cnpcode").hide();
					  jQuery("#cnp_btncode").prop('disabled', '');
				      jQuery("#cnp_btncode").prop('value', 'Get the code');
					  jQuery("#cnpfrmwcregister").hide();
					  location.reload();
					  jQuery("#cnpfrmwcsettings").show();
					  jQuery('.form-table').show();
					  jQuery('.ReceiptSettingsSection').show();
					  jQuery('.RecurringSection').show();
        			  jQuery('.button-primary').show();
					 
				  }
				  else
				  {
					  jQuery(".text-danger").html("");
					  jQuery(".text-success").html("");
					  jQuery(".cnperror").show();
					  jQuery(".text-danger").html("Invalid Code");
					  jQuery("#cnp_btncode").prop('value', 'Login');
					  jQuery("#cnp_btncode").prop('disabled', false);
				  }
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
			  }
			 }
			 else if(jQuery('#cnp_emailid').val() == "")
			 {
			  alert("Please enter connect user name");
			  return false;
			 }
		 
		
		 });
	jQuery('#rfrshtokens').on('click', function() 
		 {  			 	
			var rcnpwpjbaccntid = jQuery('#wpjobboard_clickandpledge_AccountID').val().trim();
		
		 	 jQuery.ajax({
				  type: "POST", 
				  url: ajaxurl ,
				  data: {
						'action':'getWPJBCnPAccountList',
					  	'rcnpwpjbaccountid':rcnpwpjbaccntid,
						},
				    cache: false,
				    beforeSend: function() {
				//	jQuery('.cnp_loader').show();
					jQuery("#wpjobboard_clickandpledge_AccountID").html("<option>Loading............</option>");
					},
					complete: function() {
						jQuery('.cnp_loader').hide();
					
					},	
				  success: function(htmlText) {
					
				  if(htmlText !== "")
				  {
					
					jQuery("#wpjobboard_clickandpledge_AccountID").html(htmlText);  
				    jQuery("#wpjobboard_clickandpledge_AccountID").change();
				  
				  }
				  else
				  {
				  jQuery(".cnperror").show();
				  }
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
				  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
	 return false;
 });
	jQuery('#cnpregister').on('click', function() 
		 {  			 	
			
		 	 jQuery.ajax({
				  type: "POST", 
				  url: ajaxurl ,
				  data: {
						'action':'getWPJBCnPDeleteAccountList',
					  	
						},
				    cache: false,
				    beforeSend: function() {
					jQuery('.cnp_loader').show();
					
					},
					complete: function() {
						jQuery('.cnp_loader').hide();
					
					},	
				  success: function(htmlText) {
					
				  location.reload()
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
				  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
	 return false;
 });
	jQuery('.cnpregister').on('click', function() 
		 { 
		
			jQuery(".trfrmregister").show();
			
		 });
	
	jQuery('#wpjobboard_clickandpledge_titles').ready(function(e) {
	jQuery('#wpjobboard_clickandpledge_titles').change(function(e) {
	
	   getdefaultpaymentlist();
	            });
				});
	

	    if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').is(':checked')) {	
		
				if(jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').val() == "fixednumber")
			    {
				if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() == "")
				{
				   alert("Please enter default number of payments");
				   jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				   return false;														
				}
				if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != "" && jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() <= 1)
				{
				   alert("Please enter default number of payments value greater than 1");
				   jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				   return false;														
				}
				if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != "" && !jQuery.isNumeric(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val()))
				{
				   alert("Please enter an integer(Number) value only");
				   jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				   return false;														
				}
			    } else 
				if(jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').val() == "openfield")
				{
				
				if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != "" && jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() <= 1)
				{
				alert("Please enter default number of payments value greater than 1");
				jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				return false;														
				}
				if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != "" && !jQuery.isNumeric(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val()))
				{
				alert("Please enter an integer(Number) value only");
				jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				return false;														
				}
				var dfltnoofpaymnts = jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val();
				var maxnoofpaymnts = jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val();
				
				if(parseInt(dfltnoofpaymnts) > parseInt(maxnoofpaymnts))
				{
				alert("Please enter No.Of payment value less than to Max No.Of payment value");
				jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				return false;														
				}
				/*if((jQuery('#wpjobboard_clickandpledge_PaymentRecurring').val()=="Installment")	&& 
					jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() >= 999)
				{
					alert("Please enter value between 2 to 998");
				   jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				   return false;	
				}*/
				}
	}
	                 // // openfiled fixed changing and hiding based on requirement
					// subscription checking fucntion
					jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').ready(function(e) {
					if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked') == false)
					{
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-openfield], input#wpjobboard_clickandpledge_indefinite-openfield").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-fixednumber], input#wpjobboard_clickandpledge_indefinite-fixednumber").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite], input#wpjobboard_clickandpledge_indefinite-indefinite").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite_openfield], input#wpjobboard_clickandpledge_indefinite-indefinite_openfield").show();
				    } else if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked') == true && jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked') == false)
					{
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-openfield], input#wpjobboard_clickandpledge_indefinite-openfield").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-fixednumber], input#wpjobboard_clickandpledge_indefinite-fixednumber").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite], input#wpjobboard_clickandpledge_indefinite-indefinite").hide();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite_openfield], input#wpjobboard_clickandpledge_indefinite-indefinite_openfield").hide();
					} else if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked') == true && jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked') == true)
					{
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-openfield], input#wpjobboard_clickandpledge_indefinite-openfield").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-fixednumber], input#wpjobboard_clickandpledge_indefinite-fixednumber").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite], input#wpjobboard_clickandpledge_indefinite-indefinite").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite_openfield], input#wpjobboard_clickandpledge_indefinite-indefinite_openfield").show();	
					}
					});
					// subscription checking fucntion
					jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').ready(function(e) {
					if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked') == false)
					{
					
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-openfield], input#wpjobboard_clickandpledge_indefinite-openfield").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-fixednumber], input#wpjobboard_clickandpledge_indefinite-fixednumber").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite], input#wpjobboard_clickandpledge_indefinite-indefinite").hide();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite_openfield], input#wpjobboard_clickandpledge_indefinite-indefinite_openfield").hide();
				    }
					else 
					{
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-openfield], input#wpjobboard_clickandpledge_indefinite-openfield").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-fixednumber], input#wpjobboard_clickandpledge_indefinite-fixednumber").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite], input#wpjobboard_clickandpledge_indefinite-indefinite").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite_openfield], input#wpjobboard_clickandpledge_indefinite-indefinite_openfield").show();	
					}
					});
					// installment and recurring hide/show functions
					jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').ready(function(e) {
					if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked')== false || jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked')== false)
					{
					jQuery("#wpjobboard_clickandpledge_PaymentRecurring").parent().closest('tr').hide();
				    }
					else
					{
					 jQuery("#wpjobboard_clickandpledge_PaymentRecurring").parent().closest('tr').show();
					}
					});
				// End 
	
				// recurring number max/min
	jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').ready(function(e) {
	if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').is(':checked')== true)
	{ 
		if(jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').val() != "indefinite")
		{
			if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() == "" && jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').val() == "fixednumber")
			{
			alert("Please enter default number of payments");
			jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
			return false;														
			}
			if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != "" && jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() <= 1)
			{
			alert("Please enter default number of payments value greater than 1");
			jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
			return false;														
			}
			if(!jQuery.isNumeric(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val()) && jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != "" )
			{
			alert("Please enter an integer value only");
			jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
			return false;														
			}
			if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked') == true )
					{
			if((jQuery('#wpjobboard_clickandpledge_PaymentRecurring').val()=="Installment")	&& 
					jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() >= 999)
				{
					alert("Please enter value between 2 to 998");
				   jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				   return false;	
				}
					}
		}
		noofpaymentsonload(jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').val());
	}
	});
	//Recurrings hide/ show functions
	
	jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').ready(function(e) {
	if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').is(':checked')== true)
	{ 

	//jQuery("#wpjobboard_clickandpledge_DefaultpaymentOptions").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_PaymentSubscription-Installment").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_PaymentSubscription-Subscription").parent().closest('tr').show();
	//jQuery("#wpjobboard_clickandpledge_PaymentRecurring").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Week").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-TwoWeek").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Month").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-TwoMonth").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Quarter").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-SixMonths").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Year").parent().closest('tr').show();
	// No. of payments
	jQuery("#wpjobboard_clickandpledge_indefinite-indefinite").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_indefinite-openfield").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_indefinite-indefinite_openfield").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_indefinite-fixednumber").parent().closest('tr').show();
	// No.of recurrings
//	jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').show();
//	jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').show();
	}
	else
	{
	
	//jQuery("#wpjobboard_clickandpledge_DefaultpaymentOptions").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_PaymentSubscription-Installment").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_PaymentSubscription-Subscription").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_PaymentRecurring").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Week").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-TwoWeek").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Month").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-TwoMonth").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Quarter").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-SixMonths").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Year").parent().closest('tr').hide();
	// No.Of payments
	jQuery("#wpjobboard_clickandpledge_indefinite-indefinite").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_indefinite-openfield").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_indefinite-indefinite_openfield").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_indefinite-fixednumber").parent().closest('tr').hide();
	// no Recurrings
	jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').hide();
	jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').hide();
	}
	});
	//noofpayments();
	noofpaymentsonload(jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').val());
	function getdefaultpaymentlist()
	{ 
	
		 var paymethods = [];
		var paymethods_titles = [];
		var str = '';
		var defaultval = jQuery('#wpjobboard_clickandpledge_DefaultpaymentMethod').val(); 
		
		if(jQuery('#wpjobboard_clickandpledge_Paymentmethods_CreditCard').val()!="") {
			paymethods.push('CreditCard');
			paymethods_titles.push('Credit Card');
		}
    	if(jQuery('#wpjobboard_clickandpledge_Paymentmethods_gpay').val()!="") {
			paymethods.push('gpay');
			paymethods_titles.push('Google Pay');
		}
    	if(jQuery('#wpjobboard_clickandpledge_Paymentmethods_paypal').val()!="") {
			paymethods.push('paypal');
			paymethods_titles.push('PayPal/Venmo');
		}
       if(jQuery('#wpjobboard_clickandpledge_Paymentmethods_ba').val()!="") {
			paymethods.push('ba');
			paymethods_titles.push('Bank Account');
		}
		if(jQuery('#wpjobboard_clickandpledge_Paymentmethods_eCheck').val()!= "" && jQuery('#wpjobboard_clickandpledge_Paymentmethods_eCheck').val()!=undefined) {
			paymethods.push('eCheck');
			paymethods_titles.push('eCheck');
		}
		
		if(jQuery('#wpjobboard_clickandpledge_Paymentmethods-CustomPayment').is(':checked')) {	
		    jQuery('#wpjobboard_clickandpledge_Paymentmethods-CustomPayment').closest('tr').next('tr').show();
			jQuery('#wpjobboard_clickandpledge_titles').closest('tr').next('tr').show();
					 var titles1 = jQuery('#wpjobboard_clickandpledge_titles').val(); 
					  var titlesarr1 = titles1.split(";");
						for(var j1=0;j1 < titlesarr1.length; j1++)
						 { 
							 if(titlesarr1[j1] !=""){
								 paymethods.push(titlesarr1[j1]);
								 paymethods_titles.push(titlesarr1[j1]);
							 }
						 }
						
					}
					else
					{ 
						jQuery('#wpjobboard_clickandpledge_titles').closest('tr').hide();
						jQuery('#wpjobboard_clickandpledge_reference').closest('tr').hide();
						
					}
					
					
		                if(paymethods.length > 0) {
						for(var i1 = 0; i1 < paymethods.length; i1++) {
						
							if(paymethods[i1] == defaultval) {
							str += '<option value="'+paymethods[i1]+'" selected>'+paymethods_titles[i1]+'</option>';
							} else {
							str += '<option value="'+paymethods[i1]+'">'+paymethods_titles[i1]+'</option>';
							}
						}
					} else {
					str = '<option selected="selected" value="">Please select</option>';
					}
					jQuery('#wpjobboard_clickandpledge_DefaultpaymentMethod').html(str);
	}
	function campaignlimitText(limitField, limitCount, limitNum) { 
                
					if(limitField.val().length > limitNum) {
						limitField.val( limitField.val().substring(0, limitNum) );
					} else {
						limitCount.html (limitNum - limitField.val().length);
					}
				
                }
				jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin').keyup(function(){
                    campaignlimitText(jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin'),jQuery('#TermsCondition_countdown'),1500);
                });    
				
				jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin').change(function(e) {
                    campaignlimitText(jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin'),jQuery('#TermsCondition_countdown'),1500);
                });
				jQuery('#wpjobboard_clickandpledge_receiptsettings').keyup(function(){
                    campaignlimitText(jQuery('#wpjobboard_clickandpledge_receiptsettings'),jQuery('#OrganizationInformation_countdown'),1500);
                });    
				
				jQuery('#wpjobboard_clickandpledge_receiptsettings').change(function(e) {
                    campaignlimitText(jQuery('#wpjobboard_clickandpledge_receiptsettings'),jQuery('#OrganizationInformation_countdown'),1500);
                });
					function noofpayments(paymenttypes)
                    {	
                    var noofpay = paymenttypes;
					if(noofpay == "indefinite")
					{
						jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").val('');
						jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").val('');
						jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').hide();
						jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').hide();
						jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', false);
					
					}
					if(noofpay == "openfield")
					{
					    jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").val('');
						jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").val('');
						jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').show();
						jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').show();
						jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', false);
				   }
					if(noofpay == "indefinite_openfield")
					{
                        jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").val('999');
					    jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").val('999');
						
						jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').show();
						jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').show();
						jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', true);
					}
					if(noofpay == "fixednumber")
					{
					    jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").val('');
						jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").val('');
						jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').show();
						jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').hide();
						jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', false);
					}
					}
					
function noofpaymentsonload(paymenttypes)
{	
var noofpay = paymenttypes; 
if(noofpay == "indefinite")
{
jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").val('');
jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").val('');
jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').hide();
jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').hide();
jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', false);
					
}
if(noofpay == "openfield")
{
if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != '')
 {
 jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val());
 }
else
 {
 jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val('');
 }
 if(jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val() != '')
 {
 jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val(jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val());
 }
else
 {
 jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val('');
 }
//jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").val('');
//jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").val('');
jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').show();
jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').show();
jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', false);
}
if(noofpay == "indefinite_openfield")
{
	 if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != '' && jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() <= 998)
	 {
	 jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val());
	 }
	else
	 {
	 jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val('999');
	 }
	
	jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").val('999');
	jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').show();
	jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').show();
	jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', true);
}
if(noofpay == "fixednumber")
{
 if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != '')
 {
 jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val());
 }
else
 {
 jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val('');
 }
//jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").val('');
jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").val('');
jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').show();
jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').hide();
jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', false);
}
}
	function displaycheck() {
		//Accounts Display Check
		var account_enabled = 0;
		
	
if(!jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').is(':checked') && !jQuery('#wpjobboard_clickandpledge_Paymentmethods_eCheck').is(':checked')) {
jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').closest('tr').next().hide();
/*	
jQuery('.RecurringSection').next('table').hide();
jQuery('.RecurringSection').hide();*/
		} else {
			if(jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').is(':checked')) {
				jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').closest('tr').next().show();
			} else {
				jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').closest('tr').next().hide();
			}
			
		}
		defaultpayment();
	}
	function defaultpayment() { 
		var paymethods = [];
		var paymethods_titles = [];
		var str = '';
		var defaultval = jQuery('#wpjobboard_clickandpledge_DefaultpaymentMethod').val(); 
		
		jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin').keyup(function(){
                    campaignlimitText(jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin'),jQuery('#TermsCondition_countdown'),1500);
                });    
				
				jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin').change(function(e) {
                    campaignlimitText(jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin'),jQuery('#TermsCondition_countdown'),1500);
                });
				jQuery('#wpjobboard_clickandpledge_receiptsettings').keyup(function(){
                    campaignlimitText(jQuery('#wpjobboard_clickandpledge_receiptsettings'),jQuery('#OrganizationInformation_countdown'),1500);
                });    
				
				jQuery('#wpjobboard_clickandpledge_receiptsettings').change(function(e) {
                    campaignlimitText(jQuery('#wpjobboard_clickandpledge_receiptsettings'),jQuery('#OrganizationInformation_countdown'),1500);
                });
		
		if(jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').val()!="") {
			paymethods.push('CreditCard');
			paymethods_titles.push('Credit Card');
		}
    	if(jQuery('#wpjobboard_clickandpledge_Paymentmethods_gpay').val()!="") {
			paymethods.push('gpay');
			paymethods_titles.push('Google Pay');
		}
    	if(jQuery('#wpjobboard_clickandpledge_Paymentmethods_paypal').val()!="") {
			paymethods.push('paypal');
			paymethods_titles.push('PayPal/Venmo');
		}
       if(jQuery('#wpjobboard_clickandpledge_Paymentmethods_ba').val()!="") {
			paymethods.push('ba');
			paymethods_titles.push('Bank Account');
		}
		if(jQuery('#wpjobboard_clickandpledge_Paymentmethods_eCheck').val()!="") {
			paymethods.push('eCheck');
			paymethods_titles.push('eCheck');
		}
		
		if(jQuery('#wpjobboard_clickandpledge_Paymentmethods-CustomPayment').is(':checked')) {	
		             
					    jQuery('#wpjobboard_clickandpledge_titles').closest('tr').show();
						jQuery('#wpjobboard_clickandpledge_reference').closest('tr').show();
					  var titles1 = jQuery('#wpjobboard_clickandpledge_titles').val(); 
					  var titlesarr1 = titles1.split(";");
						for(var j1=0;j1 < titlesarr1.length; j1++)
						 { 
							 if(titlesarr1[j1] !=""){
								 paymethods.push(titlesarr1[j1]);
								 paymethods_titles.push(titlesarr1[j1]);
							 }
						 }
						
					}
					else
					{
						jQuery('#wpjobboard_clickandpledge_titles').closest('tr').hide();
						jQuery('#wpjobboard_clickandpledge_reference').closest('tr').hide();
					}
					
					
		                if(paymethods.length > 0) {
						for(var i1 = 0; i1 < paymethods.length; i1++) {
							
							if(paymethods[i1] == defaultval) {
							str += '<option value="'+paymethods[i1]+'" selected>'+paymethods_titles[i1]+'</option>';
							} else {
							str += '<option value="'+paymethods[i1]+'">'+paymethods_titles[i1]+'</option>';
							}
						}
					} else {
					str = '<option selected="selected" value="">Please select</option>';
					}
					jQuery('#wpjobboard_clickandpledge_DefaultpaymentMethod').html(str);
	            }
				//Payment Methods
				jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').click(function(){
					if(jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').is(':checked')) {
						jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').closest('tr').next('tr').show();
					} else {
						jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').closest('tr').next('tr').hide();
					}
					
					if(!jQuery('#woocommerce_clickandpledge_CreditCard').is(':checked') && !jQuery('#woocommerce_clickandpledge_eCheck').is(':checked')) {
						jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin-yes').closest('tr').next('tr').hide();
						
					} else {
						if(jQuery('#woocommerce_clickandpledge_CreditCard').is(':checked') || jQuery('#woocommerce_clickandpledge_eCheck').is(':checked')) {
						jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin-yes').closest('tr').next('tr').show();
						}
					}
					defaultpayment();
				    });
					jQuery('#wpjobboard_clickandpledge_Paymentmethods-CustomPayment').click(function(){ 
					 defaultpayment();
				    });
					
					// one time only hide/ show functions
						jQuery('#wpjobboard_clickandpledge_Paymentoptions-OneTimeOnly').click(function(e) {
					
					if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-OneTimeOnly').is(':checked')== true && jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').is(':checked')== true)
					{
					jQuery("#wpjobboard_clickandpledge_DefaultpaymentOptions").parent().closest('tr').show();
				    }
					else {jQuery("#wpjobboard_clickandpledge_DefaultpaymentOptions").parent().closest('tr').hide();}
					});
					
					//Recurrings hide/ show functions
					jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').click(function(e) {
					if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').is(':checked')== true  )
					{ 
					if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-OneTimeOnly').is(':checked')== true){
						jQuery("#wpjobboard_clickandpledge_DefaultpaymentOptions").parent().closest('tr').show();
					}
					jQuery("#wpjobboard_clickandpledge_PaymentSubscription-Installment").parent().closest('tr').show();
					jQuery("#wpjobboard_clickandpledge_PaymentSubscription-Subscription").parent().closest('tr').show();
					 if((jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked')== true) && 
					    jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked')== true)
					{
					 jQuery("#wpjobboard_clickandpledge_PaymentRecurring").parent().closest('tr').show();
					}
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Week").parent().closest('tr').show();
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-TwoWeek").parent().closest('tr').show();
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Month").parent().closest('tr').show();
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-TwoMonth").parent().closest('tr').show();
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Quarter").parent().closest('tr').show();
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-SixMonths").parent().closest('tr').show();
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Year").parent().closest('tr').show();
					// No. of payments
					jQuery("#wpjobboard_clickandpledge_indefinite-indefinite").parent().closest('tr').show();
					jQuery("#wpjobboard_clickandpledge_indefinite-openfield").parent().closest('tr').show();
					jQuery("#wpjobboard_clickandpledge_indefinite-indefinite_openfield").parent().closest('tr').show();
					jQuery("#wpjobboard_clickandpledge_indefinite-fixednumber").parent().closest('tr').show();
					// No.of recurrings
					jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').show();
					jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').show();
								noofpaymentsonload(jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').val());

					}
					else
					{
					jQuery("#wpjobboard_clickandpledge_DefaultpaymentOptions").parent().closest('tr').hide();
				    jQuery("#wpjobboard_clickandpledge_PaymentSubscription-Installment").parent().closest('tr').hide();
					jQuery("#wpjobboard_clickandpledge_PaymentSubscription-Subscription").parent().closest('tr').hide();
					jQuery("#wpjobboard_clickandpledge_PaymentRecurring").parent().closest('tr').hide();
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Week").parent().closest('tr').hide();
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-TwoWeek").parent().closest('tr').hide();
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Month").parent().closest('tr').hide();
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-TwoMonth").parent().closest('tr').hide();
					jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Quarter").parent().closest('tr').hide();
				    jQuery("#wpjobboard_clickandpledge_PaymentPeriods-SixMonths").parent().closest('tr').hide();
				    jQuery("#wpjobboard_clickandpledge_PaymentPeriods-Year").parent().closest('tr').hide();
					// No.Of payments
					jQuery("#wpjobboard_clickandpledge_indefinite-indefinite").parent().closest('tr').hide();
					jQuery("#wpjobboard_clickandpledge_indefinite-openfield").parent().closest('tr').hide();
					jQuery("#wpjobboard_clickandpledge_indefinite-indefinite_openfield").parent().closest('tr').hide();
					jQuery("#wpjobboard_clickandpledge_indefinite-fixednumber").parent().closest('tr').hide();
					// no Recurrings
					jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").parent().closest('tr').hide();
					jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').hide();
							

					}
					});
					
					// installment and recurring hide/show functions
					jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').click(function(e) {
					if((jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked')== true) && 
					    jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked')== true)
					{
					    jQuery("#wpjobboard_clickandpledge_PaymentRecurring").parent().closest('tr').show();
				    }
					else
					{
					    jQuery("#wpjobboard_clickandpledge_PaymentRecurring").parent().closest('tr').hide();
					}
						
					});
					
					// subscription checking fucntion
					jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').click(function(e) {
					if((jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked')== true) && 
					    jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked')== true)
					{
					jQuery("#wpjobboard_clickandpledge_PaymentRecurring").parent().closest('tr').show();
				    }
					else {		
					jQuery("#wpjobboard_clickandpledge_PaymentRecurring").parent().closest('tr').hide();
					}
					});
					 
					
					
					// openfiled fixed changing and hiding based on requirement
					// Installment checking fucntion
					jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').click(function(e) {
					if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked') == false)
					{
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-openfield], input#wpjobboard_clickandpledge_indefinite-openfield").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-fixednumber], input#wpjobboard_clickandpledge_indefinite-fixednumber").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite], input#wpjobboard_clickandpledge_indefinite-indefinite").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite_openfield], input#wpjobboard_clickandpledge_indefinite-indefinite_openfield").show();
                    jQuery('input[name="wpjobboard_clickandpledge_indefinite"]').prop('checked', false);	 
	 				jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val('');
	 				jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val('');
                    jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').show();
					jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', false);
				    } else if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked') == true && jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked') == false)
					{
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-openfield], input#wpjobboard_clickandpledge_indefinite-openfield").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-fixednumber], input#wpjobboard_clickandpledge_indefinite-fixednumber").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite], input#wpjobboard_clickandpledge_indefinite-indefinite").hide();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite_openfield], input#wpjobboard_clickandpledge_indefinite-indefinite_openfield").hide();
                    jQuery('input[name="wpjobboard_clickandpledge_indefinite"]').prop('checked', false);	 
	 				jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val('');
	 				jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val('');
                    jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').show();
					jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', false);
					} else if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked') == true && jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked') == true)
					{
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-openfield], input#wpjobboard_clickandpledge_indefinite-openfield").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-fixednumber], input#wpjobboard_clickandpledge_indefinite-fixednumber").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite], input#wpjobboard_clickandpledge_indefinite-indefinite").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite_openfield], input#wpjobboard_clickandpledge_indefinite-indefinite_openfield").show();	
                    jQuery('input[name="wpjobboard_clickandpledge_indefinite"]').prop('checked', false);	 
	 				jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val('');
	 				jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val('');
                    jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').show();
					jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', false);
					}
					});
					// subscription checking fucntion
					jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').click(function(e) {
					if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked') == false)
					{
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-openfield], input#wpjobboard_clickandpledge_indefinite-openfield").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-fixednumber], input#wpjobboard_clickandpledge_indefinite-fixednumber").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite], input#wpjobboard_clickandpledge_indefinite-indefinite").hide();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite_openfield], input#wpjobboard_clickandpledge_indefinite-indefinite_openfield").hide();
					jQuery('input[name="wpjobboard_clickandpledge_indefinite"]').prop('checked', false);	 
	 				jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val('');
	 				jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val('');
                    jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").parent().closest('tr').show();
					jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').attr('readonly', false);
						
				    }
					else 
					{
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-openfield], input#wpjobboard_clickandpledge_indefinite-openfield").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-fixednumber], input#wpjobboard_clickandpledge_indefinite-fixednumber").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite], input#wpjobboard_clickandpledge_indefinite-indefinite").show();
					jQuery("label[for=wpjobboard_clickandpledge_indefinite-indefinite_openfield], input#wpjobboard_clickandpledge_indefinite-indefinite_openfield").show();	
					}
					});
					
					
					// No. Payments Hide/Show based open/fixed/indefinite
					
					jQuery('input[name=wpjobboard_clickandpledge_indefinite]').click(function(e) {
					noofpayments(jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').val());
				    });
				
			
	function limitText(limitField, limitCount, limitNum) {
		//alert(limitField.val());
		 if (limitField.val().length > limitNum) {
			 limitField.val( limitField.val().substring(0, limitNum) );
		 } else {
			 limitCount.html (limitNum - limitField.val().length);
		 }
	}
	jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').keypress(function(e) {
						var a = [];
						var k = e.which;

						for (i = 48; i < 58; i++)
							a.push(i);

						if (!(a.indexOf(k)>=0))
							e.preventDefault();
					});
	jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').keypress(function(e) {
						var a1 = [];
						var k1 = e.which;

						for (i1 = 48; i1 < 58; i1++)
							a1.push(i1);

						if (!(a1.indexOf(k1)>=0))
							e.preventDefault();
					});	
	jQuery('#wpjobboard_clickandpledge_OrganizationInformation').keydown(function(){
		limitText(jQuery('#wpjobboard_clickandpledge_OrganizationInformation'),jQuery('#OrganizationInformation_countdown'),1500);
	});
	jQuery('#wpjobboard_clickandpledge_OrganizationInformation').keyup(function(){
		limitText(jQuery('#wpjobboard_clickandpledge_OrganizationInformation'),jQuery('#OrganizationInformation_countdown'),1500);
	});
	
	    jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').click(function(){
		if(jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').is(':checked')) {
			jQuery('.CredicardSection').next('table').show();
			jQuery('.CredicardSection').show();
		} else {
			jQuery('.CredicardSection').next('table').hide();
			jQuery('.CredicardSection').hide();
		}
		
		if(!jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').is(':checked') && !jQuery('#wpjobboard_clickandpledge_Paymentmethods_eCheck').is(':checked')) {
			jQuery('.RecurringSection').next('table').hide();
			jQuery('.RecurringSection').hide();
		} else {
			if(jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').is(':checked') || jQuery('#wpjobboard_clickandpledge_Paymentmethods_eCheck').is(':checked')) {
				jQuery('.RecurringSection').next('table').show();
				jQuery('.RecurringSection').show();
			}
		}
		defaultpayment();
	});
	jQuery('#wpjobboard_clickandpledge_Paymentmethods_eCheck').click(function(){
		if(!jQuery('#wpjobboard_clickandpledge_Paymentmethods-CreditCard').is(':checked') && !jQuery('#wpjobboard_clickandpledge_Paymentmethods_eCheck').is(':checked')) {
			jQuery('.RecurringSection').next('table').hide();
			jQuery('.RecurringSection').hide();
		} else {
			if(jQuery('#wpjobboard_clickandpledge_Paymentmethods_eCheck').is(':checked') || jQuery('#wpjobboard_clickandpledge_Paymentmethods_eCheck').is(':checked')) {
				jQuery('.RecurringSection').next('table').show();
				jQuery('.RecurringSection').show();
			} else {
				jQuery('.RecurringSection').next('table').hide();
				jQuery('.RecurringSection').hide();
			}
		}
		defaultpayment();
	});				
	jQuery('#wpjobboard_clickandpledge_Paymentmethods-Invoice').click(function(){
		defaultpayment();
	});
	jQuery('#wpjobboard_clickandpledge_Paymentmethods-PurchaseOrder').click(function(){
		defaultpayment();
	});
	//TermsCondition
	jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin').keydown(function(){
		limitText(jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin'),jQuery('#TermsCondition_countdown'),1500);
	});
	jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin').keyup(function(){
		limitText(jQuery('#wpjobboard_clickandpledge_termsandconditionsadmin'),jQuery('#TermsCondition_countdown'),1500);
	});
	function recurringdisplay() {		
		if(jQuery('#wpjobboard_clickandpledge_isRecurring').val() == 1) {
			jQuery('#wpjobboard_clickandpledge_Periodicity').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_RecurringLabel').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_Periodicity-Week').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_Periodicity-2 Weeks').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_Periodicity-Month').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_Periodicity-2 Months').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_Periodicity-Quarter').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_Periodicity-6 Months').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_Periodicity-Year').closest('tr').show();
		    jQuery('#wpjobboard_clickandpledge_RecurringMethod').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_maxrecurrings_Installment').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_RecurringMethod_Subscription-Subscription').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_maxrecurrings_Subscription').closest('tr').show();		jQuery('#wpjobboard_clickandpledge_indefinite-on').closest('tr').show();
			
			if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked')) {
				jQuery('#wpjobboard_clickandpledge_maxrecurrings_Installment').closest('tr').show();
				jQuery('#wpjobboard_clickandpledge_indefinite-on').closest('tr').hide();
			} else {
				jQuery('#wpjobboard_clickandpledge_maxrecurrings_Installment').closest('tr').hide();
			}
			
			if(jQuery('#wpjobboard_clickandpledge_RecurringMethod_Subscription-Subscription').is(':checked')) {
				jQuery('#wpjobboard_clickandpledge_maxrecurrings_Subscription').closest('tr').show();
				jQuery('#wpjobboard_clickandpledge_indefinite-on').closest('tr').show();				
			} else {
				jQuery('#wpjobboard_clickandpledge_maxrecurrings_Subscription').closest('tr').hide();
			}
		} else {
			jQuery('#wpjobboard_clickandpledge_Periodicity').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_RecurringLabel').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_Periodicity-Week').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_Periodicity-2 Weeks').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_Periodicity-Month').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_Periodicity-2 Months').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_Periodicity-Quarter').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_Periodicity-6 Months').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_Periodicity-Year').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_RecurringMethod').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_maxrecurrings_Installment').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_RecurringMethod_Subscription-Subscription').closest('tr').hide();
			jQuery('#wpjobboard_clickandpledge_maxrecurrings_Subscription').closest('tr').hide();							
			jQuery('#wpjobboard_clickandpledge_indefinite-on').closest('tr').hide();						
		}
	}

           jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').click(function(){
		if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked')) {
			jQuery('#wpjobboard_clickandpledge_maxrecurrings_Installment').closest('tr').show();
			jQuery('#wpjobboard_clickandpledge_indefinite-on').attr('checked', false);
		} else {
			jQuery('#wpjobboard_clickandpledge_indefinite-on').attr('checked', false);
			jQuery('#wpjobboard_clickandpledge_maxrecurrings_Installment').closest('tr').hide();			
		}
		indefinite_display();
	});


	    jQuery('#wpjobboard_clickandpledge_RecurringMethod_Subscription-Subscription').click(function(){
		if(jQuery('#wpjobboard_clickandpledge_RecurringMethod_Subscription-Subscription').is(':checked')) {
			jQuery('#wpjobboard_clickandpledge_maxrecurrings_Subscription').closest('tr').show();
		} else {
		jQuery('#wpjobboard_clickandpledge_maxrecurrings_Subscription').closest('tr').hide();
		}
		indefinite_display();
	});
	function indefinite_display() {
		if(jQuery('#wpjobboard_clickandpledge_RecurringMethod_Subscription-Subscription').is(':checked')) {
			jQuery('#wpjobboard_clickandpledge_indefinite-on').closest('tr').show();
		} else {
			jQuery('#wpjobboard_clickandpledge_indefinite-on').attr('checked', false);
			jQuery('#wpjobboard_clickandpledge_indefinite-on').closest('tr').hide();			
		}
	}
	jQuery('#wpjobboard_clickandpledge_isRecurring').change(function(){
		recurringdisplay();
	});
	jQuery('#wpjobboard_clickandpledge_AccountID').change(function() {
	
		var  cnpwJaccountid= jQuery('#wpjobboard_clickandpledge_AccountID').val().trim();
		var  cnpwJcamp= jQuery('#wpjobboard_clickandpledge_ConnectCampaignAlias').val().trim();

		 	 jQuery.ajax({
				  type: "POST", 
				  url : ajaxurl ,
				  data: {
						'action':'getCnPUserconectAccountList',
					  	'cnpacid':cnpwJaccountid,
					  	'cnpcamp':cnpwJcamp,
						},
				  cache: false,
				  beforeSend: function() {
					
					jQuery("#wpjobboard_clickandpledge_ConnectCampaignAlias").html("<option>Loading............</option>");
					},
					complete: function() {
					
					},	
				  success: function(htmlText) {
			//	console.log(htmlText);
				  if(htmlText !== "")
				  {
						  console.log(htmlText);
					var res = htmlText.split("||");
				
					jQuery("#wpjobboard_clickandpledge_ConnectCampaignAlias").html(res[0]);  
					jQuery(".cnpacceptedcards").html(res[1]);  
					  if(res[2] == 0){
						  jQuery('#wpjobboard_clickandpledge_Paymentmethods-CustomPayment').prop('checked', false);
						    jQuery("#wpjobboard_clickandpledge_Paymentmethods-CustomPayment").parents("tr").hide();
						  	jQuery('#wpjobboard_clickandpledge_titles').parents('tr').hide();
						    jQuery('#wpjobboard_clickandpledge_reference').parents('tr').hide();
						  getdefaultpaymentlist();
						 	//str = '<option value="CreditCard">Credit Card</option>';
							//str += '<option value="eCheck">eCheck</option>';
						
							//jQuery('#wpjobboard_clickandpledge_DefaultpaymentMethod').html(str);
					  }
					  else
						  {
							 jQuery("#wpjobboard_clickandpledge_Paymentmethods-CustomPayment").attr('checked','checked')
							jQuery("#wpjobboard_clickandpledge_Paymentmethods-CustomPayment").parents("tr").show();
						  	jQuery('#wpjobboard_clickandpledge_titles').parents('tr').show();
						    jQuery('#wpjobboard_clickandpledge_reference').parents('tr').show();
							  getdefaultpaymentlist();
						  }
					 /* if(jQuery("#wpjobboard_clickandpledge_Paymentmethods-CustomPayment").prop('checked') == true){
						
						jQuery('#wpjobboard_clickandpledge_titles').closest('tr').show();
						jQuery('#wpjobboard_clickandpledge_reference').closest('tr').show();
						  admdefaultpayment();
					  }
					  else{	
						jQuery('#wpjobboard_clickandpledge_titles').closest('tr').hide();
						jQuery('#wpjobboard_clickandpledge_reference').closest('tr').hide();

						   admdefaultpayment();
					  }*/
				
				
				  }
				  else
				  {
				  jQuery(".cnperror").show();
				  }
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
	 return false;
 });
	function isInt(n) {
		return n % 1 === 0;
	}
	
	$("form").submit(function(event) { 
		
	
		if(jQuery('#title').val() == '')
		{
			alert('Please enter title');
			jQuery('#title').focus();
			return false;
		}
		
		// Payment Options Validation
		var recurringpaymethods = 0;
		if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-OneTimeOnly').is(':checked'))
		{
			recurringpaymethods++;
		}
		if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').is(':checked'))
		{
			recurringpaymethods++;
		} 
		
		if(recurringpaymethods == 0) {
			alert('Please select at least  one Payment Options');
			jQuery('#wpjobboard_clickandpledge_Paymentoptions-OneTimeOnly').focus();
			return false;
		}	
		if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').is(':checked'))
		{
		// Recurring Types Validation  wpjobboard_clickandpledge_titles
		var recurringtypes = 0;
		if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked'))
		{
			recurringtypes++;
		}
		if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked'))
		{
			recurringtypes++;
		}
		
		if(recurringtypes == 0) {
			alert('Please select at least  one Recurring Type(s)');
			jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').focus();
			return false;
		}
		// Periodicity Validation
		var periodicity = 0;
		if(jQuery('#wpjobboard_clickandpledge_PaymentPeriods-Week').is(':checked'))
		{
			periodicity++;
		}
		if(jQuery("#wpjobboard_clickandpledge_PaymentPeriods-2Weeks").is(':checked'))
		{
			periodicity++;
		}
		if(jQuery('#wpjobboard_clickandpledge_PaymentPeriods-Month').is(':checked'))
		{
			periodicity++;
		}
		if(jQuery('#wpjobboard_clickandpledge_PaymentPeriods-2Months').is(':checked'))
		{
			periodicity++;
		}
		if(jQuery('#wpjobboard_clickandpledge_PaymentPeriods-Quarter').is(':checked'))
		{
			periodicity++;
		}
		if(jQuery('#wpjobboard_clickandpledge_PaymentPeriods-6Months').is(':checked'))
		{
			periodicity++;
		}
		if(jQuery('#wpjobboard_clickandpledge_PaymentPeriods-Year').is(':checked'))
		{
			periodicity++;
		}
		
		if(periodicity == 0) {
			alert('Please select at least  one periodicity(s)');
			jQuery('#wpjobboard_clickandpledge_PaymentPeriods-Week').focus();
			return false;
		} 
			
					if(jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').length<=0)
					{
					   alert("Please select at least one option for number of payments");
					   jQuery("#wpjobboard_clickandpledge_indefinite").focus();
					   return false;
					}
			if(jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').val() == "fixednumber")
			    {
				if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() == "")
				{
				   alert("Please enter default number of payments");
				   jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				   return false;														
				}
				if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != "" && jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() <= 1)
				{
				   alert("Please enter default number of payments value greater than 1");
				   jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				   return false;														
				}
				if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != "" && !jQuery.isNumeric(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val()))
				{
				   alert("Please enter an integer(Number) value only");
				   jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				   return false;														
				}
					
				if((jQuery('#wpjobboard_clickandpledge_PaymentRecurring').val()=="Installment")	&& 
					jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() >= 999 &&
				    jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked') == true)
					
				{
					alert("Please enter value between 2 to 998");
				   jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
				   return false;	
				}
					
			    } else 
				if(jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').val() == "openfield" )
				{
				
					if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != "" && jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() <= 1)
					{
						alert("Please enter default number of payments value greater than 1");
						jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
						return false;														
					}
					if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != "" && !jQuery.isNumeric(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val()))
					{
					alert("Please enter an integer(Number) value only");
					jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
					return false;														
					}
					var dfltnoofpaymnts = jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val();
					var maxnoofpaymnts = jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val();

					if(parseInt(dfltnoofpaymnts) > parseInt(maxnoofpaymnts))
					{
					alert("Please enter No.Of payment value less than to Max No.Of payment value");
					jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
					return false;														
					}
					if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked') == true )
					{
						if((jQuery('#wpjobboard_clickandpledge_PaymentRecurring').val()=="Installment")	&& 
						jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() >= 999)
						{
							alert("Please enter value between 2 to 998");
							jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
							return false;	
						}
						if((jQuery('#wpjobboard_clickandpledge_PaymentRecurring').val()=="Installment")	&& 
						jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val() >= 999)
						{
						   alert("Please enter value between 2 to 998");
						   jQuery("#wpjobboard_clickandpledge_maxnoofinstallments").focus();
						   return false;	
						}
					}
				}
			else 
				if(jQuery('input[name=wpjobboard_clickandpledge_indefinite]:checked').val() == "indefinite_openfield")
				{
				
					if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked') == true )
					{
					if((jQuery('#wpjobboard_clickandpledge_PaymentRecurring').val()=="Installment")	&& 
					jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() >= 999)
					{
						alert("Please enter value between 2 to 998");
					   jQuery("#wpjobboard_clickandpledge_dfltnoofpaymnts").focus();
					   return false;	
					}
					}
		}
		}
			if(jQuery('#wpjobboard_clickandpledge_Paymentmethods-CustomPayment').is(':checked') && jQuery('#wpjobboard_clickandpledge_titles').val() == "")
			{
				alert("Please enter CustomPayment Title(s)");
				jQuery("#wpjobboard_clickandpledge_titles").focus();
				return false;														

			}
	
		
		if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').is(':checked'))
			{
		if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Installment').is(':checked') && 
		   !jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked')
		   )
		 { 
			if( jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != '' && jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() < 2)
			{	
			    alert('Please enter value greater than 1');
				jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').focus();
				return false;
			}
			if(jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() != '' && jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').val() > 998)
			{	alert('Please enter value between 2 to 998 for installment');
				jQuery('#wpjobboard_clickandpledge_dfltnoofpaymnts').focus();
				return false;
			}
			if( jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val() != '' && jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val() < 2)
			{	
			    alert('Please enter value greater than 1');
				jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').focus();
				return false;
			}
			if(jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val() != '' && jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').val() > 998)
			{	alert('Please enter value between 2 to 998  for installment');
				jQuery('#wpjobboard_clickandpledge_maxnoofinstallments').focus();
				return false;
			}
			 
		}
			}
		if(jQuery('#wpjobboard_clickandpledge_Paymentoptions-Recurring').is(':checked'))
			{
		if(jQuery('#wpjobboard_clickandpledge_PaymentSubscription-Subscription').is(':checked') && 
		jQuery('#wpjobboard_clickandpledge_maxrecurrings_Subscription').val() != '')
		{ 
			
						
			if(jQuery('#wpjobboard_clickandpledge_maxrecurrings_Subscription').val() < 2)
			{
				alert('Please enter Subscription Max. Recurrings Allowed greater than 1');
				jQuery('#wpjobboard_clickandpledge_maxrecurrings_Subscription').focus();
				return false;
			}
			if(jQuery('#wpjobboard_clickandpledge_maxrecurrings_Subscription').val() > 999)
			{
				alert('Please enter Subscription Max. Recurrings Allowed between 2-999');
				jQuery('#wpjobboard_clickandpledge_maxrecurrings_Subscription').focus();
				return false;
			}
		}
			}
    });
});
function validateEmail($email) {
		  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		  return emailReg.test( $email );
		}