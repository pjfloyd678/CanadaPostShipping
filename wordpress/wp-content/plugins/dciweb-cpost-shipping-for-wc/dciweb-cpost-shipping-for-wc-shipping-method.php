<?php

class DCIWeb_CPost_Shipping_For_WC_Shipping_Method extends WC_Shipping_Method {
	
	public function __construct() {
		$this->id                 = 'canada_post_shipping_by_dciwebsolutions'; 
		$this->method_title       = 'Canada Post (DCI)';  
		$this->title = 'Canada Post (DCI)';
		$this->method_description .= '<br><br><h3>Shipping Zone Information</h3><br>This plugin works using only the settings on this page.';

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		
		$this->init_form_fields();
		$this->init_settings();
	}
	
	public function init_form_fields() {
		include_once( 'dciweb-cpost-shipping-for-wc-api-keys.php' );
		$general_options = get_option('woocommerce_canada_post_shipping_by_dciwebsolutions_settings');

		if (!isset($general_options['canda_post_server']) || $general_options['canda_post_server'] == '') {
			$server_description = '<span style="color: Red">Enter the Canada Post Server you would like to use.</span>';
		}
		else {
			$server_description = 'The Canada Post Production Server to use';
		}

		if (!isset($general_options['api_username']) || $general_options['api_username'] == '') {
			$user_name_description = '<span style="color: Red">The Canada Post API Username.</span>';
		}
		else {
			$user_name_description = 'The Canada Post API Username';
		}

		if (!isset($general_options['api_password']) || $general_options['api_password'] == '') {
			$password_description = '<span style="color: Red">The Canada Post API Password<span style="color: Red">';
		}
		else {
			$password_description = 'The Canada Post API Password';
		}

		if (!isset($general_options['postal_code']) || $general_options['postal_code'] == '') {
			$postal_code_description = '<span style="color: Red">The postal code packages are shipped from. Fill this out if you want to ship parcels.<span style="color: Red">';
		}
		else {
			$postal_code_description = 'The postal code packages are shipped from. Fill this out if you want to ship parcels.';
		}

		$this->form_fields = array(
			'enabled' => array(
				'title' => 'Enable/Disable',
				'label' => 'Enable this option to turn on this shipping method.',
				'default' => 'yes',
				'type' => 'checkbox'),
			'api_username' => array(
				'title' => 'API Username',
				'description' => $user_name_description,
				'type' => 'text'
			),
			'api_password' => array(
				'title' => 'API Password',
				'description' => $password_description,
				'type' => 'text'
			),
			'canda_post_server' => array(
				'title' => 'Canada Post Server (Sandbox or Production)',
				'description' => $server_description,
				'default' => 'ct.soa-gw.canadapost.ca',
				'type' => 'text'
			),
			'allow_stamps' => array(
				'title' => 'Allow Letter Rate Shipping',
				'label' => 'Enable this option to turn on letter rate shipping using stamps.',
				'type' => 'checkbox'
			),
			'postal_code' => array(
				'title' => 'Postal Code',
				'description' => $postal_code_description,
				'default' => "L3Y 4Z4",
				'type' => 'text')
			);
	}
	
	public function calculate_shipping($package = Array()) {

		if ($this->get_option('enabled') == 'no') {
			return;
		}

		$allow_letter_rates = $this->get_option('allow_stamps') == 'yes';
		
		include_once 'dciweb-cpost-shipping-for-wc-shipping-rates.php';
		$canada_post = new DCIWeb_CPost_Shipping_For_WC_Shipping_Rates();
		$rates = $canada_post->calculate_shipping($package["contents"], $package["destination"], $allow_letter_rates);
		
		foreach ($rates as $rate) {
			$this->add_rate($rate);
		}
	}

	private function check_for_products_without_weights() {
		$products_without_weights_args = array(
			'weight' => " ", 
			'status' => 'publish',
			'downloadable' => false,
			'virtual' => false
		);

		$products_without_weights = wc_get_products($products_without_weights_args);
		$result = '';

		if (count($products_without_weights) > 0) {
			$result .= '<div style="background-color: #fcf8e3; color: #8a6d3b; border: solid 1px #faf2cc; padding: 1em; margin-bottom: 1em;"><b>WARNING</b><br>There are products in your store without weights entered on the inventory tab. <br> Products weights are required to be entered for this plugin to work.';
			
			$result .= '<br>For Example: <a href="' . $products_without_weights[0]->get_permalink() . '">' . $products_without_weights[0]->get_name() . ' - ' . $products_without_weights[0]->get_permalink() . '</a>';

			$result .= '</div>';
		}

		$this->method_description = $result . $this->method_description;
	}

	private function check_for_api_keys() {
		$general_options = get_option('woocommerce_canada_post_shipping_by_dciwebsolutions_settings');

		if (!isset($general_options['api_username']) || !isset($general_options['api_password']) || $general_options['api_username'] == '' || $general_options['api_password'] == '') {
			$this->method_description = '<div style="background-color: #fcf8e3; color: #8a6d3b; border: solid 1px #faf2cc; padding: 1em; margin-bottom: 1em;"><b>WARNING</b> <br> There seems to be a problem with your API Keys. (Blank Keys)</div>' . $this->method_description;
			return;	
		}

		include_once 'dciweb-cpost-shipping-for-wc-shipping-rates.php';
		$canada_post = new DCIWeb_CPost_Shipping_For_WC_Shipping_Rates();
		
		if (!$canada_post->check_api_keys()) {
			$this->method_description = '<div style="background-color: #f8d7da; color: #721c24; border: solid 1px #f5c6cb; padding: 1em; margin-bottom: 1em;"><b>ERROR</b> <br> There seems to be a problem with your API Keys. (Check fail)</div>' . $this->method_description;
		}
	}
	
	public function admin_options() {
		
		$this->check_for_products_without_weights();
		$this->check_for_api_keys();

		parent::admin_options();

		echo('<h3>Support and Feature Requests</h3>');
		echo('<p>');
		echo("We're here to help! <br /><br />If you need help, have questions or would like a feature added please feel free to reach out.<br /><br />");
		echo("The best way to contact us is through emailing <a href='mailto:peterjfloyd@gmail.com'>peterjfloyd@gmail.com</a>");
		echo('</p>');
		echo('<br />');
	}
}

?>