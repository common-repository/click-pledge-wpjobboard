<?php //ini_set('default_socket_timeout', 900);

class Config_ClickandPledge extends Wpjb_Form_Abstract_Payment
{ 
	
    public function init()
    {
        parent::init();
		
		parent::init();		
		add_filter( 'getext', 'theme_change_label_names');	
		
        wp_register_script( 'clickandpledge-admin-script', plugins_url( '/clickandpledge-admin.js', __FILE__ ));
       
		wp_enqueue_script( 'clickandpledge-admin-script' );		
		wp_enqueue_script( 'clickandpledge-admin' );		
	 	ob_start();
	 	global $wpdb;
		$accountsinfotblnm = Payment_ClickandPledge::get_cnp_wpjbaccountsinfo();
		$cnpaccountusername = $this->getwpjbCnPAccountUserID();
		if($cnpaccountusername !=""){
		$this->addGroup("clickandpledge", __("Click & Pledge [you are logged in as: ". $cnpaccountusername."]", "wpjobboard"));
	   }
		else{$this->addGroup("clickandpledge", __("Click & Pledge", "wpjobboard"));}
		$accountsinfotblnm = Payment_ClickandPledge::get_cnp_wpjbaccountsinfo();
 	    $accountstable_name = $accountsinfotblnm;
		$cnpsqlst= "SELECT count(*) FROM ". $accountstable_name;
		 $wpjbrowcount = $wpdb->get_var( $cnpsqlst );
		if($wpjbrowcount == 0){
	
		$e = $this->create("wpjobboard_clickandpledge_register");
      	$e->setRenderer(array($this, "cnpwpjblogin"));
	    $this->addElement($e, "clickandpledge");
        
		}else{
       if($wpjbrowcount > 0){
		    $e = $this->create("wpjobboard_clickandpledge_Settings");
			$e->setRenderer(array($this, "cnpwpjblogintitle"));
			$this->addElement($e, "clickandpledge");
		
	   }
		$e = $this->create("wpjobboard_clickandpledge_AccountID", Daq_Form_Element::TYPE_SELECT);
        $e->setValue($this->conf("wpjobboard_clickandpledge_AccountID"));
        $e->setLabel(__("C&P Account Id", "wpjobboard"));
			$cnptransactios=$this->get_CnPwpjbaccountslist(); 
         $keys = array_column($cnptransactios, 'AccountId');
		array_multisort(array_map(function($element) {
      return $element['AccountId'];
  }, $cnptransactios), SORT_ASC, $cnptransactios);
		if(isset($_POST['wpjobboard_clickandpledge_AccountID']) && wp_unslash(sanitize_text_field($_POST['wpjobboard_clickandpledge_AccountID']))!="")
		{
			$cnpaccountid = wp_unslash(sanitize_text_field($_POST['wpjobboard_clickandpledge_AccountID']));
		}
			else{$cnpaccountid = $this->conf("wpjobboard_clickandpledge_AccountID");}
		 foreach($cnptransactios as $cnpacnts){
			if($cnpacnts['AccountId'] == $cnpaccountid){
				 $found = true;
				 $cnpactiveuser = $cnpacnts['AccountId'];
			}
			 
			 $actval = $cnpacnts['AccountId']." [". stripslashes($cnpacnts['Organization'])."]";
			 $e->addOption($cnpacnts['AccountId'],$cnpacnts['AccountId'],$actval);		 }	 if(!isset($found)) {$cnpactiveuser = $cnptransactios[0]['AccountId'];}
			
			 $e->setHint(__("<div align='right'><a href='#' id='rfrshtokens'>Refresh Accounts</a></div>", "wpjobboard"));
			 $this->addElement($e, "clickandpledge");
			
			$this->_env = array(
				'test' => __("Test Mode", "wpjobboard"),
				'live' => __("Live Mode", "wpjobboard"),    
			 );
        $e = $this->create("wpjobboard_clickandpledge_OrderMode", Daq_Form_Element::TYPE_RADIO);
        $e->setValue($this->conf("wpjobboard_clickandpledge_OrderMode"));
        $e->setLabel(__("Order Mode:", "wpjobboard"));
        $e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge");
			
		$e = $this->create("wpjobboard_clickandpledge_ConnectCampaignAlias", Daq_Form_Element::TYPE_SELECT);
        $e->setValue($this->conf("wpjobboard_clickandpledge_ConnectCampaignAlias"));
        $e->setLabel(__("Connect Campaign URL Alias", "wpjobboard"));
        $e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
		$cnpconnectcampaign=$this->getwpjbCnPConnectCampaigns($cnpactiveuser);
      
       if(isset($cnpconnectcampaign->name) && ($cnpconnectcampaign->name !=""))
			{
			
				 $actcamval = $cnpconnectcampaign->name." (".$cnpconnectcampaign->alias.")";
				 $e->addOption($cnpconnectcampaign->alias,$cnpconnectcampaign->alias,$actcamval);	
					
				} 
				else{
                
             			for($inc = 0 ; $inc < count($cnpconnectcampaign);$inc++)
						{

						$actcamval = $cnpconnectcampaign[$inc]->name." (".$cnpconnectcampaign[$inc]->alias.")";
						$e->addOption($cnpconnectcampaign[$inc]->alias,$cnpconnectcampaign[$inc]->alias,$actcamval);	
						}
					}

        $this->addElement($e, "clickandpledge");
			
			$e = $this->create("wpjobboard_clickandpledge_apiSettings");
			  $e->setLabel(__("Payment Methods", "wpjobboard"));
				$e->setRenderer(array($this, "cnpwpjbsettings"));
				$this->addElement($e, "clickandpledge");
		
	 	$cnpacountid = $this->conf("wpjobboard_clickandpledge_AccountID");
		$cnpaccountGUID = self::getwpjbCnPAccountGUID($this->conf("wpjobboard_clickandpledge_AccountID")); 
		$cnpUID = CNPWJ_PLUGIN_UID;
		$cnpKey = CNPWJ_PLUGIN_SKY;
		$connect1  = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
	    $url = plugin_dir_path( __FILE__ ).'Auth.wsdl';
     	$client1   = new SoapClient($url, $connect1); 
		if( isset($cnpacountid) && $cnpacountid !="" && isset($cnpaccountGUID) &&  $cnpaccountGUID !="")
		{ 
			$xmlr1  = new SimpleXMLElement("<GetAccountDetail></GetAccountDetail>");
			$xmlr1->addChild('accountId',$cnpacountid);
			$xmlr1->addChild('accountGUID',$cnpaccountGUID);
			$xmlr1->addChild('username',$cnpUID);
			$xmlr1->addChild('password',$cnpKey);
			$response1                    =  $client1->GetAccountDetail($xmlr1);
        
			$responsearramex              =  $response1->GetAccountDetailResult->Amex;
			$responsearrJcb               =  $response1->GetAccountDetailResult->Jcb;
			$responsearrMaster            =  $response1->GetAccountDetailResult->Master;
			$responsearrVisa              =  $response1->GetAccountDetailResult->Visa;
			$responsearrDiscover          =  $response1->GetAccountDetailResult->Discover;
			$responsearrecheck            =  $response1->GetAccountDetailResult->Ach;
			$responsearrCustomPaymentType =  $response1->GetAccountDetailResult->CustomPaymentType;
            $responsearrAPM               =  $response1->GetAccountDetailResult->APM;
            $responsearrPayPal            =  $response1->GetAccountDetailResult->PayPal;
        	$responsearrBankAccount       =  $response1->GetAccountDetailResult->BankAccount;
           $responsearrmerchantid         =  $response1->GetAccountDetailResult->PayPalMerchantId;
		
			if($responsearramex == true || $responsearrJcb == true || $responsearrMaster== true || $responsearrVisa ==true || $responsearrDiscover == true ){ 
			$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_cc", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("CreditCard");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			if($responsearrecheck == true){
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_eCheck", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("eCheck");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			else{
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_eCheck", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
            if($responsearrAPM == true){
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_gpay", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("gpay");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			else{
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_gpay", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
         if($responsearrPayPal == true){
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_paypal", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("paypal");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
         
                $e = $this->create("wpjobboard_clickandpledge_Paymentmethods_paypalmid", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue($responsearrmerchantid);
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			else{
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_paypal", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
                $e = $this->create("wpjobboard_clickandpledge_Paymentmethods_paypalmid", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
         if($responsearrBankAccount == true){
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_ba", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("ba");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			else{
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_ba", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			if($responsearramex == true){
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_Amex", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("Amex");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			else{
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_Amex", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			if($responsearrJcb == true){
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_Jcb", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("Jcb");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			else{
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_Jcb", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			if($responsearrMaster == true){
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_master", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("Master");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			else{
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_master", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			if($responsearrVisa == true){
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_Visa", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("Visa");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}else{
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_Visa", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			if($responsearrDiscover == true){
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_Discover", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("Discover");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			else{
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_Discover", Daq_Form_Element::TYPE_HIDDEN);
				$e->setValue("");
				$e->setLabel(__("", "wpjobboard"));	$this->addElement($e, "clickandpledge");
			}
			
       		
			
		}
		
			$this->_env = array(
            'gpay' => __("Google Pay", "wpjobboard"),
        );
		$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_gpay", Daq_Form_Element::TYPE_CHECKBOX);
        $e->setValue($this->conf("wpjobboard_clickandpledge_Paymentmethods_gpay"));
        $e->setLabel(__("", "wpjobboard"));
		$e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge");
        
         $this->_env = array(
            'paypal' => __("Paypal/Venmo", "wpjobboard"),
        );
		$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_paypal", Daq_Form_Element::TYPE_CHECKBOX);
        $e->setValue($this->conf("wpjobboard_clickandpledge_Paymentmethods_paypal"));
        $e->setLabel(__("", "wpjobboard"));
		$e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge");
        
        $this->_env = array(
            'ba' => __("Bank Account", "wpjobboard"),
        );
		$e = $this->create("wpjobboard_clickandpledge_Paymentmethods_ba", Daq_Form_Element::TYPE_CHECKBOX);
        $e->setValue($this->conf("wpjobboard_clickandpledge_Paymentmethods_ba"));
        $e->setLabel(__("", "wpjobboard"));
		$e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge");
        
        
        
		
		

				$this->_env = array(
					'CustomPayment' => __("Custom Payment", "wpjobboard"),
				);
				$e = $this->create("wpjobboard_clickandpledge_Paymentmethods", Daq_Form_Element::TYPE_CHECKBOX);
				$e->setValue($this->conf("wpjobboard_clickandpledge_Paymentmethods"));
				$e->setLabel(__("", "wpjobboard"));
				$e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
				foreach($this->_env as $k => $v) {
					$e->addOption($k, $k,  $v);
				}
				$this->addElement($e, "clickandpledge");
			
	
		$e = $this->create("wpjobboard_clickandpledge_titles", Daq_Form_Element::TYPE_TEXTAREA);
        $e->setValue($this->conf("wpjobboard_clickandpledge_titles"));
        $e->setLabel(__("Title(s)", "wpjobboard"));
		$e->setHint(__("Please Enter Custom payment names by (;) separated -for example test1;test2;test3", "wpjobboard"));
      
        $this->addElement($e, "clickandpledge");
		
	
		$e = $this->create("wpjobboard_clickandpledge_reference", Daq_Form_Element::TYPE_TEXT);
        $e->setValue($this->conf("wpjobboard_clickandpledge_reference"));
        $e->setLabel(__("Reference Number", "wpjobboard"));
        $this->addElement($e, "clickandpledge");
			
		$this->_env = array(
            'CreditCard' => __("Credit Card", "wpjobboard"),
            'eCheck' => __("eCheck", "wpjobboard"),
            'gpay' => __("Google Pay", "wpjobboard"),
            'paypal' => __("PayPal/Venmo", "wpjobboard"),
            'ba' => __("Bank Account", "wpjobboard"),
			
		);
			$rule = explode(';', $this->conf("wpjobboard_clickandpledge_titles"));
        
			for($i=0;$i<count($rule);$i++)
			{
				if($rule[$i]!=""){
				$this->_env[$rule[$i]] =$rule[$i];}
			}
		
        $e = $this->create("wpjobboard_clickandpledge_DefaultpaymentMethod", Daq_Form_Element::TYPE_SELECT);
        $e->setValue($this->conf("wpjobboard_clickandpledge_DefaultpaymentMethod"));
        $e->setLabel(__("Default Payment Method", "wpjobboard"));
        $e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
		
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge");
		
       
		//Recurring Settings
		
		
		//Receipt Settings
		$this->addGroup("clickandpledge_receiptsettings", __("Receipt Settings", "wpjobboard"));
		$this->_env = array(
            'yes' => __("", "wpjobboard"),
        );
		$e = $this->create("wpjobboard_clickandpledge_emailcustomer", Daq_Form_Element::TYPE_CHECKBOX);
        $e->setValue($this->conf("wpjobboard_clickandpledge_emailcustomer"));
        $e->setLabel(__("Send Receipt to Patron", "wpjobboard"));
		$e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge_receiptsettings");
		
		// PaymentDefaultnumberofpayments
		$e = $this->create("wpjobboard_clickandpledge_receiptsettings", Daq_Form_Element::TYPE_TEXTAREA);
        $e->setValue($this->conf("wpjobboard_clickandpledge_receiptsettings"));
        $e->setLabel(__("Receipt Header", "wpjobboard"));
   
		$e->setHint(__("Maximum: 1500 characters, the following HTML tags are allowed: &lt; P>&lt; /P&gt;&lt; OL&gt;&lt; /OL&gt;&lt; LI&gt;&lt; /LI&gt;&lt; UL&gt;&lt; /UL&gt;. You have <span id='OrganizationInformation_countdown'>1500</span> characters left.", "wpjobboard"));
        $this->addElement($e, "clickandpledge_receiptsettings");
		
		// terms and Conditions admin
				
		
		$e = $this->create("wpjobboard_clickandpledge_termsandconditionsadmin", Daq_Form_Element::TYPE_TEXTAREA);
        $e->setValue($this->conf("wpjobboard_clickandpledge_termsandconditionsadmin"));
        $e->setLabel(__("Terms and Conditions", "wpjobboard"));
        $e->setHint(__("To be added at the bottom of the receipt. Typically the text provides proof that the patron has read & agreed to the terms & conditions. The following HTML tags are allowed: &lt; P>&lt; /P&gt;&lt; OL&gt;&lt; /OL&gt;&lt; LI&gt;&lt; /LI&gt;&lt; UL&gt;&lt; /UL&gt;. 
Maximum: 1500 characters, You have <span id='TermsCondition_countdown'>1500</span> characters left.", "wpjobboard"));
        $this->addElement($e, "clickandpledge_receiptsettings");
		
		//Recurring Settings
		$this->addGroup("clickandpledge_recurringsettings", __("Recurring Settings", "wpjobboard"));		
   		
	
		
	
		$this->_env = array(
            'OneTimeOnly' => __("One Time Only", "wpjobboard"),
            'Recurring' => __("Recurring", "wpjobboard"),
		
        );
        $e = $this->create("wpjobboard_clickandpledge_Paymentoptions", Daq_Form_Element::TYPE_CHECKBOX);
        $e->setValue($this->conf("wpjobboard_clickandpledge_Paymentoptions"));
        $e->setLabel(__("Payment Options", "wpjobboard"));
      
        $e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge_recurringsettings");
		
		$this->_env = array(
            'OneTimeOnly' => __("One Time Only", "wpjobboard"),
            'Recurring' => __("Recurring", "wpjobboard"),
		
        );
        $e = $this->create("wpjobboard_clickandpledge_DefaultpaymentOptions", Daq_Form_Element::TYPE_SELECT);
        $e->setValue($this->conf("wpjobboard_clickandpledge_DefaultpaymentOptions"));
        $e->setLabel(__("Default Payment Options", "wpjobboard"));
        $e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge_recurringsettings");
     
		$this->_env = array(
            'Installment' => __("Installment (e.g. pay $1000 in 10 installments of $100 each)", "wpjobboard"),
            'Subscription' => __("Subscription (e.g. pay $100 every month for 12 months)", "wpjobboard"),
		
        );
        $e = $this->create("wpjobboard_clickandpledge_PaymentSubscription", Daq_Form_Element::TYPE_CHECKBOX);
        $e->setValue($this->conf("wpjobboard_clickandpledge_PaymentSubscription"));
        $e->setLabel(__("Recurring Types", "wpjobboard"));
       
        $e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge_recurringsettings");
		
		$this->_env = array(
            'Installment' => __("Installment", "wpjobboard"),
            'Subscription' => __("Subscription", "wpjobboard"),
		
        );
        $e = $this->create("wpjobboard_clickandpledge_PaymentRecurring", Daq_Form_Element::TYPE_SELECT);
        $e->setValue($this->conf("wpjobboard_clickandpledge_PaymentRecurring"));
        $e->setLabel(__("Default Recurring Type", "wpjobboard"));
        $e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge_recurringsettings");	
		
		$this->_env = array(
            'Week' => __("Week", "wpjobboard"),
			'2Weeks' => __("2 Weeks", "wpjobboard"),
			'Month' => __("Month", "wpjobboard"),
			'2Months' => __("2 Months", "wpjobboard"),
			'Quarter' => __("Quarter", "wpjobboard"),
			'6Months' => __("6 Months", "wpjobboard"),
			'Year' => __("Year", "wpjobboard"),
        ); 
        $e = $this->create("wpjobboard_clickandpledge_PaymentPeriods", Daq_Form_Element::TYPE_CHECKBOX);
        $e->setValue($this->conf("wpjobboard_clickandpledge_PaymentPeriods"));
        $e->setLabel(__("Periodicity", "wpjobboard"));
     
        $e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge_recurringsettings");
		
		$this->_env = array(
            'indefinite' => __("Indefinite Only", "wpjobboard"),
            'openfield' => __("Open Field Only", "wpjobboard"),
            'indefinite_openfield' => __("Indefinite + Open Field Option", "wpjobboard"),
            'fixednumber' => __("Fixed Number - No Change Allowed", "wpjobboard"),
         );
        $e = $this->create("wpjobboard_clickandpledge_indefinite", Daq_Form_Element::TYPE_RADIO);
        $e->setValue($this->conf("wpjobboard_clickandpledge_indefinite"));
        $e->setLabel(__("Number of payments", "wpjobboard"));
	
        $e->addValidator(new Daq_Validate_InArray(array_keys($this->_env)));
        foreach($this->_env as $k => $v) {
            $e->addOption($k, $k,  $v);
        }
        $this->addElement($e, "clickandpledge_recurringsettings");
		
		
		
		$e = $this->create("wpjobboard_clickandpledge_dfltnoofpaymnts", Daq_Form_Element::TYPE_TEXT);
        $e->setValue($this->conf("wpjobboard_clickandpledge_dfltnoofpaymnts"));
		$e->setAttr("maxlength", "3");
        $e->setLabel(__("Default number of payments", "wpjobboard"));
      
        $this->addElement($e, "clickandpledge_recurringsettings");
		
		
		$e = $this->create("wpjobboard_clickandpledge_maxnoofinstallments", Daq_Form_Element::TYPE_TEXT);
        $e->setValue($this->conf("wpjobboard_clickandpledge_maxnoofinstallments"));
		$e->setAttr("maxlength", "3");
        $e->setLabel(__("Maximum number of installments allowed", "wpjobboard"));
      
        $this->addElement($e, "clickandpledge_recurringsettings");
		
	}	
		
      
    }

public static function cnp_jbcnpgettotal(){
$data = array();

 $cnpfrmval = explode('&', $_REQUEST['cnpfrmid']);
//print_r($cnpfrmval);
foreach($cnpfrmval as $value)
{
    $value1 = explode('=', $value);

    $data[urldecode($value1[0])] = urldecode($value1[1]);

}

           $_POST = $data;



  $rectyp = $_POST['clickandpledge_recurring_type']; 
   $isrec = $_POST['clickandpledge_onetimeonly']; 
 $recmthd =""; $recind =""; $recinst=""; $Installments ="";
  $prtotamount = 0; $shtotamount = 0; 
if( $isrec == 'clickandpledge_Recurring') {
 $recmthd = $_POST["clickandpledge_recurring_type"];
  $recind = $_POST['clickandpledge_indefinite'];
  $recinst = $_POST['clickandpledge_nooftimes'];
						if($recind == 'on') {
							$Installments = ($recmthd == 'Installment') ? 998 : 999;
						} elseif($recinst != "") {
							$Installments = $recinst;
						}
						else {
							$Installments = 999;
						}
}
$wpjbdiscount ="";
$wpjblistingid = $_POST['clickandpledge_listing_id'];
$wpjbdiscount = $_POST['clickandpledge_coupon_code'];

echo $gettotamount = self::cnpwpjbgettotamount($wpjblistingid,$wpjbdiscount,$recmthd,$Installments); 
exit;

}
public static function cnp_jbcnppaymentintent(){ //gpay
$data = array();

 $cnpfrmval = explode('&', $_REQUEST['cnpfrmid']);

foreach($cnpfrmval as $value)
{
    $value1 = explode('=', $value);

    $data[urldecode($value1[0])] = urldecode($value1[1]);

}

           $_POST = $data;



  $rectyp = $_POST['clickandpledge_recurring_type']; 
  $isrec = $_POST['clickandpledge_onetimeonly']; 
  $recmthd =""; $recind=""; $recinst =""; $Installments ="";
  $prtotamount = 0; $shtotamount = 0;
if( $isrec == 'clickandpledge_Recurring') {
$recmthd = $_POST["clickandpledge_recurring_type"];
  $recind = $_POST['clickandpledge_indefinite'];
  $recinst = $_POST['clickandpledge_nooftimes'];
						if($recind == 'on') {
							$Installments = ($recmthd == 'Installment') ? 998 : 999;
						} elseif($recinst != "") {
							$Installments = $recinst;
						}
						else {
							$Installments = 999;
						}
}
$wpjbdiscount = "";
$wpjblistingid = $_POST['clickandpledge_listing_id'];
$wpjbdiscount = $_POST['clickandpledge_coupon_code'];



 $totamount = self::cnpwpjbgettotamount($wpjblistingid,$wpjbdiscount,$recmthd,$Installments);



        $cnpOrganizationID = $_POST['clickandpledge_AccountID']; 
       
          $amountgpay = $totamount ;
          $cnpcurrency = $_POST['selectedcurrency']; 
            $cnpCurrenyCode = $cnpcurrency;
			$cnpGpayKey=""; 
			$cnpAPM_EndPoint = "";
          
	      $cnpAPMKey = self::getcnpwpjbAPMKey($cnpOrganizationID);
            if ($data['gfcnp_formmode'] != "Test" )
            {
                $cnpGpayKey= $cnpAPMKey;
                $cnpAPM_EndPoint = CNPWJ_PLUGIN_APM_EndPointLive;
            }
            else
            {
                $cnpGpayKey = $cnpAPMKey;
                $cnpAPM_EndPoint = CNPWJ_PLUGIN_APM_EndPointTest;
            }

            $cnptoken = "";
          
	      $cpapiEndpoint = $cnpAPM_EndPoint . "/Stripe/CreatePaymentIntent/". $cnpOrganizationID."?amount=".$amountgpay;
      
 
		$curl = curl_init();
		$cnpemailaddress = $cnpGpayKey; 
		curl_setopt_array($curl, array(
  		CURLOPT_URL => $cpapiEndpoint,
     	CURLOPT_RETURNTRANSFER => true,
  	    CURLOPT_ENCODING => "",
  		CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
  		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "Key: ".$cnpemailaddress,'Content-Length: 0' ),
));

$response = curl_exec($curl);
  echo  str_replace("\"", "",$response);
die(); 
}

public static function cnp_jbCreateBillingAgreement(){
        
global $wpdb;$data = array();
 $cnpfrmval = explode('&', $_REQUEST['cnpfrmid']);

foreach($cnpfrmval as $value)
{
    $value1 = explode('=', $value);

    $data[urldecode($value1[0])] = urldecode($value1[1]);

}

           $_POST = $data;



  $rectyp = $_POST['clickandpledge_recurring_type']; 
  $isrec = $_POST['clickandpledge_onetimeonly']; 
$recmthd =""; $recind=""; $recinst =""; $Installments ="";

  $prtotamount = 0; $shtotamount = 0;
if( $isrec == 'clickandpledge_Recurring') {
  $recmthd = $_POST["clickandpledge_recurring_type"];
  $recind = $_POST['clickandpledge_indefinite'];
  $recinst = $_POST['clickandpledge_nooftimes'];
						if($recind == 'on') {
							$Installments = ($recmthd == 'Installment') ? 998 : 999;
						} elseif($recinst != "") {
							$Installments = $recinst;
						}
						else {
							$Installments = 999;
						}
}
$wpjbdiscount = "";
 $wpjblistingid = $_POST['clickandpledge_listing_id'];
$wpjbdiscount = $_POST['clickandpledge_coupon_code'];
$totamount = self::cnpwpjbgettotamount($wpjblistingid,$wpjbdiscount,$recmthd,$Installments);
if( $isrec != 'clickandpledge_Recurring') {
 $totamount =  round($totamount/100,2);
}


          
    		$cnpOrganizationID = $_POST['clickandpledge_AccountID']; 
            $amountbpay = $totamount;
            $cnpcurrency = $_POST['selectedcurrency']; 
            $cnpCurrenyCode = $cnpcurrency;
			$cnpPayPalKey=""; 
			$cnpAPM_EndPoint = "";
         	  $cnpreferenceid = self::getcnpjbGUID();

	

          if ($data['gfcnp_formmode'] != "Test" )
            {
                $cnpPayPalKey= CNPWJ_PLUGIN_PayPalKeyLive;
                $cnpAPM_EndPoint = CNPWJ_PLUGIN_APM_EndPointLive;
            }
            else
            {
                $cnpPayPalKey = CNPWJ_PLUGIN_PayPalKeyLive;
                $cnpAPM_EndPoint = CNPWJ_PLUGIN_APM_EndPointTest;
            }

 			$cnptoken = "";
            $cpapiEndpoint = $cnpAPM_EndPoint . "/Paypal/CreateBillingAgreementToken/". $cnpOrganizationID;
		    $return_url = plugin_dir_url(__FILE__)."paypal.html";
            $payload = sprintf('{"Return_url":"%s","Cancel_url":"%s"}', $return_url, $return_url);

       


        $curl = curl_init();
		$cnpemailaddress = $cnpPayPalKey; 
		curl_setopt_array($curl, array(
  		CURLOPT_URL => $cpapiEndpoint,
        CURLOPT_RETURNTRANSFER => true,
  	    CURLOPT_ENCODING => "",
  		CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
  		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "Key: ".$cnpemailaddress ),
));

$response = curl_exec($curl);

if (!empty($response)) { 
  $createOrderResponse = json_decode($response, true);
  $token = $createOrderResponse['links'][0]['href'];

}

  echo  $token;
die(); 

		}

public static function cnp_jbcnpcreateorder(){ //paypal
global $wpdb;$data = array();
 $cnpfrmval = explode('&', $_REQUEST['cnpfrmid']);

foreach($cnpfrmval as $value)
{
    $value1 = explode('=', $value);

    $data[urldecode($value1[0])] = urldecode($value1[1]);

}

           $_POST = $data;



  $rectyp = $_POST['clickandpledge_recurring_type']; 
  $isrec = $_POST['clickandpledge_onetimeonly']; 
$recmthd =""; $recind=""; $recinst =""; $Installments ="";
 
  $prtotamount = 0; $shtotamount = 0;
if( $isrec == 'clickandpledge_Recurring') {
 $recmthd = $_POST["clickandpledge_recurring_type"];
  $recind = $_POST['clickandpledge_indefinite'];
  $recinst = $_POST['clickandpledge_nooftimes'];
						if($recind == 'on') {
							$Installments = ($recmthd == 'Installment') ? 998 : 999;
						} elseif($recinst != "") {
							$Installments = $recinst;
						}
						else {
							$Installments = 999;
						}
}
$wpjbdiscount = "";
 $wpjblistingid = $_POST['clickandpledge_listing_id'];
$wpjbdiscount = $_POST['clickandpledge_coupon_code'];
$totamount = self::cnpwpjbgettotamount($wpjblistingid,$wpjbdiscount,$recmthd,$Installments);
if( $isrec != 'clickandpledge_Recurring') {
 $totamount =  round($totamount/100,2);
}


          
    		$cnpOrganizationID = $_POST['clickandpledge_AccountID']; 
            $amountbpay = $totamount;
            $cnpcurrency = $_POST['selectedcurrency']; 
            $cnpCurrenyCode = $cnpcurrency;
			$cnpPayPalKey=""; 
			$cnpAPM_EndPoint = "";
         	  $cnpreferenceid = self::getcnpjbGUID();

	

          if ($data['gfcnp_formmode'] != "Test" )
            {
                $cnpPayPalKey= CNPWJ_PLUGIN_PayPalKeyLive;
                $cnpAPM_EndPoint = CNPWJ_PLUGIN_APM_EndPointLive;
            }
            else
            {
                $cnpPayPalKey = CNPWJ_PLUGIN_PayPalKeyLive;
                $cnpAPM_EndPoint = CNPWJ_PLUGIN_APM_EndPointTest;
            }

            $cnptoken = "";
          $cpapiEndpoint = $cnpAPM_EndPoint . "/Paypal/CreateOrder/". $cnpOrganizationID;
         
            $createOrderObject = new CreateOrder();
            $createOrderObject->intent = "CAPTURE"; // "AUTHORIZE";// "CAPTURE";

            $purchaseUnit = new Purchase_Units();
            $purchaseUnit->reference_id = $cnpreferenceid; 
            $purchaseUnit->description = "Donation Amount";

            $amount = new Amount();

            $amount->currency_code = $cnpCurrenyCode; // "USD";
            $amount->value = $totamount;


            $purchaseUnit->amount = $amount;

            $payee = new Payeenew(); 
			$payee = null;
          
            $purchaseUnit->payee = $payee; 
            $item_Total = new Item_Total();
            $item_Total->currency_code = $cnpCurrenyCode;
            $item_Total->value = $totamount;

            $breakdown = new Breakdown();
            $breakdown->item_total = $item_Total;

 			$breakdown->shipping = null;
 			$breakdown->tax_total = null;
            $purchaseUnit->amount->breakdown = $breakdown;

            $cnpitems = new Items();
            $unit_amount = new Unit_Amount();
 $unitamounttitle = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wpjb_pricing WHERE id = '.$wpjblistingid, OBJECT );
$cnpinc =0;
       
         $cnpitems = new Items();
         $unit_amount = new Unit_Amount();
      
                 $cnpitems->name = $unitamounttitle->title;
				 $cnpitems->sku =$unitamounttitle->title;
                 $unit_amount->currency_code = $cnpCurrenyCode;
                 $unit_amount->value = $totamount;
                 $cnpitems->unit_amount = $unit_amount;
            	 $cnpitems->quantity= '1';
                 $cnpitems->category ='DONATION';
       
       			 $purchaseUnit->items[$cnpinc] = $cnpitems ;
    
         $cnpshipping = new Shipping1(); //null object
         $cnpshipping->address = null;

            $purchaseUnit->shipping = $cnpshipping; //null object
            $purchaseUnit->shipping_method = ""; //null object

         $cnppayinstrction = new Payment_Instruction(); //null object
         $cnppayinstrction = null;
         $purchaseUnit->payment_instruction = $cnppayinstrction; //null object
         $purchaseUnit->payment_group_id = 0; //null object

            $createOrderObject->purchase_units[0] = new Purchase_Units();
           
            $createOrderObject->purchase_units[0] = $purchaseUnit;

        
 
           $application_context = new Application_Context();
       
            $application_context->return_url = $_SERVER['HTTP_REFERER'];
            $application_context->cancel_url = $_SERVER['HTTP_REFERER'];

            $createOrderObject->application_context = $application_context;

            $cnporderJSON = json_encode($createOrderObject, JSON_UNESCAPED_SLASHES);

		$curl = curl_init();
		$cnpemailaddress = $cnpPayPalKey; 
		curl_setopt_array($curl, array(
  		CURLOPT_URL => $cpapiEndpoint,
       
  		CURLOPT_RETURNTRANSFER => true,
  	    CURLOPT_ENCODING => "",
  		CURLOPT_MAXREDIRS => 10,
        
  	    CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
  		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $cnporderJSON,
        CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "Key: ".$cnpemailaddress ),
));

$response = curl_exec($curl);

if (!empty($response)) { 
  $createOrderResponse = json_decode($response, true);
  $token = $createOrderResponse['id']; 

}

  echo  $token;
die(); 

		}

public static function cnp_jbcnpbapaymentintent(){ //bank

 $cnpfrmval = explode('&', $_REQUEST['cnpfrmid']);

foreach($cnpfrmval as $value)
{
    $value1 = explode('=', $value);

    $data[urldecode($value1[0])] = urldecode($value1[1]);

}

           $_POST = $data;



  $rectyp = $_POST['clickandpledge_recurring_type']; 
  $isrec = $_POST['clickandpledge_onetimeonly']; 
$recmthd =""; $recind=""; $recinst =""; $Installments ="";
 
  $prtotamount = 0; $shtotamount = 0;
if( $isrec == 'clickandpledge_Recurring') {
 $recmthd = $_POST["clickandpledge_recurring_type"];
  $recind = $_POST['clickandpledge_indefinite'];
  $recinst = $_POST['clickandpledge_nooftimes'];
						if($recind == 'on') {
							$Installments = ($recmthd == 'Installment') ? 998 : 999;
						} elseif($recinst != "") {
							$Installments = $recinst;
						}
						else {
							$Installments = 999;
						}
}
$wpjbdiscount = "";
$wpjblistingid = $_POST['clickandpledge_listing_id'];
$wpjbdiscount = $_POST['clickandpledge_coupon_code'];
 $totamount = self::cnpwpjbgettotamount($wpjblistingid,$wpjbdiscount,$recmthd,$Installments);




    		 $cnpOrganizationID = $_POST['clickandpledge_AccountID']; 

            $amountbpay = $totamount;
            $cnpcurrency = $_POST['selectedcurrency']; 
            $cnpCurrenyCode = $cnpcurrency;
			$cnpGpayKey=""; 
			$cnpAPM_EndPoint = "";
            

			   $cnpAPMKey = self::getcnpwpjbAPMKey($cnpOrganizationID);
            if ($data['gfcnp_formmode'] != "Test" )
            {
                $cnpGpayKey= $cnpAPMKey;
                $cnpAPM_EndPoint = CNPWJ_PLUGIN_APM_EndPointLive;
            }
            else
            {
                $cnpGpayKey = $cnpAPMKey;
                $cnpAPM_EndPoint = CNPWJ_PLUGIN_APM_EndPointTest;
            }

            $cnptoken = "";
           
    		 $cpapiEndpoint =  $cnpAPM_EndPoint."/Stripe/CreateBankAccountPaymentIntent/". $cnpOrganizationID."?amount=".$amountbpay;
        
            $payloadJSON = "{";
            $payloadJSON .= "\"FirstName\": \"lakshmi\",";
            $payloadJSON .= "\"LastName\": \"pala\",";
            $payloadJSON .= "\"Email\": \"lakshmi@clickandpledge.com\",";
            $payloadJSON .= "\"Address1\": \"\",";
            $payloadJSON .= "\"Address2\": \"\",";
            $payloadJSON .= "\"Country\": \"\",";
            $payloadJSON .= "\"State\": \"t\",";
            $payloadJSON .= "\"ZipCode\": \"\",";
            $payloadJSON .= "\"Phone\": \"\"";
            $payloadJSON .= "}";

			$curl = curl_init();
		    $cnpemailaddress = $cnpGpayKey; 
		    curl_setopt_array($curl, array(
  		    CURLOPT_URL => $cpapiEndpoint,
       		CURLOPT_RETURNTRANSFER => true,
  	        CURLOPT_ENCODING => "",
  		    CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
  		    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payloadJSON,
            CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "Key: ".$cnpemailaddress),
));
       

			$response = curl_exec($curl);

		  echo  str_replace("\"", "",$response);
die(); 

		}
public static function getcnpjbGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }
    else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid =substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);// "}"
        return $uuid;
    }
}
 public static function getcnpwpjbAPMKey($accid) {
		$cnpAccountGUId ="";
		  global $wpdb;
        $table_name = Payment_ClickandPledge::get_cnp_wpjbaccountsinfo();
		$cnpaccountapmkey = $wpdb->get_var("SELECT cnpaccountsinfo_apmkey FROM $table_name where cnpaccountsinfo_orgid ='".$accid."'");
		
       
	 return $cnpaccountapmkey;
		
	}
 public static function numberformat($number, $decimals = 2,$decsep = '', $ths_sep = '') {
		$parts = explode('.', $number);
		if(count($parts) > 1) {
			return $parts[0].'.'.substr($parts[1],0,$decimals);
		} else {
			return $number;
		}
	}
public static function cnpwpjbgettotamount($wpjblistingid,$wpjbdiscount,$recmthd,$Installments)
{
     global $wpdb;
	 $unitdiscountvalue ="";

	if($wpjbdiscount != ""){
	  $unitdiscountvalue = $wpdb->get_row('SELECT discount,type FROM '.$wpdb->prefix.'wpjb_discount  WHERE code = "'.$wpjbdiscount.'"', OBJECT );
    }
	
	$unitamountset = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wpjb_pricing WHERE id = '.$wpjblistingid, OBJECT );

	$sub_total = $unitamountset->price;
		if($wpjbdiscount != '')
			{ 
				if($unitdiscountvalue->type == 1)
				{
				$untxsub_total = ($sub_total - ($sub_total * $unitdiscountvalue->discount)/100);
				} else
				{
				$untxsub_total = $sub_total - self::numberformat($unitdiscountvalue->discount,2,'.','')*100;
				}
			}
		else
		{
			$untxsub_total = $sub_total ;
		}
	
		 $jobboard_taxes = get_option('wpjb_config');
		 $job_taxes = get_option('taxes_enabled');
		 
		  $jobboard_taxpercent = $jobboard_taxes['taxes_default_rate'];
		  $jobboard_taxtype = $jobboard_taxes['taxes_price_type'];
		  $jobboard_taxset = $jobboard_taxes['taxes_enabled'];		 
		

		
		if($jobboard_taxset[0] == 1)
		{
        
		if($jobboard_taxtype == 'net')
		{
			$unit_discount ="";
		
			 $wpjb_unittaxnet =  ($unitamountset->price*$jobboard_taxpercent)/(100);
        
        if($wpjbdiscount != '')
			{
			
            $wpjb_unittax = $wpjb_unittaxnet - ($wpjb_unittaxnet*$unitdiscountvalue->discount)/100;
			} else
			{
			$wpjb_unittax = $wpjb_unittaxnet;
			}
        
			
			    if($unitdiscountvalue->type == 1)
				{
				
                 $unit_discount = (($sub_total)*$unitdiscountvalue->discount)/100;
				} else
				{
				$unit_discount = $unitdiscountvalue->discount;
				}

					$untxsub_total = ($unitamountset->price -$unit_discount );
			        $untxsub_total = round($untxsub_total,2);  
                    $untxsub_total = self::numberformat($untxsub_total,2,'.','')*100; 
		}
		else
		{
       //inclusive of tax";
		$wpjb_unittaxgross = ($unitamountset->price*$jobboard_taxpercent)/(100+$jobboard_taxpercent);
      
				
			if($wpjbdiscount != '')
			{
			
            $wpjb_unittax = $wpjb_unittaxgross - ($wpjb_unittaxgross*$unitdiscountvalue->discount)/100;
			} else
			{
			$wpjb_unittax = $wpjb_unittaxgross;
			}
			
				if($unitdiscountvalue->type == 1)
				{
				
				$unit_discount = (($sub_total/100)*$unitdiscountvalue->discount)/100;
				} 
				else
				{
				$unit_discount = $unitdiscountvalue->discount;
				}
				$wpjb_unittax=round($wpjb_unittax,2); 
             $untxsub_total = $untxsub_total;
		   	 $untxsub_total = ($untxsub_total - $wpjb_unittax);
			 $untxsub_total = self::numberformat($untxsub_total,2,'.','')*100;
      
			
		}
		}
		else 
		{
	
			 $untxsub_total = self::numberformat($untxsub_total,2,'.','')*100;
			if($wpjbdiscount != '')
			{
			    if($unitdiscountvalue->type == 1)
				{
				$unit_discount = (($sub_total/100)*$unitdiscountvalue->discount)/100;
				} else
				{
				$unit_discount = $unitdiscountvalue->discount;
				}
            }
		}

  $wpjb_unittax = round($wpjb_unittax,2)*100;


$prqty =1;

 $jbunitprice =""; $jbunittax = ""; $jbunitdiscount = "";$fnltotamount ="";
					
				
							if($recmthd == 'Installment') {
           							if($untxsub_total !=""){   $jbunitprice = (($untxsub_total/100)/$Installments)*100; 	}
          						 	 if($wpjb_unittax !=""){   $jbunittax = ($wpjb_unittax/$Installments);   }
           						 	  $totamount = self::numberformat((($jbunitprice + $jbunittax)/100),2,'.','') ;                
                                                      $fnltotamount = ($totamount * 100) ;
						
							}else {
									 if($untxsub_total !=""){   $jbunitprice = self::numberformat((($untxsub_total/100)),2,'.','')*100;}
          						 	if($wpjb_unittax !=""){   $jbunittax = round(($wpjb_unittax),2); }
                                    $totamount = self::numberformat((($jbunitprice + $jbunittax)/100),2,'.','') ; 
 									$fnltotamount = ($totamount * 1000) / 10; 	
                                                          
                                                         
           						 
								}



return	$fnltotamount;	

}
	public static function get_CnPwpjbaccountslist()
	{
			global $wpdb;
			$data['cnpaccounts'] = array();
		    $wpjbacc= Payment_ClickandPledge::get_cnp_wpjbaccountsinfo();
			$query = "SELECT * FROM " . $wpjbacc;
			$results = $wpdb->get_results($query, ARRAY_A);
			$count = sizeof($results);
			for($i=0; $i<$count; $i++){
				$data['cnpaccounts'][] = array(
				'AccountId'      => $results[$i]['cnpaccountsinfo_orgid'],
				'GUID'           => $results[$i]['cnpaccountsinfo_accountguid'],
				'Organization'   => $results[$i]['cnpaccountsinfo_orgname']    
			);

			}
		
		return $data['cnpaccounts'];
	}
	public static function getwpjbCnPAccountGUID($accid)
	{
			global $wpdb;
			$cnpAccountGUId ="";
			$wpjbacctbl= Payment_ClickandPledge::get_cnp_wpjbaccountsinfo();
		    $query = "SELECT * FROM " . $wpjbacctbl." where cnpaccountsinfo_orgid ='".$accid."'";
		    $result = $wpdb->get_results($query, ARRAY_A);
			$count = sizeof($result);
				for($i=0; $i<$count; $i++){
				 $cnpAccountGUId      = $result[$i]['cnpaccountsinfo_accountguid'];
				}
			 
			return $cnpAccountGUId;
		
	}
	public static function getwpjbCnPAccountUserID()
	{
			global $wpdb;
			$cnpAccountUserId ="";
			$wpjbacctbl= Payment_ClickandPledge::get_cnp_wpjbtokeninfo();
		    $query = "SELECT cnptokeninfo_username FROM " . $wpjbacctbl;
		    $result = $wpdb->get_results($query, ARRAY_A);
			$count = sizeof($result);
				for($i=0; $i<$count; $i++){
				 $cnpAccountUserId      = $result[$i]['cnptokeninfo_username'];
				}
			 
			return $cnpAccountUserId;
		
	}
	public function getwpjbCnPConnectCampaigns($cnpaccid)
	{

		$cnpacountid = $cnpaccid;$cnpcampaignalias="";
	    $cnpaccountGUID = $this->getwpjbCnPAccountGUID($cnpacountid);
		$cnpUID = CNPWJ_PLUGIN_UID;
		$cnpKey = CNPWJ_PLUGIN_SKY;
		$connect  = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
	    $client   = new SoapClient('https://resources.connect.clickandpledge.com/wordpress/Auth2.wsdl', $connect);
		if( isset($cnpacountid) && $cnpacountid !="" && isset($cnpaccountGUID) &&  $cnpaccountGUID !="")
		{ 
			$xmlr  = new SimpleXMLElement("<GetActiveCampaignList2></GetActiveCampaignList2>");
			$cnpsel ="";
			$xmlr->addChild('accountId', $cnpacountid);
			$xmlr->addChild('AccountGUID', $cnpaccountGUID);
			$xmlr->addChild('username', $cnpUID);
			$xmlr->addChild('password', $cnpKey);
			$response = $client->GetActiveCampaignList2($xmlr); 
			$responsearr =  $response->GetActiveCampaignList2Result->connectCampaign;
         $cnpforderRes = [];
 if( !is_array($responsearr)){
      $cnpforderRes[$responsearr->alias] = $responsearr->name;
    }
    else {
      foreach ($responsearr as $obj) {
        $cnpforderRes[$obj->alias] = $obj->name;
      }
    }
         ksort($cnpforderRes);
        natcasesort($cnpforderRes);
		}
		//print_r($responsearr);
		return $responsearr;
			
		}
	public static function getwpjbCnPConnectonCampaigns($cnpaccid,$cnpacccamp)
	{

		$cnpacountid = $cnpaccid;$cnpcampaignalias="";
	    $cnpaccountGUID = self::getwpjbCnPAccountGUID($cnpacountid); 
		$cnpUID = CNPWJ_PLUGIN_UID;
		$cnpKey = CNPWJ_PLUGIN_SKY;
		$connect  = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
	    $client   = new SoapClient('https://resources.connect.clickandpledge.com/wordpress/Auth2.wsdl', $connect);
		
		if( isset($cnpacountid) && $cnpacountid !="" && isset($cnpaccountGUID) &&  $cnpaccountGUID !="")
		{ 
			$xmlr  = new SimpleXMLElement("<GetActiveCampaignList2></GetActiveCampaignList2>");
			$cnpsel ="";
			$xmlr->addChild('accountId', $cnpacountid);
			$xmlr->addChild('AccountGUID', $cnpaccountGUID);
			$xmlr->addChild('username', $cnpUID);
			$xmlr->addChild('password', $cnpKey);
			$response = $client->GetActiveCampaignList2($xmlr); 
			$responsearr =  $response->GetActiveCampaignList2Result->connectCampaign;
			
			if($cnpacccamp != 'Loading............' || $cnpacccamp != '' )
			{
				 $cnpcampaignalias 	 =  $cnpacccamp;
			}
			
			 $camrtrnval = "<option value=''>Select Campaign Nameee</option>";
       if(isset($responsearr->alias) && ($responsearr->alias !=""))
			// if(count($responsearr) == 1)
				{
					if($responsearr->alias == $cnpcampaignalias){ $cnpsel ="selected='selected'";}
				 $camrtrnval.= "<option value='".$responsearr->alias."' ".$cnpsel." >".$responsearr->name." (".$responsearr->alias.")</option>";
				}else{
					for($inc = 0 ; $inc < count($responsearr);$inc++)
					{ if($responsearr[$inc]->alias == $cnpcampaignalias){ $cnpsel ="selected='selected'";}else{$cnpsel ="";}
					 $camrtrnval .= "<option value='".$responsearr[$inc]->alias."' ".$cnpsel.">".$responsearr[$inc]->name." (".$responsearr[$inc]->alias.")</option>";
					}

				}	
				}
		
		return $camrtrnval;
			
		}
	public static function getwpjbCnPactivePaymentList($cnpaccid)
	{

		global $wpdb;
		$cmpacntacptdcards = "";
		$cnpacountid = $cnpaccid;
		$cnpaccountGUID = self::getwpjbCnPAccountGUID($cnpaccid); 
		$cnpUID = CNPWJ_PLUGIN_UID;
		$cnpKey = CNPWJ_PLUGIN_SKY;
		$connect1  = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
	 
          $url = plugin_dir_path( __FILE__ ).'Auth.wsdl';
          $client1   = new SoapClient($url, $connect1); 
		if( isset($cnpacountid) && $cnpacountid !="" && isset($cnpaccountGUID) &&  $cnpaccountGUID !="")
		{ 
			$xmlr1  = new SimpleXMLElement("<GetAccountDetail></GetAccountDetail>");
			$xmlr1->addChild('accountId',$cnpacountid);
			$xmlr1->addChild('accountGUID',$cnpaccountGUID);
			$xmlr1->addChild('username',$cnpUID);
			$xmlr1->addChild('password',$cnpKey);
			$response1                    =  $client1->GetAccountDetail($xmlr1);
       // print_r($response1);
			$responsearramex              =  $response1->GetAccountDetailResult->Amex;
			$responsearrJcb               =  $response1->GetAccountDetailResult->Jcb;
			$responsearrMaster            =  $response1->GetAccountDetailResult->Master;
			$responsearrVisa              =  $response1->GetAccountDetailResult->Visa;
			$responsearrDiscover          =  $response1->GetAccountDetailResult->Discover;
			$responsearrecheck            =  $response1->GetAccountDetailResult->Ach;
			$responsearrCustomPaymentType =  $response1->GetAccountDetailResult->CustomPaymentType;
			$responsearrAPM               =  $response1->GetAccountDetailResult->APM;
            $responsearrPayPal            =  $response1->GetAccountDetailResult->PayPal;
        	$responsearrBankAccount       =  $response1->GetAccountDetailResult->BankAccount;
            $responsearrmerchantid       =  $response1->GetAccountDetailResult->PayPalMerchantId;
			
	
			$cmpacntacptdcards .= '			
			<ul style="margin:0px;">
            	<li><label for="wpjobboard_clickandpledge_Paymentmethods_CreditCard"><input type="checkbox" id="wpjobboard_clickandpledge_Paymentmethods_CreditCard" class="checkbox_active" value="CreditCard" name="wpjobboard_clickandpledge_Paymentmethods_CreditCard"  onclick="block_creditcard(this.checked);" ';
			if(($responsearramex == true || $responsearrJcb == true || $responsearrMaster== true || $responsearrVisa ==true || $responsearrDiscover == true) )
			{$cmpacntacptdcards .= 'checked="checked"';}
		     $cmpacntacptdcards .= 'checked="checked" disabled="disabled"> Credit Card</label>
			 <script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_Visa").val("");
				jQuery("#wpjobboard_clickandpledge_Paymentmethods_Amex").val("");
				jQuery("#wpjobboard_clickandpledge_Paymentmethods_Discover").val("");
				jQuery("#wpjobboard_clickandpledge_Paymentmethods_master").val("");
				jQuery("#wpjobboard_clickandpledge_Paymentmethods_Jcb").val("");
				jQuery("#wpjobboard_clickandpledge_Paymentmethods_eCheck").val("");</script>
			 			<div class="tracceptedcards" style="padding: 12px 200px;">
			 				<ul class="accounts">														
									<li class="account"><strong>Accepted Credit Cards</strong></li>';
								if($responsearrVisa == true){
									
							      $cmpacntacptdcards .= '
							      	<li class="account"><label for="payment_cnp_Visa">
									<input type="Checkbox" name="payment_cnp_Visa" id="payment_cnp_Visa"';
									if(isset($responsearrVisa)){ $cmpacntacptdcards .='checked="checked "'; }
									 $cmpacntacptdcards .= 'value="Visa" checked="checked" disabled="disabled">Visa</label></li>';
									$cmpacntacptdcards .= '<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_Visa").val("Visa");</script>';
								  }
								
								if($responsearramex == true){
									$cmpacntacptdcards .= '
									<li><label for="payment_cnp_American_Express">
									<input type="Checkbox" name="payment_cnp_American_Express" id="payment_cnp_American_Express"';
									if(isset($responsearramex)){ $cmpacntacptdcards .='checked="checked"'; }
									$cmpacntacptdcards .= 'value="American Express" checked="checked" disabled="disabled">American Express</label></li>';
									$cmpacntacptdcards .= '<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_Amex").val("American Express");</script>';
								}
								
								if($responsearrDiscover == true){
								 $cmpacntacptdcards .= '
									<li><label for="payment_cnp_Discover">
									<input type="Checkbox" name="payment_cnp_Discover" id="payment_cnp_Discover"'; 
									if(isset($responsearrDiscover)){ $cmpacntacptdcards .='checked="checked"'; }
										$cmpacntacptdcards .= ' value="Discover" checked="checked" disabled="disabled">Discover</label></li>';
									$cmpacntacptdcards .= '<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_Discover").val("Discover");</script>';
								}
								
								if($responsearrMaster == true){
								  $cmpacntacptdcards .= '
									<li><label for="payment_cnp_MasterCard">
									<input type="Checkbox" name="payment_cnp_MasterCard" id="payment_cnp_MasterCard"';
									if(isset($responsearrMaster)){ $cmpacntacptdcards .='checked="checked"'; }
									$cmpacntacptdcards .= ' value="MasterCard"  checked="checked" disabled="disabled">MasterCard</label></li>';
									$cmpacntacptdcards .= '<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_master").val("MasterCard");</script>';
								}
								
								if($responsearrJcb == true){
								  $cmpacntacptdcards .= '
									<li><label for="payment_cnp_JCB">
									
									<input type="Checkbox" name="payment_cnp_JCB" id="payment_cnp_JCB"';
									if(isset($responsearrJcb)){ $cmpacntacptdcards .='checked="checked"'; }
									$cmpacntacptdcards .= ' value="JCB" checked="checked" disabled="disabled">JCB</label></li>';
					$cmpacntacptdcards .= '<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_Jcb").val("JCB");</script>';
								}
								
			$cmpacntacptdcards .= '</ul></div></li>';
			
			if($responsearrecheck == true){
			$cmpacntacptdcards .='<li><label for="wpjobboard_clickandpledge_Paymentmethods_eCheck"><input type="checkbox" value="eCheck" id=wpjobboard_clickandpledge_Paymentmethods_eCheck" class="checkbox_active" name="wpjobboard_clickandpledge_Paymentmethods_eCheck" onclick="block_echek(this.checked);"';
				if(isset($responsearrecheck)){ $cmpacntacptdcards .='checked="checked"'; }
				 $cmpacntacptdcards .= ' checked="checked" disabled="disabled">eCheck</label></li>';
		$cmpacntacptdcards.='<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_eCheck").val("eCheck");</script>';
			}else
			{
				$cmpacntacptdcards .= '<input type="hidden" value="" name="wpjobboard_clickandpledge_Paymentmethods_eCheck" id="wpjobboard_clickandpledge_Paymentmethods_eCheck">
				<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_eCheck").val("");</script>';
				
			}
        
		/*	if($responsearrAPM == true){
			$cmpacntacptdcards .='<li><label for="wpjobboard_clickandpledge_Paymentmethods_gpay">
            <input type="checkbox" value="gpay" id=wpjobboard_clickandpledge_Paymentmethods_gpay" class="checkbox_active" name="wpjobboard_clickandpledge_Paymentmethods_gpay" onclick="block_echek(this.checked);"';
				if(isset($responsearrAPM)){ //$cmpacntacptdcards .='checked="checked"'; 
                }
				 $cmpacntacptdcards .= '  >Google Pay</label></li>'; //checked="checked"
		$cmpacntacptdcards.='<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_gpay").val("gpay");</script>';
			}else
			{
				$cmpacntacptdcards .= '<input type="hidden" value="" name="wpjobboard_clickandpledge_Paymentmethods_gpay" id="wpjobboard_clickandpledge_Paymentmethods_gpay">
				<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_gpay").val("");</script>';
				
			}
        
        
        if($responsearrPayPal == true){
			$cmpacntacptdcards .='<li><label for="wpjobboard_clickandpledge_Paymentmethods_paypal"><input type="checkbox" value="paypal" id=wpjobboard_clickandpledge_Paymentmethods_paypal" class="checkbox_active" name="wpjobboard_clickandpledge_Paymentmethods_paypal" onclick="block_echek(this.checked);"';
				if(isset($responsearrPayPal)){ //$cmpacntacptdcards .='checked="checked"'; 
                }
				 $cmpacntacptdcards .= '  >Paypal/Venmo</label></li>'; //checked="checked"
		$cmpacntacptdcards.='<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_paypal").val("paypal");jQuery("#wpjobboard_clickandpledge_Paymentmethods_paypalmid").val("'.$responsearrmerchantid.'");</script>';
			}else
			{
				$cmpacntacptdcards .= '<input type="hidden" value="" name="wpjobboard_clickandpledge_Paymentmethods_paypal" id="wpjobboard_clickandpledge_Paymentmethods_paypal">
				<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_paypal").val("");jQuery("#wpjobboard_clickandpledge_Paymentmethods_paypalmid").val("");</script>';
				
			}
        
         if($responsearrBankAccount == true){
			$cmpacntacptdcards .='<li><label for="wpjobboard_clickandpledge_Paymentmethods_ba"><input type="checkbox" value="Bank Account" id=wpjobboard_clickandpledge_Paymentmethods_ba" class="checkbox_active" name="wpjobboard_clickandpledge_Paymentmethods_ba" onclick="block_echek(this.checked);"';
				if(isset($responsearrPayPal)){ //$cmpacntacptdcards .='checked="checked"'; 
                }
				 $cmpacntacptdcards .= ' >Bank Account</label></li>'; //checked="checked"
		$cmpacntacptdcards.='<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_ba").val("ba");</script>';
			}else
			{
				$cmpacntacptdcards .= '<input type="hidden" value="" name="wpjobboard_clickandpledge_Paymentmethods_ba" id="wpjobboard_clickandpledge_Paymentmethods_ba">
				<script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_ba").val("");</script>';
				
			}*/
        
        
        
        
			
					$cmpacntacptdcards .= '</ul><input type="hidden" value="'.$responsearrCustomPaymentType.'" name="cnpcp" id="cnpcp">||'.$responsearrCustomPaymentType;

		}	
		
		return $cmpacntacptdcards;
		wp_exit();
	}
	public function cnpwpjbsettings($input)	{
		?>
		<?php $cnptransactios=$this->get_CnPwpjbaccountslist();
if(isset($_POST['wpjobboard_clickandpledge_AccountID']) &&  wp_unslash(sanitize_text_field($_POST['wpjobboard_clickandpledge_AccountID']))!="")
		{
			$cnpaccountid = wp_unslash(sanitize_text_field($_POST['wpjobboard_clickandpledge_AccountID']));
		}
			else{$cnpaccountid = $this->conf("wpjobboard_clickandpledge_AccountID");}
?>
	
		<?php foreach($cnptransactios as $cnpacnts){
			if($cnpacnts['AccountId'] == $cnpaccountid){
				 $found = true;
				 $cnpactiveuser = $cnpacnts['AccountId'];
			}?>
		
		<?php }
			?>
		
		    <?php  if(!isset($found)) {$cnpactiveuser = $cnptransactios[0]['AccountId'];}?>
			
			<div class="cnpacceptedcards">	<?php 
            $cnpactivepaymnts=self::getwpjbCnPactivePaymentList($cnpactiveuser);
			$rtrnpaymnts= explode("||",$cnpactivepaymnts); 
		 
  			echo $rtrnpaymnts[0]; // List of Payment Methods 
			if($rtrnpaymnts[1] == 0)
			{
				?>
				<script>jQuery(document).ready(function($) { 
				
			   jQuery('#wpjobboard_clickandpledge_Paymentmethods-CustomPayment').prop('checked', false);
				
			   jQuery("#wpjobboard_clickandpledge_titles").parents("tr").hide();
			   jQuery("#wpjobboard_clickandpledge_reference").parents("tr").hide(); 
			   jQuery("#wpjobboard_clickandpledge_Paymentmethods-CustomPayment").parents("tr").hide();
					
					str = '<option value="CreditCard">Credit Card</option>';
					str += '<option value="eCheck">eCheck</option>';
              
						
					jQuery('#wpjobboard_clickandpledge_DefaultpaymentMethod').html(str);
					});
			</script>
			<?php }
			?>
			</div>
		<?php
		
	}
	 public function cnpwpjblogintitle($input) 
     {
       ?>
			<div align='right'><a href='#' id='cnpregister'>Change User</a></div>
	<?php } 
	 public function cnpwpjblogin($input) 
    {
       ?>
		<tr valign="top" class="trfrmregister">
			<th scope="row" class="titledesc"><?php _e('Login', 'wpjobboard' ); ?></th>
			<td class="forminp" id="cnp_recdtl">

			<div>	
				<?php global $wpdb;
					$accountsinfotblnm  = Payment_ClickandPledge::get_cnp_wpjbaccountsinfo();
					$accountstable_name = $accountsinfotblnm;
					$cnpsqlst           = "SELECT count(*) FROM ". $accountstable_name;
					$wpjbrowcount       = $wpdb->get_var( $cnpsqlst );
					if ($wpjbrowcount !=0) {?>| </li><li><a href="#" class="cnpsettings">Go to Settings</a>  </li><?php }?>
					  <style>.div-table {
					  display: table;         
					  width: auto;         
					  background-color: #fff;         
					  border: 0px ;         
					  border-spacing: 5px; /* cellspacing:poor IE support for  this */
					}
					.div-table-row {
					  display: table-row;
					  width: auto;
					  clear: both;
					}
					.div-table-col {
					  float: left; /* fix for  buggy browsers */
					  display: table-column;         
					  width: 200px;         
					  background-color: #ccc;  
					}
					.cnp_row{
						display: block;
						padding:5px 0px;
					}
					</style>
				</ul>
			</div>
			<div id="cnpfrmwcregister">
				<div class="tab-content" id="cnpfrmwcregister">
				<div id="content" class="col-sm-12 div-table">
						<img src="<?php echo WP_PLUGIN_URL; ?>/<?php echo plugin_basename( dirname(__FILE__)) ?>/cp-logo.png" align="Click&Pledge Logo">
					    <ol>					    
					    <li>Enter the email address associated with your Click & Pledge account, and click on <strong>Get the Code</strong>.</li>
						<li>Please check your email inbox for the Login Verification Code email.</li>
						<li>Enter the provided code and click <strong>Login</strong>.</li>
						</ol>
		  
							<div class="form-group required div-table-row cnp_row">							
								<div class="col-sm-10">
								<input type="textbox" id="cnp_emailid" placeholder="Connect User Name" name="cnp_emailid" maxlength="50" min="6" size="30" >
								</div>						
							</div>
							<div class="form-group required cnploaderimage div-table-row cnp_row" style="display:none">						
								<div class="col-sm-10">							
								<img src='<?php echo WP_PLUGIN_URL; ?>/<?php echo plugin_basename( dirname(__FILE__)) ?>/ajax-loader_trans.gif' title='loader' alt='loader'/>
								</div>
							</div>
							<div class="form-group required cnpcode div-table-row cnp_row" style="display:none">							
								<div class="col-sm-10">
								<input type="textbox" id="cnp_code" placeholder="Code" name="cnp_code"  size="30">
								</div>
							</div>
							<div class="form-group required div-table-row">						
								<div class="col-sm-10">
								<input type="button" id="cnp_btncode" value="Get the code" name="cnp_btncode" class="button">
								</div>
							</div>
							<div class="form-group required cnperror div-table-row cnp_row" style="display:none">						
								<div class="col-sm-10">
								<span class="text-danger" style="color:#841a09">Sorry but we cannot find the email in our system. Please try again.</span>
								<span class="text-success" style="color:#008000"></span>
								</div>
							</div>
				 	</div>
				</div>
			</div>

				
			</td>
		</tr><?php
    }

	
	
	
	/**
	 * Save acceptedcreditcards details table.
	 */
	
