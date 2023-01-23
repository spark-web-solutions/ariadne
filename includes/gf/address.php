<?php
// Gravity Forms Custom Addresses (Australia)
add_filter('gform_address_types', 'spark_gf_australian_address', 10, 2);
if (!function_exists('spark_gf_australian_address')) {
	function spark_gf_australian_address($address_types, $form_id) {
		$address_types['australia'] = array(
				'label' => __('Australia', 'spark_theme'), // labels the dropdown
				'zip_label' => __('Postcode', 'spark_theme'), // what it says
				'state_label' => __('State', 'spark_theme'), // as above
				'states' => array(
						'',
						'ACT' => 'ACT',
						'NSW' => 'NSW',
						'NT' => 'NT',
						'QLD' => 'QLD',
						'SA' => 'SA',
						'TAS' => 'TAS',
						'VIC' => 'VIC',
						'WA' => 'WA',
				),
		);
		return $address_types;
	}
}
