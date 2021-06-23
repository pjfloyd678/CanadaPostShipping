<?php

class Canada_Post_Shipping_For_WooCommerce_Shipping_Method extends WC_Shipping_Method {
	
	public function __construct() {
		$this->id                 = 'canada_post_shipping_by_nosites_left'; 
		$this->method_title       = 'Canada Post';  
		$this->title = 'Canada Post';
		$this->method_description .= '<br><br><h3>Shipping Zone Information</h3><br>This plugin works using only the settings on this page. The plugin was built before shipping zones were added to WooCommerce and will not show up in the shipping zone menu.';

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		
		$this->init_form_fields();
		$this->init_settings();
	}
	
	public function init_form_fields() {
		$general_options = get_option('woocommerce_canada_post_shipping_by_nosites_left_settings');

		if (!isset($general_options['api_username']) || $general_options['api_username'] == '') {
			$user_name_description = '<span style="color: Red">The Canada Post API User Name.</span>';
		}
		else {
			$user_name_description = 'The Canada Post API User Name';
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
				'default' => '',
				'type' => 'text'
			),
			'api_password' => array(
				'title' => 'API Password',
				'description' => $password_description,
				'default' => '',
				'type' => 'text'
			),
			'allow_stamps' => array(
				'title' => 'Allow Letter Rate Shipping',
				'label' => 'Enable this option to turn on letter rate shipping using stamps.',
				'type' => 'checkbox'),
			'postal_code' => array(
				'title' => 'Postal Code',
				'description' => $postal_code_description,
				'type' => 'text')
			);
	}
	
	public function calculate_shipping($package = Array()) {

		if ($this->get_option('enabled') == 'no') {
			return;
		}

		$allow_letter_rates = $this->get_option('allow_stamps') == 'yes';
		
		include_once 'canada-post-shipping-for-woocommerce-canada-post.php';
		$canada_post = new Canada_Post_Shipping_For_WooCommerce_Canada_Post();
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
		$general_options = get_option('woocommerce_canada_post_shipping_by_nosites_left_settings');

		if (!isset($general_options['api_username']) || !isset($general_options['api_password']) || $general_options['api_username'] == '' || $general_options['api_password'] == '') {
			$this->method_description = '<div style="background-color: #fcf8e3; color: #8a6d3b; border: solid 1px #faf2cc; padding: 1em; margin-bottom: 1em;"><b>WARNING</b> <br> Although the plugin will work as-is it\'s recommended you set up your own free Canada Post account for the most reliable service and best rates.<br><a href="https://www.smallfishanalytics.com/how-to-set-up-your-canada-post-account/">Click here for instructions on how to set up your account and configure the plugin</b></a></div>' . $this->method_description;
			return;	
		}

		include_once 'canada-post-shipping-for-woocommerce-canada-post.php';
		$canada_post = new Canada_Post_Shipping_For_WooCommerce_Canada_Post();
		
		if (!$canada_post->check_api_keys()) {
			$this->method_description = '<div style="background-color: #f8d7da; color: #721c24; border: solid 1px #f5c6cb; padding: 1em; margin-bottom: 1em;"><b>ERROR</b> <br> The API keys you\'ve entered don\'t appear to be valid. Your production API keys are required for the plugin to work. <br> See the following link for instructions on how to get the API keys set up correctly. <br><a href="https://www.smallfishanalytics.com/how-to-set-up-your-canada-post-account/">Click here for instructions on how to set up your account and configure the plugin</b></a></div>' . $this->method_description;
		}
	}
	
	public function admin_options() {
		
		$this->check_for_products_without_weights();
		$this->check_for_api_keys();

		parent::admin_options();
				
		echo('<h3>Support and Feature Requests</h3>');
		echo('<p>');
		echo("We're here to help! <br /><br />If you need help, have questions or would like a feature added please feel free to reach out.<br /><br />");
		echo("The best way to contact us is through emailing <a href='mailto:mike@smallfishanalytics.com'>mike@smallfishanalytics.com</a>");
		echo('</p>');
		echo('<br />');
		echo('<h3>Premium Version</h3>');
		echo('<p>');
		echo('We do offer a premium version of our plugin that gives you more control over the shipping options offered to your customers.<br /><br />');
		echo('The premium version gives you many benefits');
		echo('<ul>');
		echo('<li>1. Re-Name Shipping Methods</li>');
		echo('<li>2. Add Handling Fees</li>');
		echo('<li>3. Enable or Disable Shipping Methods</li>');
		echo('<li>4. Define Shipping Boxes To Use</li>');
		echo('<li>5. Ship By Dimensions and Weight</li>');
		echo('</ul>');
		echo('<br />');
		echo('<a href="https://www.smallfishanalytics.com/canada-post-shipping-for-woocommerce/">Check out our website to see all of the premium version features.</a>');
		echo('<br /><br />');
		echo('<a href="https://www.smallfishanalytics.com/canada-post-shipping-for-woocommerce/"><img src="'. plugin_dir_url(__FILE__) .'premium_features.png" /></a>');
		echo('</p>');
	}
}

?>