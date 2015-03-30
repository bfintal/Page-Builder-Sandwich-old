<?php

/**
 * Ninja Forms Shortcode
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Create our shortcode
 */
add_action( 'init', 'sandwich_ninjaforms', 11 );
function sandwich_ninjaforms() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	if ( ! is_admin() ) {
		return;
	}
	
	if ( ! function_exists( 'ninja_forms_get_all_forms' ) ) {
		return;
	}
	
	// Get all the Ninja Forms
	$options = array(
		'0' => sprintf( '— %s —', __( 'Select', 'pbsandwich' ) ),
	);
	
	// @see http://docs.ninjaforms.com/article/159-ninja-formsformsget-all
	$allForms = ninja_forms_get_all_forms();
	
	foreach( $allForms as $form ) {
		if ( ! empty( $form['id'] ) && ! empty( $form['data']['form_title'] ) ) {
			$options[ $form['id'] ] = $form['data']['form_title'];
		}
	}
	
	// Register Shortcake UI
	shortcode_ui_register_for_shortcode(
		'ninja_forms',
		array(
			'label' => __( 'Ninja Forms', 'pbsandwich' ),
			'listItemImage' => 'dashicons-feedback',
			'attrs' => array(
				array(
					'label' => __( 'Choose the form to display', 'pbsandwich' ),
					'attr' => 'id',
					'type' => 'select',
					'options' => $options,
				),
			),
		)
	);
	
}


/**
 * Adds Ninja Forms styles into the preview
 */
add_action( 'init', 'sandwich_ninjaforms_add_editor_styles', 10 );
function sandwich_ninjaforms_add_editor_styles() {

	if ( ! is_admin() ) {
		return;
	}
	
	if ( defined( 'NINJA_FORMS_URL' ) ) {
		add_editor_style( NINJA_FORMS_URL . 'css/ninja-forms-display.css' );
	}
	
}