<?php
class Wpjb_Form_Payment_clickandpledge extends Daq_Form_Abstract 
{
     public function __construct($options = array()) 
    {   
         $this->_data = $data;
         $request = Daq_Request::getInstance();

	     if(DOING_AJAX && $request->post("action") == "wpjb_payment_render") {
             add_filter("wpjb_payment_render_response", array($this, "script"));
         }
       
         parent::__construct($options);
     }   
     public function script($response) 
     { 
	     wp_register_script( 'clickandpledge', plugins_url( '/clickandpledge.js', __FILE__ ) ,array("jquery", "wpjb-vendor-clickandpledge"));
	     $scripts = wp_scripts()->registered["clickandpledge"];
		 $response["load"] = array($scripts->src."?time=".time());
       
         return $response;
     }
    public function init() 
    {
       $request = Daq_Request::getInstance();
    
        $cnppayment = new Payment_ClickandPledge;
		$this->addGroup("default");
        
        $e = $this->create("fullname");
        $e->setLabel(__("Full Name", "wpjobboard"));
		$e->setRequired(true);
        $this->addElement($e, "default");
        
        $e = $this->create("email");
        $e->setLabel(__("Email", "wpjobboard"));
        $e->setRequired(true);
        $this->addElement($e, "default");

        
        $e = $this->create("cnpsettings");
	    $e->setRenderer(array($this, "clickandpledgepaymentmethods"));
        $this->addElement($e, "clickandpledge");

        apply_filters("wpjb_form_init_payment_clickandpledge", $this);
    }

    public function inputStripe($input) 
    {
        $e = new Daq_Form_Element_Text("");
        $e->addClass($input->getClasses());
		
        foreach($input->getAttr() as $aKey => $aVal) {
            $e->setAttr($aKey, $aVal);
        }
        echo $e->render();
    }
	
    public function getMerchant() { 
		$merchant       = array();
		$payment_arr    = get_option('wpjb_payment_method');
	    $data           = $payment_arr["clickandpledge_payment"];
  	//	print_r($data);
		$merchant['wpjobboard_clickandpledge_AccountID']               = $data['wpjobboard_clickandpledge_AccountID'];
		$merchant['wpjobboard_clickandpledge_AccountGuid']             = Config_ClickandPledge::getwpjbCnPAccountGUID($data['wpjobboard_clickandpledge_AccountID']);		
		$merchant['wpjobboard_clickandpledge_OrderMode']   		       = $data['wpjobboard_clickandpledge_OrderMode'];
		$merchant['wpjobboard_clickandpledge_ConnectCampaignAlias']    = $data['wpjobboard_clickandpledge_ConnectCampaignAlias'];
		$merchant['wpjobboard_clickandpledge_Paymentmethods']          = $data['wpjobboard_clickandpledge_Paymentmethods'];
		$merchant['wpjobboard_clickandpledge_Paymentoptions']          = $data['wpjobboard_clickandpledge_Paymentoptions'];
		$merchant['wpjobboard_clickandpledge_titles']                  = $data['wpjobboard_clickandpledge_titles'];
		$merchant['wpjobboard_clickandpledge_reference']               = $data['wpjobboard_clickandpledge_reference'];
		$merchant['wpjobboard_clickandpledge_DefaultpaymentMethod']    = $data['wpjobboard_clickandpledge_DefaultpaymentMethod'];
		$merchant['wpjobboard_clickandpledge_receiptsettings']         = $data['wpjobboard_clickandpledge_receiptsettings'];
		$merchant['wpjobboard_clickandpledge_termsandconditionsadmin'] = $data['wpjobboard_clickandpledge_termsandconditionsadmin'];
		$merchant['wpjobboard_clickandpledge_DefaultpaymentOptions']   = $data['wpjobboard_clickandpledge_DefaultpaymentOptions'];
		$merchant['wpjobboard_clickandpledge_PaymentSubscription']     = $data['wpjobboard_clickandpledge_PaymentSubscription'];
		$merchant['wpjobboard_clickandpledge_PaymentRecurring']        = $data['wpjobboard_clickandpledge_PaymentRecurring'];
		$merchant['wpjobboard_clickandpledge_PaymentPeriods']          = $data['wpjobboard_clickandpledge_PaymentPeriods'];
		$merchant['wpjobboard_clickandpledge_indefinite']              = $data['wpjobboard_clickandpledge_indefinite'];
		$merchant['wpjobboard_clickandpledge_dfltnoofpaymnts']         = $data['wpjobboard_clickandpledge_dfltnoofpaymnts'];
		$merchant['wpjobboard_clickandpledge_maxnoofinstallments']     = $data['wpjobboard_clickandpledge_maxnoofinstallments'];
		$merchant['clickandpledge_installments']                       = $data['clickandpledge_installments'];
		$merchant['order']                                             = $data['order'];
		$merchant['clickandpledge_reference_number']                   = $data['clickandpledge_reference_number'];
		$merchant['wpjobboard_clickandpledge_emailcustomer']           = $data['wpjobboard_clickandpledge_emailcustomer'];
		$merchant['wpjobboard_clickandpledge_Paymentmethods_cc']       = $data['wpjobboard_clickandpledge_Paymentmethods_cc'];
		$merchant['wpjobboard_clickandpledge_Paymentmethods_eCheck']   = $data['wpjobboard_clickandpledge_Paymentmethods_eCheck'];
		
		$merchant['wpjobboard_clickandpledge_Paymentmethods_Amex']     = $data['wpjobboard_clickandpledge_Paymentmethods_Amex'];
		$merchant['wpjobboard_clickandpledge_Paymentmethods_Jcb']      = $data['wpjobboard_clickandpledge_Paymentmethods_Jcb'];
		$merchant['wpjobboard_clickandpledge_Paymentmethods_master']   = $data['wpjobboard_clickandpledge_Paymentmethods_master'];
		$merchant['wpjobboard_clickandpledge_Paymentmethods_Visa']     = $data['wpjobboard_clickandpledge_Paymentmethods_Visa'];
		$merchant['wpjobboard_clickandpledge_Paymentmethods_Discover'] = $data['wpjobboard_clickandpledge_Paymentmethods_Discover'];
		
    
       $merchant['wpjobboard_clickandpledge_Paymentmethods_gpay']      = $data['wpjobboard_clickandpledge_Paymentmethods_gpay'][0];
       $merchant['wpjobboard_clickandpledge_Paymentmethods_paypal']    = $data['wpjobboard_clickandpledge_Paymentmethods_paypal'][0];
		$merchant['wpjobboard_clickandpledge_Paymentmethods_ba']        = $data['wpjobboard_clickandpledge_Paymentmethods_ba'][0];
		$merchant['wpjobboard_clickandpledge_Paymentmethods_paypalmid']  = $data['wpjobboard_clickandpledge_Paymentmethods_paypalmid'];
		
	return $merchant;
	 }
	 
    public function bind(array $post, array $get) {
	   // this is a good place to set $this->data
	   //print_r($get);
       $this->setObject(new Wpjb_Model_Payment($post["id"]));
       parent::bind($post, $get);
     }
	 public function getYears() {		 
		 $str = '';
		
		 for ($i = date('Y'); $i < date('Y') + 21; $i++) {
			$str .= '<option value="'.strftime('%Y', mktime(0, 0, 0, 1, 1, $i)).'">'.strftime('%Y', mktime(0, 0, 0, 1, 1, $i)).'</option>';				
		}
		
		return $str;
	 }
	  public function getMonths() {
		$str = '';
		for ($i = 1; $i <= 12; $i++) {
			if(date('m') == sprintf('%02d', $i)) {
			$str .= '<option value="'.sprintf('%02d', $i).'" selected>'.sprintf('%02d', $i).' ('.strftime('%B', mktime(0, 0, 0, $i, 1, 2000)).')</option>';
			} else {
			$str .= '<option value="'.sprintf('%02d', $i).'">'.sprintf('%02d', $i).' ('.strftime('%B', mktime(0, 0, 0, $i, 1, 2000)).')</option>';
			}			
		}
		
		return $str;
	 }
	
	 public function number_format($number, $decimals = 2,$decsep = '', $ths_sep = '') {
		$parts = explode('.', $number);
		if(count($parts) > 1) {
			return $parts[0].'.'.substr($parts[1],0,$decimals);
		} else {
			return $number;
		}
	}
 public  function getCnPjbAccountName($accid) {
		$cnpAccountorg ="";
 	 global $wpdb;
     
		 $cnpAccountorg = $wpdb->get_var("SELECT cnpaccountsinfo_orgname FROM ".$wpdb->prefix."cnp_wp_jbcnpaccountsinfo WHERE cnpaccountsinfo_orgid ='".$accid."'");
		
       
	 return $cnpAccountorg;
		
	}
public  function getCnPjbCurrency($accid) {
		$cnpcurrvals ="";
 	    global $wpdb;
       
		$cnpcurs = $wpdb->get_var("SELECT cnpaccountsinfo_cnpcurrency FROM  ".$wpdb->prefix."cnp_wp_jbcnpaccountsinfo WHERE cnpaccountsinfo_orgid ='".$accid."'");
		
        if($cnpcurs == 840) {$cnpcurrvals = "USD";}elseif($cnpcurs == 978) {$cnpcurrvals = "EURO";}elseif($cnpcurs == 826) {$cnpcurrvals = "POUND";}else {$cnpcurrvals = "CAD";}
	 return $cnpcurrvals;
		
	}
    public function clickandpledgepaymentmethods() 
    {
	 global $wpdb;
	
	  $listrow = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'wpjb_pricing WHERE id = '.wp_unslash(sanitize_text_field($_POST['defaults']['pricing_id'])), OBJECT ); 
	  $a  = get_option('wpjb_payment_method');
	  $data = $a["clickandpledge_payment"];

