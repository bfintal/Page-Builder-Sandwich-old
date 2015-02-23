<?php

/**
 * Creates the view for Jetpack's contact form
 */

add_action( 'init', 'sandwich_jetpack_portfolio', 11 );

function sandwich_jetpack_portfolio() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode') ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'portfolio',
        array(
            'label' => __( 'Jetpack' , 'jetpack' ) . ' ' . _x( 'Portfolio', 'Module Name', 'jetpack' ),
            'listItemImage' => 'dashicons-portfolio',
            'attrs' => array(
                array(
                    'label' => 'Display Portfolio Types',
                    'attr'  => 'display_types',
                    'type'  => 'checkbox',
                ),
                array(
                    'label' => 'Display Portfolio Tags',
                    'attr'  => 'display_tags',
                    'type'  => 'checkbox',
                ),
                array(
                    'label' => 'Display Portfolio Tags',
                    'attr'  => 'display_content',
                    'type'  => 'checkbox',
                ),
                array(
                    'label' => 'Entries to display',
                    'attr'  => 'include_type',
                    'type'  => 'text',
					'description' => 'Enter slug names of entries to display. If none entered, defaults to displaying all entries',
                ),
                array(
                    'label' => 'Tags to display',
                    'attr'  => 'include_tag',
                    'type'  => 'text',
					'description' => 'Enter slug names of tags to display. If none entered, defaults to displaying all tags',
                ),
                array(
                    'label' => 'Columns to display',
                    'attr'  => 'columns',
                    'type'  => 'text',
					'description' => 'Enter slug names of entries to display. If none entered, defaults to displaying all entries',
                ),
                array(
                    'label' => 'Entries to display',
                    'attr'  => 'showposts',
                    'type'  => 'text',
					'description' => 'Enter the number of entries to display. If none entered, defaults to 5',
					'value' => '5',
                ),
                array(
                    'label' => 'Display order',
                    'attr'  => 'order',
                    'type'  => 'text',
					'description' => 'Enter the sorting direction. Use ASC or DESC only',
					'value' => 'DESC',
                ),
                array(
                    'label' => 'Order Criteria',
                    'attr'  => 'orderby',
                    'type'  => 'text',
					'description' => 'Enter the criteria of entries to be sorted by. Choose from date, author, title, rand',
					'value' => 'date',
                ),
			),
        )
    );
	
	// Make sure Jetpack is activated
	if ( ! class_exists( 'Jetpack' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_disabled' );
		return;
	}

	// Make sure the contact form module is turned on
	if ( ! Jetpack::is_module_active( 'custom-content-types' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_portfolio_disabled' );
		return;
	}
	
	
}


function sandwich_jetpack_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'portfolio', "Requires Jetpack plugin and its Portfolio module" );
}

function sandwich_jetpack_portfolio_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'portfolio', "Requires Jetpack's Custom Content Type module" );
}