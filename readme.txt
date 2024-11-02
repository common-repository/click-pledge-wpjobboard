=== WordPress-WPJobBoard ===
Contributors: ClickandPledge
Plugin Name: Click & Pledge WPJobBoard
Plugin URI: http://clickandpledge.com/
Author URI: http://clickandpledge.com/
Tags: WPJobBoard, Jobboard, cnp, clickandpledge, payment gateway, payment module,  online payments, Click & Pledge, Click&Pledge, Click, Pledge, Salesforce, Payment, Kamran
Requires at least: 5.0
Tested up to     : 6.6.1
Stable tag       : 4.24080000-WP6.6.1-JB5.11.2
Version          : 4.24080000-WP6.6.1-JB5.11.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

The Click & Pledge WBJobBoard plugin is a 3rd party add-on to the WPJobBoard plugin.  The plugin allows the Site Owner to embed a payment form to process payments via Visa, American Express, Discover, and Mastercard through their Click & Pledge Merchant Account.

Payment form data posts via an encrypted connection from your SSL-secured site to 
<a href="https://paas.cloud.clickandpledge.com/PaymentService.svc?wsdl target="_blank">https://paas.cloud.clickandpledge.com/PaymentService.svc?wsdl</a>,
Click & Pledge’s Payment Application Interface (PAI) for the Trio payment and administrative engine. 

Available posting methods are 
* Operation
* OperationBase64Encode
* Echo

Security Details are available at:
<a href="https://clickandpledge.com/about/our-security/"  target="_blank">
https://clickandpledge.com/about/our-security/ </a>

Review the Click & Pledge Privacy Statement at:
<a href="https://clickandpledge.com/privacy/" target="_blank">
https://clickandpledge.com/privacy/ </a>


= About Click & Pledge =
Click & Pledge is a Level 1 PCI DSS-certified payment services provider offering products to over 20,000 clients worldwide.

== Installation ==
* Download the plugin.
* Extract the plugin
* Upload the Plugin folder to the wp-content/plugins folder in the WordPress directory online, using an FTP program.
* Go to the Plugins screen in the admin area and find the newly uploaded Plugin in the list.
* Activate Plugin
* Click on the Activate link to activate the plugin.
* Once activated, the plugin may be reviewed at:  Settings(WPJB)->Configuration (Payment Methods Section)

Add your WordPress Site URL to Click & Pledge "Allowed URLS"
   Login into Click & Pledge Connect, the online interface for a Click & Pledge account
   Navigate to <a href="https://login.connect.clickandpledge.com/Settings" target="_blank"> Settings </a> - API Information.
 

== Required Settings ==

* Account Number
* Account GUID
* Account Type (USD, EUR, CAD, GBP, or HKD)

To locate Account Number and API Account GUID:
   Login into Click & Pledge Connect, the online interface for a Click & Pledge account
   Navigate to <a href="https://login.connect.clickandpledge.com/Settings" target="_blank"> Settings </a> - API Information.


= Minimum Requirements =

* WordPress 3.3 (it will work with WordPress 3.0 but 3.3 version is recommended)PHP 5.2.4 or greater
* PHP 5.2.0
* MySQL 5.0+
* Hosting Site secured via SSL
* Click & Pledge Account

== Frequently Asked Questions ==

* Where can I find JobBoard documentation and user guides?

http://wpjobboard.net/kb/installation-and-activation/

* Where can I found the Click & Pledge documentation and user guides? 

http://manual.clickandpledge.com/WPJobBoard.html

== Changelog ==
= 4.24080000-WP6.6.1-JB5.11.2 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 4.23100100-WP6.3.2-JB5.10.0 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 4.22100000-WP6.0.3-JB5.8.8 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 4.22030000-WP5.9.1-JB5.8.3 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 04.2104000000-WP5.7.1-JB5.7.5 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 04.210100000-WP5.6-JB5.7.1 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 03.2006010000-WP5.4.2-JB5.6.2 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 03.2003000000-WP5.3.2-JB5.5.3 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 2.000.003 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 2.000.002 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 2.000.001 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 2.000.000 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard/42000-release-notes

= 1.0.0  =
* Release for public

= Resources =

* <a href="https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/wordpress-plugins/wpjobboard" target="_blank">WPJobBoard Click & Pledge Forum</a>
* <a href="https://manual.clickandpledge.com/WPJobBoard.html" target="_blank">Integration Manual WPJobBoard Click & Pledge</a>

== Upgrade Notice ==