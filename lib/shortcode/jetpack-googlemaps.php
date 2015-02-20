<?php

/**
 * Creates the view for Jetpack's google maps shortcode
 */

add_action( 'init', 'sandwich_jetpack_googlemaps', 11 );

function sandwich_jetpack_googlemaps() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode') ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'googlemaps',
        array(
            'label' => __( 'Jetpack' , 'jetpack' ) . ' ' . _x( 'Google Maps Shortcode', 'Module Name', 'jetpack' ),
            'listItemImage' => 'dashicons-email-alt',
            'attrs' => array(
                array(
                    'label' => '',
                    'attr'  => 'content',
                    'type'  => 'textarea',
                ),
			),
        )
    );
	
	// Make sure Jetpack is activated
	if ( ! class_exists( 'Jetpack' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_googlemaps_disabled' );
		return;
	}

	// Make sure the google maps shortcode module is turned on
	if ( ! Jetpack::is_module_active( 'googlemaps' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_googlemaps_disabled' );
		return;
	}
	
	
}


function sandwich_jetpack_googlemaps_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'googlemaps', "Requires Jetpack's shortcode module" );
}