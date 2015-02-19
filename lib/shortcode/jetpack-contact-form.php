<?php

/**
 * Creates the view for Jetpack's contact form
 */

add_action( 'init', 'sandwich_jetpack_contact_form', 11 );

function sandwich_jetpack_contact_form() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode') ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'contact-form',
        array(
            'label' => __( 'Jetpack' , 'jetpack' ) . ' ' . _x( 'Contact Form', 'Module Name', 'jetpack' ),
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
	GambitPBSandwich::printDisabledShortcakeStlyes( 'contact-form', "Requires Jetpack's Contact Form module" );
}