	  $availableCurrencies = array();
	  $paymentMethods = array();
	  $merchant = $this->getMerchant(); 
	  $id = wp_unslash(sanitize_text_field($_POST['defaults']['pricing_id']));
	
	  
	  $selectedCurrency = $listrow->currency;

	
	if(isset($merchant['wpjobboard_clickandpledge_Paymentmethods_cc']) && $merchant['wpjobboard_clickandpledge_Paymentmethods_cc'] !=""){
	$paymentMethods[$merchant['wpjobboard_clickandpledge_Paymentmethods_cc']] = 'Credit Card';
    }
	if(isset($merchant['wpjobboard_clickandpledge_Paymentmethods_eCheck']) && $merchant['wpjobboard_clickandpledge_Paymentmethods_eCheck'] !=""){
			    $paymentMethods[$merchant['wpjobboard_clickandpledge_Paymentmethods_eCheck']] = 'eCheck';
    }
    if(isset($merchant['wpjobboard_clickandpledge_Paymentmethods_gpay']) && $merchant['wpjobboard_clickandpledge_Paymentmethods_gpay'] !=""){
			    $paymentMethods[$merchant['wpjobboard_clickandpledge_Paymentmethods_gpay']] = 'Google Pay';
    }
     if(isset($merchant['wpjobboard_clickandpledge_Paymentmethods_paypal']) && $merchant['wpjobboard_clickandpledge_Paymentmethods_paypal'] !=""){
			    $paymentMethods[$merchant['wpjobboard_clickandpledge_Paymentmethods_paypal']] = 'PayPal/Venmo';
    }
      if(isset($merchant['wpjobboard_clickandpledge_Paymentmethods_ba']) && $merchant['wpjobboard_clickandpledge_Paymentmethods_ba'] !=""){
			    $paymentMethods[$merchant['wpjobboard_clickandpledge_Paymentmethods_ba']] = 'Bank Account';
    }
		if(isset($merchant['wpjobboard_clickandpledge_Paymentmethods']) && count($merchant['wpjobboard_clickandpledge_Paymentmethods']) > 0) {
				 
			   
			   foreach($merchant['wpjobboard_clickandpledge_Paymentmethods'] as $method) {
					
					 
				
				     if($method == 'CustomPayment')
					 $customtypes = explode(";",$merchant['wpjobboard_clickandpledge_titles']);
				
				 if(count($customtypes) > 0) {
				     foreach($customtypes as $custompays)
					{
						if(trim($custompays) != '') {
							$paymentMethods[$custompays] = trim($custompays);
							$customtypes[] = trim($custompays);
						} 
					 }
				
				 } 
				 
					
					
					 
				 }
			
		}
			 else {
				 if(isset($merchant['wpjobboard_clickandpledge_Paymentmethods']) && $merchant['wpjobboard_clickandpledge_Paymentmethods'] !=""){ 
				 $customtypes = explode(";",$merchant['wpjobboard_clickandpledge_titles']);
				 
				if(count($customtypes) > 0) {
				     foreach($customtypes as $custompays)
					{
						if(trim($custompays) != '') {
							$paymentMethods[$custompays] = trim($custompays);
							$customtypes[] = trim($custompays);
						} 
					 }
					
				 } 
				 }
			 }
	
	 $defaultpayment = $merchant['wpjobboard_clickandpledge_DefaultpaymentMethod'];
     

	 
	 wp_register_script( 'clickandpledge-plugin-script', plugins_url( '/clickandpledge.js', __FILE__ ) );
	 wp_enqueue_script( 'clickandpledge-plugin-script' );
	 
	 wp_register_script( 'jquery.validate.min-script', plugins_url( '/jquery.validate.min.js', __FILE__ ) );
	 wp_enqueue_script( 'jquery.validate.min-script' );
	 
	 wp_register_script( 'clickandpledge_validations-script', plugins_url( '/clickandpledge_validations.js', __FILE__ ) );
	 wp_enqueue_script( 'clickandpledge_validations-script' );
		  
	 ?>
		<style type="text/css">
		.form-row > label > span {
			display: block;
			width: 200px;
			float: left;
			line-height: 2em;
		}
		.form-row label.error {
			color: red;
			font-style: italic;
		}
		.cnpwpjb_field {
			margin: 0;
			padding: 6px;
			float: left;
			clear: none;
			width: 70%;		
		}
		.wpjb-element-name-cnpsettings fieldset .wpjb-element-input-text{
			margin: 6px 0 6px 0;
	    	padding: 6px 0 6px 0;
	    	clear: both;
    		overflow: hidden;
    	}
		.wpjb-element-name-cnpsettings > label.wpjb-label{
			display:none!important;
		}
		.wpjb-element-name-cnpsettings > div.wpjb-field  {
			width: 100% !important;
		}
		.wpjb-element-name-cnpsettings .wpjb-fieldset-cnppaymentoptions .cnpwpjb_field{
			padding-top:6px!important;
		}
		@media (max-width: 760px) and (min-width: 320px){
			.cnpwpjb_field {
			    width: 100%;
			}
		}
    #divsubmitDonation_stripePay, #divsubmitDonation_stripePay_link{
    width: 210px;
    float: left;
    padding-right: 8px;
    padding-bottom: 5px;
}
.btn-gpay {
    display: flex;
    background-color: #000;
    height: 50px;
    border-radius: 4px;
    width: 100%;
    vertical-align: middle;
    text-align: center;
    background-image: url("data:image/svg+xml,%3Csvg width='41' height='17' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cpath d='M19.526 2.635v4.083h2.518c.6 0 1.096-.202 1.488-.605.403-.402.605-.882.605-1.437 0-.544-.202-1.018-.605-1.422-.392-.413-.888-.62-1.488-.62h-2.518zm0 5.52v4.736h-1.504V1.198h3.99c1.013 0 1.873.337 2.582 1.012.72.675 1.08 1.497 1.08 2.466 0 .991-.36 1.819-1.08 2.482-.697.665-1.559.996-2.583.996h-2.485v.001zm7.668 2.287c0 .392.166.718.499.98.332.26.722.391 1.168.391.633 0 1.196-.234 1.692-.701.497-.469.744-1.019.744-1.65-.469-.37-1.123-.555-1.962-.555-.61 0-1.12.148-1.528.442-.409.294-.613.657-.613 1.093m1.946-5.815c1.112 0 1.989.297 2.633.89.642.594.964 1.408.964 2.442v4.932h-1.439v-1.11h-.065c-.622.914-1.45 1.372-2.486 1.372-.882 0-1.621-.262-2.215-.784-.594-.523-.891-1.176-.891-1.96 0-.828.313-1.486.94-1.976s1.463-.735 2.51-.735c.892 0 1.629.163 2.206.49v-.344c0-.522-.207-.966-.621-1.33a2.132 2.132 0 0 0-1.455-.547c-.84 0-1.504.353-1.995 1.062l-1.324-.834c.73-1.045 1.81-1.568 3.238-1.568m11.853.262l-5.02 11.53H34.42l1.864-4.034-3.302-7.496h1.635l2.387 5.749h.032l2.322-5.75z' fill='%23FFF'/%3E%3Cpath d='M13.448 7.134c0-.473-.04-.93-.116-1.366H6.988v2.588h3.634a3.11 3.11 0 0 1-1.344 2.042v1.68h2.169c1.27-1.17 2.001-2.9 2.001-4.944' fill='%234285F4'/%3E%3Cpath d='M6.988 13.7c1.816 0 3.344-.595 4.459-1.621l-2.169-1.681c-.603.406-1.38.643-2.29.643-1.754 0-3.244-1.182-3.776-2.774H.978v1.731a6.728 6.728 0 0 0 6.01 3.703' fill='%2334A853'/%3E%3Cpath d='M3.212 8.267a4.034 4.034 0 0 1 0-2.572V3.964H.978A6.678 6.678 0 0 0 .261 6.98c0 1.085.26 2.11.717 3.017l2.234-1.731z' fill='%23FABB05'/%3E%3Cpath d='M6.988 2.921c.992 0 1.88.34 2.58 1.008v.001l1.92-1.918C10.324.928 8.804.262 6.989.262a6.728 6.728 0 0 0-6.01 3.702l2.234 1.731c.532-1.592 2.022-2.774 3.776-2.774' fill='%23E94235'/%3E%3C/g%3E%3C/svg%3E");
    background-repeat: no-repeat;
   
    background-repeat: no-repeat;
    background-position: center;
    background-size: 55px;
}
.LinkButton {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    background-color: #33ddb3;
    border: 0;
    border-radius: var(--borderRadius);
    cursor: pointer;
    font-family: -apple-system, system-ui, BlinkMacSystemFont, SF Pro Text, Helvetica Neue, Helvetica, Arial, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol;
    max-height: 64px;
    min-height: 50px;
    padding: 0;
    position: relative;
    transition: background-color .15s ease;
    border-radius: 4px;
    padding: 10px 40px;
}
.LinkButton-inner {
    color: #1d3944;
    height: 100%;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    width: 100%;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    -webkit-align-items: center;
    align-items: center;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-justify-content: center;
    justify-content: center;
}
.LinkButton-text {
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    white-space: nowrap;
    font-size: 36vh;
    font-size: min(15px, 36vh);
    font-weight: 500;
}
.LinkButton-textContent{
    -webkit-box-align: baseline;
    -ms-flex-align: baseline;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    -webkit-align-items: baseline;
    align-items: baseline;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    gap: min(7px, 14vh);
    -webkit-justify-content: center;
    justify-content: center;
}
.LinkButton-logoSvg {
    -ms-flex-negative: 0;
    display: block;
    -webkit-flex-shrink: 0;
    flex-shrink: 0;
    height: .88em;
    position: static;
    width: auto;
}
.LinkButton-arrow {
    fill: #1d3944;
    -ms-flex-negative: 0;
    display: block;
    -webkit-flex-shrink: 0;
    flex-shrink: 0;
    max-height: 16px;
    max-width: 24px;
}
.btn-achpay:disabled {
    opacity: .65;
}
.btn-achpay {
    padding: 10px !important;
    color: #fff;
    cursor: pointer;
    display: block;
    padding-bottom: 10px;
    max-width: 750px;
    margin: auto;
    width: 100%;
    color: #fff;
    background-color: #343a40;
    border-color: #343a40;
    border-radius: .25rem;
    border: 1px solid transparent;
}
.btn-paypal {
    background-origin: content-box;
    background-position: 50%;
    background-repeat: no-repeat;
    background-size: contain;
    background-color: rgb(255, 196, 57);
    border-color: rgb(255, 196, 57);
    display: block;
    height: 55px;
    border-radius: 4px;
    cursor: pointer;
}

