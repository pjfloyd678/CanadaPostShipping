=== Canada Post Shipping For WooCommerce ===
Contributors: smallfishes
Tags: shipping, canada post, canada post shipping, shipping rates, woocommerce
Requires at least: 3.0.1
Tested up to: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Add Canada Post as a shipping option for your customers

== Description ==

Canada Post Shipping For WooCommerce is a plugin that adds support to WooCommerce for Canada Post. 

After installing the plugin your customers will have the option to choose Canada Post as a shipping method 
during the checkout. The plugin automatically calculates both letter and parcel shipping rates for your customers 
by using the weights you provide on each of your products.

If you need help with the plugin please feel free to email at mike@smallfishanalytics.com.

More detailed instructions and help documents can be found on the 
[Small Fish Analytics](http://www.smallfishanalytics.com/support) website.

== Installation ==

1. Download the plugin and activate it
2. Go to WooCommerce > Settings > Shipping
3. Enable the Canada Post shipping method
4. Enter your postal code if you want to enable parcel shipping

== Screenshots ==

1. Settings Page
2. Shipping Quotes

== Changelog ==
= 2.9.3 = 
Fixed a bug where some items were not showing letter rate when they should be due to size
Declare support for WooCommerce 4.9

= 2.9.2 = 
Removed check for WooCommerce being active during plugin activation due to error on multi site installs

= 2.9.1 =
Fix small bug that was allowing letter rate to show even when letter rate was disabled

= 2.9.0 =
Declare official suppot for WooCommerce 4.8 and WordPress 5.6
Add additional code to ensure thicker items won't quote letter rate

= 2.8.7 =
Declare official support for WooCommerce 4.5 and WordPress 5.5

= 2.8.6 =
Bug fix for weights less than zero causing shipping not to show

= 2.8.5 =
Small bug fix to avoid a warning showing when adding items to cart

= 2.8.4 =
Declare official support for WooCommerce 4.2.0

= 2.8.3 =
Declare official support for WooCommerce 4.0.1
Declare official support for WordPress 5.4
Update stamp rates for Canada / US / International 

= 2.8.2 =
Declare official support for WooCommerce 3.9.2

= 2.8.1 =
Declare official support for WooCommerce 3.8.0

= 2.8.0 =
Declare official support for WordPress 5.2
Declare official support for WooCommerce 3.6.4

= 2.7.0 =
Fixed an issue where clicking to disable the shipping method wasn't working
Added a check to ensure entered API keys are valid to help avoid confusion
Added a check on the source postal code being entered
Added a note about shipping zones

= 2.6.0 =
Fixed an issue where downloadable and virtual producs were triggering weight warnings on the settings page.
Declare latest WordPress and WooCommerce version support.

= 2.5.2 =
Fixed an issue where very light weight packages weren't calculating shipping correctly.

= 2.5.1 =
Fixed an issue where the plugin was warning weights were missing when they weren't in some cases. Also showing example products without weights now.

= 2.5.0 =
Added audits for stores that don't have weights entered on their products.

= 2.4.1 =
Declare support for WooCommerce 3.4

= 2.4.0 =
Update support site and email details
Better handling of fuel surcharge

= 2.3.0 =
Made a change to include the fuel surcharge in the shipping quote as requested by users.

= 2.2.0 =
Made a change so shipping taxes are calculated according to the settings in WooCommerce.
Fixed a PHP warning reported by a user.

= 2.1.0 =
Added new settings fields for people to use their own API keys to avoid potential issues with performance and reliability.

= 2.0.3 =
Fixed a PHP 7 warning

= 2.0.2 =
Fixed a bug where we forgot include_once and the classes were being included more than once with recent version of WooCommerce

= 2.0.1 =
Fixed a bug with imperial weight conversions breaking the API calls.

= 2.0.0 =
Added the Canada Post API integration to the plugin.

= 1.0.3 =
Test with WordPress 4.4 and update readme.txt.

= 1.0.2 =
Support for multiple weights and dimension units in WooCommerce.

= 1.0.1 =
Modified content on the settings page.

= 1.0.0 =
Initial Release
