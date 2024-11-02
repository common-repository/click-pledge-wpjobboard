<?php
/*
Plugin Name: Click & Pledge WPJobBoard
Plugin URI: http://manual.clickandpledge.com/WPJobBoard.html
Description: This plugin is to integrate WPJobBoard Click & Pledge Payment system. Please contact Click & Pledge for assistance, support@clickpledge.com
Author: Click & Pledge
Version: 4.24080000-WP6.6.1-JB5.11.2
Author URI: http://manual.clickandpledge.com/
*/
define( 'CNPWJ_PLUGIN_UID', "14059359-D8E8-41C3-B628-E7E030537905");
define( 'CNPWJ_PLUGIN_SKY', "5DC1B75A-7EFA-4C01-BDCD-E02C536313A3");
define('CNPWJ_PLUGIN_PayPalKeyLive', '45CC9362-04B0-41C5-94B7-D0DA2EAE6754');
define('CNPWJ_PLUGIN_GpayKeyLive', '49f64b6350f89ad4412ca84535316453b7754ccb');
define('CNPWJ_PLUGIN_APM_EndPointLive', 'https://api.cloud.clickandpledge.com'); 
define('CNPWJ_PLUGIN_APM_EndPointTest', 'https://api.cloud.clickandpledge.com');

function wpjb_payment_clickandpledge($list) {
  global $wpjobboard;
 
  include_once dirname(__FILE__)."/clickandpledge_payment.class.php";
  $cnp = new Payment_ClickandPledge();
  // registers new payment method
  $list[$cnp->getEngine()] = get_class($cnp);
  return $list;
}

add_filter('wpjb_payments_list', 'wpjb_payment_clickandpledge');