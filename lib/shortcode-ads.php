<?php

/**
 * The "Get More Shortcodes" link.
 *
 * This adds a "Get More Shortcodes" button in the list of existing shortcodes. When it's clicked
 * open the extensions pbsandwi.ch link
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_get_more_shortcodes', 11 );

function sandwich_get_more_shortcodes() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Only create the UI when in the admin
	if ( ! is_admin() ) {
		return;
	}
	
	// Create our UI
	shortcode_ui_register_for_shortcode(
        'pbs_get_more_shortcodes',
        array(
            'label' => '_' . __( 'Get More Shortcodes', 'pbsandwich' ), // We prepend a "_" to make this the last entry
            'listItemImage' => 'dashicons-external',
            'attrs' => array(),
        )
    );
	
}