.margin-top-10 {
    margin-top: 10px !important;
}
.p-2 {
    padding: .5rem !important;
}
.btn-block {
    display: block;
    width: 100%;
}
		</style>
       
        <?php 
        $html .='
		<script type="text/javascript">
		  var WPJB_PAYMENT_ID = '.$id.';
		  //if (typeof ajaxurl === "undefined") {
			//ajaxurl = "'.$ajaxurl.'";
		 // }
		</script>
		
		<fieldset><legend><div style="float:right;margin-top:-13px;">
        <img src='.WP_PLUGIN_URL.'/'.plugin_basename( dirname(__FILE__)).'/cp-logo.png" align="Click&Pledge Logo">
    	</div>'.__("Payment Details", "wpjobboard").'</legend>
		<div class="payment-errors"></div><div class="htmlholder">
	<div style="display:none;"><input type="hidden" name="cnpversion" id="cnpversion" value="4.24080000-WP6.6.1-JB5.11.2"/></div>
		';
		?>
		<script>
			 jQuery(document).ready(function($)
			 {
             
	            var defaultval = jQuery("#wpjobboard_clickandpledge_DefaultpaymentOptions").val();
               
				if(defaultval == "Recurring")
				{
				   jQuery("#recurring_selection").show();
                 jQuery('#bankreccnt').show();
                 jQuery('#paypal-button-container2').show();
                 jQuery('#paypal-button-container').hide();
                
				} 
				else if(defaultval == "OneTimeOnly")
				{	
				  jQuery("#recurring_selection").hide();
                jQuery('#bankreccnt').hide();
                 jQuery('#paypal-button-container2').hide();
                 jQuery('#paypal-button-container').show();
				}
				
			    jQuery(".clsrecpayopt").click(function(){
				if(jQuery(".clsrecpayopt:checked").val() == "clickandpledge_OneTimeOnly") {
				jQuery("#recurring_selection").hide();
                jQuery('#bankreccnt').hide();
                    jQuery('#paypal-button-container2').hide();
                 jQuery('#paypal-button-container').show();
				}	
				else
				{
				jQuery("#recurring_selection").show();
                jQuery('#bankreccnt').show();
                   jQuery('#paypal-button-container2').show();
                 jQuery('#paypal-button-container').hide();
				}
				});
				jQuery(".clsrecpayopt").ready(function(){
				if(jQuery(".clsrecpayopt:checked").val() == "clickandpledge_OneTimeOnly") {
				  jQuery("#recurring_selection").hide();
				}	
				else
				{
				  jQuery("#recurring_selection").show();
                     jQuery('#bankreccnt').show();
				}
				});
				
				jQuery("#clickandpledge_recurring:checked").click(function(){
				
				if(jQuery("#clickandpledge_recurring:checked").val() == "clickandpledge_OneTimeOnly") {
				jQuery("#recurring_selection").hide();
                     jQuery('#bankreccnt').hide();
				}	
				else
				{
				jQuery("#recurring_selection").show();
                     jQuery('#bankreccnt').show();
				}
					
				});
				jQuery("#clickandpledge_subscriptions").click(function(){
					jQuery("#indefinite").show();
				});
				jQuery("#clickandpledge_installments").click(function(){
					jQuery("#indefinite").hide();
				});
				jQuery("#clickandpledge_indefinite_recurrings").click(function(){
					jQuery("#clickandpledge_nooftimes").fadeToggle( "<b>Paragraph. </b>" );	
				});
			 });
			 
            </script><?php
		if($merchant['wpjobboard_clickandpledge_OrderMode'] == "test")
		{
			?>
			<script>
					jQuery( document ).ready(function() {
			var d = new Date();
			var n = d.getFullYear() + 1;
			//document.getElementById("#clickandpledge_cardNumber").value = "4111111111111111";
			jQuery("#clickandpledge_cardNumber").val("4111111111111111");
			jQuery("#clickandpledge_cardNumber").prop("readonly", true);
			jQuery("#clickandpledge_cardNumber").css({"background-color": "#f4f4f4"});
			jQuery("#clickandpledge_cardExpMonth").val("06");
			jQuery("#clickandpledge_cvc").val("123");
			jQuery("#clickandpledge_cardExpMonth").prop("readonly", true);
			jQuery("#clickandpledge_cardExpYear").prop("readonly", true);
			jQuery("#clickandpledge_cvc").prop("readonly", true);
						
			jQuery("#clickandpledge_echeck_AccountType").prop("disabled", true);
			jQuery("#clickandpledge_echeck_AccountType").css({"background-color": "#f4f4f4"});
			jQuery("#clickandpledge_echeck_NameOnAccount").prop("disabled", true);
			jQuery("#clickandpledge_echeck_NameOnAccount").css({"background-color": "#f4f4f4"});
			jQuery("#clickandpledge_echeck_IdType").prop("disabled", true);
			jQuery("#clickandpledge_echeck_IdType").css({"background-color": "#f4f4f4"});
			jQuery("#clickandpledge_echeck_CheckType").prop("disabled", true);
			jQuery("#clickandpledge_echeck_CheckType").css({"background-color": "#f4f4f4"});
			jQuery("#clickandpledge_echeck_CheckNumber").prop("disabled", true);
			jQuery("#clickandpledge_echeck_CheckNumber").css({"background-color": "#f4f4f4"});
			jQuery("#clickandpledge_echeck_RoutingNumber").prop("disabled", true);
			jQuery("#clickandpledge_echeck_RoutingNumber").css({"background-color": "#f4f4f4"});
			jQuery("#clickandpledge_echeck_AccountNumber").prop("disabled", true);
			jQuery("#clickandpledge_echeck_AccountNumber").css({"background-color": "#f4f4f4"});
			jQuery("#clickandpledge_echeck_retypeAccountNumber").prop("disabled", true);
			jQuery("#clickandpledge_echeck_retypeAccountNumber").css({"background-color": "#f4f4f4"});
			});
			</script>
		<?php
		}
		
		if(count($paymentMethods) > 0) {
			$rechtml ="";
		if(in_array("Recurring",$merchant['wpjobboard_clickandpledge_Paymentoptions']))
		{
		  if(count($merchant['wpjobboard_clickandpledge_Paymentoptions']) != 1){
			$rechtml .= '<fieldset class="wpjb-fieldset-default wpjb-fieldset-cnppaymentoptions"><div class="wpjb-element-input-text"><label class="wpjb-label">
		  '.__("Payment Options <span class='wpjb-required'>*</span>", "wpjobboard").'</label><div class="cnpwpjb_field">';
		
		  foreach($merchant['wpjobboard_clickandpledge_Paymentoptions'] as $payopt)
			  
		  {  if($merchant['wpjobboard_clickandpledge_DefaultpaymentOptions'] == $payopt)
			  {
				  if($payopt == "OneTimeOnly") {$dpayopt = "One Time Only";}
			      else if($payopt == "Recurring"){ $dpayopt = "Recurring";}
			   $rechtml .= '<input type="radio" value="clickandpledge_'.$payopt.'" name="clickandpledge_onetimeonly" id="clickandpledge_recurring" class="clsrecpayopt cnp_payment_method_selection" checked>  '.$dpayopt.' </br>';			  
			  } else
			  {
				  if($payopt == "OneTimeOnly") {$dpayopt = "One Time Only";}else if($payopt == "Recurring"){ $dpayopt = "Recurring";}
			  $rechtml .= '<input type="radio" value="clickandpledge_'.$payopt.'" name="clickandpledge_onetimeonly" id="clickandpledge_onetimeonly" class="clsrecpayopt wpjb-field">  '.$dpayopt.' </br>';	 
			  }	
	      }
			  $rechtml .= '</div></div></fieldset>';
		 }
			else
			{
				$rechtml .= '<input type="hidden" value="clickandpledge_Recurring" name="clickandpledge_onetimeonly" id="clickandpledge_onetimeonly" >';
			}
          $rechtml .= '<fieldset class="wpjb-fieldset-default wpjb-fieldset-cnprecurring">
		  <div id="recurring_selection">

		  <div class="wpjb-element-input-text" >
		  <label class="wpjb-label">
		  '.__("Recurring types <span class='wpjb-required'>*</span>", "wpjobboard").'</label><div class="cnpwpjb_field">
		  <select name="clickandpledge_recurring_type" id="recurring_select">';
		  if(isset($merchant['wpjobboard_clickandpledge_PaymentSubscription']) && !empty($merchant['wpjobboard_clickandpledge_PaymentSubscription'])){
		  foreach($merchant['wpjobboard_clickandpledge_PaymentSubscription'] as $recurringmethod)
		  {
			 if($merchant['wpjobboard_clickandpledge_PaymentRecurring'] == $recurringmethod)
			 {
			 $rechtml .= '<option value="'.$recurringmethod.'" selected>'.$recurringmethod.'</option>'; 
			 }	else
			 {
			 $rechtml .= '<option value="'.$recurringmethod.'">'.$recurringmethod.'</option>'; 
			 }				 
			 
		   }
		   $rechtml .= '</select></div></div>

		 <div class="wpjb-element-input-text" id="indefinite" style="display:none !important;">
		 <label class="wpjb-label">Indefinite Recurrings
		 '.__("", "wpjobboard").'
		 </label><div class="cnpwpjb_field" style="padding-top: 8px;"><input type="checkbox" value="clickandpledge_indefinite_recurrings" name="clickandpledge_indefinite_recurrings" id="clickandpledge_indefinite_recurrings"> 
		 </div></div>';
		  }

		 $rechtml .= '<div class="wpjb-element-input-text"><label class="wpjb-label">'.__("Periodicity", "wpjobboard").'</label>
		 <div class="cnpwpjb_field"><select name="clickandpledge_periods" id="clickandpledge_periods" class="required">';
		if(isset($merchant['wpjobboard_clickandpledge_PaymentPeriods']) && !empty($merchant['wpjobboard_clickandpledge_PaymentPeriods'])){
		foreach($merchant['wpjobboard_clickandpledge_PaymentPeriods'] as $cnpperiods)
		 {
			if($cnpperiods == "2Weeks")
			 {
				 $cnpPeriodicity ="2 Weeks";
			 }elseif($cnpperiods == "2Months")
			 {
				 $cnpPeriodicity ="2 Months";
			 }
			 elseif($cnpperiods == "6Months")
			 {
				 $cnpPeriodicity ="6 Months";
			 }else
			 {
				 $cnpPeriodicity =$cnpperiods;
			 }
			 $rechtml .= '<option value="'.$cnpperiods.'">'.$cnpPeriodicity.'</option>';
		   }
		    $rechtml .= '</select> <input type="hidden" name="clickandpledge_maxinst" id="clickandpledge_maxinst"  value="'.$merchant['wpjobboard_clickandpledge_maxnoofinstallments'].'"></div></div>'; 

			 if($merchant['wpjobboard_clickandpledge_indefinite'] == 'fixednumber')
			 {
			 $rechtml .= '<div class="wpjb-element-input-text"><label class="wpjb-label">'.__("Number of payments", "wpjobboard").'</label>
			     <div class="cnpwpjb_field"><input type="text" name="clickandpledge_nooftimes" id="clickandpledge_nooftimes" class="required" value="'.$merchant['wpjobboard_clickandpledge_dfltnoofpaymnts'].'" readonly></div></div>'; 
			 } else if($merchant['wpjobboard_clickandpledge_indefinite'] == 'indefinite_openfield')
			 {
				$rechtml .= '<div class="wpjb-element-input-text"><label class="wpjb-label">'.__("Number of payments", "wpjobboard").'</label>
			 		<div class="cnpwpjb_field"><input type="text" name="clickandpledge_nooftimes" id="clickandpledge_nooftimes" class="required" value="'.$merchant['wpjobboard_clickandpledge_dfltnoofpaymnts'].'" maxlength="3">
					<input name="clickandpledge_indefinite" type="hidden" id="clickandpledge_indefinite" class="required" value="on"></div></div>'; 
			 } else if($merchant['wpjobboard_clickandpledge_indefinite'] == 'openfield')
			 {
			 $rechtml .= '<div class="wpjb-element-input-text"><label class="wpjb-label">'.__("Number of payments", "wpjobboard").'</label>
			 		<div class="cnpwpjb_field"><input type="text" name="clickandpledge_nooftimes" id="clickandpledge_nooftimes" class="required" value="'.$merchant['wpjobboard_clickandpledge_dfltnoofpaymnts'].'" maxlength="3"></div></div>'; 
			 }else if($merchant['wpjobboard_clickandpledge_indefinite'] == 'indefinite')
			 {
			 $rechtml .= '<div class="wpjb-element-input-text"><label class="wpjb-label" ><span>'.__("Number of payments", "wpjobboard").'</span></label>
			 	<div class="cnpwpjb_field">
			 	<span for="clickandpledge_cart_number"> Indefinite Recurring Only</span>
			 <input name="clickandpledge_nooftimes" type="hidden" id="clickandpledge_nooftimes" class="required" value="999">
			 <input name="clickandpledge_indefinite" type="hidden" id="clickandpledge_indefinite" class="required" value="on"></div></div>'; 
			 }
			$rechtml .= '</div>
			 </fieldset>
		    ';
	}}
			
			$paymenthtml .= '<div id="payment_methods" style="padding:10px 0px" class="wpjb-form">';
				
				foreach($paymentMethods as $pkey => $pval) {
                  $cnpclientid = "AbnZ3lA18tFW9CwySaLTk2SqYuYyG03r-fjlpCLwCtYhbKbuBFVdU3dXRFEqOw23KDeOz40sbdVqQN5B";
                  $cnppaypalmerchantid = $merchant['wpjobboard_clickandpledge_Paymentmethods_paypalmid'];
                  $cnpselaccid = $merchant['wpjobboard_clickandpledge_AccountID'];
                  $cnppaypalcurr =  $this->getCnPjbCurrency($cnpselaccid);
				  $defaultpayment = $merchant['wpjobboard_clickandpledge_DefaultpaymentMethod'];
					if($pkey == $defaultpayment) {
						
					$paymenthtml .= '<input type="radio" id="cnp_payment_method_selection_'.$pkey.'" name="cnp_payment_method_selection" onclick="displaysection(this.value);" class="cnp_payment_method_selection" value="'.$pkey.'" checked="checked">&nbsp<b>'.$pval.'</b>   ';
					 } 
					else{
						
					 $paymenthtml .= '<input type="radio" id="cnp_payment_method_selection_'.$pkey.'" name="cnp_payment_method_selection" onclick="displaysection(this.value);" class="cnp_payment_method_selection" value="'.$pkey.'"> <b>'.$pval.'</b>   ';
					 } 
			
					$paymenthtml .= ' <script type="text/javascript">
					     var simple = "#cnp_payment_method_selection_'.$pkey.'";
					
                        var dftpmt = "'.$defaultpayment.'";
                        if(dftpmt == "paypal")
                        {	
                        function loadAsync(url, callback) {
  var s = document.createElement("script");
  s.setAttribute("src", url); s.onload = callback;
  document.head.insertBefore(s, document.head.firstElementChild);
} 
loadAsync("https://www.paypal.com/sdk/js?client-id='.$cnpclientid.'&intent=capture&currency='.$cnppaypalcurr.'&debug=false&enable-funding=venmo&buyer-country=US&disable-funding=paylater,credit,card&merchant-id='. $cnppaypalmerchantid.'", function() {
  paypal.Buttons({

 // Call your server to set up the transaction
            createOrder: function(data, actions) {
         
                return PayPalJBCreateOrder();
            },

            // Call your server to finalize the transaction
            onApprove: function(data, actions) {
            
            var jData = JSON.stringify(data);
            var pData = JSON.parse(jData);
            console.log(pData.orderID);
 			console.log(jData);
            jQuery("#paypal_paymentnumber").val(pData.orderID);
            var sbmtid ="wpjb-place-order";
         
            if(window[sbmtid]){return false;} 
            window[sbmtid]=true; 
            jQuery(".wpjb-place-order").trigger("click",[true]);
            
           
            }
  }).render("#paypal-button-container");
});

                        }
						 jQuery(simple).click(function(){
                         
						 jQuery("#cnp_CreditCard_div").hide();					
					     jQuery("#cnp_eCheck_div").hide();
                         jQuery("#cnp_gpay_div").hide();
					     jQuery("#cnp_Custompay_div").show();
                          jQuery("#cnp_paypal_div").hide();
					  
						 });
				         </script>';
				      
                }
				$paymenthtml .= '</div>';
			
				   $paymenthtml .= '<script>
                       
             
function loadAsync(url, callback) {
  var s = document.createElement("script");
  s.setAttribute("src", url); s.onload = callback;
  document.head.insertBefore(s, document.head.firstElementChild);
}
function observeElement(element, property, callback, delay = 0) { 

            let elementPrototype = Object.getPrototypeOf(element); 

            if (elementPrototype.hasOwnProperty(property)) { 

                let descriptor = Object.getOwnPropertyDescriptor(elementPrototype, property); 

                Object.defineProperty(element, property, { 

                    get: function () { 

                        return descriptor.get.apply(this, arguments); 

                    }, 

                    set: function () { 

                        let oldValue = this[property]; 

                        descriptor.set.apply(this, arguments); 

                        let newValue = this[property]; 

                        if (typeof callback == "function") { 

                            setTimeout(callback.bind(this, oldValue, newValue), delay); 

                        } 

                        return newValue; 

                    } 

                }); 

            } 

        } 
                   jQuery("#cnp_payment_method_selection_CreditCard").click(function(){
						 jQuery("#cnp_CreditCard_div").show();					
						 jQuery("#cnp_eCheck_div").hide();
						 jQuery("#cnp_Custompay_div").hide();
						 jQuery("#cnp_gpay_div").hide();
                           jQuery("#cnp_paypal_div").hide();
                              jQuery("#cnp_ba_div").hide();
                         
					     });
						 jQuery("#cnp_payment_method_selection_eCheck").click(function(){
						 jQuery("#cnp_CreditCard_div").hide();					
						 jQuery("#cnp_eCheck_div").show();
						 jQuery("#cnp_Custompay_div").hide();
						 jQuery("#cnp_gpay_div").hide();
                           jQuery("#cnp_paypal_div").hide();
                              jQuery("#cnp_ba_div").hide();
					     });
                          jQuery("#cnp_payment_method_selection_gpay").click(function(){
						 jQuery("#cnp_CreditCard_div").hide();					
						 jQuery("#cnp_eCheck_div").hide();
						 jQuery("#cnp_Custompay_div").hide();
						 jQuery("#cnp_gpay_div").show();
                           jQuery("#cnp_paypal_div").hide();
                              jQuery("#cnp_ba_div").hide();
					     });
                           jQuery("#cnp_payment_method_selection_paypal").click(function(){
                          
                           loadAsync("https://www.paypal.com/sdk/js?client-id='.$cnpclientid.'&intent=capture&currency='.$cnppaypalcurr.'&debug=false&enable-funding=venmo&buyer-country=US&disable-funding=paylater,credit,card&merchant-id='. $cnppaypalmerchantid.'", function() {
  paypal.Buttons({

 // Call your server to set up the transaction
            createOrder: function(data, actions) {
         
                return PayPalJBCreateOrder();
            },

            // Call your server to finalize the transaction
            onApprove: function(data, actions) {
            
            var jData = JSON.stringify(data);
            var pData = JSON.parse(jData);
            console.log(pData.orderID);
 			console.log(jData);
            jQuery("#paypal_paymentnumber").val(pData.orderID);
            var sbmtid ="wpjb-place-order";
         
            if(window[sbmtid]){return false;} 
            window[sbmtid]=true; 
            jQuery(".wpjb-place-order").trigger("click",[true]);
            
           
            }
  }).render("#paypal-button-container");
});

						 jQuery("#cnp_CreditCard_div").hide();					
						 jQuery("#cnp_eCheck_div").hide();
						 jQuery("#cnp_Custompay_div").hide();
						 jQuery("#cnp_gpay_div").hide();
                           jQuery("#cnp_paypal_div").show();
                            jQuery("#cnp_ba_div").hide();
					     });
                         jQuery("#cnp_payment_method_selection_ba").click(function(){
						 jQuery("#cnp_CreditCard_div").hide();					
						 jQuery("#cnp_eCheck_div").hide();
						 jQuery("#cnp_Custompay_div").hide();
						 jQuery("#cnp_gpay_div").hide();
                         jQuery("#cnp_paypal_div").hide();
                         jQuery("#cnp_ba_div").show();
					     });
                   </script> <script>
				   function displaysection(sec) {
				  
					if(sec == "CreditCard") {
						jQuery("#cnp_CreditCard_div").show();					
						jQuery("#cnp_eCheck_div").hide();
						jQuery("#cnp_Custompay_div").hide();
					    jQuery("#cnp_gpay_div").hide();
                        jQuery("#cnp_ba_div").hide();
                          jQuery("#cnp_paypal_div").hide();
						
					} else if(sec == "eCheck") {
						jQuery("#cnp_CreditCard_div").hide();					
						jQuery("#cnp_eCheck_div").show();
						jQuery("#cnp_Custompay_div").hide();
                        jQuery("#cnp_gpay_div").hide();
						jQuery("#cnp_ba_div").hide();
                          jQuery("#cnp_paypal_div").hide();
					}
                    else if(sec == "gpay") {
						jQuery("#cnp_CreditCard_div").hide();					
						jQuery("#cnp_gpay_div").show();
						jQuery("#cnp_Custompay_div").hide();
						jQuery("#cnp_ba_div").hide();
                       	jQuery("#cnp_eCheck_div").hide();
                          jQuery("#cnp_paypal_div").hide();
					}
                     else if(sec == "ba") {
						jQuery("#cnp_CreditCard_div").hide();					
						jQuery("#cnp_gpay_div").hide();
                        jQuery("#cnp_ba_div").show();
						jQuery("#cnp_Custompay_div").hide();
                       	jQuery("#cnp_eCheck_div").hide();
                          jQuery("#cnp_paypal_div").hide();
										 
					}
                    else if(sec == "paypal") {
                   
                    loadAsync("https://www.paypal.com/sdk/js?client-id='.$cnpclientid.'&intent=capture&currency='.$cnppaypalcurr.'&debug=false&enable-funding=venmo&buyer-country=US&disable-funding=paylater,credit,card&merchant-id='. $cnppaypalmerchantid.'", function() {
  paypal.Buttons({

 // Call your server to set up the transaction
            createOrder: function(data, actions) {
         
                return PayPalJBCreateOrder();
            },

            // Call your server to finalize the transaction
            onApprove: function(data, actions) {
            
            var jData = JSON.stringify(data);
            var pData = JSON.parse(jData);
            console.log(pData.orderID);
 			console.log(jData);
            jQuery("#paypal_paymentnumber").val(pData.orderID);
            var sbmtid ="wpjb-place-order";
         
            if(window[sbmtid]){return false;} 
            window[sbmtid]=true; 
            jQuery(".wpjb-place-order").trigger("click",[true]);
            
           
            }
  }).render("#paypal-button-container");
});
let inputBox = document.querySelector("#paypaltoken"); 

 

        observeElement(inputBox, "value", function (oldValue, newValue) { 

            if (newValue != "") { 

             
                jQuery(".paypal_paymentnumber").val(newValue);
              
				  var sbmtid ="wpjb-place-order";
                          if(window[sbmtid]){return false;} 
                          window[sbmtid]=true; 
                          jQuery(".wpjb-place-order").trigger("click",[true]);
                $(".paypal-checkout-sandbox").hide(); 

            } 

        }); 
						jQuery("#cnp_CreditCard_div").hide();					
						jQuery("#cnp_gpay_div").hide();
                        jQuery("#cnp_ba_div").hide();
						jQuery("#cnp_Custompay_div").hide();
                       	jQuery("#cnp_eCheck_div").hide();
                       jQuery("#cnp_paypal_div").show();
										 
					}
					 else {
						jQuery("#cnp_CreditCard_div").hide();					
						jQuery("#cnp_eCheck_div").hide();
						jQuery("#cnp_Custompay_div").show();
                        jQuery("#cnp_gpay_div").hide();
                        jQuery("#cnp_ba_div").hide();
						 jQuery("#cnp_paypal_div").hide();
					}
					
				}
				   </script>';
			}
			
		 // From here to check values 
		 
		$html .= $rechtml." ".$paymenthtml;
		 $cdivdisplay = ($defaultpayment == 'CreditCard') ? 'block' : 'none';
		 
         $html .= '<div style="display:'.$cdivdisplay.'; padding:10px 0px;" id="cnp_CreditCard_div" class="wpjb-form">';		
		 if($merchant['wpjobboard_clickandpledge_Paymentmethods_Amex'] !="")
		 {
			 $html .= "<img src='".WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__)) . "/images/amex.jpg' title='American Express' alt='American Express' style='display:inline-block;' />";;
		 }
		if($merchant['wpjobboard_clickandpledge_Paymentmethods_master'] !="")
		 {
			 $html .= " <img src='".WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__)) . "/images/mastercard.gif' title='MasterCard' alt='MasterCard' style='display:inline-block;' />";
		 }
		if($merchant['wpjobboard_clickandpledge_Paymentmethods_Visa'] !="")
		 {
			 $html .= " <img src='".WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__)) . "/images/visa.jpg' title='Visa' alt='Visa' style='display:inline-block;' />";
		 }
		if($merchant['wpjobboard_clickandpledge_Paymentmethods_Discover'] !="")
		 {
			 $html .= " <img src='".WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__)) . "/images/discover.jpg' title='Discover' alt='Discover' style='display:inline-block;' />";
		 }
		if($merchant['wpjobboard_clickandpledge_Paymentmethods_Jcb'] !="")
		 {
			 $html .= " <img src='".WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__)) . "/images/JCB.jpg' title='JCB' alt='JCB' style='display:inline-block;' />";
		 }
			$html .= '<div id="onetime_selection">
			<fieldset class="wpjb-fieldset-default">
			<div class="wpjb-element-input-text">
				<label class="wpjb-label">
				 '.__("Name On Card <span class='wpjb-required'>*</span>", "wpjobboard").'</label>
				<div class="cnpwpjb_field"> <input type="text" maxlength="50" data-clickandpledge="number" name="clickandpledge_nameOnCard" id="clickandpledge_nameOnCard" class=" required NameOnCard"  placeholder="Name On Card"/>
				 
			    </div>
			    <style>
				  #clickandpledge_cardExpMonth,#clickandpledge_cardExpYear
				  {
					  width :41%;
				  }
				</style>
		    </div>
		  
		  	<div class="wpjb-element-input-text">
				<label class="wpjb-label">
			  '.__("Card Number <span class='wpjb-required'>*</span>", "wpjobboard").'</label>
			  <div class="cnpwpjb_field"><input type="text" maxlength="17" minlength="15" data-clickandpledge="number" name="clickandpledge_cardNumber" id="clickandpledge_cardNumber"  class="required creditcard" placeholder="Card Number" required/></div>
			</div>

			<div class="wpjb-element-input-text">
			<label class="wpjb-label">
			  <span>'.__("CVV <span class='wpjb-required'>*</span>", "wpjobboard").'</span>	</label>		  
			 	<div class="cnpwpjb_field"> <input type="text" size="4" data-clickandpledge="cvc" maxlength="4" name="clickandpledge_cvc" id="clickandpledge_cvc" class="required Cvv2" placeholder="CVV" required/></div>
			</div>
		  
		  	<div class="wpjb-element-input-text">
			 <label class="wpjb-label"><span>'.__("Expiration <span class='wpjb-required'>*</span>", "wpjobboard").'</span></label>
			 <div class="cnpwpjb_field"><select name="clickandpledge_cardExpMonth" id="clickandpledge_cardExpMonth" class="required">'.$this->getMonths().'</select>
			 <span> / </span>
			 <select name="clickandpledge_cardExpYear" id="clickandpledge_cardExpYear" class="required" data-clickandpledge="exp-year">'.$this->getYears().'</select>
		   	 </div>
		   	</div>';
		
		$html .= '</fieldset></div></div>'; //CreditCard Div End

		
		$eCheckdivdisplay = ($defaultpayment == 'eCheck') ? 'block' : 'none';
		
		$html .= '<div style="display:'.$eCheckdivdisplay.'" id="cnp_eCheck_div">';
		$html .= "<p><img src='".WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__)) . "/images/eCheck.png' title='eCheck' alt='eCheck'/></p>";
		if($merchant['wpjobboard_clickandpledge_OrderMode'] == "test")
		{
			$html .= '<p class="" style="margin:0 0 10px;color:red;">eCheck does not support test transactions</p>';
		}
			$html .= '<fieldset class="wpjb-fieldset-default">
			<div class="wpjb-element-input-text">
				<label class="wpjb-label">
			  	'.__("Account Type <span class='wpjb-required'>*</span>", "wpjobboard").'</label>
			  	<div class="cnpwpjb_field">
			  	<select name="clickandpledge_echeck_AccountType" id="clickandpledge_echeck_AccountType" title="Account Type" >
					<option value="SavingsAccount">SavingsAccount</option>
					<option value="CheckingAccount">CheckingAccount</option>
			  	</select>			
		 		</div>
		 	</div>

		 	<div class="wpjb-element-input-text">
				<label class="wpjb-label">
			  	'.__("Name on Account <span class='wpjb-required'>*</span>", "wpjobboard").'</label>
			  	<div class="cnpwpjb_field">
			  	<input type="text" data-clickandpledge="number" name="clickandpledge_echeck_NameOnAccount" id="clickandpledge_echeck_NameOnAccount" class="required AccountNumber"  placeholder="Name on Account"/>			
		 		</div>
		 	</div>
			<div class="wpjb-element-input-text">
				<label class="wpjb-label">
			  	'.__("Type of ID <span class='wpjb-required'>*</span>", "wpjobboard").'</label>
			 	<div class="cnpwpjb_field"> <select name="clickandpledge_echeck_IdType" id="clickandpledge_echeck_IdType" title="Type of ID" >
					<option value="Driver">Driver</option>
					<option value="Military">Military</option>
					<option value="State">State</option>
			  	</select>			
		 		</div>
		 	</div>
			<div class="wpjb-element-input-text">
				<label class="wpjb-label">
			  	'.__("Check Type <span class='wpjb-required'>*</span>", "wpjobboard").'</label>
			  	<div class="cnpwpjb_field">
				  <select name="clickandpledge_echeck_CheckType" id="clickandpledge_echeck_CheckType" title="Check Type" >
						<option value="Company">Company</option>
						<option value="Personal">Personal</option>
				  </select>			
		 		</div>
		 	</div>
		 	<div class="wpjb-element-input-text">
		 		<label class="wpjb-label">
			  	'.__("Check Number <span class='wpjb-required'>*</span>", "wpjobboard").'</label>
			 	<div class="cnpwpjb_field"> <input type="text" data-clickandpledge="number" name="clickandpledge_echeck_CheckNumber" id="clickandpledge_echeck_CheckNumber" class="required CheckNumber"  placeholder="Check Number"/>
				</div>
			</div>
			<div class="wpjb-element-input-text">
				<label class="wpjb-label">
			  	'.__("Routing Number <span class='wpjb-required'>*</span>", "wpjobboard").'</label>
			  	<div class="cnpwpjb_field"><input type="text" data-clickandpledge="number" name="clickandpledge_echeck_RoutingNumber" id="clickandpledge_echeck_RoutingNumber" class="required RoutingNumber"  placeholder="Routing Number"/>
			  	</div>
			</div>
			<div class="wpjb-element-input-text">		
				<label class="wpjb-label">
			  	'.__("Account Number <span class='wpjb-required'>*</span>", "wpjobboard").'</label>
				<div class="cnpwpjb_field">
				<input type="text" data-clickandpledge="number" name="clickandpledge_echeck_AccountNumber" id="clickandpledge_echeck_AccountNumber" class="required AccountNumber"  placeholder="Account Number"/>			
		 		</div>
		 	</div>
			<div class="wpjb-element-input-text">
				<label class="wpjb-label">
			  	'.__("Retype Account Number <span class='wpjb-required'>*</span>", "wpjobboard").'</label>
			 	<div class="cnpwpjb_field"> <input type="text" data-clickandpledge="number" name="clickandpledge_echeck_retypeAccountNumber" id="clickandpledge_echeck_retypeAccountNumber" class="required AccountNumber" placeholder="Retype Account Number"/>			
		 		</div>
		 	</div>
		 </fieldset>		
			';			
		
		$html .= '</div></div>'; //eCheck Div End

