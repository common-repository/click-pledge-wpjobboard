<?php
require_once dirname(__FILE__).'/clickandpledge_admin_controll.php';
require_once dirname(__FILE__).'/clickandpledge_front_controll.php';
class Payment_ClickandPledge extends Wpjb_Payment_Abstract 
{
	 var $responsecodes = array();
     public function __construct(Wpjb_Model_Payment $data = null) 
     {
       $this->_default = array(
            "disabled" => "0"
        );
        
        $this->_data = $data;
	  
	   add_action( 'wp_ajax_render', array( __CLASS__, 'processTransaction' ) );
	   add_action( 'wp_ajax_nopriv_render', array( __CLASS__, 'processTransaction' ) );
	   add_filter('gform_currency_setting_message', array($this, 'gformCurrencySettingMessage'));
	 
	   add_action( 'wp_ajax_cnp_WPJBgetcode', array('Config_ClickandPledge', 'cnp_wpjbgetconnectcode'));
	   add_action( 'wp_ajax_nopriv_cnp_WPJBgetcode',  array('Config_ClickandPledge','cnp_wpjbgetconnectcode'));
	   add_action( 'wp_ajax_cnp_WPJBgetAccounts', array('Config_ClickandPledge', 'cnp_wpjbgetcnpaccounts'));
	   add_action( 'wp_ajax_nopriv_cnp_WPJBgetAccounts',  array('Config_ClickandPledge','cnp_wpjbgetcnpaccounts'));
	   add_action( 'wp_ajax_getCnPUserconectAccountList', array('Config_ClickandPledge','cnp_getCnPUserConnectAccountList'));
	   add_action( 'wp_ajax_nopriv_getCnPUserconectAccountList', array('Config_ClickandPledge','cnp_getCnPUserConnectAccountList'));
	   add_action( 'wp_ajax_getWPJBCnPAccountList', array('Config_ClickandPledge','cnp_getWPJBCnPAccountList'));
	   add_action( 'wp_ajax_nopriv_getWPJBCnPAccountList', array('Config_ClickandPledge','cnp_getWPJBCnPAccountList'));
	   add_action( 'wp_ajax_getWPJBCnPDeleteAccountList', array('Config_ClickandPledge','cnp_getWPJBCnPDeleteAccountList'));
	   add_action( 'wp_ajax_nopriv_getWPJBCnPDeleteAccountList', array('Config_ClickandPledge','cnp_getWPJBCnPDeleteAccountList'));
     
     
        add_action( 'wp_ajax_cnp_jbcnppaymentintent', array('Config_ClickandPledge','cnp_jbcnppaymentintent'));
	    add_action( 'wp_ajax_nopriv_cnp_jbcnppaymentintent', array('Config_ClickandPledge','cnp_jbcnppaymentintent'));
		add_action( 'wp_ajax_cnp_jbcnpgettotal', array('Config_ClickandPledge','cnp_jbcnpgettotal'));
	    add_action( 'wp_ajax_nopriv_cnp_jbcnpgettotal', array('Config_ClickandPledge','cnp_jbcnpgettotal'));
        add_action( 'wp_ajax_cnp_jbcnpbapaymentintent', array('Config_ClickandPledge','cnp_jbcnpbapaymentintent') );
		add_action( 'wp_ajax_nopriv_cnp_jbcnpbapaymentintent', array('Config_ClickandPledge','cnp_jbcnpbapaymentintent') );
        add_action( 'wp_ajax_cnp_jbcnpcreateorder', array('Config_ClickandPledge','cnp_jbcnpcreateorder') );
	    add_action( 'wp_ajax_nopriv_cnp_jbcnpcreateorder', array('Config_ClickandPledge','cnp_jbcnpcreateorder') );
        add_action( 'wp_ajax_cnp_jbCreateBillingAgreement', array('Config_ClickandPledge','cnp_jbCreateBillingAgreement') );
	    add_action( 'wp_ajax_nopriv_cnp_jbCreateBillingAgreement', array('Config_ClickandPledge','cnp_jbCreateBillingAgreement') );
     
	   $this->responsecodes = array(2054=>'Total amount is wrong',2055=>'AccountGuid is not valid',2056=>'AccountId is not valid',2057=>'Username is not valid',2058=>'Password is not valid',2059=>'Invalid recurring parameters',2060=>'Account is disabled',2101=>'Cardholder information is null',2102=>'Cardholder information is null',2103=>'Cardholder information is null',2104=>'Invalid billing country',2105=>'Credit Card number is not valid',2106=>'Cvv2 is blank',2107=>'Cvv2 length error',2108=>'Invalid currency code',2109=>'CreditCard object is null',2110=>'Invalid card type ',2111=>'Card type not currently accepted',2112=>'Card type not currently accepted',2210=>'Order item list is empty',2212=>'CurrentTotals is null',2213=>'CurrentTotals is invalid',2214=>'TicketList lenght is not equal to quantity',2215=>'NameBadge lenght is not equal to quantity',2216=>'Invalid textonticketbody',2217=>'Invalid textonticketsidebar',2218=>'Invalid NameBadgeFooter',2304=>'Shipping CountryCode is invalid',2305=>'Shipping address missed',2401=>'IP address is null',2402=>'Invalid operation',2501=>'WID is invalid',2502=>'Production transaction is not allowed. Contact support for activation.',2601=>'Invalid character in a Base-64 string',2701=>'ReferenceTransaction Information Cannot be NULL',2702=>'Invalid Refrence Transaction Information',2703=>'Expired credit card',2805=>'eCheck Account number is invalid',2807=>'Invalid payment method',2809=>'Invalid payment method',2811=>'eCheck payment type is currently not accepted',2812=>'Invalid check number',1001=>'Internal error. Retry transaction',1002=>'Error occurred on external gateway please try again',2001=>'Invalid account information',2002=>'Transaction total is not correct',2003=>'Invalid parameters',2004=>'Document is not a valid xml file',2005=>'OrderList can not be empty',3001=>'Invalid RefrenceTransactionID',3002=>'Invalid operation for this transaction',4001=>'Fraud transaction',4002=>'Duplicate transaction',5001=>'Declined (general)',5002=>'Declined (lost or stolen card)',5003=>'Declined (fraud)',5004=>'Declined (Card expired)',5005=>'Declined (Cvv2 is not valid)',5006=>'Declined (Insufficient fund)',5007=>'Declined (Invalid credit card number)');
		 
	        global $wpdb;
			$settingstable_name = self::get_cnp_wpjbsettingsinfo();
			$tokentable_name    = self::get_cnp_wpjbtokeninfo();
			$accountstable_name = self::get_cnp_wpjbaccountsinfo();
			$charset_collate    = $wpdb->get_charset_collate();
			
		 $settingssql = "CREATE TABLE $settingstable_name (
			  `cnpsettingsinfo_id` int(11) NOT NULL AUTO_INCREMENT,
			  `cnpsettingsinfo_clientid` varchar(255) NOT NULL,
			  `cnpsettingsinfo_clentsecret` varchar(255) NOT NULL,
			  `cnpsettingsinfo_granttype` varchar(255) NOT NULL,
			  `cnpsettingsinfo_scope` varchar(255) NOT NULL,
			   PRIMARY KEY (`cnpsettingsinfo_id`)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $settingssql );
			
