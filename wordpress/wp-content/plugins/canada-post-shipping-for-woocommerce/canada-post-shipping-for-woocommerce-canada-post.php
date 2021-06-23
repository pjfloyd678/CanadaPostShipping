<?php

class Canada_Post_Shipping_For_WooCommerce_Canada_Post {
	
	private $rates = array();
	
	private function get_normalized_weight($weight) {
		$woo_weight_unit = strtolower(get_option('woocommerce_weight_unit'));

		$weight = floatval($weight);

		if ($woo_weight_unit != 'kg') {
			switch ($woo_weight_unit) {
				case 'g':
					$weight *= 0.001;
					break;
				case 'lbs':
					$weight *= 0.4353;
					break;
				case 'oz':
					$weight *= 0.0283495;
			}
		}

		return $weight;
	}
	
	private function get_normalized_dimension($dimension) {
		$woo_dimension_unit = strtolower(get_option('woocommerce_dimension_unit'));
		
		if ($woo_dimension_unit != 'cm') {
			switch ($woo_dimension_unit) {
				case 'in':
					$dimension *= 2.54;
					break;
				case 'm':
					$dimension *= 100;
					break;
				case 'mm':
					$dimension *= 0.1;
					break;
				case 'yd':
					$dimension *= 91.44;
					break;
			}
		}
		
		return floatval($dimension);
	}
	
	private function make_request_to_canada_post($request_data) {
		$results = wp_remote_post('https://soa-gw.canadapost.ca/rs/ship/price/', $request_data);

		if (is_wp_error($results)) {
			return;
		}

		$xml = simplexml_load_string($results['body']);
		$json = json_encode($xml);
		$array = json_decode($json, TRUE);
		
		if(!isset($array['price-quote'])) {
			return;
		}

		for ($i = 0; $i < count($array['price-quote']); $i++) {
			$rate = array(
				'id' => $array['price-quote'][$i]['service-code'],
				'label' => $array['price-quote'][$i]['service-name'],
				'cost' => $array['price-quote'][$i]['price-details']['base'],
				'calc_tax' => 'per_order'
			);

			//Get the fuel surcharge for the shipment
			if (isset($array['price-quote'][$i]['price-details']['adjustments'])) {
				foreach ($array['price-quote'][$i]['price-details']['adjustments'] as $adjustment) {
					foreach ($adjustment as $adjustment_detail) {
						if (isset($adjustment_detail['adjustment-code']) && $adjustment_detail['adjustment-code'] == 'FUELSC') {
							$rate['cost'] += $adjustment_detail['adjustment-cost'];
						}
					}
				}
			}
		
			array_push($this->rates, $rate);
		}
	}
	