$Gpaydivdisplay = ($defaultpayment == 'gpay') ? 'block' : 'none';
    
  
		$html .= '<div style="display:'.$Gpaydivdisplay.'" id="cnp_gpay_div">';
$html .= '<fieldset class="wpjb-fieldset-default">
			<script src="https://js.stripe.com/v3/"></script>
<div class="field" id="divsubmitDonation_stripePay" >
       <button type="button" class="navigation_button p-2 btn-gpay" id="submitDonation_stripePay" tabindex="" style="padding: 16px !important; max-width: 1750px; margin: auto;">&nbsp;</button>
</div>
<div class="field" id="divsubmitDonation_stripePay_link" >
<button id="submitDonation_stripePay_link" class="LinkButton" type="button" aria-label="Pay with Link" name="pay_with_link_arrow" >
<span class="LinkButton-inner">
<span class="LinkButton-text">
<span class="LinkButton-textContent">Pay with
<span class="LinkButton-logo--inlineAdjustment">
<svg class="InlineSVG LinkButton-logoSvg" focusable="false" viewBox="0 0 250 113.3" fill="none">
<path fill="#1D3944" d="M39.8 1.7C41.5.6 43.4 0 45.5 0c2.7 0 5.3 1.1 7.2 3 1.9 1.9 3 4.5 3 7.2 0 2-.6 4-1.7 5.7-1.1 1.7-2.7 3-4.6 3.8-1.9.8-3.9 1-5.9.6-2-.4-3.8-1.4-5.2-2.8-1.4-1.4-2.4-3.3-2.8-5.2-.4-2-.2-4 .6-5.9.7-2 2-3.5 3.7-4.7zM0 1.1h18.3v110.6H0V1.1zM247.2 32.7c-6.3 13.6-13.8 26.6-22.3 38.9l25.1 40h-21.6L213 87c-15.5 17.7-30.8 26.3-45.6 26.3-18 0-25.4-12.9-25.4-27.5V75.3c0-19.3-2-24.8-8.6-23.9-12.5 1.7-31.6 30.2-44 60.3H72.3v-79h18.3v39.5c10.4-17.6 20-32.7 35.4-38.5 8.9-3.4 16.5-1.9 20.4-.2 14.2 6.3 14.2 21.5 14 42v8.7c0 7.4 2.1 10.7 7.1 11.2 3 .3 6-.4 8.6-1.9V1.1h18.3v79.2s15.9-14.5 32.6-47.5h20.2zM54.6 32.8H36.3v78.9h18.3V32.8z"></path>
</svg>
</span>
<svg class="InlineSVG LinkButton-arrow" focusable="false" width="21" height="14" viewBox="0 0 21 14" fill="none">
<path d="M14.5247 0.219442C14.2317 -0.0733252 13.7568 -0.0731212 13.4641 0.219898C13.1713 0.512917 13.1715 0.98779 13.4645 1.28056L18.5 6.5L19 7L18.5 7.75C18 8.5 13.4645 12.7194 13.4645 12.7194C13.1715 13.0122 13.1713 13.4871 13.4641 13.7801C13.7568 14.0731 14.2317 14.0733 14.5247 13.7806L20.7801 7.53056C20.9209 7.38989 21 7.19902 21 7C21 6.80098 20.9209 6.61011 20.7801 6.46944L14.5247 0.219442Z" fill="#1D3944"></path>
<path d="M14 4L4 4" stroke="#1D3944" stroke-width="1.5" stroke-linecap="round"></path>
<path d="M14 4V1" stroke="#1D3944" stroke-width="1.5" stroke-linecap="round"></path>
<path d="M14 13V10" stroke="#1D3944" stroke-width="1.5" stroke-linecap="round"></path>
<path d="M4 9.25C3.58579 9.25 3.25 9.58579 3.25 10C3.25 10.4142 3.58579 10.75 4 10.75V9.25ZM14 9.25H4V10.75H14V9.25Z" fill="#1D3944"></path>
<path d="M1.00007 6.25C0.585853 6.24996 0.250037 6.58572 0.25 6.99993C0.249963 7.41415 0.58572 7.74996 0.999934 7.75L1.00007 6.25ZM14.0001 6.25115L1.00007 6.25L0.999934 7.75L13.9999 7.75115L14.0001 6.25115Z" fill="#1D3944"></path>
</svg>
</span>
</span>
</span>
</button>
</div>
		 </fieldset>
         ';		
	
				
		      $html .= '<input type="hidden"  name="wpjbgpaypymntintent" id="wpjbgpaypymntintent" class="gpay_paymentnumber">';	     

		$html .= '</div></div>
        <script>
         stripegpaycall(100);
 

