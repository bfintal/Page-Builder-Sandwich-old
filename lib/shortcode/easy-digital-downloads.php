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

	if ( ! is_admin() ) {
		return;
	}

	// Check if Easy Digital Downloads exist. Terminate if not.
	if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
		return;
	}

	// Register Shortcake UI for Easy Digital Downloads Download History
	shortcode_ui_register_for_shortcode( 'download_history', 
		array(
			'label' => __( 'EDD Download History', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress download-count',
		) 
	);
	
	// Register Shortcake UI for Easy Digital Downloads Receipt
	shortcode_ui_register_for_shortcode(
		'edd_receipt',
		array(
			'label' => __( 'EDD Receipt', 'pbsandwich' ),
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
					'attr' => 'price',
					'type' => 'text',
					'description' => __( 'Enter a Price here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Discount Code', 'pbsandwich' ),
					'attr' => 'discount',
					'type' => 'text',
					'description' => __( 'Enter a Discount code here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Products', 'pbsandwich' ),
					'attr' => 'products',
					'type' => 'text',
					'description' => __( 'Enter a Product id here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Date', 'pbsandwich' ),
					'attr' => 'date',
					'type' => 'text',
					'description' => __( 'Enter the desired date here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Payment Key', 'pbsandwich' ),
					'attr' => 'payment_key',
					'type' => 'text',
					'description' => __( 'Enter the Payment Key here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Payment Method', 'pbsandwich' ),
					'attr' => 'payment_method',
					'type' => 'text',
					'description' => __( 'Enter a desired payment method here. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
				array(
					'label' => __( 'Payment ID', 'pbsandwich' ),
					'attr' => 'payment_id',
					'type' => 'text',
					'description' => __( 'Enter a payment ID here, if needed. Defaults to 0, indicating disabled.', 'pbsandwich' ),
					'value' => '0',
				),
			),
		)
	);
	
	// Register Shortcake UI for Easy Digital Downloads Profile Editor
	shortcode_ui_register_for_shortcode( 'edd_profile_editor', 
		array(
			'label' => __( 'EDD Profile Editor', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress download-count',
		) 
	);

	// Register Shortcake UI for Easy Digital Downloads Login Form
	sandwich_add_logged_out_shortcode( 'edd_login' );
	shortcode_ui_register_for_shortcode(
		'edd_login',
		array(
			'label' => __( 'EDD Login Form', 'pbsandwich' ),
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
			'label' => __( 'EDD Discount Codes', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress download-count',
		) 
	);
	
	// Register Shortcake UI for Easy Digital Downloads Download History
	shortcode_ui_register_for_shortcode( 'downloads', 
		array(
			'label' => __( 'EDD Downloadable Products', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress download-count',
		) 
	);
}



/**
 * Adds EDD styles into the preview
 */
add_action( 'init', 'sandwich_edd_add_editor_styles', 10 );
function sandwich_edd_add_editor_styles() {

	if ( ! is_admin() ) {
		return;
	}
	
	// Enqueue EDD Styles
	if ( function_exists( 'edd_register_styles' ) ) {
		
		// Force EDD to register frontend styles
		edd_register_styles();
		
		// Get those styles and add them in the editor for our previews
		global $wp_styles;
		if ( ! empty( $wp_styles->registered['edd-styles'] ) ) {
			add_editor_style( $wp_styles->registered['edd-styles']->src );
		}

	}
	
}