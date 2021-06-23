<?php
/**
* Plugin Name: DCI Web Solutions Plug-In - Canada Post Shipping For WooCommerce
* Description: Integrates with Canada Post allowing you to provide accurate shipping quotes for your customers.
* Version: 1.0.1
* Author: DCI Web Solutions Inc.
* Author URI: https://dciwebsolutions.com/support
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
	$methods[] = 'DCIWeb_CPost_Shipping_For_WC_Shipping_Method';
	return $methods;
}

function shipping_method_init() {
	include_once 'dciweb-cpost-shipping-for-wc-shipping-method.php';
}

function add_nositesleft_admin_options($links) {
	$custom_links = array(
		'<a href="admin.php?page=wc-settings&tab=shipping&section=DCIWeb_CPost_Shipping_For_WC_Shipping_Method">Settings</a>',
		'<a href="http://dciwebsolutions.com">Support</a>');
		
	return array_merge($custom_links, $links);
}

add_filter( 'woocommerce_shipping_methods', 'calculate_shipping' );
add_action( 'woocommerce_shipping_init', 'shipping_method_init' );
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_nositesleft_admin_options' );
