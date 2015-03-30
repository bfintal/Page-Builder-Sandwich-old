<?php

/**
 * Create our Contact Form 7 shortcode
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_contact_form_7', 11 );
function sandwich_contact_form_7() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	if ( ! is_admin() ) {
		return;
	}
	
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return;
	}
	
	$options = array(
		'0' => sprintf( '— %s —', __( 'Select', 'pbsandwich' ) ),
	);
	$forms = WPCF7_ContactForm::find();
	foreach ( $forms as $form ) {
		$options[ $form->id() ] = $form->title();
	}
	
	// Register Shortcake UI
	shortcode_ui_register_for_shortcode(
		'contact-form-7',
		array(
			'label' => __( 'Contact Form 7', 'contact-form-7' ),
			'listItemImage' => 'dashicons-email',
			'attrs' => array(
				array(
					'label' => __( 'Select a contact form', 'pbsandwich' ),
					'attr' => 'id',
					'type' => 'select',
					'options' => $options,
				),
				array(
					'label' => __( 'Title', 'pbsandwich' ),
					'attr'	=> 'title',
					'type'	=> 'text',
					'value' => '',
				),
			),
		)
	);
	
}


/**
 * Contact Form 7 shortcode is disabled while in the admin, so our previews will not work
 * This brings back the shortcode only during preview generation
 */
add_action( 'shortcode_ui_before_do_shortcode', 'sandwich_contact_form_7_preview_fix' );
function sandwich_contact_form_7_preview_fix( $shortcode ) {
	if ( ! defined( 'WPCF7_PLUGIN_DIR' ) ) {
		return;
	}
	if ( ! is_admin() ) {
		return;
	}
	if ( ! preg_match( '/\[contact-form-7/', $shortcode ) ) {
		return;
	}
	
	require_once( WPCF7_PLUGIN_DIR . '/includes/controller.php' );
	
	if ( ! function_exists( 'wpcf7_add_shortcodes' ) ) {
		return;
	}
	
	// Initialize the contact form 7 shortcode
	wpcf7_add_shortcodes();
}