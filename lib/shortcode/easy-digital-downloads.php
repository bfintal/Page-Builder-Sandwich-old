<?php

/**
 * Shortcode Template File for Easy Digital Downloads
 */


/**
 * Create our shortcode for Easy Digital Downloads
 */
add_action( 'init', 'sandwich_easy_digital_downloads', 11 );
function sandwich_easy_digital_downloads() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	// Register Shortcake UI for Easy Digital Downloads Download History
	shortcode_ui_register_for_shortcode( 'download_history', 
		array(
			'label' => __( 'Easy Digital Downloads - Download History', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress download-count',
		) 
	);
	
	// Register Shortcake UI for Easy Digital Downloads Receipt
	shortcode_ui_register_for_shortcode(
		'edd_receipt',
		array(
			'label' => __( 'Easy Digital Downloads - Receipt', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress download-count',
			'attrs' => array(
				array(
					'label' => __( 'Error Message', 'pbsandwich' ),
					'attr' => 'error',
					'type' => 'text',
					'description' => __( 'Enter a custom error message here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Price', 'pbsandwich' ),
					'attr'	=> 'price',
					'type'	=> 'text',
					'description' => __( 'Enter a Price here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Discount Code', 'pbsandwich' ),
					'attr'	=> 'discount',
					'type'	=> 'text',
					'description' => __( 'Enter a Discount code here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Products', 'pbsandwich' ),
					'attr'	=> 'products',
					'type'	=> 'text',
					'description' => __( 'Enter a Product id here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Date', 'pbsandwich' ),
					'attr'	=> 'date',
					'type'	=> 'text',
					'description' => __( 'Enter the desired date here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Payment Key', 'pbsandwich' ),
					'attr'	=> 'payment_key',
					'type'	=> 'text',
					'description' => __( 'Enter the Payment Key here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Payment Method', 'pbsandwich' ),
					'attr'	=> 'payment_method',
					'type'	=> 'color',
					'description' => __( 'Enter a desired payment method here. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Payment ID', 'pbsandwich' ),
					'attr'	=> 'payment_id',
					'type'	=> 'text',
					'description' => __( 'Enter a payment ID here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
			),
		)
	);
	
	// Register Shortcake UI for Easy Digital Downloads Profile Editor
	shortcode_ui_register_for_shortcode( 'edd_profile_editor', 
		array(
			'label' => __( 'Easy Digital Downloads - Profile Editor', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress download-count',
		) 
	);

	// Register Shortcake UI for Easy Digital Downloads Login Form
	shortcode_ui_register_for_shortcode(
		'edd_login',
		array(
			'label' => __( 'Easy Digital Downloads - Login Form', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress download-count',
			'attrs' => array(
				array(
					'label' => __( 'Custom URL', 'pbsandwich' ),
					'attr' => 'redirect',
					'type' => 'url',
					'description' => __( 'Enter an optional custom URL to redirect to after a successful login.', 'pbsandwich' ),
				),
			),
		)
	);
	
	// Register Shortcake UI for Easy Digital Downloads Discount Codes
		shortcode_ui_register_for_shortcode( 'download_discounts', 
		array(
			'label' => __( 'Easy Digital Downloads - Discount Codes', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress download-count',
		) 
	);
	
	// Register Shortcake UI for Easy Digital Downloads Download History
		shortcode_ui_register_for_shortcode( 'downloads', 
		array(
			'label' => __( 'Easy Digital Downloads - Downloadable Products', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress download-count',
		) 
	);
}