function stripegpaycall(donationamount) {

    var pk = "pk_test_51JkXsiH8mU0lDjNKzvzyu2nqrhY34ZCg6FOd2N2ZNgnO50YnMbKSQE69d2aCVzs8fQ48w0Ez81YCWUnp0ingT10T00ncFMd3PN";
   
    var currencycodeiso = "usd";

    var stripe = Stripe(pk, {
        apiVersion: "2020-08-27",
    });

    paymentRequest = stripe.paymentRequest({
        country: "US",
        currency: currencycodeiso,
        total: {
            label: "Total Charge",
            amount: donationamount,
        },
        requestPayerName: true,
        requestPayerEmail: true,
    });

    var elements = stripe.elements();
    var prButton = elements.create("paymentRequestButton", {
        paymentRequest: paymentRequest,
    });

  
    // Check the availability of the Payment Request API first.
    paymentRequest.canMakePayment().then(function (result) {

        let button = document.getElementById("submitDonation_stripePay");

        if (result) {
            //OSBrowser();
            var apay = result["applePay"];
            var gpay = result["googlePay"];
            var link = result["link"];
            if (apay == true || gpay == true || link == true) {

            var apm_enabled = "true";
                if (apm_enabled == "true" || apm_enabled == "True") {
                    if (gpay == true) {
                     
                        jQuery("#divsubmitDonation_stripePay").css("display", "block");
                        jQuery("#divsubmitDonation_stripePay_link").css("display", "none");
                    }
                    else if (apay == true) {
                     
                        jQuery("#divsubmitDonation_stripePay").css("display", "none");
                        jQuery("#divsubmitDonation_stripePay_link").css("display", "none");

                      

                       
                    }
                    else if (link == true) {
                      
                        jQuery("#divsubmitDonation_stripePay").css("display", "none");
                        jQuery("#divsubmitDonation_stripePay_link").css("display", "block");
                    }
                    
                }
            } else {
              
                jQuery("#divsubmitDonation_stripePay").css("display", "none");
                jQuery("#divsubmitDonation_stripePay_link").css("display", "none");
            }
        }
        else {
            //OSBrowser();
            jQuery("#divsubmitDonation_stripePay").css("display", "none");
            jQuery("#divsubmitDonation_stripePay_link").css("display", "none");
        }
    });

}