	public static function cnp_wpjbgetconnectcode(){
  		$cnpemailaddress = $_REQUEST['cnpemailid'];
		$response =	wp_remote_get('https://api.cloud.clickandpledge.com/users/requestcode', array('headers' => array('content-type' => 'application/x-www-form-urlencoded', 'email' => $cnpemailaddress)) );
	 		 try {
			// Note that we decode the body's response since it's the actual JSON feed
				 $responsebody = wp_remote_retrieve_body($response);
				echo $responsebody;

			} catch ( Exception $ex ) {
				$json = null;
			}
	
 
  wp_die(); // ajax call must die to avoid trailing 0 in your response
}
public static function get_cnpwpjbtransactions($cnpemailid,$cnpcode)
	{
		global $wpdb;
		
		$table_name = Payment_ClickandPledge::get_cnp_wpjbsettingsinfo();
        $sql = "SELECT * FROM ". $table_name;
        $results = $wpdb->get_results($sql, ARRAY_A);

        $count = sizeof($results);
        for($i=0; $i<$count; $i++){
			 $password="password";
			 $cnpsecret = openssl_decrypt($results[$i]['cnpsettingsinfo_clentsecret'],"AES-128-ECB",$password);
			 $rtncnpdata = "client_id=".$results[$i]['cnpsettingsinfo_clientid']."&client_secret=". $cnpsecret."&grant_type=".$results[$i]['cnpsettingsinfo_granttype']."&scope=".$results[$i]['cnpsettingsinfo_scope']."&username=".$cnpemailid."&password=".$cnpcode;
        }

        return $rtncnpdata;
		
	}

public static function delete_cnpwpjbtransactions()
	{
		global $wpdb;
		
        $table_name = Payment_ClickandPledge::get_cnp_wpjbtokeninfo();
        $wpdb->query("DELETE FROM ". $table_name);
	}
public static function  insrt_cnpwpjbtokeninfo($cnpemailid, $cnpcode, $cnptoken, $cnprtoken)
	{
		  global $wpdb;
        $table_name = Payment_ClickandPledge::get_cnp_wpjbtokeninfo();
         $wpdb->insert($table_name, array('cnptokeninfo_username' => $cnpemailid, 
					'cnptokeninfo_code' => $cnpcode, 
					'cnptokeninfo_accesstoken' => $cnptoken,
					'cnptokeninfo_refreshtoken' => $cnprtoken));
		
            $id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
			
        return $id;
	}
	public static function delete_wpjbcnpaccountslist()
	{
		global $wpdb;
        $table_name = Payment_ClickandPledge::get_cnp_wpjbaccountsinfo();
        $wpdb->query("DELETE FROM ". $table_name);
	}
	public static function insert_cnpwpjbaccountsinfo($cnporgid,$cnporgname,$cnpaccountid,$cnpufname,$cnplname,$cnpuid,$cnpcur,$cnpgtway,$cnpapmkey){
        global $wpdb;
        $table_name = Payment_ClickandPledge::get_cnp_wpjbaccountsinfo();
      
            $wpdb->insert($table_name, array('cnpaccountsinfo_orgid' => $cnporgid, 
					'cnpaccountsinfo_orgname' => $cnporgname, 
					'cnpaccountsinfo_accountguid' => $cnpaccountid,
					'cnpaccountsinfo_userfirstname' => $cnpufname,
					'cnpaccountsinfo_userlastname'=> $cnplname,
					'cnpaccountsinfo_userid'=> $cnpuid,
                    'cnpaccountsinfo_cnpcurrency'=> $cnpcur,
					'cnpaccountsinfo_gatewayname'=> $cnpgtway,
					'cnpaccountsinfo_apmkey'=> $cnpapmkey));
            $id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
			
        return $id;
    }
	/**/
	public static  function cnp_getCnPUserConnectAccountList() {
		 $cnpwjbaccountid = $_REQUEST['cnpacid']; 
		 $cnpwjbcamp = $_REQUEST['cnpcamp'];
		
		 $cnprtrntxt = self::getwpjbCnPConnectonCampaigns($cnpwjbaccountid,$cnpwjbcamp);
	     $cnprtrnpaymentstxt = self::getwpjbCnPactivePaymentList($cnpwjbaccountid);
		 $rtntxt = $cnprtrntxt."||".$cnprtrnpaymentstxt;
		echo $rtntxt;
	  die();
	}
	 public static function getwpjbCnPrefreshtoken() {
		 
		global $wpdb;
		
        $table_name = Payment_ClickandPledge::get_cnp_wpjbtokeninfo();
		$settingtable_name = Payment_ClickandPledge::get_cnp_wpjbsettingsinfo();
        $sql = "SELECT cnptokeninfo_refreshtoken  FROM ". $table_name;
        $cnprefreshtkn = $wpdb->get_var( $sql );
		
		$cnpsettingsquery = "SELECT *  FROM ".$settingtable_name;
		 $results = $wpdb->get_results($cnpsettingsquery, ARRAY_A);

        $count = sizeof($results);
        for($i=0; $i<$count; $i++){
			 $password="password";
			 $cnpsecret = openssl_decrypt($results[$i]['cnpsettingsinfo_clentsecret'],"AES-128-ECB",$password);
			 $rtncnpdata = "client_id=".$results[$i]['cnpsettingsinfo_clientid']."&client_secret=". $cnpsecret."&grant_type=refresh_token&scope=".$results[$i]['cnpsettingsinfo_scope']."&refresh_token=".$cnprefreshtkn;
        }
		
			return $rtncnpdata;
			exit;
		
	 }
	public static function cnp_getWPJBCnPDeleteAccountList()
	{
		echo $rtncnpdata      = self::delete_wpjbcnpaccountslist();
		echo $cnptransactios  = self::delete_cnpwpjbtransactions();
	}
	public static function cnp_getWPJBCnPAccountList()
	{
		
		 $rtnrefreshtokencnpdata = self::getwpjbCnPrefreshtoken();
		$rcnpwpjbaccountid = $_REQUEST['rcnpwpjbaccountid'];
		$response = wp_remote_post( "https://aaas.cloud.clickandpledge.com/IdServer/connect/token", array('headers' => array('content-type' => 'application/x-www-form-urlencoded', 'email' => $cnpemailaddress),'body' =>$rtnrefreshtokencnpdata) );
		try {
 
        $cnptokendata = json_decode( wp_remote_retrieve_body($response));
		 $cnptoken = $cnptokendata->access_token;
			 $cnprtokentyp = $cnptokendata->token_type;
			if($cnptoken != "")
			{
			 
			$response1 =	wp_remote_get('https://api.cloud.clickandpledge.com/users/accountlist', array('headers' => array('accept' => 'application/json','content-type' => 'application/json', 'authorization' => $cnprtokentyp." ".$cnptoken)) );
	  
		 try {
			
				$cnpAccountsdata = json_decode( wp_remote_retrieve_body($response1));
				$camrtrnval = "";
					$rtncnpdata = self::delete_wpjbcnpaccountslist();
					$confaccno 	 =  $rcnpwpjbaccountid;	
				
					foreach($cnpAccountsdata as $cnpkey =>$cnpvalue)
					{
					 $selectacnt ="";
					 $cnporgid = $cnpvalue->OrganizationId;
					 $cnporgname = addslashes($cnpvalue->OrganizationName);
					 $cnpaccountid = $cnpvalue->AccountGUID;
					 $cnpufname = addslashes($cnpvalue->UserFirstName);
					 $cnplname = addslashes($cnpvalue->UserLastName);
				     $cnpuid = $cnpvalue->UserId;
                     $cnpgtway = addslashes($cnpvalue->GatewayName);
				     $cnpcur = addslashes($cnpvalue->CurrencyCode);
                     $cnpAPMkey = addslashes($cnpvalue->APMKey);
                    
					 $rtncnpdata = self::insert_cnpwpjbaccountsinfo($cnporgid,$cnporgname,$cnpaccountid,$cnpufname,$cnplname,$cnpuid,$cnpcur,$cnpgtway,$cnpAPMkey);
					 if($confaccno == $cnporgid){$selectacnt ="selected='selected'";}
					 	 $camrtrnval .= "<option value='".$cnporgid."' ".$selectacnt.">".$cnporgid." [".$cnpvalue->OrganizationName."]</option>"; }
					echo $camrtrnval;
					wp_die();

			} catch ( Exception $ex ) {
				$json = null;
			}
			  
				}
 
		} catch ( Exception $ex ) {
			$json = null;
		} 
		
	}
	public static function cnp_wpjbgetcnpaccounts(){
		
		$cnpemailid = $_REQUEST['wpjbcnpemailid'];
		$cnpcode    = $_REQUEST['wpjbcnpcode'];
		$cnptransactios = self::get_cnpwpjbtransactions($cnpemailid,$cnpcode);
	
		$responseap = wp_remote_post("https://aaas.cloud.clickandpledge.com/idserver/connect/token", array('headers' => array('content-type' => 'application/x-www-form-urlencoded'),'body' =>$cnptransactios) );
		try {
 
        // Note that we decode the body's response since it's the actual JSON feed
        	$cnptokendata = json_decode(  wp_remote_retrieve_body($responseap) );
			
			if(!isset($cnptokendata->error)){
			$cnptoken = $cnptokendata->access_token;
			$cnprtoken = $cnptokendata->refresh_token;
			$cnptransactios = self::delete_cnpwpjbtransactions();
			$rtncnpdata =	self::insrt_cnpwpjbtokeninfo($cnpemailid,$cnpcode,$cnptoken,$cnprtoken);	
			
			if($rtncnpdata != "")
			{
				$response1 =	wp_remote_get('https://api.cloud.clickandpledge.com/users/accountlist', array('headers' => array('accept' => 'application/json','content-type' => 'application/json', 'authorization' => 'Bearer'." ".$cnptoken)) );
	  
		 try {
			 $cnpAccountsdata = json_decode(  wp_remote_retrieve_body($response1) );
			  $cnptransactios = self::delete_wpjbcnpaccountslist();
					
					foreach($cnpAccountsdata as $cnpkey =>$cnpvalue)
					{
					 $cnporgid = $cnpvalue->OrganizationId;
					 $cnporgname = addslashes($cnpvalue->OrganizationName);
					 $cnpaccountid = $cnpvalue->AccountGUID;
					 $cnpufname = addslashes($cnpvalue->UserFirstName);
					 $cnplname = addslashes($cnpvalue->UserLastName);
				     $cnpuid = $cnpvalue->UserId;
                     $cnpgtway = addslashes($cnpvalue->GatewayName);
				     $cnpcur = addslashes($cnpvalue->CurrencyCode);
                     $cnpAPMkey = addslashes($cnpvalue->APMKey);
                 	 $cnptransactios = self::insert_cnpwpjbaccountsinfo($cnporgid,$cnporgname,$cnpaccountid,$cnpufname,$cnplname,$cnpuid,$cnpcur,$cnpgtway,$cnpAPMkey);	
						
					}
					
				   echo "success";
 
			} catch ( Exception $ex ) {
				$json = null;
			} 
			  
			  
				 
					
				
					
				
			}
			}
			else{
				echo "error";
			}
 
    } catch ( Exception $ex ) {
        $json = null;
    } 
		
		
		die();
	}	
}
class CreateOrder
    {
        public $intent;
        public $purchase_units  = array();
        public $application_context;
 
   		   public function __construct()
  		   {
              $this->purchase_units[] = new Purchase_Units();
              $this->application_context = new Application_Context();
           }
    }

     class Application_Context
    {
        public $return_url;
        public $cancel_url ;
    }

     class Purchase_Units 
    {
        public $reference_id;
        public $description;
        public $amount;
        public $payee;
        public $items = array();
        public $shipping;
        public $shipping_method ;
        public $payment_instruction;
        public $payment_group_id;
        public $custom_id;
        public $invoice_id ;
        public $soft_descriptor;
     		public function __construct()
  		   {
              $this->amount = new Amount();
              $this->payee = new Payeenew();
              $this->items[] = new Items();
              $this->shipping = new Shipping1();
              $this->payment_instruction = new Payment_Instruction();
           }
    }

     class Amount
    {
        public $currency_code;
        public $value;
        public $breakdown;
         public function __construct()
  		   {
              $this->breakdown = new Breakdown();
           }
      
    }

     class Breakdown
    {
        public $item_total;
        public $shipping;
        public $tax_total;
     
      public function __construct()
  		   {
              $this->item_total = new Item_Total();
     		  $this->shipping  = new Shipping();
              $this->tax_total = new Tax_Total();
           }
    }

     class Item_Total
    {
        public $currency_code;
        public $value;
    }

     class Shipping
    {
        public $currency_code;
        public $value;
    }

     class Tax_Total
    {
        public $currency_code;
        public $value;
    }

     class Payeenew
    {
        public $email_address;
        public $merchant_id;
    }

     class Shipping1
    {
         public $address;
         public function __construct()
  		   {
              $this->address = new Address();
           }
    }

     class Address
    {
        public $address_line_1;
        public $address_line_2 ;
        public $admin_area_2 ;
        public $country_code;
        public $postal_code;
        public $admin_area_1;
    }

     class Payment_Instruction
    {
         public $disbursement_mode;
         public $platform_fees = array();
         public function __construct()
  		   {
              $this->platform_fees[] = new Platform_Fees();
           }
    }

     class Platform_Fees
    {
      public $amount;
       public $payee;
     
      public function __construct()
  		   {
              $this->amount = new Amount1();
              $this->payee = new Payee1();
           }
    }

     class Amount1
    {
        public $currency_code;
        public $value;
    }

     class Payee1
    {
        public $email_address;
    }

     class Items
    {
        public $name;
        public $sku;
        public $unit_amount;
        public $quantity;
        public $category;
        public function __construct()
  		   {
              $this->unit_amount = new Unit_Amount();
              
           }
    }

     class Unit_Amount
    {
        public $currency_code;
        public $value;
    }

    class BillingAgreement
    {
        public $description;
        public $shipping_address;
        public $payer;
        public $plan;
    
    public function __construct()
  		   {
              $this->shipping_address = new Shipping_Address();
   			  $this->payer= new Payer();
   			  $this->plan = new Plan();
              
           }
    }

     class Payer
    {
        public $payment_method;
    }

     class Plan
    {
        public $type;
        public $merchant_preferences;
        public function __construct()
  		   {
              $this->merchant_preferences = new Merchant_Preferences();
   			 
              
           }
    }

     class Merchant_Preferences
    {
        public $return_url;
        public $cancel_url;
        public $notify_url ;
        public $accepted_pymt_type;
        public $skip_shipping_address ;
        public $immutable_shipping_address ;
    }

     class Shipping_Address
    {
        public $line1 ;
        public $city ;
        public $state;
        public $postal_code;
        public $country_code;
        public $recipient_name;
    }
 class CreateOrderResponse
    {
        public $id;
        public $status;
        public $links = array();
 public function __construct()
  		   {
              $this->links = new Link();
   			 
              
           }
    }
 
     class Link
    {
        public $href;
        public $rel;
        public $method;
    }
?>