			$tokensql = "CREATE TABLE $tokentable_name (
 			`cnptokeninfo_id` int(11) NOT NULL AUTO_INCREMENT,
			`cnptokeninfo_username` varchar(255) NOT NULL,
			`cnptokeninfo_code` varchar(255) NOT NULL,
			`cnptokeninfo_accesstoken` text NOT NULL,
			`cnptokeninfo_refreshtoken` text NOT NULL,
			`cnptokeninfo_date_added` datetime NOT NULL,
			`cnptokeninfo_date_modified` datetime NOT NULL,
			 PRIMARY KEY (`cnptokeninfo_id`)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $tokensql );
			
			$accountssql = "CREATE TABLE $accountstable_name (
 			  `cnpaccountsinfo_id` int(11) NOT NULL AUTO_INCREMENT,
			  `cnpaccountsinfo_orgid` varchar(100) NOT NULL,
  			  `cnpaccountsinfo_orgname` varchar(250) NOT NULL,
  	          `cnpaccountsinfo_accountguid` varchar(250) NOT NULL,
			  `cnpaccountsinfo_userfirstname` varchar(250) NOT NULL,
			  `cnpaccountsinfo_userlastname` varchar(250) NOT NULL,
			  `cnpaccountsinfo_userid` varchar(250) NOT NULL,
			  `cnpaccountsinfo_crtdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `cnpaccountsinfo_crtdby` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`cnpaccountsinfo_id`)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $accountssql );
     
     
      $check_column = (array) $wpdb->get_results("SELECT count(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME = '$accountstable_name' AND COLUMN_NAME = 'cnpaccountsinfo_cnpcurrency'")[0];

		$table_name = $accountstable_name;
		 $check_column = (int) array_shift($check_column);
		 if($check_column == 0) {
		 $wpdb->query("ALTER TABLE $table_name   ADD COLUMN `cnpaccountsinfo_cnpcurrency` varchar(250) NOT NULL,ADD COLUMN `cnpaccountsinfo_gatewayname` varchar(250) NOT NULL,ADD COLUMN `cnpaccountsinfo_apmkey` varchar(250) NOT NULL");
		  }
     
			$cnpsql= "SELECT count(*) FROM ". $settingstable_name;
			$rowcount = $wpdb->get_var( $cnpsql );
			if($rowcount == 0)
			{
					$cnpfldname = 'connectwordpressplugin';
					$cnpfldtext = 'zh6zoyYXzsyK9fjVQGd8m+ap4o1qP2rs5w/CO2fZngqYjidqZ0Fhbhi1zc/SJ5zl';
					$cnpfldpwd = 'password';
					$cnpfldaccsid = 'openid profile offline_access';


					$wpdb->insert( 
						$settingstable_name, 
						array( 
							'cnpsettingsinfo_clientid' => $cnpfldname, 
							'cnpsettingsinfo_clentsecret' => $cnpfldtext, 
							'cnpsettingsinfo_granttype' => $cnpfldpwd,
							'cnpsettingsinfo_scope' => $cnpfldaccsid,
						) 
					);
			}
     }
	public static function get_cnp_wpjbsettingsinfo(){
        global $wpdb;
        return $wpdb->prefix . "cnp_wp_jbcnpsettingsinfo";
    }

	 public static function get_cnp_wpjbtokeninfo(){
        global $wpdb;
        return $wpdb->prefix . "cnp_wp_jbcnptokeninfo";
    }

	 public static function get_cnp_wpjbaccountsinfo(){
        global $wpdb;
        return $wpdb->prefix . "cnp_wp_jbcnpaccountsinfo";
    }


	 function get_user_ip() {
		$ipaddress = '';
		 if (isset($_SERVER['HTTP_CLIENT_IP']))
			 $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		 else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			 $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		 else if(isset($_SERVER['HTTP_X_FORWARDED']))
			 $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		 else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			 $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		 else if(isset($_SERVER['HTTP_FORWARDED']))
			 $ipaddress = $_SERVER['HTTP_FORWARDED'];
		 else
			 $ipaddress = $_SERVER['REMOTE_ADDR'];		
		$parts = explode(',', $ipaddress);
        if(count($parts) > 1) $ipaddress = $parts[0];
		return $ipaddress; 
	}
	
	public function safeString( $str,  $length=1, $start=0 )
	{
		$str = preg_replace('/\x03/', '', $str); //Remove new line characters
		return substr((($str)), $start, $length);
	}
	 public function number_format($number, $decimals = 2,$decsep = '', $ths_sep = '') {
		$parts = explode('.', $number);
		if(count($parts) > 1) {
			return $parts[0].'.'.substr($parts[1],0,$decimals);
		} else {
			return $number;
		}
	}
	  
     public function getEngine() {
       return "clickandpledge_payment";
     }     
     public function getTitle() {
       return "Click & Pledge"; 
     }

     public function getForm()
     {        
        return "Config_ClickandPledge";
     }  

    
	public function getFormFrontend()
	{
	    return "Wpjb_Form_Payment_clickandpledge";
	}
     public function processTransaction() {
	 
      $post = $_POST;
      $get  = $_GET;
	   if($post['token_msg'] != '') {
            throw new Exception("Invalid payment [".$post['token_msg']."]", 3);
        }
      return array(
					"external_id" => $post['token_id'],
					"is_recurring"  => "1",
					"paid" => $post['token_amount'],
		  			
                     );		
		     
	}
 	
	public function bind(array $post, array $get)
    {
        $this->setObject(new Wpjb_Model_Payment($post["id"]));
        
        parent::bind($post, $get);
    }
	public function render() {
		   $html = "";
		
		  $payment_arr    = get_option('wpjb_payment_method');
	      $data_config    = $payment_arr["clickandpledge_payment"];
	         $arr = array("action"=>"wpjb_payment_accept", "engine"=>$this->getEngine());
          
		  $request = Daq_Request::getInstance();
          $form = $request->post("form");
	
		  
	      $data_id = $this->_data->id;
	      $amount = $this->_data->payment_sum-$this->_data->payment_paid;;
	
	      $discount_amount = $this->_data->payment_discount;
	      $payment_currency = $this->_data->payment_currency;
		  $info = $this->getObject()->successMessages();
		  // Building XML 
           $params = $_POST;
	
		 foreach($params as $paramkey => $paramvalue)
		 {
		 
		 }	
			
	
	    $cnpVersion ="4.24080000-WP6.6.1-JB5.11.2";
		$dom = new DOMDocument('1.0', 'UTF-8');
		$root = $dom->createElement('CnPAPI', '');
		$root->setAttribute("xmlns","urn:APISchema.xsd");
		$root = $dom->appendChild($root);

		$version=$dom->createElement("Version","1.5");
		$version=$root->appendChild($version);

		$engine = $dom->createElement('Engine', '');
		$engine = $root->appendChild($engine);

		$application = $dom->createElement('Application','');
		$application = $engine->appendChild($application);

		$applicationid=$dom->createElement('ID','CnP_WPJobBoard_WordPress');
		$applicationid=$application->appendChild($applicationid);

		$applicationname=$dom->createElement('Name','CnP_WPJobBoard_WordPress');
		$applicationid=$application->appendChild($applicationname);

		$applicationversion=$dom->createElement('Version',$cnpVersion);
		$applicationversion=$application->appendChild($applicationversion);
		

		$request = $dom->createElement('Request', '');
		$request = $engine->appendChild($request);

		$operation=$dom->createElement('Operation','');
		$operation=$request->appendChild($operation);

		$operationtype=$dom->createElement('OperationType','Transaction');
		$operationtype=$operation->appendChild($operationtype);
		if($this->get_user_ip() != '') {
		$ipaddress=$dom->createElement('IPAddress',$this->get_user_ip());
		$ipaddress=$operation->appendChild($ipaddress);
		}
		
		$httpreferrer=$dom->createElement('UrlReferrer',htmlentities($_SERVER['HTTP_REFERER']));
		$httpreferrer=$operation->appendChild($httpreferrer);
		
		$authentication=$dom->createElement('Authentication','');
		$authentication=$request->appendChild($authentication);

		$accounttype=$dom->createElement('AccountGuid',$paramvalue['clickandpledge_AccountGuid']); 
		$accounttype=$authentication->appendChild($accounttype);
		
		$accountid=$dom->createElement('AccountID',$paramvalue['clickandpledge_AccountID']);
		$accountid=$authentication->appendChild($accountid);
				 
		$order=$dom->createElement('Order','');
		$order=$request->appendChild($order);

		if($paramvalue['clickandpledge_OrderMode'] == 'test' ){
		$orderMode = 'Test';
		}else{		
		$orderMode = 'Production';
		}
		$ordermode=$dom->createElement('OrderMode',$orderMode);
		$ordermode=$order->appendChild($ordermode);
		// Connect Campaign URL Alias 
		
		$campaignurl = $paramvalue['wpjobboard_clickandpledge_ConnectCampaignAlias'];
		$ordercampaign  = $dom->createElement('ConnectCampaignAlias','');
		$ordercampaign  = $order->appendChild($ordercampaign);
		$ordercampaign->appendChild($dom->createCDATASection($campaignurl));
		global $wpdb;
		$paymentdetails = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wpjb_payment WHERE id = '.$data_id, OBJECT );
		
		 $jobboard_taxes = get_option('wpjb_config');
		 $job_taxes = get_option('taxes_enabled');
		 
		 $jobboard_taxpercent = $jobboard_taxes['taxes_default_rate'];
		 $jobboard_taxtype = $jobboard_taxes['taxes_price_type'];
		 $jobboard_taxset = $jobboard_taxes['taxes_enabled'];
		
		 $orderdetails = array();
		 $orderdetails['BillingEmail'] = $paymentdetails->email;
		
		 $orderdetails['CustomFields'] = array();
		 $order_custom_fields = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wpjb_meta meta INNER JOIN  '.$wpdb->prefix.'wpjb_meta_value meta_value ON meta.id=meta_value.meta_id WHERE meta.meta_type=3 AND meta_value.object_id = '.$params['object_id'].' ORDER BY meta_value.meta_id ASC', OBJECT );
		
		 if(count($order_custom_fields) > 0) {	
			$oldname = $strval = '';
		    $fieldindex = 0;
			 foreach($order_custom_fields as $single_row) {				
				 $fieldindex++;
				 if($oldname == '') $oldname = $single_row->name;
				 if($oldname != $single_row->name) {
					 $orderdetails['CustomFields'][$oldname] = substr($strval,0,-1);
					 $strval = '';
				 } 
				 if(count($order_custom_fields) == $fieldindex) {
					 $orderdetails['CustomFields'][$single_row->name] = $single_row->value;
				 }				
				 $strval .= $single_row->value.',';
				 $oldname = $single_row->name;								
			 }
		 }	
		
		 if(isset($paramvalue['clickandpledge_listing_id']) && $paramvalue['clickandpledge_listing_id'] != '') {
			 $parts = explode('_', $paramvalue['clickandpledge_listing_id']);
			 if(count($parts) == 3) {
				 $listid = $parts[2];
				 if($listid != '') {
					 $listrow = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wpjb_pricing WHERE id = '.$listid, OBJECT );
					 if($listrow != '') {
						 $orderdetails['CustomFields']['Listing Type'] = $listrow->title;
					 }
				 }
			}
	   }
	
	   $object_type = $wpdb->get_row('SELECT object_type FROM '.$wpdb->prefix.'wpjb_payment WHERE object_id = '.$params['object_id'], OBJECT );
		
		if($object_type->object_type == 1) {
			 $orderplaced = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wpjb_job WHERE id = '.$params['object_id'], OBJECT );
			
			 $orderdetails['ItemName'] = 'Job: '.$orderplaced->job_title;
			 if($orderplaced->job_description != '')
			 $orderdetails['CustomFields']['Description'] = $orderplaced->job_description;
			 if($orderplaced->job_country != '') {
				 $countries = Wpjb_List_Country::getAll();
				 
				 
				 if(count($countries) > 0) {
					 foreach($countries as $country) {
						 if($country['code'] == $orderplaced->job_country)
						 $orderdetails['CustomFields']['Job Country'] = $country['name'];
					 }
				 }
				
			 }
			 if($orderplaced->job_state != '')
			 $orderdetails['CustomFields']['Job State'] = $orderplaced->job_state;
			 if($orderplaced->job_zip_code != '')
			 $orderdetails['CustomFields']['Job Zip-Code'] = $orderplaced->job_zip_code;
			 if($orderplaced->job_city != '')
			 $orderdetails['CustomFields']['Job City'] = $orderplaced->job_city;
             if($orderplaced->job_address != '')
			 $orderdetails['CustomFields']['Job Address'] = $orderplaced->job_address;	
			 if($orderplaced->company_name != '')
			 $orderdetails['CustomFields']['Company Name'] = $orderplaced->company_name;
			 if($orderplaced->company_email != '')
			 $orderdetails['CustomFields']['Contact Email'] = $orderplaced->company_email;
			 if($orderplaced->company_url != '')
			 $orderdetails['CustomFields']['Website'] = $orderplaced->company_url;	
       		

			 $job = new Wpjb_Model_Job($params['object_id']);			
			 if(isset($job->getTag()->type) && is_array($job->getTag()->type)) {
				 foreach($job->getTag()->type as $type) {
					 $orderdetails['CustomFields']['Job Type'] = $type->title;
			 }
			 }			
			 if(isset($job->tag->category) && is_array($job->tag->category)) {
				 foreach($job->tag->category as $cat) {
					 $orderdetails['CustomFields']['Category'] = $cat->title;
				 }
			 }						
			
		  } else if($object_type->object_type == 2) { //Resume
			 $orderplaced = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'wpjb_resume r INNER JOIN '.$wpdb->prefix.'posts p ON r.post_id = p.id WHERE r.id = '.$params['object_id'], OBJECT );			
			 if($orderplaced) {
				 if($orderplaced->post_title != '') {
					 $orderdetails['ItemName'] = 'Resume: '.$orderplaced->post_title;
				 }else {
					 $orderdetails['ItemName'] = 'Single Resume Access';
				 }
			 }
			 else {
				 $orderdetails['ItemName'] = 'Single Resume Access';
			 }			
		 } else if($object_type->object_type == 3) { //Membership
			 $orderplaced = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'wpjb_membership WHERE id = '.$params['object_id'], OBJECT );
			 if($orderplaced->package_id != '') {
				 $package = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'wpjb_pricing WHERE id = '.$orderplaced->package_id, OBJECT );
				 $orderdetails['ItemName'] = 'Membership: '.$package->title;
			 } else {
				 $orderdetails['ItemName'] = 'Employer Membership Package';
			 }						
		 }
		 $UnitPriceCalculate = $TotalDiscountCalculate = 0;		
		 
		 $cardholder=$dom->createElement('CardHolder','');
		 $cardholder=$order->appendChild($cardholder);

		   $billinginfo=$dom->createElement('BillingInformation','');
		   $billinginfo=$cardholder->appendChild($billinginfo);
		
		   if(isset($paramvalue['cnp_payment_method_selection']) && $paramvalue['cnp_payment_method_selection'] == 'CreditCard') {
			   $cnpname = explode(" ",$paramvalue['fullname']); $cnplnamearr =array();
			   if(count($cnpname)>1){
				   $cnpfname = $cnpname[0];
				   for($i=1;$i<count($cnpname);$i++)
				   {					  
				    $cnplname .= $cnpname[$i]." ";
				   }
				  
			   }
			   else{
				   $cnpfname = $paramvalue['fullname'];
				   $cnplname = $paramvalue['fullname'];
			   } 
			
			   
			$billfirst_name  = $dom->createElement('BillingFirstName','');
			$billfirst_name  = $billinginfo->appendChild($billfirst_name);
			$billfirst_name->appendChild($dom->createCDATASection($this->safeString($cnpfname, 50)));
			   
			
			   
			$billlast_name  = $dom->createElement('BillingLastName','');
			$billlast_name  = $billinginfo->appendChild($billlast_name);
			$billlast_name->appendChild($dom->createCDATASection($this->safeString($cnplname, 50)));
			   
	      } else if(isset($paramvalue['cnp_payment_method_selection']) && $paramvalue['cnp_payment_method_selection'] == 'eCheck') {
			   $cnpname = explode(" ",$paramvalue['fullname']);
			   if(count($cnpname)>1){
				   $cnpfname = $cnpname[0];
				   $cnplname = $cnpname[1];
				   
			   }
			   else{
				   $cnpfname = $paramvalue['fullname'];
				   $cnplname = $paramvalue['fullname'];
			   }
			
			     
			   		 $billfirst_name  = $dom->createElement('BillingFirstName','');
					 $billfirst_name  = $billinginfo->appendChild($billfirst_name);
					 $billfirst_name->appendChild($dom->createCDATASection($this->safeString($cnpfname,50)));
		
			   
			   $billlast_name  = $dom->createElement('BillingLastName','');
					 $billlast_name  = $billinginfo->appendChild($billlast_name);
					 $billlast_name->appendChild($dom->createCDATASection($this->safeString($cnplname,50)));
			   
		   }  else if(isset($paramvalue['cnp_payment_method_selection']) && $paramvalue['cnp_payment_method_selection'] == $paramvalue['cnp_payment_method_selection']) {
			    $cnpname = explode(" ",$paramvalue['fullname']);
			   if(count($cnpname)>1){
				   $cnpfname = $cnpname[0];
				   $cnplname = $cnpname[1];
				   
			   }
			   else{
				   $cnpfname = $paramvalue['fullname'];
				   $cnplname = $paramvalue['fullname'];
			   }
			
			         $billfirst_name  = $dom->createElement('BillingFirstName','');
					 $billfirst_name  = $billinginfo->appendChild($billfirst_name);
					 $billfirst_name->appendChild($dom->createCDATASection($this->safeString($cnpfname,50)));
			         $billlast_name  = $dom->createElement('BillingLastName','');
					 $billlast_name  = $billinginfo->appendChild($billlast_name);
					 $billlast_name->appendChild($dom->createCDATASection($this->safeString($cnplname,50)));
			   
		   }

		   if (isset($orderdetails['BillingEmail']) && $orderdetails['BillingEmail'] != '') {
		
			
			$bill_email  = $dom->createElement('BillingEmail','');
			$bill_email  = $billinginfo->appendChild($bill_email);
			$bill_email->appendChild($dom->createCDATASection($this->safeString($orderdetails['BillingEmail'], 50)));
		   }
			
		 $billingaddress=$dom->createElement('BillingAddress','');
		 $billingaddress=$cardholder->appendChild($billingaddress);	
			
		 if(isset($orderdetails['CustomFields']) && count($orderdetails['CustomFields']) > 0) {
			 $customfieldlist = $dom->createElement('CustomFieldList','');
			 $customfieldlist = $cardholder->appendChild($customfieldlist);
			
			 foreach($orderdetails['CustomFields'] as $key => $val) {
				 $customfield = $dom->createElement('CustomField','');
				 $customfield = $customfieldlist->appendChild($customfield);
					
			
				 
				  $fieldname  = $dom->createElement('FieldName',"");
				  $fieldname  = $customfield->appendChild($fieldname);
				  $fieldname->appendChild($dom->createCDATASection($key));
				 
				  $fieldvalue  = $dom->createElement('FieldValue',"");
				  $fieldvalue  = $customfield->appendChild($fieldvalue);
				  $fieldvalue->appendChild($dom->createCDATASection($this->safeString($val, 500)));
			 }			
		 }
	
		 $paymentmethod=$dom->createElement('PaymentMethod','');
		 $paymentmethod=$cardholder->appendChild($paymentmethod);
	
		 if(isset($paramvalue['cnp_payment_method_selection']) && $paramvalue['cnp_payment_method_selection'] == 'CreditCard') {
			 $payment_type=$dom->createElement('PaymentType','CreditCard');
			 $payment_type=$paymentmethod->appendChild($payment_type);
		
			 $creditcard=$dom->createElement('CreditCard','');
			 $creditcard=$paymentmethod->appendChild($creditcard);
			
			 
			 	$credit_name  = $dom->createElement('NameOnCard',"");
				$credit_name  = $creditcard->appendChild($credit_name);
				$credit_name->appendChild($dom->createCDATASection($this->safeString($paramvalue['clickandpledge_nameOnCard'], 50)));
					
			 $credit_number=$dom->createElement('CardNumber',$this->safeString( str_replace(' ', '', $paramvalue['clickandpledge_cardNumber']), 17));
			 $credit_number=$creditcard->appendChild($credit_number);

			 $credit_cvv=$dom->createElement('Cvv2',$paramvalue['clickandpledge_cvc']);
			 $credit_cvv=$creditcard->appendChild($credit_cvv);

			 $credit_expdate=$dom->createElement('ExpirationDate',$paramvalue['clickandpledge_cardExpMonth'] . "/" . substr($paramvalue['clickandpledge_cardExpYear'],2,2));
			 $credit_expdate=$creditcard->appendChild($credit_expdate);
		}
		 else if(isset($paramvalue['cnp_payment_method_selection']) && $paramvalue['cnp_payment_method_selection'] == 'eCheck') {
			 $payment_type=$dom->createElement('PaymentType','Check');
			 $payment_type=$paymentmethod->appendChild($payment_type);
			
			 $echeck=$dom->createElement('Check','');
			 $echeck=$paymentmethod->appendChild($echeck);
			 
			 if(!empty($paramvalue['clickandpledge_echeck_AccountNumber'])) {
			 $ecAccount=$dom->createElement('AccountNumber',$this->safeString($paramvalue['clickandpledge_echeck_AccountNumber'], 17));
			 $ecAccount=$echeck->appendChild($ecAccount);
			 }
			 if(!empty($paramvalue['clickandpledge_echeck_AccountType'])) {
			 $ecAccount_type=$dom->createElement('AccountType',$paramvalue['clickandpledge_echeck_AccountType']);
			 $ecAccount_type=$echeck->appendChild($ecAccount_type);
			 }
			 if(!empty($paramvalue['clickandpledge_echeck_RoutingNumber'])) {
			 $ecRouting=$dom->createElement('RoutingNumber',$this->safeString($paramvalue['clickandpledge_echeck_RoutingNumber'], 9));
			 $ecRouting=$echeck->appendChild($ecRouting);
			 }
			 if(!empty($paramvalue['clickandpledge_echeck_CheckNumber'])) {
			 $ecCheck=$dom->createElement('CheckNumber',$this->safeString($paramvalue['clickandpledge_echeck_CheckNumber'], 10));
			 $ecCheck=$echeck->appendChild($ecCheck);
			 }
			 if(!empty($paramvalue['clickandpledge_echeck_CheckType'])) {
			 $ecChecktype=$dom->createElement('CheckType',$paramvalue['clickandpledge_echeck_CheckType']);
			 $ecChecktype=$echeck->appendChild($ecChecktype);
			 }
			 if(!empty($paramvalue['clickandpledge_echeck_NameOnAccount'])) {
			 $ecName=$dom->createElement('NameOnAccount',$this->safeString($paramvalue['clickandpledge_echeck_NameOnAccount'], 100));
			 $ecName=$echeck->appendChild($ecName);
			 }
			 if(!empty($paramvalue['clickandpledge_echeck_IdType'])) {
			 $ecIdtype=$dom->createElement('IdType',$paramvalue['clickandpledge_echeck_IdType']);
			 $ecIdtype=$echeck->appendChild($ecIdtype);
			 }			
			 if(!empty($paramvalue['clickandpledge_echeck_IdNumber'])) {
			 $IdNumber=$dom->createElement('IdNumber',$this->safeString($paramvalue['clickandpledge_echeck_IdNumber'], 30));
			 $IdNumber=$creditcard->appendChild($IdNumber);
			 }
			 if(!empty($paramvalue['clickandpledge_echeck_IdStateCode'])) {
			 $IdStateCode=$dom->createElement('IdStateCode', $paramvalue['clickandpledge_echeck_IdStateCode']);
			 $IdStateCode=$creditcard->appendChild($IdStateCode);
			 }			
		 }
		 else if(isset($paramvalue['cnp_payment_method_selection']) && $paramvalue['cnp_payment_method_selection'] == 'gpay') {
            
			$payment_type=$dom->createElement('PaymentType','Stripe');
			$payment_type=$paymentmethod->appendChild($payment_type);
			
			$paypal=$dom->createElement('Stripe','');
			$paypal=$paymentmethod->appendChild($paypal);
			
			$paypalorderid=$dom->createElement('PaymentIntent',$this->safeString($paramvalue['wpjbgpaypymntintent'],50));
			$paypalorderid=$paypal->appendChild($paypalorderid);		
		 }
    else if(isset($paramvalue['cnp_payment_method_selection']) && $paramvalue['cnp_payment_method_selection'] == 'paypal') {
            
		$payment_type=$dom->createElement('PaymentType','PayPal');
			$payment_type=$paymentmethod->appendChild($payment_type);
			
			$paypal=$dom->createElement('PayPal','');
			$paypal=$paymentmethod->appendChild($paypal);
			
			$paypalorderid=$dom->createElement('PayPalOrderId',$this->safeString($paramvalue['paypal_paymentnumber'],50));
			$paypalorderid=$paypal->appendChild($paypalorderid);		
		 }
    else if(isset($paramvalue['cnp_payment_method_selection']) && $paramvalue['cnp_payment_method_selection'] == 'ba') {
            
		$payment_type=$dom->createElement('PaymentType','Stripe');
			$payment_type=$paymentmethod->appendChild($payment_type);
			
			$bankaccount=$dom->createElement('Stripe','');
			$bankaccount=$paymentmethod->appendChild($bankaccount);
			
			$bankaccountorderid=$dom->createElement('BankAccountPaymentIntent',$this->safeString($paramvalue['wpjbbapymntintent'],50));
			$bankaccountorderid=$bankaccount->appendChild($bankaccountorderid);	
    
   			$CustomUserAgent=$dom->createElement('UserAgent',$this->safeString($_SERVER['HTTP_USER_AGENT']));
			$CustomUserAgent=$bankaccount->appendChild($CustomUserAgent);
    
    
		 }	
		 else if(isset($paramvalue['cnp_payment_method_selection']) && ($paramvalue['cnp_payment_method_selection'] != 'CreditCard' || $paramvalue['cnp_payment_method_selection'] != 'eCheck')) {
			 $payment_type=$dom->createElement('PaymentType','CustomPaymentType');
			 $payment_type=$paymentmethod->appendChild($payment_type);
			 
			 $custompay=$dom->createElement('CustomPaymentType','');
			 $custompay=$paymentmethod->appendChild($custompay);
			 
			 $PurchaseOrder=$dom->createElement('CustomPaymentName',$paramvalue['cnp_payment_method_selection']);
			 $PurchaseOrder=$custompay->appendChild($PurchaseOrder);	
			 
			 $CheckNumber=$dom->createElement('CustomPaymentNumber',$paramvalue['clickandpledge_reference_number']);
			 $CheckNumber=$custompay->appendChild($CheckNumber);
			 
		 } else {
			 $payment_type=$dom->createElement('PaymentType','CreditCard');
			 $payment_type=$paymentmethod->appendChild($payment_type);
		
			 $creditcard=$dom->createElement('CreditCard','');
			 $creditcard=$paymentmethod->appendChild($creditcard);
						
			 $credit_name=$dom->createElement('NameOnCard',$this->safeString($paramvalue['clickandpledge_nameOnCard'], 50));
			 $credit_name=$creditcard->appendChild($credit_name);
					
			 $credit_number=$dom->createElement('CardNumber',$this->safeString(str_replace(' ', '', $paramvalue['clickandpledge_cardNumber']), 17));
			 $credit_number=$creditcard->appendChild($credit_number);

			 $credit_cvv=$dom->createElement('Cvv2',$paramvalue['clickandpledge_cvc']);
			 $credit_cvv=$creditcard->appendChild($credit_cvv);

			 $credit_expdate=$dom->createElement('ExpirationDate',$paramvalue['clickandpledge_cardExpMonth'] . "/" . substr($paramvalue['clickandpledge_cardExpYear'],2,2));
			 $credit_expdate=$creditcard->appendChild($credit_expdate);
		 }
		
		 $orderitemlist=$dom->createElement('OrderItemList','');
		 $orderitemlist=$order->appendChild($orderitemlist);				
						
		 $orderitem=$dom->createElement('OrderItem','');
		 $orderitem=$orderitemlist->appendChild($orderitem);

		 $itemid=$dom->createElement('ItemID',101300);
		 $itemid=$orderitem->appendChild($itemid);

		
		 $itemname  = $dom->createElement('ItemName',"");
				  $itemname  = $orderitem->appendChild($itemname);
				  $itemname->appendChild($dom->createCDATASection($this->safeString(trim($orderdetails['ItemName']), 100)));
$unitdiscountvalue ="";
if($params['discount_code'] != ""){
	
  $unitdiscountvalue = $wpdb->get_row('SELECT discount,type FROM '.$wpdb->prefix.'wpjb_discount  WHERE code = "'.$params['discount_code'].'"', OBJECT );
	
}
		
$unitamountset = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wpjb_pricing WHERE id = '.$params['pricing_id'], OBJECT );

$sub_total = $this->number_format($unitamountset->price,2,'.','')*100;
	
		if($params['discount_code'] != '')
			{ 
				if($unitdiscountvalue->type == 1)
				{
				$untxsub_total = ($sub_total - ($sub_total * $unitdiscountvalue->discount)/100);
				} else
				{
				$untxsub_total = $sub_total - $this->number_format($unitdiscountvalue->discount,2,'.','')*100;
				}
			}
		else
		{
			$untxsub_total = $sub_total ;
		}
		
		$quntity=$dom->createElement('Quantity',1);
		$quntity=$orderitem->appendChild($quntity);
		 
		$line_subtotal = $paymentdetails->payment_sum;
		
		if($jobboard_taxset[0] == 1)
		{
		if($jobboard_taxtype == 'net')
        {
			
			
			$unit_discount ="";
		
			 $wpjb_unittaxnet =  ($unitamountset->price*$jobboard_taxpercent)/(100);
			if($params['discount_code'] != '')
			{
			         
				$wpjb_unittax = $wpjb_unittaxnet - ($wpjb_unittaxnet*$unitdiscountvalue->discount)/100;
			} else
			{
			$wpjb_unittax = $wpjb_unittaxnet;
			}
			
			    if($unitdiscountvalue->type == 1)
				{
			
                $unit_discount = (($sub_total/100)*$unitdiscountvalue->discount)/100;
				} else
				{
				$unit_discount = $unitdiscountvalue->discount;
				}
         	 		 $untxsub_total = round($unitamountset->price,2);  
       				 $sub_total =$this->number_format($untxsub_total,2,'.','')*100; 
		}
		else  // gross
		{
			$wpjb_unittaxgross = ($unitamountset->price*$jobboard_taxpercent)/(100+$jobboard_taxpercent);
			
			if($params['discount_code'] != '')
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
	
				$sub_total = ($unitamountset->price - $wpjb_unittax);
			$sub_total = $this->number_format($sub_total,2,'.','')*100;
		}
		}
		else // tax uncheck
		{
			
			   $sub_total = $this->number_format($unitamountset->price,2,'.','')*100;
			
			    if($unitdiscountvalue->type == 1)
				{
				$unit_discount = (($sub_total/100)*$unitdiscountvalue->discount)/100;
				} else
				{
				$unit_discount = $unitdiscountvalue->discount;
				}
			
		}
		     if(isset($paramvalue['clickandpledge_onetimeonly']) &&  $paramvalue['clickandpledge_onetimeonly'] == 'clickandpledge_Recurring' ) 
		 {
			 if($paramvalue['clickandpledge_recurring_type'] == 'Installment') 
			 {
             if(($jobboard_taxset[0] == 1) &&  ($jobboard_taxtype == 'gross'))
		     {
           $nooftimes = $paramvalue['clickandpledge_nooftimes'];
         
             $recurring_unitprice = $this->number_format((($sub_total/100)/$nooftimes),2,'.','')*100;
		 	$recurring_unittax = round(($wpjb_unittax/$nooftimes),2)*100;
		 	$recurring_unitdiscount = round(($unit_discount/$nooftimes),2)*100;
             }else
             {
             $nooftimes = $paramvalue['clickandpledge_nooftimes'];
		 	$recurring_unitprice = $this->number_format((($sub_total/100)/$nooftimes),2,'.','')*100;
           $recurring_unittax=$this->number_format($wpjb_unittax/$nooftimes,2,'.','')*100;; 
		
		 	$recurring_unitdiscount = round(($unit_discount/$nooftimes),2)*100;
             }
             }
             }else
             {
    
		
             }
		 if(isset($paramvalue['clickandpledge_onetimeonly']) &&  $paramvalue['clickandpledge_onetimeonly'] == 'clickandpledge_Recurring' ) 
		 {
			 if($paramvalue['clickandpledge_recurring_type'] == 'Installment') 
			 {
				
			
             
				  $unitprice=$dom->createElement('UnitPrice', $recurring_unitprice);
				  $unitprice=$orderitem->appendChild($unitprice);
				  if($jobboard_taxset[0] == 1)
				  {
					$unittax=$dom->createElement('UnitTax',$recurring_unittax);
				  $unittax=$orderitem->appendChild($unittax);  
				  } else
				  {
					 $unittax=$dom->createElement('UnitTax',0);
				  $unittax=$orderitem->appendChild($unittax); 
				  }
				  
				 
				  $unitdiscount=$dom->createElement('UnitDiscount',$recurring_unitdiscount);
				  $unitdiscount=$orderitem->appendChild($unitdiscount);
				 
			 } 
			  else if($paramvalue['clickandpledge_recurring_type'] == 'Subscription') 
			  {	
                 $wpjb_unittax = round($wpjb_unittax,2)*100;
		         $unit_discount = round($unit_discount,2)*100;
                 $unitprice=$dom->createElement('UnitPrice',$sub_total);
				 $unitprice=$orderitem->appendChild($unitprice);
				 if($jobboard_taxset[0] == 1)
				  {
				 $unittax=$dom->createElement('UnitTax',$wpjb_unittax);
				 $unittax=$orderitem->appendChild($unittax);  
				  } else
				  {
				$unittax=$dom->createElement('UnitTax',0);
				 $unittax=$orderitem->appendChild($unittax);  
				  }
				 
				  if($params['discount_code'] != '')
				{
				 $unitdiscount=$dom->createElement('UnitDiscount',$unit_discount);
				 $unitdiscount=$orderitem->appendChild($unitdiscount);			  
			     } 	
			  }
		 } else {
              
                $wpjb_unittax = round($wpjb_unittax,2)*100;
		 		$unit_discount = round($unit_discount,2)*100;
				 $unitprice=$dom->createElement('UnitPrice',$sub_total);
				 $unitprice=$orderitem->appendChild($unitprice);
				
				 if($jobboard_taxset[0] == 1)
				  {
				 $unittax=$dom->createElement('UnitTax',$wpjb_unittax);
				 $unittax=$orderitem->appendChild($unittax);  
				  } else
				  {
				 $unittax=$dom->createElement('UnitTax', 0);
				 $unittax=$orderitem->appendChild($unittax);  
				  }
				 
				 
				 if($params['discount_code'] != '')
				{
				 $unitdiscount=$dom->createElement('UnitDiscount',$unit_discount);
				 $unitdiscount=$orderitem->appendChild($unitdiscount);	
				} 	
				
		
		 }
	
		 $receipt=$dom->createElement('Receipt','');
		 $receipt=$order->appendChild($receipt);
		if($paramvalue['clickandpledge_email_customer'] == 'yes' )
		{
						
		   $email_sendreceipt =$dom->createElement('SendReceipt',"true");
		   $email_sendreceipt=$receipt->appendChild($email_sendreceipt);
		}
		else{
			  $email_sendreceipt=$dom->createElement('SendReceipt',"false");
		      $email_sendreceipt=$receipt->appendChild($email_sendreceipt);		
		   }
		 $recipt_lang=$dom->createElement('Language','ENG');
		 $recipt_lang=$receipt->appendChild($recipt_lang);
		
		 if(isset($paramvalue['wpjobboard_clickandpledge_receiptsettings']) && $paramvalue['wpjobboard_clickandpledge_receiptsettings'] != '')
		 {
			 
			 
		     $recipt_org  = $dom->createElement('OrganizationInformation','');
			 $recipt_org  = $receipt->appendChild($recipt_org);
			 $recipt_org->appendChild($dom->createCDATASection($this->safeString( (trim($paramvalue['wpjobboard_clickandpledge_receiptsettings'])), 1500)));
			 
			 
		 }		
				
	      if(isset($paramvalue['wpjobboard_clickandpledge_termsandconditionsadmin']) && $paramvalue['wpjobboard_clickandpledge_termsandconditionsadmin'] != '')
		 {
			
			  
		     $recipt_terms  = $dom->createElement('TermsCondition','');
			 $recipt_terms  = $receipt->appendChild($recipt_terms);
			 $recipt_terms->appendChild($dom->createCDATASection($this->safeString((trim($paramvalue['wpjobboard_clickandpledge_termsandconditionsadmin'])), 1500)));
		 }

		 if(isset($paramvalue['email']) && $paramvalue['email'] == 'yes' ) 
		 { //Sending the email based on admin settings
			 $recipt_email=$dom->createElement('EmailNotificationList','');
			 $recipt_email=$receipt->appendChild($recipt_email);			
			
			 $email_notification = '';		
			 if (isset($paramvalue['email']) && $paramvalue['email'] != '') 
			 {
				 $email_notification = $paramvalue['email'];
			 }
								
			 $email_note=$dom->createElement('NotificationEmail',$email_notification);
			 $email_note=$recipt_email->appendChild($email_note);
		 }
		 $transation=$dom->createElement('Transaction','');
		 $transation=$order->appendChild($transation);

		 $trans_type=$dom->createElement('TransactionType','Payment');
		 $trans_type=$transation->appendChild($trans_type);

		 $trans_desc=$dom->createElement('DynamicDescriptor','DynamicDescriptor');
		 $trans_desc=$transation->appendChild($trans_desc); 
		
		
		 if(isset($paramvalue['clickandpledge_onetimeonly']) &&  $paramvalue['clickandpledge_onetimeonly'] == 'clickandpledge_Recurring' )
		 {
			 $trans_recurr=$dom->createElement('Recurring','');
			 $trans_recurr=$transation->appendChild($trans_recurr);
			 if($paramvalue['clickandpledge_recurring_type'] == 'Installment')
			 {
			 $total_installment=$dom->createElement('Installment',$paramvalue['clickandpledge_nooftimes']);
			 $total_installment=$trans_recurr->appendChild($total_installment); 
			 } 
			 else if($paramvalue['clickandpledge_recurring_type'] == 'Subscription')
			 {
			 $total_installment=$dom->createElement('Installment',$paramvalue['clickandpledge_nooftimes']);
			 $total_installment=$trans_recurr->appendChild($total_installment); 
			 }
			 if($paramvalue['clickandpledge_periods'] == "2Weeks")
			 {
				 $cnpPeriodicity ="2 Weeks";
			 }elseif($paramvalue['clickandpledge_periods'] == "2Months")
			 {
				 $cnpPeriodicity ="2 Months";
			 }
			 elseif($paramvalue['clickandpledge_periods'] == "6Months")
			 {
				 $cnpPeriodicity ="6 Months";
			 }else
			 {
				 $cnpPeriodicity =$paramvalue['clickandpledge_periods'];
			 }
			 $total_periodicity=$dom->createElement('Periodicity',$cnpPeriodicity);
			 $total_periodicity=$trans_recurr->appendChild($total_periodicity);
			
			 if(isset($paramvalue['clickandpledge_recurring_type']) == 'Installment') 
			 {
				 $RecurringMethod=$dom->createElement('RecurringMethod',$paramvalue['clickandpledge_recurring_type']);
				 $RecurringMethod=$trans_recurr->appendChild($RecurringMethod);
			 } 
			 else 
			 {
				 $RecurringMethod=$dom->createElement('RecurringMethod',$paramvalue['clickandpledge_recurring_type']);
				 $RecurringMethod=$trans_recurr->appendChild($RecurringMethod);
			 }	
		 }
		   $trans_totals=$dom->createElement('CurrentTotals','');
		   $trans_totals=$transation->appendChild($trans_totals);		
		  
		
if(isset($paramvalue['clickandpledge_onetimeonly']) &&  $paramvalue['clickandpledge_onetimeonly'] == 'clickandpledge_Recurring' ) {
if($paramvalue['clickandpledge_recurring_type'] == 'Installment') {	

				if($jobboard_taxset[0] == 1)
				  {
			 $Total = ($recurring_unitprice + $recurring_unittax) - $recurring_unitdiscount;  
				  } else
				  {
			 $Total = $recurring_unitprice - $recurring_unitdiscount; 
				  }
			  
		

			    if($unit_discount > 0) 
			   {
				   $total_discount=$dom->createElement('TotalDiscount',$recurring_unitdiscount);
				   $total_discount=$trans_totals->appendChild($total_discount);
			   }
			   if($jobboard_taxset[0] == 1)
			   {
			   $total_tax=$dom->createElement('TotalTax',$recurring_unittax);
			   $total_tax=$trans_totals->appendChild($total_tax);
			   }
			   else
			   {
			   $total_tax=$dom->createElement('TotalTax',0);
			   $total_tax=$trans_totals->appendChild($total_tax);  
			   }
			   $total_amount=$dom->createElement('Total',$Total);
			   $total_amount=$trans_totals->appendChild($total_amount);
			   
			   } 
			    else if($paramvalue['clickandpledge_recurring_type'] == 'Subscription')
				{
			    if($jobboard_taxset[0] == 1)
				  {
			       $Total = $this->number_format(($sub_total + $wpjb_unittax) - $unit_discount, 2, '.', '');  
                  
				  } else
				  {
			        $Total = $this->number_format(($sub_total) - $unit_discount, 2, '.', '');  
				  }
				
			   
			   if($unit_discount > 0) 
			   {
				   $total_discount=$dom->createElement('TotalDiscount',$unit_discount);
				   $total_discount=$trans_totals->appendChild($total_discount);
			   }
			    if($jobboard_taxset[0] == 1)
				  {
				$total_tax=$dom->createElement('TotalTax',$wpjb_unittax);
				$total_tax=$trans_totals->appendChild($total_tax);
				  }
				  else
				  {
				$total_tax=$dom->createElement('TotalTax',0);
				$total_tax=$trans_totals->appendChild($total_tax);  
				  }
		   
			   $total_amount=$dom->createElement('Total',$Total);
			   $total_amount=$trans_totals->appendChild($total_amount);
			   
			    }
		   } 
		   else 
		   {
			 
				if($jobboard_taxset[0] == 1)
				  {
            
			         $Total = $this->number_format(($sub_total + $wpjb_unittax) - $unit_discount, 2, '.', '');
               
				  } else
				  {
					 $Total = $this->number_format(($sub_total) - $unit_discount, 2, '.', '');  
				  }
          
			   if($unit_discount > 0) 
			   {
				   $total_discount=$dom->createElement('TotalDiscount',$unit_discount);
				   $total_discount=$trans_totals->appendChild($total_discount);
			   }
			   if($jobboard_taxset[0] == 1)
				  {
           $total_tax=$dom->createElement('TotalTax',$wpjb_unittax);
		   $total_tax=$trans_totals->appendChild($total_tax);
				  }
				  else
				  {
					$total_tax=$dom->createElement('TotalTax',0);
		   $total_tax=$trans_totals->appendChild($total_tax);  
				  }
		   
		   $total_amount=$dom->createElement('Total',$Total);
		   $total_amount=$trans_totals->appendChild($total_amount);
		   }
		
		  if($unit_discount > 0) {
			  if(isset($params['discount_code']) && $params['discount_code'] != '') {
				  $trans_coupon=$dom->createElement('CouponCode',$params['discount_code']);
				  $trans_coupon=$transation->appendChild($trans_coupon);
			  }
		  }
			
		$strParam =$dom->saveXML();  
//print_r($strParam);
//exit;
		global $wpdb;
		$orderplacedcheck = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'wpjb_payment WHERE id = '.$data_id, OBJECT );
	
		if($orderplacedcheck) {
	
		$post['object_id'] = $orderplacedcheck->object_id;
		$post['object_type'] = $orderplacedcheck->object_type;
		
		 $connect = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
		 $client = new SoapClient('https://paas.cloud.clickandpledge.com/paymentservice.svc?wsdl', $connect);
       	 $soapParams = array('instruction'=>$strParam);
		 $response = $client->Operation($soapParams);

		 $html = '';
		 $paramsresult = array();
		 if(($response === FALSE)) {			
			$paramsresult['error'] = 'Connection to payment gateway failed - no data returned.';
			$paramsresult['ResultCode'] = '99999';
			$paramsresult['status'] = 'Fail';
		} else {	
			$ResultCode=$response->OperationResult->ResultCode;
			$transation_number=$response->OperationResult->TransactionNumber;
			$VaultGUID=$response->OperationResult->VaultGUID;
			$ResultData = $response->OperationResult->ResultData;
			$GatewayTransactionNumber = $response->OperationResult->GatewayTransactionNumber;
            $AuthorizationCode = $response->OperationResult->AuthorizationCode;
			if($ResultCode=='0')
			{
				$response_message = $response->OperationResult->ResultData;
				//Success
				$paramsresult['TransactionNumber'] = $VaultGUID;
				$paramsresult['trxn_result_code'] = $response_message;
				$paramsresult['status'] = 'Success';
				$paramsresult['ResultCode'] = $ResultCode;
				$fnlcnpamount = $this->number_format($Total/100,2,'.','');	
		
				
			
					  $html.= '<input type="hidden" id="wpjb-clickandpledge-id" value="'.$data_id.'" />';
				      $html.= '<input type="hidden" id="wpjb-clickandpledge-amount" value="'.$fnlcnpamount.'" />';
					  $html.= '<input type="hidden" id="wpjb-clickandpledge-type" value="'.$post['object_type'].'" />';
					  $html.= '<input type="hidden" id="wpjb-clickandpledge-payment-id" value="'.$paramsresult['TransactionNumber'].'" />';
							 
        
			}
          else if ( $ResultCode == '1' && $ResultData == 'Pending') {
                 $response_message = $response->OperationResult->ResultData;
				
				$paramsresult['TransactionNumber'] = $VaultGUID;
				$paramsresult['trxn_result_code'] = $response_message;
				$paramsresult['status'] = 'Success';
				$paramsresult['ResultCode'] = $ResultCode;
				$fnlcnpamount = $this->number_format($Total/100,2,'.','');	
		
				
          
         $html.='<div><h1 style="font-weight: bold;font-size: 36px;margin-top: 20px;margin-bottom: 10px; text-align: center;">Thank you</h1> </div>
    <div><h3 style="font-size: 24px;margin-top: 20px;margin-bottom: 10px; text-align: center;">We appriciate your Support </h3></div>
    <div style="text-align: center;">Transaction Result: <label style="font-weight: bold; color: #FEA900">Pending<label> </div>
    <div  style="width: 100%; text-align: center;"><label style="color: #ff0000; font-size: 16px; line-height: normal;text-align: center;">your payment will be authorized after your payment source has been verified by your bank<label> </div>
    <div style="text-align: center;"><strong>Order Number:</strong> '.$transation_number.'  </div>
    <div style="text-align: center;"><strong>Gateway Transaction Number:</strong> '.$GatewayTransactionNumber.'  </div>
    <div style="text-align: center;"><strong>Authorization Code:</strong> '.$AuthorizationCode.'  </div><script> jQuery(".wpjb-flash-error").css("display", "none"); // Hides the div instantly</script>';
          
          
          }
			else
			{
				if(in_array( $ResultCode, array( 2051,2052,2053 )))
				{
					$AdditionalInfo = $response->OperationResult->AdditionalInfo;
				}
				else
				{
					if(isset($classObject->responsecodes[$ResultCode] ) )
					{
						$AdditionalInfo = $classObject->responsecodes[$ResultCode];
					}
					else
					{
						$AdditionalInfo = 'Unknown error';
					}
				}
				$paramsresult['error'] = $AdditionalInfo;
				$paramsresult['ResultCode'] = $ResultCode;
				$paramsresult['status'] = 'Fail';
				$html.= '<input type="hidden" id="wpjb-clickandpledge-id" value="" />';
				$html.= '<input type="hidden" id="wpjb-clickandpledge-amount" value="0" />';
				$html.= '<input type="hidden" id="wpjb-clickandpledge-type" value="'.$post['object_type'].'" />';
				$html.= '<input type="hidden" id="wpjb-clickandpledge-payment-id" value="" />';
				$html.= '<input type="hidden" id="wpjb-clickandpledge-responsemsg" value="'.$this->responsecodes[$ResultCode].'" />';
			}
		}
	   
	       
		}
		
        $html.= '<div class="wpjb-clickandpledge-result">';
        
        $html.= '<div class="wpjb-clickandpledge-pending wpjb-flash-info">';
        $html.= '<div class="wpjb-flash-icon"><span class="wpjb-glyphs wpjb-icon-spinner wpjb-animate-spin"></span></div>';
        $html.= '<div class="wpjb-flash-body">';
        $html.= '<p><strong>'.__("Placing Order", "wpjobboard").'</strong></p>';
        $html.= '<p>'.__("Waiting for payment confirmation ...", "wpjobboard").'</p>';
        $html.= '</div>';
        $html.= '</div>';
        
        $html.= '<div class="wpjb-flash-info wpjb-none">';
        $html.= '<div class="wpjb-flash-icon"><span class="wpjb-glyphs wpjb-icon-ok"></span></div>';
        $html.= '<div class="wpjb-flash-body"></div>';
        $html.= '</div>';
        
        $html.= '<div class="wpjb-flash-error wpjb-none">';
        $html.= '<div class="wpjb-flash-icon"><span class="wpjb-glyphs wpjb-icon-cancel-circled"></span></div>';
        $html.= '<div class="wpjb-flash-body"></div>';
        $html.= '</div>';
        
        $html.= '</div>';
		
		return $html;
		
 }
	
	 
}
?>