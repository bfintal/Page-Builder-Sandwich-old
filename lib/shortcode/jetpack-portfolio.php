<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_jetpack_portfolio', 11 );

function sandwich_jetpack_portfolio() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
		'portfolio',
		array(
			'label' => __( 'Jetpack' , 'jetpack' ) . ' ' . _x( 'Portfolio', 'Module Name', 'jetpack' ),
			'listItemImage' => 'dashicons-portfolio',
			'attrs' => array(
				array(
					'label' => __( 'Display Portfolio Project Types', 'pbsandwich' ),
					'attr' => 'display_types',
					'type' => 'checkbox',
				),
				array(
					'label' => __( 'Display Portfolio Project Tags', 'pbsandwich' ),
					'attr' => 'display_tags',
					'type' => 'checkbox',
				),
				array(
					'label' => __( 'Display Portfolio Post Content', 'pbsandwich' ),
					'attr' => 'display_content',
					'type' => 'checkbox',
				),
				array(
					'label' => __( 'Project Types to display', 'pbsandwich' ),
					'attr' => 'include_type',
					'type' => 'text',
					'description' => __( 'Enter slug names of Project Types to display. Multiple slugs can be added by separating them with a comma. This entry must not be left blank in order to work.', 'pbsandwich' ),
					'value' => 'all'				
				),
				array(
					'label' => __( 'Tags to display', 'pbsandwich' ),
					'attr' => 'include_tag',
					'type' => 'text',
					'description' => __( 'Enter slug names of tags to display. Multiple slugs can be added by separating them with a comma. If none entered, it will not influence the post display.', 'pbsandwich' ),
				),
				array(
					'label' => __( 'Columns to display', 'pbsandwich' ),
					'attr' => 'columns',
					'type' => 'select',
					'description' => __( 'Select the number of columns to use to display entries.', 'pbsandwich' ),
					'options' => array( 1, 2, 3, 4, 5, 6 ),
					'value' => '2',
				),
				array(
					'label' => __( 'Amount of posts to display', 'pbsandwich' ),
					'attr' => 'showposts',
					'type' => 'text',
					'description' => __( 'Enter the number of entries to display. If none entered, defaults to 5', 'pbsandwich' ),
					'value' => '5',
				),
				array(
					'label' => __( 'Display Order', 'pbsandwich' ),
					'attr' => 'order',
					'type' => 'select',
					'description' => __( 'Choose sorting direction. Ascending displays your oldest post first, while Descending displays your latest post first.', 'pbsandwich' ),
					'options' => sandwich_functions_display_dir(),
					'value' => 'DESC',
				),
				array(
					'label' => __( 'Display Order criteria', 'pbsandwich' ),
					'attr' => 'orderby',
					'type' => 'select',
					'description' => __( 'Choose the criteria of entries to be sorted by.', 'pbsandwich' ),
					'options' => sandwich_functions_display_order(),
					'value' => 'date',
				),
			),
		)
	);
	
	// Make sure Jetpack is activated
	if ( ! class_exists( 'Jetpack' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_portfolio_disabled' );
		return;
	}

	// Make sure the contact form module is turned on
	if ( ! Jetpack::is_module_active( 'custom-content-types' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_portfolio_disabled' );
		return;
	}
	
}

function sandwich_jetpack_portfolio_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'portfolio', __( "Requires Jetpack's Custom Content Type module", 'pbsandwich' ) );
}