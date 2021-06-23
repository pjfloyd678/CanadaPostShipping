<?php
/**
* Plugin Name: Canada Post Shipping For WooCommerce
* Description: Integrates with Canada Post allowing you to provide accurate shipping quotes for your customers.
* Version: 2.9.3
* Author: Small Fish Analytics Inc.
* Author URI: http://www.smallfishanalytics.com/support?source=plugin_summary
* WC requires at least: 3.0.0
* WC tested up to: 4.9.0
*/

/**
 * Exit if accessed directly
**/
if (!defined('ABSPATH')) { 
    exit; 
}

function calculate_shipping($methods) {
	$methods[] = 'Canada_Post_Shipping_For_WooCommerce_Shipping_Method';
	return $methods;
}

function shipping_method_init() {
	include_once 'canada-post-shipping-for-woocommerce-shipping-method.php';
}

function add_nositesleft_admin_options($links) {
	$custom_links = array(
		'<a href="admin.php?page=wc-settings&tab=shipping&section=canada_post_shipping_for_woocommerce_shipping_method">Settings</a>',
		'<a href="http://www.smallfishanalytics.com/support">Support</a>');
		
	return array_merge($custom_links, $links);
}

add_filter('woocommerce_shipping_methods', 'calculate_shipping');
add_action('woocommerce_shipping_init', 'shipping_method_init');
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_nositesleft_admin_options');
