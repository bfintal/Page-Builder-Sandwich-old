<?php

/**
 * Shortcode Template File for MailChimp
 */


/**
 * Create our shortcode
 */
add_action( 'init', 'sandwich_mailchimp', 11 );
function sandwich_mailchimp() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Check if MailChimp is active. Either Lite or Pro. Terminate if neither exists.
	if ( ! class_exists( 'MC4WP_Lite' ) &&  ! defined( 'MC4WP_VERSION' ) ) {
		return;
	}
		
	// Register Shortcake UI for MailChimp Form
	shortcode_ui_register_for_shortcode(
		'mc4wp_form',
		array(
			'label' => __( 'MailChimp Form', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress',
		)
	);
}