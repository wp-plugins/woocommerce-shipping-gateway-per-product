=== Woocommerce Product Shippings per Product ===
Contributors: dreamfox
Donate link: http://www.dreamfox.nl
Tags: woocommerce,shipping,plugin,free
Requires at least: 3.0.1
Tested up to: 3.8
Stable tag: 1.1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin for woocommerce lets you select the available shippings for each individual product. 

== Description ==

This plugin for woocommerce lets you select the available shippings for each individual product.
You can select for eacht individual product the shipping that will be used by checkout. If no selection is made, then the default shippings are displayed. If you for example only select paypal then only paypal will available for that product by checking out.
Works on Woocommerce 2.0.13 - Woocommerce 2.1.6

This version is limited to set different shippings for 10 product.
For a small fee you can get the full version with no limitation at: <a href="http://www.dreamfox.nl" target="_blank">www.dreamfox.nl</a>.

<b>other plugins from Dreamfox:</b><br>
Payment gateway per product:  <a href="http://wordpress.org/plugins/woocommerce-product-payments/" target="_blank">Information</a> - <a href="http://wordpress.org/plugins/woocommerce-product-payments/" target="_blank">Free version</a> - <a href="http://www.dreamfox.nl/shop/payment-gateway-per-product-woocommerce/" target="_blank">Full version</a>

== Installation ==

= For automatic installation: =

The simplest way to install is to click on 'Plugins' then 'Add' and type 'Woocommerce Product Shippings' in the search field.

= For manual installation 1: =

1. Login to your website and go to the Plugins section of your admin panel.
1. Click the Add New button.
1. Under Install Plugins, click the Upload link.
1. Select the plugin zip file (woocommerce-product-shippings.x.x.x.zip) from your computer then click the Install Now button.
1. You should see a message stating that the plugin was installed successfully.
1. Click the Activate Plugin link.

= For manual installation 2: =

1. You should have access to the server where WordPress is installed. If you don't, see your system administrator.
1. Copy the plugin zip file (woocommerce-product-shippings.x.x.x.zip) up to your server and unzip it somewhere on the file system.
1. Copy the "woocommerce-product-shippings" folder into the /wp-content/plugins directory of your WordPress installation.
1. Login to your website and go to the Plugins section of your admin panel.
1. Look for "woocommerce-product-shippings" and click Activate.
1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==

= What happens if I add more then one product in the shoppingcard with different selected shippings? =

Allowed shippings goes before denied shippings. so if for example you set:
- product 1 to free
- product 2 to free & UPS
Then 'free' shippings are shown by checkout

= More Information =

For more information, feel free to visit the official website for this plugin: <a href="http://www.dreamfox.nl" target="_blank">Dreamfox</a>.


== Screenshots ==

1. screenshot1.png
2. screenshot2.png
3. screenshot3.png

== Changelog === 
= 1.1.5 =
* Restrict to save for autosave & revision

= 1.1.4 =
* Tested on Woocommerce 2.1.2

= 1.1.3 =
* Remove duplicate added files

= 1.1.2 =
* Fix conflicting with wp_mandrill plugin
 
= 1.1.1 =
* Fix bugs for multiple products on cart

= 1.1.0 =
* Fix bugs for shipping avaibality

= 1.0.9 =
* Fix country not available issue on switching settings

= 1.0.8 =
* replace deprecated functions with stable &  
* remove warnings

= 1.0.7 =
* add readme.txt file and fixes

= 1.0.6 =
* Rename default function to avoid confliction with function.php

= 1.0.5 =
* Fix Coding and flush

= 1.0.4 =
* Tested on Woocommerce 2.0.17

= 1.0.3 =
* add limit

= 1.0.2 =
* fixed typo

= 1.0.1 =
* added author

== Upgrade notice ==

= 1.0 =
Upgrade for new version

= 0.5 =
This version fixes a bug.  Upgrade immediately.