</script>'; //Gpay Div End
    
  
    $badivdisplay = ($defaultpayment == 'ba') ? 'block' : 'none';
  $cnpOrganizationID = $merchant['wpjobboard_clickandpledge_AccountID'];
   $cnpaccountName =  $this->getCnPjbAccountName($cnpOrganizationID);

	 $cnptotamount = $listrow->price;
	 $html .= '<div style="display:'.$badivdisplay.'" id="cnp_ba_div">';
     $html .= '<fieldset class="wpjb-fieldset-default">
			<script src="https://js.stripe.com/v3/"></script>
            <script>jQuery(document).ready(function(){
jQuery("#submitDonation_ach_link_account").prop("disabled",true);

});</script>
          <div> <button type="button" '. $disablednone_text .' class="btn-block margin-top-10 btn btn-dark p-2 btn-achpay" id="submitDonation_ach_link_account" tabindex="" style="padding: 16px !important; max-width: 750px; margin: auto; color: #fff;cursor: pointer;" onclick="processClick1_StripeFinancialConnections(); ">
                                                        <svg width="50px" height="23px" viewBox="0 -0.5 17 17" version="1.1" style="vertical-align: text-bottom;"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" stroke="#000000">
                                                            <g stroke-width="0"></g>
                                                            <g stroke-linecap="round" stroke-linejoin="round"></g>
                                                            <g>
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <g transform="translate(1.000000, 0.000000)" fill="#ffffff">
                                                                        <path d="M15,14 L15,13 L13.998,13 L13.998,12 L13,12 L13,6.032 L12.031,6.032 L12.031,12 L10,12 L10,6.032 L9,6.032 L9,12 L7,12 L7,6.032 L6,6.032 L6,12 L4,12 L4,6.032 L3.01,6.032 L3,12 L2,12 L2,13 L1,13 L1,14 L0,14 L0,15 L16,15 L16,14 L15,14 Z" class="si-glyph-fill"></path>
                                                                        <path d="M2.021,6 L13.979,6 L13.979,5 L14.979,5 L14.979,4 L13.969,4 L8.031,0.094 L2.031,4 L1,4 L1,5 L2.021,5 L2.021,6 Z" class="si-glyph-fill"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>

                                                        <span style="font-size: 1.2em">Pay with Bank Account </span>
                                                    </button></div><div'.$disablednonel_text.' style="margin: 15px 0; vertical-align: middle;">
                                                    <input id="chkBankAccount" name="chkBankAccount" type="checkbox" class="" value="" ><label> By clicking [accept], you authorize