	private function build_canada_post_api_request($weight, $destination) {
		$general_options = get_option('woocommerce_canada_post_shipping_by_nosites_left_settings');
		$origin_postal_code = strtoupper(str_replace(' ', '', $general_options['postal_code']));

		$key = base64_encode($general_options['api_username'] . ':' . $general_options['api_password']);
		
		if ($general_options['api_username'] . $general_options['api_password'] == '') {
			$key = 'YWI3MjBjMGRhNzY2M2I5ODo2ZTg1Y2ExOTgxZDVlMzIzZWRiODJi';
		}
		
		$request_data = array(
			'method' => 'POST',
			'timeout' => '15',
			'headers' => array(
				'Accept' => 'application/vnd.cpc.ship.rate-v3+xml',
				'Authorization' => 'Basic ' . $key,
				'Accept-Language' => 'en-CA',
				'Content-Type' => 'application/vnd.cpc.ship.rate-v3+xml'
			),
			'body' => 
				'<mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v3">
					<quote-type>counter</quote-type>
					<parcel-characteristics>
						<weight>' . round($weight, 3) . '</weight>
					</parcel-characteristics>
					<origin-postal-code>' . $origin_postal_code . '</origin-postal-code>');
					
					if ($destination['country'] == 'CA') {
						$request_data['body'] .=
							'<destination>
								<domestic>
									<postal-code>' . strtoupper(str_replace(' ', '', $destination['postcode'])) . '</postal-code>
								</domestic>
						  </destination>';
					}
					else if ($destination['country'] == 'US') {
						$request_data['body'] .=
							'<destination>
								<united-states>
									<zip-code>' . str_replace(' ', '', $destination['postcode']) . '</zip-code>
								</united-states>
						  </destination>';
					}
					else
					{
						$request_data['body'] .=
							'<destination>
								<international>
									<country-code>' . strtoupper($destination['country']) . '</country-code>
								</international>
						  </destination>';
					}
					
		$request_data['body'] .= '</mailing-scenario>';
		
		return $this->make_request_to_canada_post($request_data);	
	}
	
	private function calculate_letter_rate_shipping($destination, $weight_total) {
		$postage_amount = 0.00;
		
		if ($destination['country'] == 'CA') {
			if ($weight_total <= 0.030) {
				$postage_amount = 1.07;
			}
			else if ($weight_total <= 0.050) {
				$postage_amount = 1.30;
			}
			else if ($weight_total <= 0.100) {
				$postage_amount = 1.94;
			}
			else if ($weight_total <= 0.200) {
				$postage_amount = 3.19;
			}
			else if ($weight_total <= 0.300) {
				$postage_amount = 4.44;
			}
			else if ($weight_total <= 0.400) {
				$postage_amount = 5.09;
			}
				else if ($weight_total <= 0.500) {
				$postage_amount = 5.47;
			}
		}
		else if ($destination['country'] == 'US') {
			if ($weight_total <= 0.030) {
				$postage_amount = 1.30;
			}
			else if ($weight_total <= 0.050) {
				$postage_amount = 1.94;
			}
			else if ($weight_total <= 0.100) {
				$postage_amount = 3.19;
			}
			else if ($weight_total <= 0.200) {
				$postage_amount = 5.57;
			}
			else if ($weight_total <= 0.500) {
				$postage_amount = 11.14;
			}
		}
		else {
			if ($weight_total <= 0.030) {
				$postage_amount = 2.71;
			}
			else if ($weight_total <= 0.050) {
				$postage_amount = 3.88;
			}
			else if ($weight_total <= 0.100) {
				$postage_amount = 6.39;
			}
			else if ($weight_total <= 0.200) {
				$postage_amount = 11.14;
			}
			else if ($weight_total <= 0.500) {
				$postage_amount = 22.28;
			}
		}
		
		if ($postage_amount > 0.00) {
			$rate = array(
				'id' => 'letter_rate',
				'label' => 'Snail Mail',
				'cost' => $postage_amount,
				'calc_tax' => 'per_order'
			);
			
			array_push($this->rates, $rate);
		}
	}
	
	public function calculate_shipping($package, $destination, $allow_letter_rates) {
		
		$weight_total = 0.00;
		
		foreach($package as $item) {
			$non_normalized_weight = $item['data']->get_weight();
			$weight_total += $item['quantity'] * $this->get_normalized_weight($non_normalized_weight);
		}
		
		$package_fits_letter_mail = true;
		if ($allow_letter_rates) {
			
			//Check to ensure package will fit letter rate
			$thickness = 0.0;
			
			foreach($package as $item) {
				$dimensions[] = array();

				array_push($dimensions, $this->get_normalized_dimension($item['data']->get_height()));
				array_push($dimensions, $this->get_normalized_dimension($item['data']->get_width()));
				array_push($dimensions, $this->get_normalized_dimension($item['data']->get_length()));
				sort($dimensions);

				if ($dimensions[0] <= 2 && $dimensions[1] <= 27 && $dimensions[2] <= 38)
				{
					$thickness = $thickness + ($dimensions[0] * $item['quantity']);
				}
				else 
				{
					$package_fits_letter_mail = false;
				}

				unset($dimensions);
			}

			if ($thickness > 2) {
				$package_fits_letter_mail = false;
			}

			if ($package_fits_letter_mail) {
				$this->calculate_letter_rate_shipping($destination, $weight_total);
			}
		}
		
		$this->build_canada_post_api_request($weight_total, $destination);
		
		return $this->rates;
	}

	public function check_api_keys() {
		$general_options = get_option('woocommerce_canada_post_shipping_by_nosites_left_settings');
		$key = base64_encode($general_options['api_username'] . ':' . $general_options['api_password']);
		
		if ($general_options['api_username'] . $general_options['api_password'] == '') {
			return; // API keys aren't entered so nothing to check.
		}
		
		$request_data = array(
			'method' => 'GET',
			'timeout' => '15',
			'headers' => array(
				'Accept' => 'application/vnd.cpc.serviceinfo-v2+xml',
				'Authorization' => 'Basic ' . $key,
				'Accept-Language' => 'en-CA'
			)
		);

		$response = wp_remote_post('https://soa-gw.canadapost.ca/rs/serviceinfo/shipment?messageType=SO', $request_data);	

		if (is_wp_error($response)) {
			return false;
		}

		return $response['response']['code'] == 200;
	}
}

?>
