<?php

/**
 * Shortcode Jetpack Template File (not just for Jetpack, but for others that have dependencies as well)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Create our shortcode
 */
add_action( 'init', 'sandwich_myshortcode', 11 );
function sandwich_myshortcode() {
	
	// No need to register our shortcode since it already exists

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Only create the UI when in the admin
	if ( ! is_admin() ) {
		return;
	}
	
	// Register Shortcake UI
	shortcode_ui_register_for_shortcode(
		'myshortcode',
		array(
			'label' => __( 'Some Existing Shortcode', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress',
			'attrs' => array(
				array(
					'label' => __( 'Content', 'pbsandwich' ),
					'attr'	=> 'content',
					'type'	=> 'textarea',
				),
				array(
					'label' => __( 'Some Text', 'pbsandwich' ),
					'attr'	=> 'some_text',
					'type'	=> 'text',
				),
				array(
					'label' => __( 'Some Color', 'pbsandwich' ),
					'attr'	=> 'some_color',
					'type'	=> 'color',
					'value' => '#333333',
				),
			),
		)
	);
	
	// TODO: If the rendered shortcode in the editor NEEDS to be previewed in a logged out state (e.g. login forms)
	// uncomment this and add in your shortcode here.
	// sandwich_add_logged_out_shortcode( 'myshortcode' );
	
	// Make sure Jetpack is activated
	if ( ! class_exists( 'Jetpack' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_myshortcode_disabled' );
		return;
	}

	// Make sure our required Jetpack module is turned on
	if ( ! Jetpack::is_module_active( 'some-jetpack-module' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_myshortcode_disabled' );
		return;
	}
}


/**
 * Disable & display a note on our shortcode on why it's disabled
 */
function sandwich_jetpack_myshortcode_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'myshortcode', __( "Requires Jetpack's Special module", 'pbsandwich' ) );
}