'.$cnpaccountName.'
to debit the bank account specified for any amount owed for charges arising from this charge. You may amend or cancel this authorization at any time by providing notice to
'.$cnpaccountName.'
with 30 (thirty) days notice.</label></div>
<div id="bankreccnt" style="display: none; margin: 15px 0; vertical-align: middle;">You further authorize
'.$cnpaccountName.'
to debit your bank account as part of your recurring commitment. Payments that fall outside of the regular debits authorized above will only be debited after your authorization is obtained</div>
		 </fieldset>';		
		
				
		      $html .= '<input type="hidden"  name="wpjbbapymntintent" id="wpjbbapymntintent" class="ba_paymentnumber">';	     

		$html .= '</div></div>'; //BA Div End
    
 $paypaldivdisplay = ($defaultpayment == 'paypal') ? 'block' : 'none';
  
    
   	$html .= '<div style="display:'.$paypaldivdisplay.'" id="cnp_paypal_div">';
    
     $cnpclientid = "AbnZ3lA18tFW9CwySaLTk2SqYuYyG03r-fjlpCLwCtYhbKbuBFVdU3dXRFEqOw23KDeOz40sbdVqQN5B";
     $cnppaypalmerchantid = $merchant['wpjobboard_clickandpledge_Paymentmethods_paypalmid'];
     $cnpselaccid = $merchant['wpjobboard_clickandpledge_AccountID'];
 
    $cnppaypalcurr =  $this->getCnPjbCurrency($cnpselaccid);
  


 $input .= '<script src="https://www.paypal.com/sdk/js?client-id='.$cnpclientid.'&intent=capture&currency='.$cnppaypalcurr.'&debug=false&enable-funding=venmo&buyer-country=US&disable-funding=paylater,credit,card&merchant-id='.$cnppaypalmerchantid.'" data-namespace="paypal_one_time" data-partner-attribution-id="Clickandpledge_Cart" data-csp-nonce="xyz-123"></script><div id="paypal-button-container" style="max-width: 750px; margin: auto;"></div>';
         
 $html .= ' <fieldset class="wpjb-fieldset-default">
 
    <div id="paypal-button-container" style="max-width: 750px; margin: auto;"></div>
    <div id="paypal-button-container2" style="display: none; max-width: 750px; margin: auto;">
    <button type="button" onclick="PayPalRecurring();" class="btn-block margin-top-10 btn p-2 btn-paypal" id="submitDonation_PayPay" tabindex="" '.$disablednonel_text.'>
    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAxcHgiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAxMDEgMzIiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaW5ZTWluIG1lZXQiIHhtbG5zPSJodHRwOiYjeDJGOyYjeDJGO3d3dy53My5vcmcmI3gyRjsyMDAwJiN4MkY7c3ZnIj48cGF0aCBmaWxsPSIjMDAzMDg3IiBkPSJNIDEyLjIzNyAyLjggTCA0LjQzNyAyLjggQyAzLjkzNyAyLjggMy40MzcgMy4yIDMuMzM3IDMuNyBMIDAuMjM3IDIzLjcgQyAwLjEzNyAyNC4xIDAuNDM3IDI0LjQgMC44MzcgMjQuNCBMIDQuNTM3IDI0LjQgQyA1LjAzNyAyNC40IDUuNTM3IDI0IDUuNjM3IDIzLjUgTCA2LjQzNyAxOC4xIEMgNi41MzcgMTcuNiA2LjkzNyAxNy4yIDcuNTM3IDE3LjIgTCAxMC4wMzcgMTcuMiBDIDE1LjEzNyAxNy4yIDE4LjEzNyAxNC43IDE4LjkzNyA5LjggQyAxOS4yMzcgNy43IDE4LjkzNyA2IDE3LjkzNyA0LjggQyAxNi44MzcgMy41IDE0LjgzNyAyLjggMTIuMjM3IDIuOCBaIE0gMTMuMTM3IDEwLjEgQyAxMi43MzcgMTIuOSAxMC41MzcgMTIuOSA4LjUzNyAxMi45IEwgNy4zMzcgMTIuOSBMIDguMTM3IDcuNyBDIDguMTM3IDcuNCA4LjQzNyA3LjIgOC43MzcgNy4yIEwgOS4yMzcgNy4yIEMgMTAuNjM3IDcuMiAxMS45MzcgNy4yIDEyLjYzNyA4IEMgMTMuMTM3IDguNCAxMy4zMzcgOS4xIDEzLjEzNyAxMC4xIFoiPjwvcGF0aD48cGF0aCBmaWxsPSIjMDAzMDg3IiBkPSJNIDM1LjQzNyAxMCBMIDMxLjczNyAxMCBDIDMxLjQzNyAxMCAzMS4xMzcgMTAuMiAzMS4xMzcgMTAuNSBMIDMwLjkzNyAxMS41IEwgMzAuNjM3IDExLjEgQyAyOS44MzcgOS45IDI4LjAzNyA5LjUgMjYuMjM3IDkuNSBDIDIyLjEzNyA5LjUgMTguNjM3IDEyLjYgMTcuOTM3IDE3IEMgMTcuNTM3IDE5LjIgMTguMDM3IDIxLjMgMTkuMzM3IDIyLjcgQyAyMC40MzcgMjQgMjIuMTM3IDI0LjYgMjQuMDM3IDI0LjYgQyAyNy4zMzcgMjQuNiAyOS4yMzcgMjIuNSAyOS4yMzcgMjIuNSBMIDI5LjAzNyAyMy41IEMgMjguOTM3IDIzLjkgMjkuMjM3IDI0LjMgMjkuNjM3IDI0LjMgTCAzMy4wMzcgMjQuMyBDIDMzLjUzNyAyNC4zIDM0LjAzNyAyMy45IDM0LjEzNyAyMy40IEwgMzYuMTM3IDEwLjYgQyAzNi4yMzcgMTAuNCAzNS44MzcgMTAgMzUuNDM3IDEwIFogTSAzMC4zMzcgMTcuMiBDIDI5LjkzNyAxOS4zIDI4LjMzNyAyMC44IDI2LjEzNyAyMC44IEMgMjUuMDM3IDIwLjggMjQuMjM3IDIwLjUgMjMuNjM3IDE5LjggQyAyMy4wMzcgMTkuMSAyMi44MzcgMTguMiAyMy4wMzcgMTcuMiBDIDIzLjMzNyAxNS4xIDI1LjEzNyAxMy42IDI3LjIzNyAxMy42IEMgMjguMzM3IDEzLjYgMjkuMTM3IDE0IDI5LjczNyAxNC42IEMgMzAuMjM3IDE1LjMgMzAuNDM3IDE2LjIgMzAuMzM3IDE3LjIgWiI+PC9wYXRoPjxwYXRoIGZpbGw9IiMwMDMwODciIGQ9Ik0gNTUuMzM3IDEwIEwgNTEuNjM3IDEwIEMgNTEuMjM3IDEwIDUwLjkzNyAxMC4yIDUwLjczNyAxMC41IEwgNDUuNTM3IDE4LjEgTCA0My4zMzcgMTAuOCBDIDQzLjIzNyAxMC4zIDQyLjczNyAxMCA0Mi4zMzcgMTAgTCAzOC42MzcgMTAgQyAzOC4yMzcgMTAgMzcuODM3IDEwLjQgMzguMDM3IDEwLjkgTCA0Mi4xMzcgMjMgTCAzOC4yMzcgMjguNCBDIDM3LjkzNyAyOC44IDM4LjIzNyAyOS40IDM4LjczNyAyOS40IEwgNDIuNDM3IDI5LjQgQyA0Mi44MzcgMjkuNCA0My4xMzcgMjkuMiA0My4zMzcgMjguOSBMIDU1LjgzNyAxMC45IEMgNTYuMTM3IDEwLjYgNTUuODM3IDEwIDU1LjMzNyAxMCBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA2Ny43MzcgMi44IEwgNTkuOTM3IDIuOCBDIDU5LjQzNyAyLjggNTguOTM3IDMuMiA1OC44MzcgMy43IEwgNTUuNzM3IDIzLjYgQyA1NS42MzcgMjQgNTUuOTM3IDI0LjMgNTYuMzM3IDI0LjMgTCA2MC4zMzcgMjQuMyBDIDYwLjczNyAyNC4zIDYxLjAzNyAyNCA2MS4wMzcgMjMuNyBMIDYxLjkzNyAxOCBDIDYyLjAzNyAxNy41IDYyLjQzNyAxNy4xIDYzLjAzNyAxNy4xIEwgNjUuNTM3IDE3LjEgQyA3MC42MzcgMTcuMSA3My42MzcgMTQuNiA3NC40MzcgOS43IEMgNzQuNzM3IDcuNiA3NC40MzcgNS45IDczLjQzNyA0LjcgQyA3Mi4yMzcgMy41IDcwLjMzNyAyLjggNjcuNzM3IDIuOCBaIE0gNjguNjM3IDEwLjEgQyA2OC4yMzcgMTIuOSA2Ni4wMzcgMTIuOSA2NC4wMzcgMTIuOSBMIDYyLjgzNyAxMi45IEwgNjMuNjM3IDcuNyBDIDYzLjYzNyA3LjQgNjMuOTM3IDcuMiA2NC4yMzcgNy4yIEwgNjQuNzM3IDcuMiBDIDY2LjEzNyA3LjIgNjcuNDM3IDcuMiA2OC4xMzcgOCBDIDY4LjYzNyA4LjQgNjguNzM3IDkuMSA2OC42MzcgMTAuMSBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA5MC45MzcgMTAgTCA4Ny4yMzcgMTAgQyA4Ni45MzcgMTAgODYuNjM3IDEwLjIgODYuNjM3IDEwLjUgTCA4Ni40MzcgMTEuNSBMIDg2LjEzNyAxMS4xIEMgODUuMzM3IDkuOSA4My41MzcgOS41IDgxLjczNyA5LjUgQyA3Ny42MzcgOS41IDc0LjEzNyAxMi42IDczLjQzNyAxNyBDIDczLjAzNyAxOS4yIDczLjUzNyAyMS4zIDc0LjgzNyAyMi43IEMgNzUuOTM3IDI0IDc3LjYzNyAyNC42IDc5LjUzNyAyNC42IEMgODIuODM3IDI0LjYgODQuNzM3IDIyLjUgODQuNzM3IDIyLjUgTCA4NC41MzcgMjMuNSBDIDg0LjQzNyAyMy45IDg0LjczNyAyNC4zIDg1LjEzNyAyNC4zIEwgODguNTM3IDI0LjMgQyA4OS4wMzcgMjQuMyA4OS41MzcgMjMuOSA4OS42MzcgMjMuNCBMIDkxLjYzNyAxMC42IEMgOTEuNjM3IDEwLjQgOTEuMzM3IDEwIDkwLjkzNyAxMCBaIE0gODUuNzM3IDE3LjIgQyA4NS4zMzcgMTkuMyA4My43MzcgMjAuOCA4MS41MzcgMjAuOCBDIDgwLjQzNyAyMC44IDc5LjYzNyAyMC41IDc5LjAzNyAxOS44IEMgNzguNDM3IDE5LjEgNzguMjM3IDE4LjIgNzguNDM3IDE3LjIgQyA3OC43MzcgMTUuMSA4MC41MzcgMTMuNiA4Mi42MzcgMTMuNiBDIDgzLjczNyAxMy42IDg0LjUzNyAxNCA4NS4xMzcgMTQuNiBDIDg1LjczNyAxNS4zIDg1LjkzNyAxNi4yIDg1LjczNyAxNy4yIFoiPjwvcGF0aD48cGF0aCBmaWxsPSIjMDA5Y2RlIiBkPSJNIDk1LjMzNyAzLjMgTCA5Mi4xMzcgMjMuNiBDIDkyLjAzNyAyNCA5Mi4zMzcgMjQuMyA5Mi43MzcgMjQuMyBMIDk1LjkzNyAyNC4zIEMgOTYuNDM3IDI0LjMgOTYuOTM3IDIzLjkgOTcuMDM3IDIzLjQgTCAxMDAuMjM3IDMuNSBDIDEwMC4zMzcgMy4xIDEwMC4wMzcgMi44IDk5LjYzNyAyLjggTCA5Ni4wMzcgMi44IEMgOTUuNjM3IDIuOCA5NS40MzcgMyA5NS4zMzcgMy4zIFoiPjwvcGF0aD48L3N2Zz4" data-v-b01da731="" alt="" role="presentation" class="paypal-logo paypal-logo-paypal paypal-logo-color-blue" style="height: 24px;"></button></div>
       ';		
	 $html .= '<input type="hidden"  name="paypal_paymentnumber" id="paypal_paymentnumber" class="paypal_paymentnumber">
     <input type="hidden"  name="clickandpledge_listing_id" id="clickandpledge_listing_id" class="clickandpledge_listing_id" value ='.$_POST["defaults"]["pricing_id"].'>
     <input type="hidden"  name="clickandpledge_coupon_code" id="clickandpledge_coupon_code" class="clickandpledge_coupon_code" value ='.$_POST["discount"].'><input id="paypaltoken" type="hidden" />
              
              <script> 
       

    
             
