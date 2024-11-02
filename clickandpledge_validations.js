  jQuery(document).ready(function() {
	  alert('validationjs file loading');
      function in_array (needle, haystack, argStrict) {
          var key = '',
            strict = !! argStrict;
        
          if (strict) {
            for (key in haystack) {
              if (haystack[key] === needle) {
                return true;
              }
            }
          } else {
            for (key in haystack) {
              if (haystack[key] == needle) {
                return true;
              }
            }
          }
    
      return false;
    }

   //Billing Information
		var BillingFirstName_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,50})$/;
		var BillingLastName_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,50})$/;
		var BillingMI_reg = /^([a-zA-Z0-9]{1})$/;
		var BillingEmail_reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var BillingPhone_reg = /^([0-9\.\-\(\)\+]){10,50}$/;
		
		//Billing Address
		var BillingAddress1_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,100})$/;
		var BillingAddress2_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{0,100})$/;
		var BillingAddress3_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{0,100})$/;
		var BillingCity_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,50})$/;
		var BillingStateProvince_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,50})$/;
		var BillingPostalCode_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,20})$/;
		var BillingCountryCode_reg = /^([0-9]{3})$/;
		
		//Shipping Information
		var ShippingFirstName_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,50})$/;
		var ShippingLastName_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,50})$/;
		var ShippingMI_reg = /^([a-zA-Z0-9]{1})$/;
		var ShippingEmail_reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var ShippingPhone_reg = /^([0-9\.\-\(\)\+]){10,50}$/;
		
		//Shipping Address
		var ShippingAddress1_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,100})$/;
		var ShippingAddress2_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{0,100})$/;
		var ShippingAddress3_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{0,100})$/;
		var ShippingCity_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,50})$/;
		var ShippingStateProvince_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,50})$/;
		var ShippingPostalCode_reg = /^([a-zA-Z0-9\.\,\#\&\-\ \']{2,20})$/;
		var ShippingCountryCode_reg = /^([0-9]{3})$/;

        //Payment Method
        var allowed_PaymentTypes = new Array('CreditCard', 'Check', 'Invoice', 'PurchaseOrder', 'ReferenceTransaction');
        //Payment Type 'Credit Card'
        var NameOnCard_reg = /^([a-zA-Z0-9\.\,\#\-\ \']){2,50}$/;
        var CardNumber_reg = /^([0-9]){15,17}$/;
        var Cvv2_reg = /^([0-9]){3,4}$/;
         
		/*
        var allowed_echeck_accounttype = new Array('CheckingAccount', 'SavingsAccount');
        var allowed_echeck_checktype = new Array('Company', 'Personal');
        var periodicity_options = new Array('Week', '2 Weeks', 'Month', '2 Months', 'Quarter', '6 Months', 'Year');
        var allowed_recurringmethod = new Array('Installment', 'Subscription');
        var allowed_currency_codes = new Array('840', '978', '826', '124', '344');
        var allowed_currency_symbols = new Array('$', 'ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬', 'Ãƒâ€šÃ‚Â£', 'C$', 'HK$');
		*/
		
        //Payment Method 'Check'
        var AccountNumber_reg = /^([a-zA-Z0-9]){1,17}$/;
        var allowed_AccountTypes = new Array('Company', 'Personal');
        var RoutingNumber_reg = /^([a-zA-Z0-9]){1,9}$/;
        var CheckNumber_reg = /^([a-zA-Z0-9]){1,10}$/;
        var allowed_CheckTypes = new Array('Company', 'Personal');
        var NameOnAccount_reg = /^([a-zA-Z0-9]){0,100}$/;
        var allowed_IdTypes = new Array('Driver', 'Military', 'State');
        var IdNumber_reg = /^([a-zA-Z0-9]){0,30}$/;
        var IdStateCode_reg = /^([a-zA-Z0-9]){0,3}$/;
		
		var OrderNumber_reg = /^([a-zA-Z0-9]){1,36}$/;
        
//payment Method 'Invoice'
var CheckNumber_reg = /^([0-9]){1,50}$/;
//payment Method 'PurchaseOrder'
var PurchaseOrderNumber_reg = /^([0-9]){1,50}$/;
		
        //ThirdParty
        var allowed_ThirdParty = new Array('SocialNetworks', 'eNewsletters', 'CRMs', 'MobilePayment');
        
        //SocialNetworks
        var allowed_NetworkNames = new Array('Twitter', 'ClickandPledge');
        var NetworkUserID_reg = /^([a-zA-Z0-9]){1,50}$/;
        var NetworkPassword_reg = /^([a-zA-Z0-9]){1,50}$/;
        var NetworkComment_reg = /^([a-zA-Z0-9]){1,1000}$/;
       
       //eNewsletters
        var allowed_eNewsletterNames = new Array('ConstantContact', 'MailChimp', 'iContact');
        var eNewsLetterUserID_reg = /^([a-zA-Z0-9]){1,50}$/;
        var eNewsLetterPassword_reg = /^([a-zA-Z0-9]){1,50}$/;
        var eNewsLetterCustomParam1_reg = /^([a-zA-Z0-9]){1,200}$/;
        
        //CRMs
        var allowed_ProviderNames = new Array('SalesForce');
        var ProviderUserID_reg = /^([a-zA-Z0-9]){1,50}$/;
        var ProviderPassword_reg = /^([a-zA-Z0-9]){1,50}$/;
        
        //MobilePayment
        var CellPhoneNumber_reg = /^([a-zA-Z0-9]){11,25}$/;
        
        //OrderItem->Ticketlist
        var TicketID_reg = /^([0-9]){1,10}$/;
        var SecurityCode_reg = /^([a-zA-Z0-9]){1,5}$/;
        var SerialNo_reg = /^([0-9]){0,10}$/;
        var FullName_reg = /^([a-zA-Z0-9]){1,5}$/;
        var LineNo_reg = /^([0-9]){1}$/;
        var LineContent_reg = /^([a-zA-Z0-9]){0,36}$/;
        var ConditionOfSale_reg = /^([a-zA-Z0-9]){0,500}$/;
        
        //NameBadge
        var NameBadgeID_reg = /^([0-9]){1,10}$/;
        var allowed_Enable1dBarcodes = new Array('True', 'False');
        var allowed_Enable2dBarcodes = new Array('True', 'False');
        var NameBadge_FullName_reg = /^([a-zA-Z0-9]){0,20}$/;
        
        //ItemID
        var ItemID_reg = /^([0-9]){1,10}$/;
        var ItemName_reg = /^([a-zA-Z0-9]){2,50}$/;
        var Quantity_reg = /^([0-9]){1,7}$/;
        var UnitPrice_reg = /^([0-9]){1,10}$/;
        var UnitDeductible_reg = /^([0-9]){0,10}$/;
        var UnitTax_reg = /^([0-9]){0,10}$/;
        var UnitDiscount_reg = /^([0-9]){0,10}$/;
        var SKU_reg = /^([a-zA-Z0-9]){1,100}$/;
        var Campaign_reg = /^([a-zA-Z0-9]){1,80}$/;
        var item_num = jQuery("[name^='UnitPrice']").length;
		
        //Biling Information
        jQuery.validator.addMethod("BillingFirstName", function(value, element) {
        return this.optional(element) || (BillingFirstName_reg.test(value));
        }, "Invalid Billing First Name.");
        jQuery.validator.addMethod("BillingMI", function(value, element) {
        return this.optional(element) || (BillingMI_reg.test(value));
        }, "Invalid Billing Middle Name.");
        jQuery.validator.addMethod("BillingLastName", function(value, element) {
        return this.optional(element) || (BillingLastName_reg.test(value));
        }, "Invalid Billing Last Name.");
        jQuery.validator.addMethod("BillingEmail", function(value, element) {
        return this.optional(element) || (BillingEmail_reg.test(value));
        }, "Invalid Email Address.");
        jQuery.validator.addMethod("BillingPhone", function(value, element) {
        return this.optional(element) || (BillingPhone_reg.test(value));
        }, "Invalid Billing Phone.");
        
        //Billing Address
        jQuery.validator.addMethod("BillingAddress1", function(value, element) {
        return this.optional(element) || ( BillingAddress1_reg.test(value));
        }, "Invalid Billing Address.");
        jQuery.validator.addMethod("BillingAddress2", function(value, element) {
        return this.optional(element) || ( BillingAddress2_reg.test(value));
        }, "Invalid Billing Address.");
        jQuery.validator.addMethod("BillingAddress3", function(value, element) {
        return this.optional(element) || ( BillingAddress3_reg.test(value));
        }, "Invalid Billing Address.");
        jQuery.validator.addMethod("BillingCity", function(value, element) {
        return this.optional(element) || ( BillingCity_reg.test(value));
        }, "Invalid Billing City.");
        jQuery.validator.addMethod("BillingStateProvince", function(value, element) {
        return this.optional(element) || ( BillingStateProvince_reg.test(value));
        }, "Invalid Billing State or Province.");
        jQuery.validator.addMethod("BillingPostalCode", function(value, element) {
        return this.optional(element) || ( BillingPostalCode_reg.test(value));
        }, "Invalid Postal Code.");
        jQuery.validator.addMethod("BillingCountryCode", function(value, element) {
        return this.optional(element) || ( BillingCountryCode_reg.test(value));
        }, "Invalid Billing Country Code.");
        
        /* Shipping Information*/
        jQuery.validator.addMethod("ShippingFirstName", function(value, element) {
        return this.optional(element) || (ShippingFirstName_reg.test(value));
        }, "Invalid Shipping First Name.");
        jQuery.validator.addMethod("ShippingMI", function(value, element) {
        return this.optional(element) || (ShippingMI_reg.test(value));
        }, "Invalid Shipping Middle Name.");
        jQuery.validator.addMethod("ShippingLastName", function(value, element) {
        return this.optional(element) || (ShippingLastName_reg.test(value));
        }, "Invalid Shipping Last Name.");
        jQuery.validator.addMethod("ShippingEmail", function(value, element) {
        return this.optional(element) || (ShippingEmail_reg.test(value));
        }, "Invalid Shipping Email Address.");
        jQuery.validator.addMethod("ShippingPhone", function(value, element) {
        return this.optional(element) || (ShippingPhone_reg.test(value));
        }, "Invalid Shipping Phone.");
        
        //Shipping Address
        jQuery.validator.addMethod("ShippingAddress1", function(value, element) {
        return this.optional(element) || ( ShippingAddress1_reg.test(value));
        }, "Invalid Shipping Address.");
        jQuery.validator.addMethod("ShippingAddress2", function(value, element) {
        return this.optional(element) || ( ShippingAddress2_reg.test(value));
        }, "Invalid Shipping Address.");
        jQuery.validator.addMethod("ShippingCity", function(value, element) {
        return this.optional(element) || ( ShippingCity_reg.test(value));
        }, "Invalid Shipping City.");
        jQuery.validator.addMethod("ShippingStateProvince", function(value, element) {
        return this.optional(element) || ( ShippingStateProvince_reg.test(value));
        }, "Invalid Shipping State or Province.");
        jQuery.validator.addMethod("ShippingPostalCode", function(value, element) {
        return this.optional(element) || ( ShippingPostalCode_reg.test(value));
        }, "Invalid Postal Code.");
        jQuery.validator.addMethod("ShippingCountryCode", function(value, element) {
        return this.optional(element) || ( ShippingCountryCode_reg.test(value));
        }, "Invalid Shipping Country Code.");
        
        //Payment Method
        jQuery.validator.addMethod("PaymentType", function(value, element) {
        return this.optional(element) || ( in_array(value, allowed_PaymentTypes ));
        }, "Invalid Payment Method.");
        
        //Payment Type 'Credit Card'
        jQuery.validator.addMethod("NameOnCard", function(value, element) {
        return this.optional(element) || ( NameOnCard_reg.test(value));
        }, "Please enter at least 2 characters");
		jQuery.validator.addMethod("CardNumber", function(value, element) {
        return this.optional(element) || ( CardNumber_reg.test(value));
        }, "Invalid Card Number.");
        jQuery.validator.addMethod("Cvv2", function(value, element) {
        return this.optional(element) || ( Cvv2_reg.test(value));
        }, "Please enter 3 or 4 digits.");
        
        function checkdate() {
            var now_date = new Date();
			var now_month = now_date.getMonth()+1; 
			//var seleted_date = document.getElementById('ExpirationMonth').value;
			var seleted_date = jQuery('.ExpirationMonth').val();
			var now_year = now_date.getFullYear().toString().substr(2,2);
			//var sel_year = document.getElementById('ExpirationYear').value;
			var sel_year = jQuery('.ExpirationYear').value;
			if(sel_year < now_year){
			    return false;					
			}
			if(seleted_date < now_month && sel_year == now_year){	
				 return false;
			}
            return true;
        }
        jQuery.validator.addMethod("ExpirationYear", function(value, element) {
        return this.optional(element) || (checkdate());
        }, "Invalid Expiration Date. It should be the future date");
		
		
		
        //Payment Type 'ReferenceTransaction'
        jQuery.validator.addMethod("OrderNumber", function(value, element) {
        return this.optional(element) || ( OrderNumber_reg.test(value));
        }, "Invalid Order Number.");
        jQuery.validator.addMethod("VaultGUID", function(value, element) {
        return this.optional(element) || ( OrderNumber_reg.test(value));
        }, "Invalid VaultGUID."); 
        
        //Payment Type 'Check'
        jQuery.validator.addMethod("AccountNumber", function(value, element) {
        return this.optional(element) || ( AccountNumber_reg.test(value));
        }, "Invalid Account Number.");
        jQuery.validator.addMethod("RetypeAccountNumber", function(value, element) {
        if (jQuery('.AccountNumber').val() != value) { return false;	} else { return true;}
        }, "AccountNumber and RetypeAccountNumber must be same.");
        jQuery.validator.addMethod("AccountType", function(value, element) {
        return this.optional(element) || ( in_array(value, allowed_AccountTypes ));
        }, "Invalid Account Type.");
        jQuery.validator.addMethod("RoutingNumber", function(value, element) {
        return this.optional(element) || ( RoutingNumber_reg.test(value));
        }, "Invalid Routing Number.");
        jQuery.validator.addMethod("CheckNumber", function(value, element) {
        return this.optional(element) || ( CheckNumber_reg.test(value));
        }, "Invalid Check Number."); 
        jQuery.validator.addMethod("CheckType", function(value, element) {
        return this.optional(element) || ( in_array(value, allowed_CheckTypes ));
        }, "Invalid Check Type.");
        jQuery.validator.addMethod("NameOnAccount", function(value, element) {
        return this.optional(element) || ( NameOnAccount_reg.test(value));
        }, "Invalid Name On Account.");
        jQuery.validator.addMethod("IdType", function(value, element) {
        return this.optional(element) || ( in_array(value, allowed_IdTypes ));
        }, "Invalid Id Type.");
        jQuery.validator.addMethod("IdNumber", function(value, element) {
        return this.optional(element) || ( IdNumber_reg.test(value));
        }, "Invalid Id Number.");
        jQuery.validator.addMethod("IdStateCode", function(value, element) {
        return this.optional(element) || ( IdStateCode_reg.test(value));
        }, "Invalid Id State Code.");
        //Payment Type 'Invoice'
		jQuery.validator.addMethod("InvoiceCheckNumber", function(value, element) {
		return this.optional(element) || ( CheckNumber_reg.test(value));
		}, "Invalid Check Number.");
		//Payment Type 'PurchaseOrder'
		jQuery.validator.addMethod("PurchaseOrderNumber", function(value, element) {
		return this.optional(element) || ( PurchaseOrderNumber_reg.test(value));
		}, "Invalid Purchase Order Number.");
		
        //ThirdParty
        jQuery.validator.addMethod("ThirdParty", function(value, element) {
        return this.optional(element) || ( in_array(value, allowed_ThirdParty));
        }, "Invalid Third Party.");
        
        //SocialNetwords
        jQuery.validator.addMethod("NetworkName", function(value, element) {
        return this.optional(element) || (in_array(value, allowed_NetworkName));
        }, "Invalid Network Name.");
        jQuery.validator.addMethod("NetworkUserID", function(value, element) {
        return this.optional(element) || ( NetworkUserID_reg.test(value) );
        }, "Invalid Network User ID.");
        jQuery.validator.addMethod("NetworkPassword", function(value, element) {
        return this.optional(element) || ( NetworkPassword_reg.test(value) );
        }, "Invalid Network Password.");
        jQuery.validator.addMethod("NetworkComment", function(value, element) {
        return this.optional(element) || ( NetworkComment_reg.test(value) );
        }, "Invalid Network Password.");
        
        //eNewsletters
        jQuery.validator.addMethod("eNewsletterName", function(value, element) {
        return this.optional(element) || (in_array(value, allowed_eNewsletterNames));
        }, "Invalid eNewsletterName.");
        jQuery.validator.addMethod("eNewsLetterUserID", function(value, element) {
        return this.optional(element) || ( eNewsLetterUserID_reg.test(value) );
        }, "Invalid eNewsLetterUserID.");
        jQuery.validator.addMethod("eNewsLetterPassword", function(value, element) {
        return this.optional(element) || ( eNewsLetterPassword_reg.test(value) );
        }, "Invalid eNewsLetterPassword.");
        jQuery.validator.addMethod("eNewsLetterCustomParam1", function(value, element) {
        return this.optional(element) || ( eNewsLetterCustomParam1_reg.test(value) );
        }, "Invalid eNewsLetterCustomParam1.");
        
        //CRMs
        jQuery.validator.addMethod("ProviderName", function(value, element) {
        return this.optional(element) || ( in_array(value, allowed_ProviderNames) );
        }, "Invalid ProviderName.");
        jQuery.validator.addMethod("ProviderUserID", function(value, element) {
        return this.optional(element) || ( ProviderUserID_reg.test(value) );
        }, "Invalid Provider User ID.");
        jQuery.validator.addMethod("ProviderPassword", function(value, element) {
        return this.optional(element) || ( ProviderPassword_reg.test(value) );
        }, "Invalid Provider Password.");
        
        //CellPhoneNumber
        jQuery.validator.addMethod("CellPhoneNumber", function(value, element) {
        return this.optional(element) || ( CellPhoneNumber_reg.test(value) );
        }, "Invalid Cell Phone Number.");
        
        //TicketList
        jQuery.validator.addMethod("TicketID", function(value, element) {
        return this.optional(element) || ( TicketID_reg.test(value) );
        }, "Invalid Ticket ID.");   
        jQuery.validator.addMethod("SecurityCode", function(value, element) {
        return this.optional(element) || ( SecurityCode_reg.test(value) );
        }, "Invalid Security Code.");
        jQuery.validator.addMethod("SerialNo", function(value, element) {
        return this.optional(element) || ( SerialNo_reg.test(value) );
        }, "Invalid Serial No.");
        jQuery.validator.addMethod("FullName", function(value, element) {
        return this.optional(element) || ( FullName_reg.test(value) );
        }, "Invalid Full Name.");
        jQuery.validator.addMethod("LineNo", function(value, element) {
        return this.optional(element) || ( LineNo_reg.test(value) );
        }, "Invalid Line No.");
        jQuery.validator.addMethod("LineContent", function(value, element) {
        return this.optional(element) || ( LineContent_reg.test(value) );
        }, "Invalid Line Content.");
        jQuery.validator.addMethod("ConditionOfSale", function(value, element) {
        return this.optional(element) || ( ConditionOfSale_reg.test(value) );
        }, "Invalid Condition Of Sale.");
        
        //NameBadge
        jQuery.validator.addMethod("NameBadgeID", function(value, element) {
        return this.optional(element) || ( NameBadgeID_reg.test(value) );
        }, "Invalid Name Badge ID.");
        jQuery.validator.addMethod("Enable1dBarcode", function(value, element) {
        return this.optional(element) || ( in_array(value, allowed_Enable1dBarcodes) );
        }, "Invalid Enable1d Bar code.");
        jQuery.validator.addMethod("Enable2dBarcode", function(value, element) {
        return this.optional(element) || ( in_array(value, allowed_Enable2dBarcodes) );
        }, "Invalid Enable2d Bar code.");
        jQuery.validator.addMethod("NameBadge_FullName", function(value, element) {
        return this.optional(element) || ( NameBadge_FullName_reg.test(value) );
        }, "Invalid Full Name on Name Badge.");
        
        //ItemId
        jQuery.validator.addMethod("ItemID", function(value, element) {
        return this.optional(element) || ( ItemID_reg.test(value) );
        }, "Invalid ItemID.");
        jQuery.validator.addMethod("ItemName", function(value, element) {
        return this.optional(element) || ( ItemName_reg.test(value) );
        }, "Invalid Item Name.");
        jQuery.validator.addMethod("Quantity", function(value, element) {
        return this.optional(element) || ( Quantity_reg.test(value) );
        }, "Invalid Quantity.");
		for(i=1;i<=item_num;i++){
        jQuery.validator.addMethod("UnitPrice"+i, function(value, element) {
        return this.optional(element) || ( UnitPrice_reg.test(value) );
        }, "Invalid Unit Price.");
		}
        jQuery.validator.addMethod("UnitDeductible", function(value, element) {
        return this.optional(element) || ( UnitDeductible_reg.test(value) );
        }, "Invalid Unit Deductible.");
        jQuery.validator.addMethod("UnitTax", function(value, element) {
        return this.optional(element) || ( UnitTax_reg.test(value) );
        }, "Invalid Unit Tax.");
        jQuery.validator.addMethod("UnitDiscount", function(value, element) {
        return this.optional(element) || ( UnitDiscount_reg.test(value) );
        }, "Invalid Unit Discount.");
        jQuery.validator.addMethod("SKU", function(value, element) {
        return this.optional(element) || ( SKU_reg.test(value) );
        }, "Invalid SKU.");
        jQuery.validator.addMethod("Campaign", function(value, element) {
        return this.optional(element) || ( Campaign_reg.test(value) );
        }, "Invalid Campaign.");
		
		//Functions used in Salesforce Shopping Cart
		var regnewvalidation =  /[=\"\<\>\&\\\{\}]/;
		jQuery.validator.addMethod("Notallowed", function(value, element) {
			return this.optional(element) || (!regnewvalidation.test(value));
		}, "Invalid Characters.");
		
		jQuery.validator.addMethod("maxlength", function (value, element, len) {
		return value == "" || value.length <= len;
		});
		
		jQuery.validator.addMethod("maxLen", function (value, element, param) {
		if (jQuery(element).val().length > param) {
			return false;
		} else {
			return true;
		}
		}, "Please enter no more than {0} characters.");
		
		jQuery.validator.addClassRules({
		  cnpminlength_valid: {
			Notallowed: true,
			minlength: 2,
			maxlength: 50
		  },
		  cnpminlength_title: {
			Notallowed: true,
			required: true,
			minlength: 2,
			maxLen: 100
		  },
		  Store_Label: {
			Notallowed: true,
			required: true,
			minlength: 2,
			maxLen: 40
		  },
		  Store_Name: {
			Notallowed: true,
			required: true,
			minlength: 2,
			maxLen: 20
		  },
		  cnpminlength_description: {
			Notallowed: true,
			minlength: 2,
			maxlength: 500
		  },
		  cnpminlength6valid: {
			Notallowed: true,
			minlength: 6,
			maxlength: 50
		  },
		  cnpoption_title: {
			Notallowed: true,
			required: true,
			minlength: 2,
			maxLen: 80
		  },
		  cnpoption_value: {
			Notallowed: true,
			required: true,
			minlength: 2,
			maxLen: 255
		  },
		  cnp_installmentmax: {
			Notallowed: true,
			min: 2,
			max: 998,
			digits:true
		  },
		  cnp_subscriptionmax: {
			Notallowed: true,
			min: 2,
			max: 999,
			digits:true
		  } 
		});
             
    /* Customised the messages */
    //jQuery.validator.messages.required = "Please enter value for this field!";
	
  });