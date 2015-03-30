<?php

/**
 * Creates the view for Jetpack's contact form
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_jetpack_contact_form', 11 );

function sandwich_jetpack_contact_form() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	// Will not run if not in administration page.
	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'contact-form',
        array(
            'label' => __( 'Jetpack' , 'pbsandwich' ) . ' ' . __( 'Contact Form', 'pbsandwich' ),
            'listItemImage' => 'dashicons-email-alt',
            'attrs' => array(),
        )
    );
	
	// Make sure Jetpack is activated
	if ( ! class_exists( 'Jetpack' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_contact_form_disabled' );
		return;
	}

	// Make sure the contact form module is turned on
	if ( ! Jetpack::is_module_active( 'contact-form' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_contact_form_disabled' );
		return;
	}
	
}


function sandwich_jetpack_contact_form_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'contact-form', __( "Requires Jetpack's Contact Form module", 'pbsandwich' ) );
}