function loadAsync(url, callback) {
  var s = document.createElement("script");
  s.setAttribute("src", url); s.onload = callback;
  document.head.insertBefore(s, document.head.firstElementChild);
}
loadAsync("https://www.paypal.com/sdk/js?client-id='.$cnpclientid.'&intent=capture&currency='.$cnppaypalcurr.'&debug=false&enable-funding=venmo&buyer-country=US&disable-funding=paylater,credit,card&merchant-id='. $cnppaypalmerchantid.'", function() {
  paypal.Buttons({

 // Call your server to set up the transaction
            createOrder: function(data, actions) {
         
                return PayPalJBCreateOrder();
            },

            // Call your server to finalize the transaction
            onApprove: function(data, actions) {
            
            var jData = JSON.stringify(data);
            var pData = JSON.parse(jData);
            console.log(pData.orderID);
 			console.log(jData);
            jQuery("#paypal_paymentnumber").val(pData.orderID);
            var sbmtid ="wpjb-place-order";
         
            if(window[sbmtid]){return false;} 
            window[sbmtid]=true; 
            jQuery(".wpjb-place-order").trigger("click",[true]);
            
           
            }
  }).render("#paypal-button-container");
});

 
        let inputBox = document.querySelector("#paypaltoken"); 

 

        observeElement(inputBox, "value", function (oldValue, newValue) { 

            if (newValue != "") { 

             
                jQuery(".paypal_paymentnumber").val(newValue);
              
				  var sbmtid ="wpjb-place-order";
                          if(window[sbmtid]){return false;} 
                          window[sbmtid]=true; 
                          jQuery(".wpjb-place-order").trigger("click",[true]);
                $(".paypal-checkout-sandbox").hide(); 

            } 

        }); 

 

        function observeElement(element, property, callback, delay = 0) { 

            let elementPrototype = Object.getPrototypeOf(element); 

            if (elementPrototype.hasOwnProperty(property)) { 

                let descriptor = Object.getOwnPropertyDescriptor(elementPrototype, property); 

                Object.defineProperty(element, property, { 

                    get: function () { 

                        return descriptor.get.apply(this, arguments); 

                    }, 

                    set: function () { 

                        let oldValue = this[property]; 

                        descriptor.set.apply(this, arguments); 

                        let newValue = this[property]; 

                        if (typeof callback == "function") { 

                            setTimeout(callback.bind(this, oldValue, newValue), delay); 

                        } 

                        return newValue; 

                    } 

                }); 

            } 

        } 

    </script>  </fieldset> ';	   

		$html .= '</div>';  //paypal Div End
    
		$customtypes = explode(";",$merchant['wpjobboard_clickandpledge_titles']);
		foreach($customtypes as $customval) {
		
		}
		
		if(($defaultpayment != 'CreditCard') && ($defaultpayment != 'eCheck')  && ($defaultpayment != 'gpay') && ($defaultpayment != 'ba') && ($defaultpayment != 'paypal') ) $Custompaydivdisplay = 'block'; else $Custompaydivdisplay = 'none';
		$html .= '<div style="display:'.$Custompaydivdisplay.'" id="cnp_Custompay_div" class="wpjb-form">';
		$html .= '
		<fieldset class="wpjb-fieldset-default">
		<div class="wpjb-element-input-text">
			<label class="wpjb-label">
			'.__(" ".$merchant['wpjobboard_clickandpledge_reference']."  <span class='wpjb-required'>*</span>", "wpjobboard").'</span></label>
			<div class="cnpwpjb_field"><input type="text" name="clickandpledge_reference_number" id="clickandpledge_reference_number" class="reference_number"  placeholder="Enter Reference number"/>
			</div>		
		</div>
		</fieldset>
		';
		
		 //PurchaseOrder Div End
		
		$html .= '</div><div id="hidden-fileds">';
		
			$listing_id = '';
			
			$listing_id = wp_unslash(sanitize_text_field($_POST['defaults']['pricing_id']));
		    $amount = ($this->_data->payment_paid)*100;
            $currency = strtolower($this->_data->payment_currency);
			$varArray = array(
				'clickandpledge_AccountID'=>$merchant['wpjobboard_clickandpledge_AccountID'],
				'clickandpledge_AccountGuid'=>$merchant['wpjobboard_clickandpledge_AccountGuid'],
				'clickandpledge_OrderMode' => $merchant['wpjobboard_clickandpledge_OrderMode'],
				'clickandpledge_Amount' => $this->_data->payment_paid,
				'clickandpledge_Discount' => $this->_data->payment_discount,
				'clickandpledge_TermsCondition' => htmlspecialchars($merchant['wpjobboard_clickandpledge_TermsCondition']),
				'clickandpledge_installments' => $merchant['clickandpledge_installments'],
				'clickandpledge_email_customer' => (is_array($merchant['wpjobboard_clickandpledge_emailcustomer']) && count($merchant['wpjobboard_clickandpledge_emailcustomer']) > 0) ? $merchant['wpjobboard_clickandpledge_emailcustomer'][0] : '',
				'clickandpledge_maxrecurrings_Subscription' => $merchant['wpjobboard_clickandpledge_maxrecurrings_Subscription'],
				'clickandpledge_maxrecurrings_Installment' => $merchant['wpjobboard_clickandpledge_maxrecurrings_Installment'],
				'wpjobboard_clickandpledge_reference' => $merchant['wpjobboard_clickandpledge_reference'],
				'wpjobboard_clickandpledge_ConnectCampaignAlias' => $merchant['wpjobboard_clickandpledge_ConnectCampaignAlias'],
				'wpjobboard_clickandpledge_PaymentRecurring' => $merchant['wpjobboard_clickandpledge_PaymentRecurring'],
				'wpjobboard_clickandpledge_DefaultpaymentOptions' => $merchant['wpjobboard_clickandpledge_DefaultpaymentOptions'],
				'wpjobboard_clickandpledge_Paymentoptions' => $merchant['wpjobboard_clickandpledge_Paymentoptions'],
				'wpjobboard_clickandpledge_termsandconditionsadmin' => htmlspecialchars($merchant['wpjobboard_clickandpledge_termsandconditionsadmin']),
				'wpjobboard_clickandpledge_receiptsettings' => htmlspecialchars($merchant['wpjobboard_clickandpledge_receiptsettings']),
				
				'order' => $merchant['order'],
				'selectedcurrency' => $selectedCurrency,
								
				'wpjobboard_clickandpledge_emailcustomer' => $merchant['wpjobboard_clickandpledge_emailcustomer'],
				'clickandpledge_listing_id' => $listing_id,
				'clickandpledge_coupon_code' => (isset($_POST['discount'])) ? wp_unslash(sanitize_text_field($_POST['discount'])) : '',
			);
			
			foreach($varArray as $k=>$v)
			{
				$html.= '<input type="hidden" name="'.$k.'" id="'.$k.'" value="'.$v.'" />';
			}
		//eCheck Div End
		 $html .= '</div></fieldset>'; 
	// Ending custom payments 
	  echo $html;
  return $html;
 }

}
?>
