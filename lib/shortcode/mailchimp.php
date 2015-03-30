<?php

/**
 * Shortcode Template File for MailChimp
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


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

	if ( ! is_admin() ) {
		return;
	}
	
	// Include the required styles
	if ( defined( 'MC4WP_LITE_PLUGIN_URL' ) ) {
		add_editor_style( MC4WP_LITE_PLUGIN_URL . 'assets/css/form.min.css' );
	}
	if ( defined( 'MC4WP_PLUGIN_URL' ) ) {
		add_editor_style( MC4WP_PLUGIN_URL . 'assets/css/form.min.css' );
	}
		
	// Register Shortcake UI for MailChimp Form
	shortcode_ui_register_for_shortcode(
		'mc4wp_form',
		array(
			'label' => __( 'MailChimp Form', 'pbsandwich' ),
			'listItemImage' => 'dashicons-email-alt',
		)
	);
}